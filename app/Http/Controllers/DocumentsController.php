<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\Signee;
use App\Models\Version;
use App\Models\Document;
use App\Models\Template;
use App\Models\Placeholder;

use App\HelperFunctions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

use Mews\Purifier\Facades\Purifier;

class DocumentsController extends Controller
{
    public function template_store(Request $request) {
        try {
            $errors = [];

            $doc_name = trim($request->input('document_name'));
            $doc_description = trim($request->input('document_description'));

            if (strlen($doc_description) > 0) {
                // Safeguards against XSS
                Purifier::clean($doc_description);
            }

            $doc_major_ver = filter_var(trim($request->input('document_major_ver')), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            $doc_minor_ver = filter_var(trim($request->input('document_minor_ver')), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            $doc_patch_ver = filter_var(trim($request->input('document_patch_ver')), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

            $group_read = filter_var(trim($request->input('group_read')), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            $group_edit = filter_var(trim($request->input('group_edit')), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            $subgroup_read = filter_var(trim($request->input('subgroup_read')), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            $subgroup_edit = filter_var(trim($request->input('subgroup_edit')), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

            if (strlen($doc_name) < 3) {
                $errors[] = 'Document name must be at least 3 characters!';
            }

            if (($doc_major_ver === null) || ($doc_minor_ver === null) || ($doc_patch_ver === null)) {
                $errors[] = 'Document version must be numeric!';
            }

            if (!$request->hasFile('document_file')) {
                $errors[] = 'No file submitted!';
            } else {
                if ($request->file('document_file')->extension() != 'pdf') {
                    $errors[] = 'Only PDF files may be uploaded!';
                }
            }
            
            if (count($errors) == 0) {
                $checksum = sha1($request->file('document_file'), true);
                $doc_version = Version::where('checksum', '=', $checksum)->first();

                if ($doc_version === null) {
                    $head_vers = $doc_major_ver . '.' . $doc_minor_ver . '.' . $doc_patch_ver;
                    $json = HelperFunctions::metatag_json_string($request->input('document_metatags'));

                    DB::beginTransaction();

                    $template = Template::create([
                        'name' => $doc_name,
                        'filename' => $request->file('document_file')->getClientOriginalName(),
                        'head_version' => null,
                        'owner_user' => auth()->user()->id,
                        'owner_group' => auth()->user()->group_id,
                        'group_read' => $group_read,
                        'group_edit' => $group_edit,
                        'world_read' => $subgroup_read,
                        'world_edit' => $subgroup_read,
                        'description' => $doc_description,
                        'metatags' => $json
                    ]);
    
                    $template->save();

                    $version = Version::create([
                        'template_id' => $template->id,
                        'semver' => $head_vers,
                        'checksum' => $checksum,
                        'contributor' => auth()->user()->id,
                        'is_head' => 1
                    ]);

                    $version->save();
                    $template->head_version = $version->id;
                    $template->save();

                    $local_directory = 'private/templates/' . $template->id;
                    $version_basename = bin2hex($checksum) . '.pdf';

                    $dir = Storage::disk('local')->makeDirectory($local_directory);
                    $file = Storage::disk('local')->putFileAs($local_directory, $request->file('document_file'), $version_basename);
                    $result = PDFServiceController::register_pdf_signature_placeholders($template->id, $version->id, $version_basename);

                    if ($result == 200) {
                        DB::commit();

                        return response()->json([
                            'code' => 200,
                            'result' => route('get-version', ['id' => $version->id])
                        ]);
                    } else {
                        DB::rollBack();

                        if ($result == 400) {
                            Storage::disk('local')->delete($file);
                            Storage::disk('local')->delete($dir);

                            return response()->json([
                                'code' => 400,
                                'result' => 'The PDF you uploaded was either corrupted or not standards compliant, therefore it was deleted!'
                            ]);

                        } else {
                            Log::critical('PDF microservice error occurred');

                            return response()->json([
                                'code' => 500,
                                'result' => 'A system error has occurred!'
                            ]);
                        }
                    }
                } else {
                    return response()->json([
                        'code' => 409,
                        'result' => 'Template exists in the library already!'
                    ]);
                }
            } else {
                return response()->json([
                    'code' => 400,
                    'result' => $errors
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::critical($e);

            return response()->json([
                'code' => 500,
                'result' => 'A system error has occurred!'
            ]);
        }
    }

    public function template_pdf(Request $request) {
        try {
            $version = Version::select('templates.filename', 'versions.semver', DB::raw('HEX(`checksum`) AS version_checksum'))
            ->leftJoin('templates', 'versions.template_id', '=', 'templates.id')
            ->where(function($query) use ($request) {
                $query->where('versions.template_id', '=', $request->id);
                $query->where('versions.is_head', '=', 1);
            })->first();

            if (!is_null($version)) {
                $checksum = strtolower($version->version_checksum);
                $version_file = 'private/templates/' . $request->id . '/' . $checksum . '.pdf';

                if (Storage::disk('local')->exists($version_file)) {
                    $filename = substr($version->filename, 0, (strrpos($version->filename, '.')));
                    $filename .= '_v' . $version->semver . '.pdf';
    
                    return response()->make(Storage::get($version_file), 200, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="' . $filename . '"'
                    ]);
                } else {
                    Log::critical('Missing version file [' . $checksum . '.pdf]');
                }
            } else {
                // Error 404....not found
            }
        } catch (\Exception $e) {
            Log::critical($e);
        }
    }

    public function template_blank(Request $request) {
        try {
            $template = Template::select('name', 'head_version')
            ->where('id', '=', $request->id)
            ->first();

            $version = Version::with('placeholders')
            ->where('id', '=', $template->head_version)
            ->first();

            return view('documents.template-blank', [
                'menuItem' => 'docs_tools', 
                'template_id' => $request->id,
                'template_name' => $template->name,
                'version' => $version
            ]);
        } catch (\Exception $e) {
            Log::critical($e);
        }
    }

    public function templates(Request $request) {
        try {
            $templates = Template::select('templates.id', 'templates.name', 'versions.semver', 'templates.filename', 'users.name AS owner_name', 'groups.name AS group_name', 'templates.description', 'templates.metatags')
            ->leftJoin('versions', 'templates.head_version', '=', 'versions.template_id')
            ->leftJoin('users', 'templates.owner_user', '=', 'users.id')
            ->leftJoin('groups', 'templates.owner_group', '=', 'groups.id')
            ->where('templates.owner_user', '=', auth()->user()->id)
            ->orWhere('templates.world_read', '=', 1)
            ->orWhere(function($query) {
                $query->where('templates.owner_group', '=', auth()->user()->group_id);
                $query->where('templates.group_read', '=', 1);
            })
            ->paginate(15);

            return view('documents.templates-view', ['menuItem' => 'docs_tools', 'templates' => $templates]);
        } catch (\Exception $e) {
            Log::critical($e);
        }
    }

    public function template_form(Request $request) {
        return view('documents.template-form', ['menuItem' => 'docs_tools']);
    }

    public function version_update(Request $request) {
        try {
            $version_data = $request->json()->all();
            $placeholder_ordinal = [];
            $placeholder_success = 0;

            DB::beginTransaction();

            foreach ($version_data['placeholders'] as $placeholder) {
                if (!in_array($placeholder['order'], $placeholder_ordinal)) {
                    $placeholder_success += Placeholder::where('id', '=', $placeholder['id'])
                    ->update([
                        'friendly_name' => $placeholder['friendly_name'],
                        'order' => $placeholder['order']
                    ]);
    
                    $placeholder_ordinal[] = $placeholder['order'];
                }
            }

            if (count($version_data['placeholders']) == count($placeholder_ordinal)) {
                $version_result = Version::where('id', '=', $request->id)
                ->update([
                    'enforce_sig_order' => $version_data['enforce_order']
                ]);

                if (($version_result == 1) && ($placeholder_success == count($version_data['placeholders']))) {
                    DB::commit();

                    return response()->json([
                        'code' => 200,
                        'result' => 'Template version updated!'
                    ]);
                } else {
                    DB::rollBack();

                    return response()->json([
                        'code' => 400,
                        'result' => 'Failed to update template version!'
                    ]);
                }
            } else {
                DB::rollBack();

                return response()->json([
                    'code' => 400,
                    'result' => 'Signature order must not be duplicated!'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::critical($e);

            return response()->json([
                'code' => 500,
                'result' => 'A system error has occurred!'
            ]);
        }
    }

    public function version(Request $request) {
        try {
            $version = Version::with('template', 'placeholders')
            ->where('id', '=', $request->id)->first();

            $select_html = '<select class="form-select requisigner-placeholder-order">' . "\n";

            for ($i = 0; $i < $version->placeholders->count(); $i++) {
                $select_html .= '<option value="' . ($i+1) . '">' . ($i+1) . '</option>' . "\n";
            }

            $select_html .= '</select>' . "\n";

            return view('documents.version-form', [
                'menuItem' => 'docs_tools',
                'version' => $version,
                'order_select_menu' => $select_html
            ]);
        } catch (\Exception $e) {
            Log::critical($e);
        }
    }

    public function pdf_viewer(Request $request) {
        try {
            $template = Template::select('id', 'name')->where('id', '=', $request->id)->first();

            if (!is_null($template)) {
                return view('documents.pdf-viewer', ['menuItem' => 'docs_tools', 'template' => $template]);
            } else {
                // Error 404....not found?
            }
        } catch (\Exception $e) {
            Log::critical($e);
        }
    }

    public function assign(Request $request) {
        try {
            $errors = [];
            $document_details = $request->json()->all();

            $title = trim($document_details['title']);
            $due_date = trim($document_details['due_date']);
            $version_id = filter_var(trim($document_details['version_id']), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

            if ($version_id === null) {
                $errors[] = 'Invalid template version submitted!';
            }

            if (strlen($title) < 4) {
                $errors[] = 'Title must be a minimum of 4 characters!';
            }

            if (!is_array($document_details['signees'])) {
                $errors[] = 'Invalid signee list!';
            } else {
                if (count($document_details['signees']) == 0) {
                    $errors[] = 'You must specify at least 1 signee!';
                }
            }

            if (!is_array($document_details['metatags'])) {
                $errors[] = 'Invalid metatag list!';
            }

            if (!empty($due_date)) {
                if (Carbon::canBeCreatedFromFormat($due_date, 'm-d-Y g:i A')) {
                    $due_date = Carbon::createFromFormat('m-d-Y g:i A', $due_date)->format('Y-m-d H:i:s');
                } else {
                    $errors[] = 'Invalid due date!';
                }
            } else {
                $due_date = null;
            }

            if (preg_match('/^[A-Za-z0-9+\/]+={0,2}$/', $document_details['pdf_document']) !== 1) {
                $errors[] = 'Invalid PDF document!';
            } else {
                $document_details['pdf_document'] = base64_decode($document_details['pdf_document']);
            }

            if (count($errors) == 0) {
                $file_hash = sha1($document_details['pdf_document'], true);
                DB::beginTransaction();

                $valid_placeholders = [];
                $placeholders = Placeholder::where('version_id', '=', $version_id)->get();

                foreach ($placeholders as $placeholder) {
                    $valid_placeholders[] = $placeholder->id;
                }

                $document = Document::create([
                    'version_id' => $version_id,
                    'requestor' => auth()->user()->id,
                    'title' => $title,
                    'checksum' => $file_hash,
                    'metatags' => json_encode($document_details['metatags']),
                    'complete_by' => $due_date
                ]);

                $document->save();
                $signees_to_add = [];
                $now = Carbon::now()->format('Y-m-d H:i:s');

                foreach ($document_details['signees'] as $signee) {
                    if (in_array($signee['placeholder'], $valid_placeholders)) {
                        $signees_to_add[] = [
                            'document_id' => $document->id,
                            'user_id' => $signee['user_id'],
                            'placeholder_id' => $signee['placeholder'],
                            'signed_on' => null,
                            'created_at' => $now, 
                            'updated_at' => $now
                        ];
                    }
                }
                
                if (count($signees_to_add) == count($valid_placeholders)) {
                    Signee::insert($signees_to_add);
                    $pdf_file = bin2hex($file_hash) . '.pdf';
                    Storage::disk('docs')->put($pdf_file, $document_details['pdf_document']);
                    DB::commit();
    
                    return response()->json([
                        'code' => 200,
                        'result' => 'Document registered for signing!'
                    ]);
                } else {
                    DB::rollBack();

                    return response()->json([
                        'code' => 400,
                        'result' => 'Invalid signature placeholders detected!'
                    ]);
                }
            } else {
                return response()->json([
                    'code' => 400,
                    'result' => $errors
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::critical($e);

            return response()->json([
                'code' => 500,
                'result' => 'A system error occurred!'
            ]);
        }
    }

    public function tools(Request $request) {
        return view('documents.tools', ['menuItem' => 'docs_tools']);
    }

    public function signing(Request $request) {
        try {
            $documents = Document::with('version', 'owner')
            ->with([
                'signees' => function ($query) {
                    $query->where('user_id', '=', auth()->user()->id);
                }
            ])
            ->paginate(15);

            return view('documents.signing-list', ['menuItem' => 'docs_tools', 'documents' => $documents]);
        } catch (\Exception $e) {
            Log::critical($e);
        }
    }
}