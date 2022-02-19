<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\Version;
use App\Models\Document;
use App\Models\Template;

use App\HelperFunctions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

                    $template = Template::create([
                        'name' => $doc_name,
                        'filename' => $request->file('document_file')->getClientOriginalName(),
                        'head_version' => null,
                        'owner' => auth()->user()->id,
                        'group_read' => $group_read,
                        'group_edit' => $group_edit,
                        'child_read' => $subgroup_read,
                        'child_edit' => $subgroup_read,
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
                    $path = Storage::disk('local')->putFileAs($local_directory, $request->file('document_file'), (bin2hex($checksum) . '.pdf'));

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
            Log::critical($e);
            return response()->json([
                'code' => 500,
                'result' => 'A system error has occurred!'
            ]);
        }
    }

    public function tools(Request $request) {
        return view('documents.tools', ['menuItem' => 'docs_tools']);
    }

    public function signing(Request $request) {
        return view('documents.signing-list', ['menuItem' => 'docs_tools']);
    }

    public function templates(Request $request) {
        return view('documents.templates-view', ['menuItem' => 'docs_tools']);
    }

    public function template_form(Request $request) {
        return view('documents.template-form', ['menuItem' => 'docs_tools']);
    }
}