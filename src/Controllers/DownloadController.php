<?php

namespace Tahaazare\SecureDownload\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Tahaazare\SecureDownload\Models\SecureDownloadLink;
use Tahaazare\SecureDownload\SecureDownload;

class DownloadController extends Controller
{
    public function handle(Request $request)
    {
        $payload = json_decode(base64_decode($request->payload), true);

        if (!isset($payload['file'], $payload['expires'], $payload['hash'], $payload['type'])) {
            abort(400, 'Invalid payload');
        }

        $filePath = $payload['file'];
        $expires = $payload['expires'];
        $type = $payload['type'];
        $providedHash = $payload['hash'];

        $expectedHash = SecureDownload::buildHash($filePath, $expires, $type);

        if ($providedHash !== $expectedHash || now()->timestamp > $expires) {
            abort(403, 'Invalid or expired link');
        }

        $linkRecord = SecureDownloadLink::where('payload_hash', $providedHash)->first();

        if (!$linkRecord) {
            abort(403, 'Link not found or invalid');
        }

        if (
            $linkRecord->max_downloads !== null &&
            $linkRecord->download_count >= $linkRecord->max_downloads
        ) {
            abort(403, 'Download limit reached');
        }

        $linkRecord->increment('download_count');
        $linkRecord->update(['last_download_at' => now()]);

        switch (strtolower($type)) {
            case 'storage':
                $fullPath = storage_path('app/' . $filePath);
                break;

            case 'public':
                $fullPath = public_path($filePath);
                break;

            case 'url':
                return redirect()->away($filePath);

            default:
                abort(400, 'Invalid file type');
        }

        if (!file_exists($fullPath)) {
            abort(404, 'File not found');
        }

        return response()->download($fullPath);
    }
}
