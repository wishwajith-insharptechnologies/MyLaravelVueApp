<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileUploadService
{

    public static function ImageUpload($request)
    {
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            $filePaths = [];

            foreach ($files as $file) {
                $path = $file->store('uploads', 'public');
                $filePaths[] = [
                    'name' => $file->getClientOriginalName(),
                    'url' => Storage::url($path),
                ];
            }

            return response()->json([
                'message' => 'Files uploaded successfully',
                'filePaths' => $filePaths
            ], 200);
        }

        return response()->json(['message' => 'No files uploaded'], 400);
    }
}
