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
}