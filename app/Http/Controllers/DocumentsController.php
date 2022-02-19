<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\LibraryEntity;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentsController extends Controller
{
    public function library_store(Request $request) {
        try {
            $errors = [];

            $doc_name = trim($request->input('document_name'));
            $doc_description = trim($request->input('document_description'));
            $doc_shared = filter_var($request->input('document_shared'), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            $doc_major_ver = filter_var(trim($request->input('document_major_ver')), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            $doc_minor_ver = filter_var(trim($request->input('document_minor_ver')), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            $doc_patch_ver = filter_var(trim($request->input('document_patch_ver')), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

            if (strlen($doc_name) < 3) {
                $errors[] = 'Document name must be at least 3 characters!';
            }

            if ($doc_shared === null) {
                $errors[] = 'Invalid input for document sharing setting!';
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
                $document = LibraryEntity::where('checksum', '=', $checksum)->first();

                if ($document === null) {
                    $head_vers = 'v' . $doc_major_ver . '.' . $doc_minor_ver . '.' . $doc_patch_ver;

                    $library_entity = LibraryEntity::create([
                        'name' => $doc_name,
                        'filename' => $request->file('document_file')->getClientOriginalName(),
                        'head_version' => $head_vers,
                        'checksum' => bin2hex($checksum),
                        'owner' => auth()->user()->id,
                        'shared' => $doc_shared,
                        'description' => $doc_description
                    ]);
    
                    $library_entity->save();

                    $local_directory = 'private/library/' . $library_entity->id;
                    Storage::disk('local')->makeDirectory($local_directory);
                    $path = Storage::disk('local')->putFile($local_directory, $request->file('document_file'));

                    return response()->json([
                        'code' => 200,
                        'result' => 'Document added!'
                    ]);
                } else {
                    return response()->json([
                        'code' => 409,
                        'result' => 'Document exists in the library already!'
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
                'result' => 'An error occurred in the system!'
            ]);
        }
    }

    public function tools(Request $request) {
        return view('documents.tools', ['menuItem' => 'docs_tools']);
    }

    public function signing(Request $request) {
        return view('documents.signing-list', ['menuItem' => 'docs_tools']);
    }

    public function library(Request $request) {
        return view('documents.library-view', ['menuItem' => 'docs_tools']);
    }

    public function upload(Request $request) {
        return view('documents.upload', ['menuItem' => 'docs_tools']);
    }
}