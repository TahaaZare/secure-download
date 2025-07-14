<?php

namespace Tahaazare\SecureDownload\Enums;

enum FileTypeEnum: string
{
    case Storage = 'storage';
    case Public = 'public';
    case Url = 'url';
}
