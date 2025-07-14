# 🔒 Laravel Secure Download | لینک دانلود امن در لاراول

این پکیج به شما کمک می‌کند تا برای فایل‌های خود لینک دانلود **امن و موقت** تولید کنید.  
به‌طوری‌که لینک فقط برای مدت مشخصی معتبر باشد (مثلاً ۳۰ ثانیه، ۵ دقیقه یا ۲ ساعت)  
و فقط برای یک مسیر مشخص قابل استفاده باشد.

This package allows you to generate **temporary signed download links** for your files in Laravel.  
Each link is valid for a limited time and specific file path.
---

## 📦 نصب | Installation

## 1. Install the Package

Run the following command in your Laravel project terminal:

```bash
composer require tahaazare/secure-download
```

## 2. Publish the Configuration File (Optional but Recommended)

```bash
php artisan vendor:publish --provider="Tahaazare\SecureDownload\SecureDownloadServiceProvider"
```


The config file will be located at

```bash 
config/secure-download.php
```


## 3. Set the Security Key in `.env`
```bash
SECURE_DOWNLOAD_SECRET=your-secure-key
```

----

## 🛠 ساخت لینک دانلود امن | Generate Secure Download Link

```bash
$link = SecureDownload::generate(
    'files/report.pdf',
    30,
    TimeUnitEnum::Seconds,
    FileTypeEnum::Storage,
    null, //int | max_downloads 
);
```
