<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7\MimeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function preview(Request $request)
    {
        abort_unless($request->filled('payload'), 400);

        try {
            // $filepath = $request->get('payload');
            $filepath = Crypt::decryptString($request->get('payload'));
        } catch (\Exception $exception) {
            return response('Bad Request', 400);
        }

        abort_unless(Storage::exists($filepath), 404, 'File tidak ditemukan!');

        $headers = [
            'Content-Type' => MimeType::fromFilename(basename($filepath)),
            'Content-Disposition' => 'inline; filename="' . basename($filepath) . '"',
            'Content-Transfer-Encoding' => 'binary',
            'Accept-Ranges' => 'bytes'
        ];
        if ($this->fileCanBePreviewed($filepath)) {
            $headers = [];
        }

        return response()->file(Storage::path($filepath), $headers);
    }

    private function fileCanBePreviewed(string $filepath): bool
    {
        $viewableMimes = [
            'application\/pdf',
            'application\/json',
            'text\/*',
            'audio\/*',
            'video\/*'
        ];

        $fileMime = MimeType::fromFilename(basename($filepath));
        foreach ($viewableMimes as $mime) {
            if (preg_match('/^' . $mime . '/', $fileMime)) return true;
        }

        return false;
    }
}
