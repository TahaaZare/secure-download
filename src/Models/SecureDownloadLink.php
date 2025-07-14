<?php

namespace Tahaazare\SecureDownload\Models;

use Illuminate\Database\Eloquent\Model;

class SecureDownloadLink extends Model
{
    protected $fillable = [
        'payload_hash',
        'file_path',
        'type',
        'expires_at',
        'user_id',
        'download_count',
        'max_downloads',
        'last_download_at',
    ];

    protected $dates = [
        'expires_at',
        'last_download_at',
        'created_at',
        'updated_at',
    ];
}
