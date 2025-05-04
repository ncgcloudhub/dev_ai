<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FlipbookController extends Controller
{
    public function showUploadForm()
    {
        return view('backend.flipbook.upload');
    }

    public function upload(Request $request)
    {
        $pdfUrl = 'https://file-examples.com/storage/fe07feb1a26815fd492794e/2017/10/file-sample_150kB.pdf';

        // Your Heyzine Client ID (store in .env and services.php)
        $clientId = config('services.heyzine.client_id');

        // Encode PDF URL for use in query string
        $encodedPdfUrl = urlencode($pdfUrl);

        // Build Heyzine flipbook URL
        $flipbookUrl = "https://heyzine.com/api1?pdf={$encodedPdfUrl}&k={$clientId}&t=My+Flipbook&s=Public+Test+PDF&d=1&fs=1";

        return view('backend.flipbook.result', [
            'url' => $flipbookUrl,
            'embed' => "<iframe src=\"{$flipbookUrl}\" width=\"100%\" height=\"600px\" frameborder=\"0\" allowfullscreen></iframe>"
        ]);
    }
}
