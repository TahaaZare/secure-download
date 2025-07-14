<?php

namespace Tahaazare\SecureDownload\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Tahaazare\SecureDownload\Models\SecureDownloadLink;

class DownloadController extends Controller
{
    public function handle(Request $request)
    {
        $payload = json_decode(base64_decode($request->payload), true);

        if (!isset($payload['file'], $payload['expires'], $payload['hash'], $payload['type'])) {
            abort(400, 'Invalid payload');
        }

        $expectedHash = hash_hmac('sha256', $payload['file'] . $payload['expires'] . $payload['type'], config('secure-download.secret'));

        if ($payload['hash'] !== $expectedHash || now()->timestamp > $payload['expires']) {
            abort(403, 'Invalid or expired link');
        }

        $linkRecord = SecureDownloadLink::where('payload_hash', $payload['hash'])->first();

        if (!$linkRecord) {
            abort(403, 'Link not found or invalid');
        }

        $maxDownloads = $linkRecord->max_downloads;

        if ($maxDownloads && $linkRecord->download_count > $linkRecord->max_downloads) {
            abort(403, 'Download limit reached');
        }

        $linkRecord->increment('download_count');
        $linkRecord->last_download_at = now();
        $linkRecord->save();

        $type = $payload['type'];
        $filePath = $payload['file'];

        switch ($type) {
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
