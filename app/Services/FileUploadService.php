<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{

    public static function ImageUpload($request)
    {

        if ($request->hasFile('file')) {
            $file = $request->file('file');

                $path = $file->store('uploads', 'public');
                $filePaths = [
                    'name' => $file->getClientOriginalName(),
                    'url' => $path,
                ];
            return  $filePaths;
        }

        return "no file";
    }

    public static function packageImageUpload($request)
    {
            try {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $uploadDirectory = '/public/images/product';
                if (!is_dir($uploadDirectory)) {
                    mkdir($uploadDirectory, 0755, true);
                }
                $image->move(public_path() .$uploadDirectory, $imageName);
                return $imageName;
            } catch (\Exception $e) {
                Log::error('Error uploading image: ' . $e->getMessage());
                return false;
            }
    }
}
