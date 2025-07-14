<?php

namespace Tahaazare\SecureDownload;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Tahaazare\SecureDownload\Enums\FileTypeEnum;
use Tahaazare\SecureDownload\Enums\TimeUnitEnum;
use Tahaazare\SecureDownload\Models\SecureDownloadLink;

class SecureDownload
{
    /**
     * Generate a secure temporary download link.
     *
     * @param string $filePath File path or URL
     * @param int|null $time Duration (default from config)
     * @param TimeUnitEnum $unit Time unit (seconds, minutes, hours)
     * @param FileTypeEnum $type File storage type
     * @param int|null $maxDownloads Maximum allowed downloads (null = unlimited)
     * @return string Signed temporary URL
     */
    public static function generate(
        string $filePath,
        ?int $time = null,
        TimeUnitEnum $unit = TimeUnitEnum::Seconds,
        FileTypeEnum $type = FileTypeEnum::Storage,
        ?int $maxDownloads = null
    ): string {
        $time = $time ?? config('secure-download.lifetime');
        $now = now();

        $expires = match ($unit) {
            TimeUnitEnum::Minutes => $now->addMinutes($time)->timestamp,
            TimeUnitEnum::Hours   => $now->addHours($time)->timestamp,
            default               => $now->addSeconds($time)->timestamp,
        };

        $payloadHash = self::buildHash($filePath, $expires, $type->value);

        SecureDownloadLink::updateOrCreate(
            ['payload_hash' => $payloadHash],
            [
                'file_path'        => $filePath,
                'type'             => $type->value,
                'expires_at'       => now()->setTimestamp($expires),
                'user_id'          => Auth::id() ?? null,
                'download_count'   => 0,
                'last_download_at' => null,
                'max_downloads'    => $maxDownloads,
            ]
        );

        $payload = [
            'file'    => $filePath,
            'type'    => $type->value,
            'expires' => $expires,
            'hash'    => $payloadHash,
        ];

        return URL::temporarySignedRoute('secure-download.file', $expires, [
            'payload' => base64_encode(json_encode($payload)),
        ]);
    }

    /**
     * Generate hash for payload.
     *
     * @param string $file
     * @param int $expires
     * @param string $type
     * @return string
     */
    public static function buildHash(string $file, int $expires, string $type): string
    {
        return hash_hmac('sha256', $file . $expires . $type, config('secure-download.secret'));
    }
}
