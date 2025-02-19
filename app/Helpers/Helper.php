<?php 

namespace App\Helpers;
use Carbon\Carbon;


class Helper{
    public static function active_class($path, $active = 'active') {
        return call_user_func_array('Request::is', (array)$path) ? $active : '';
    }

    public static function is_active_route($path) {
        return call_user_func_array('Request::is', (array)$path) ? 'true' : 'false';
    }
    public static function show_class($path) {
        return call_user_func_array('Request::is', (array)$path) ? 'open' : '';
    }

    public static function fileUpload($file,$function,$key = null)
    {
        $data = [];
        $type = "";
        $size = "";
        $name = "";
        $extension = $file->getClientOriginalExtension();
        $filename = time() . $key . '.' . $extension;
        $size = Helper::formatSizeUnits($file->getSize());
        $file->move(public_path('images/'.$function), $filename);
        $path = 'images/'.$function.'/' . $filename;
        $name = $file->getClientOriginalName();
        if ($extension == "pdf") {
            $type = "pdf";
        } else if ($extension == "mp4" || $extension == "webm") {
            $type = "video";
        } else if ($extension == "jpg" || $extension == "png" || $extension == "jpeg" || $extension == "gif") {
            $type = "image";
        } else if ($extension == "mp3") {
            $type = "audio";
        } else {
            $type = "unknown";
        }
        $data['type'] = $type;
        $data['name'] = $name;
        $data['path'] = $path;
        $data['size'] = $size;
        return $data;
    }
    public static function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}