# 🔒 Laravel Secure Download | لینک دانلود امن در لاراول

## 📖 Introduction | معرفی


این پکیج به شما کمک می‌کند تا برای فایل‌های خود لینک دانلود **امن و موقت** تولید کنید.  
به‌طوری‌که لینک فقط برای مدت مشخصی معتبر باشد (مثلاً ۳۰ ثانیه، ۵ دقیقه یا ۲ ساعت)  
و فقط برای یک مسیر مشخص قابل استفاده باشد.

همچنین برای اینکه محدودیت تعداد دانلود و اطلاعات لینک‌ها به درستی ذخیره شود،  


This package helps you generate secure, temporary download links for your files in Laravel.
Each link is valid for a specific time (e.g., 30 seconds, 5 minutes, or 2 hours)
and is only usable for the given file path.

To track download limits and link information,
you must run database migrations after installation to create the necessary table
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
