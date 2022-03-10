<?php

namespace App\Http\Controllers;

use App\Models\Placeholder;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PDFServiceController extends Controller
{
    public static function register_pdf_signature_placeholders($template_id, $version_id, $version_basename) {
        try {
            $sig_placeholder_uri = config('app.pdf_microservice_url') . '/templates/version/placeholders/' . $template_id . '/' . $version_basename;
            $response = Http::get($sig_placeholder_uri);

            if ($response->ok()) {
                foreach ($response->json() as $i => $field_name) {
                    $placeholder = Placeholder::create([
                        'version_id' => $version_id,
                        'pdf_name' => $field_name,
                        'friendly_name' => '',
                        'order' => ($i + 1)
                    ]);
                    
                    $placeholder->save();
                }

                return 200;
            } else {
                return $response->status();
            }
        } catch (\Exception $e) {
            Log::critical($e);
            return 500;
        }
    }
}