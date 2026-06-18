<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;

class FunctionControl extends Controller
{
    public static function upload_image($image_file,$folder)
    {
        $arr = [];
        $allowedExt  = ['jpg', 'jpeg', 'png', 'webp'];
        $allowedMime = [
            'image/jpeg',
            'image/png',
            'image/webp',
        ];
        $ext  = strtolower($image_file->getClientOriginalExtension());
        $mime = $image_file->getClientMimeType();
        if (!in_array($ext, $allowedExt) || !in_array($mime, $allowedMime)) {
            $arr['title'] = "รูปแบบไฟล์ไม่ถูกต้อง";
            $arr['text'] = "ระบบรองรับเฉพาะไฟล์รูปภาพ (JPG, JPEG, PNG, WEBP) เท่านั้น";
            $arr['status'] = 422;
        }else{
            $filename = 'image_' . date('dmY_His') . '.' . $image_file->getClientOriginalExtension();
            $path = $image_file->storeAs($folder, $filename, 'public');
            if($path){
                $arr['path'] = $path;
                $arr['name'] = $filename;
                $arr['ext'] = $image_file->getClientOriginalExtension();
                $arr['status'] = 200;
                $arr['message'] = "SUCCESS";
            }else{
                $arr['path'] = null;
                $arr['status'] = 500;
                $arr['message'] = "ERROR";
            }
        }

        return $arr;
    }

    public static function upload_imagefile($image_file,$folder)
    {
        $arr = [];
        $allowedExt  = ['pdf', 'jpg', 'jpeg', 'png', 'webp'];
        $allowedMime = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/webp',
        ];
        $ext  = strtolower($image_file->getClientOriginalExtension());
        $mime = $image_file->getClientMimeType();
        if (!in_array($ext, $allowedExt) || !in_array($mime, $allowedMime)) {
            $arr['title'] = "รูปแบบไฟล์ไม่ถูกต้อง";
            $arr['text'] = "ระบบรองรับเฉพาะไฟล์รูปภาพ (JPG, JPEG, PNG, WEBP) เท่านั้น";
            $arr['status'] = 422;
        }
        else{
            $filename = 'image_' . date('dmY_His') . '.' . $image_file->getClientOriginalExtension();
            $path = $image_file->storeAs($folder, $filename, 'public');
            if($path){
                $arr['path'] = $path;
                $arr['name'] = $filename;
                $arr['ext'] = $image_file->getClientOriginalExtension();
                $arr['status'] = 200;
                $arr['message'] = "SUCCESS";
            }else{
                $arr['path'] = null;
                $arr['status'] = 500;
                $arr['message'] = "ERROR";
            }
        }
        return $arr;
    }

    public static function upload_file($file, string $folder, array $allowedExt = [], array $allowedMime = [], $i=null)
    {
        $arr = [];
        if (empty($allowedExt)) {
            $allowedExt = ['pdf', 'jpg', 'jpeg', 'png', 'webp'];
        }
        $ext  = strtolower($file->getClientOriginalExtension());
        $mime = $file->getClientMimeType();
        if (!in_array($ext, $allowedExt)) {
            $arr['title']  = "รูปแบบไฟล์ไม่ถูกต้อง";
            $arr['text']   = "ระบบรองรับเฉพาะไฟล์ประเภท: " . strtoupper(implode(', ', $allowedExt)) . " เท่านั้น";
            $arr['status'] = 422;
            return $arr;
        }
        if (!empty($allowedMime) && !in_array($mime, $allowedMime)) {
            $arr['title']  = "รูปแบบไฟล์ไม่ถูกต้อง";
            $arr['text']   = "ชนิดไฟล์ (MIME) ไม่ถูกต้อง";
            $arr['status'] = 422;
            return $arr;
        }
        $filename = 'file_'.$i.'_'. date('dmY_His') . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $filename, 'public');
        if ($path) {
            $arr['path']    = $path;
            $arr['name']    = $filename;
            $arr['ext']     = $file->getClientOriginalExtension();
            $arr['status']  = 200;
            $arr['message'] = "SUCCESS";
        } else {
            $arr['path']    = null;
            $arr['status']  = 500;
            $arr['message'] = "ERROR";
        }
        return $arr;
    }
}
