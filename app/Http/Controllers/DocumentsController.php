<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\Version;
use App\Models\Document;
use App\Models\Template;

use App\HelperFunctions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Mews\Purifier\Facades\Purifier;

class DocumentsController extends Controller
{
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
                    Storage::disk('local')->makeDirectory($local_directory);
                    Storage::disk('local')->putFileAs($local_directory, $request->file('document_file'), (bin2hex($checksum) . '.pdf'));

                    DB::commit();

                    return response()->json([
                        'code' => 200,
                        'result' => 'Template added!'
                    ]);
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
            return view('documents.template-blank', ['menuItem' => 'docs_tools', 'template_id' => $request->id]);
        } catch (\Exception $e) {
            Log::critical($e);
        }
    }

    public function tools(Request $request) {
        return view('documents.tools', ['menuItem' => 'docs_tools']);
    }

    public function signing(Request $request) {
        return view('documents.signing-list', ['menuItem' => 'docs_tools']);
    }

    public function templates(Request $request) {
        try {
            $templates = Template::select('templates.id', 'templates.name', 'versions.semver', 'templates.filename', 'users.name AS owner_name', 'templates.description', 'templates.metatags')
            ->leftJoin('versions', 'templates.head_version', '=', 'versions.template_id')
            ->leftJoin('users', 'templates.owner_user', '=', 'users.id')
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
}