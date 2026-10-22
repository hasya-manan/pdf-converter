<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Paperdoc\Facades\Paperdoc;
use Illuminate\Support\Str;
use Exception;

class ConversionController extends Controller
{
        public function convert(Request $request)
        {
            // 1. Validate the request
            $request->validate([
                'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
                'format' => 'required|string|in:pdf-to-word,word-to-pdf'
            ]);

            $file = $request->file('file');
            $format = $request->input('format');

            try {
                // 2. Save the uploaded file temporarily
                $tempName = Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('temp', $tempName, 'public');
                
                // Get absolute path for input
                $inputPath = storage_path('app/public/temp/' . $tempName);

                // 3. Determine output path and extension
                $outputExtension = ($format === 'pdf-to-word') ? 'docx' : 'pdf';
                
                // Get original name without extension, and add the new extension
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // Add a tiny random string just in case two people upload "Document.pdf" at the exact same second
                $outputName = $originalName . '-' . Str::random(4) . '.' . $outputExtension; 
                
                // Get absolute path for output
                $outputPath = storage_path('app/public/temp/' . $outputName);

                // 4. Perform the conversion using Paperdoc
                // Paperdoc's convert method takes (inputPath, outputPath, targetFormat)
                Paperdoc::convert($inputPath, $outputPath, $outputExtension);

                // 5. Clean up the original uploaded file
                if (file_exists($inputPath)) {
                    unlink($inputPath);
                }

                // 6. Return the download URL
                        
                return response()->json([
                    'message' => 'Conversion successful!',
                    'status' => 'success',
                
                    'download_url' => url('/download/' . $outputName . '?name=' . urlencode($originalName . '.' . $outputExtension))
                ]);

                } catch (Exception $e) {
                // Log the error so we can see it in Render!
                \Log::error('Conversion Failed: ' . $e->getMessage());
                
                return response()->json([
                    'message' => 'Conversion failed: ' . $e->getMessage(),
                    'status' => 'error'
                ], 500);
            }
        }

       public function download(Request $request, $filename)
    {
        // Check multiple possible locations
        $paths = [
            storage_path('app/public/temp/' . $filename),
            storage_path('app/temp/' . $filename),
            public_path('temp/' . $filename),
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                // Get the custom name from the URL, or fallback to the random name
                $downloadName = $request->query('name', $filename);
                
                // Download the file WITHOUT deleting it immediately
                return response()->download($path, $downloadName);
            }
        }

        return response()->json(['error' => 'File not found on server. It may have been deleted after a server restart.'], 404);
    }
}
