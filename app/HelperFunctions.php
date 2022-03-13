<?php
namespace App;

use App\Models\Group;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class HelperFunctions
{
    public static function max_upload_size() {
        $matches = [];
        $max_upload_size_val = preg_match('/(\d+.*)/i', trim(ini_get('upload_max_filesize')), $matches);   
        return ($matches[1] . 'B');
    }

    public static function get_parent_groups($group_id) {
        $group = Group::select('id', 'parent_id')->where('id', '=', $group_id)->first();
        $ancestors = [];

        while (!is_null($group) && !is_null($group->parent_id)) {
            $ancestors[] = $group->parent_id;
            $group = Group::select('parent_id')->where('id', '=', $group->parent_id)->first();
        }

        return $ancestors;
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

    public static function convertDatetimeField($db_datetime, $new_format)
    {
        $db_field = Carbon::createFromFormat('Y-m-d H:i:s', $db_datetime);
        return $db_field->format($new_format);
    }
}