<?php

namespace App\Http\Controllers;

use App\Models\Signature;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SignatureController extends Controller
{
    public function update_visible(Request $request) {
        try {
            if ($request->id == auth()->user()->id) {
                $visible_signature = $request->json()->all();
   
                if (!empty($visible_signature['image'])
                    && !empty($visible_signature['controlPoints'])) {
                        $result = Signature::where('user_id', '=', auth()->user()->id)
                        ->update(['visual_signature_cpts' => json_encode($visible_signature['controlPoints'])]);
                        $local_filename = 'private/signatures/' . auth()->user()->id . '/visible.png';
    
                        if ($result == 1) {
                            $image_url = explode(',', $visible_signature['image']);
                            Storage::disk('local')->put($local_filename, base64_decode($image_url[1]));
    
                            return response()->json([
                                'code' => 200,
                                'result' => 'Visible signature updated!'
                            ]);
                        } else {
                            return response()->json([
                                'code' => 500,
                                'result' => 'Visible signature update failed!'
                            ]);
                        }
                } else {
                    return response()->json([
                        'code' => 400,
                        'result' => 'Bad Request'
                    ]);
                }
            } else {
                return response()->json([
                    'code' => 403,
                    'result' => 'Insufficient Access'
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

    public function signature_cpts(Request $request) {
        try {
            if ($request->id == auth()->user()->id) {
                $result = Signature::select('visual_signature_cpts')
                ->where('user_id', '=', $request->id)
                ->first();

                $json = (!empty($result->visual_signature_cpts)) ? json_decode($result->visual_signature_cpts) : '';

                return response()->json([
                    'code' => 200,
                    'result' => $json
                ]);
            } else {
                return response()->json([
                    'code' => 403,
                    'result' => 'Forbidden resource request!'
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

    public function signatures(Request $request) {
        if ($request->id == auth()->user()->id) {
            return view('signatures.index', ['menuItem' => 'my_sigs', 'user_id' => $request->id]);
        } else {
            //TODO Error 403
        }
    }
}