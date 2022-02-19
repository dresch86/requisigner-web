<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class HelperFunctions
{
    public static function max_upload_size() {
        $matches = [];
        $max_upload_size_val = preg_match('/(\d+.*)/i', trim(ini_get('upload_max_filesize')), $matches);   
        return ($matches[1] . 'B');
    }

    public static function metatag_json_string($string) {
        $sanitized = strip_tags(trim($string));
        $tags = explode(',', $sanitized);
        $tag_count = count($tags);

        for ($i=0; $i < $tag_count; $i++) {
            $tags[$i] = trim($tags[$i]); 
        }

        return json_encode($tags);
    }
}