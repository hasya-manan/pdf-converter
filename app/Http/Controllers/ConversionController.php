<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConversionController extends Controller
{
     public function convert(Request $request)
    {
        // 1. Validate the request
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240', // max 10MB
            'format' => 'required|string|in:pdf-to-word,word-to-pdf'
        ]);

        // 2. Get the uploaded file and the requested format
        $file = $request->file('file');
        $format = $request->input('format');

        // We will add the actual Docswap conversion logic here in the next step.
        // For now, let's just test if the file is reaching Laravel successfully.
        
        return response()->json([
            'message' => "Successfully received file: " . $file->getClientOriginalName(),
            'format' => $format,
            'status' => 'success'
        ]);
    }
}
