<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $folderPath = public_path('src/images'); // Path to your folder

        Log::info('Incoming request data:', [
            'input' => $request->all(),
        ]);

        $validator = Validator::make($request->all(), [
            'file-upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->all());
            return response()->json(['error' => $validator->errors()->all()], 422); // Return validation errors
        }

        // Handle the file upload
        if ($request->hasFile('file-upload')) {
            $files = File::files($folderPath);

            // Delete all files
            foreach ($files as $file) {
                File::delete($file);
            }

            $file = $request->file('file-upload');
            $uploadPath = 'src/images/'; // Specify your upload directory
            $fileName = 'image.' . $file->getClientOriginalExtension(); // Always name it image with the original extension
            $file->move(public_path($uploadPath), $fileName); // Move the file to the specified directory

            return "File uploaded successfully"; // Return plain text
        }

        return "error";
    }
}
