<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;

class ImageController extends Controller
{
    public function checkFolder(Request $request)
    {
        $folderPath = public_path('src/images'); // Path to your folder

        // Get all files in the directory
        $files = File::files($folderPath);

        // Check if there are any files
        if (count($files) > 0) {
            // Get the first file
            $firstFile = $files[0];

            // Return a JSON response with file existence and URL
            return response()->json([
                'exists' => true,
                'url' => asset('src/images/' . basename($firstFile)) // Return the URL of the file
            ]);
        } else {
            return response()->json(['exists' => false, 'message' => 'There is no file to display.'], 404);
        }
    }
}
