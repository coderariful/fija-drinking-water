<?php

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

if (!function_exists('auth_user')) {
    function auth_user(): User|Authenticatable|null
    {
        return auth()->user();
    }
}

if (!function_exists('user')) {
    function user(): User|Authenticatable|null
    {
        return auth()->user();
    }
}

if (!function_exists('generalSettings')) {
    function generalSettings(?string $key = null): array|string|null
    {
        if ($key){
            return cache(CACHE_GENERAL_SETTINGS)[$key] ?? null;
        }
        return cache(CACHE_GENERAL_SETTINGS) ?? [];
    }
}

if (!function_exists('settings')) {
    function settings(?string $key = null, $fallback = null): array|string|null
    {
        $value = generalSettings($key);

        return is_null($value) ? $fallback : $value;
    }
}

if (!function_exists('httpPostCurl')) {
    function httpPostCurl($url, array $postData)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        return curl_exec($ch);
    }
}

function paginationIndex(LengthAwarePaginator $paginator, $iterationIndex): int
{
    return ($paginator->currentPage() - 1) * $paginator->perPage() + $iterationIndex;
}

function banglaMonth($month): string
{
    $month = strtolower($month);
    $banglaMonth = [
        'january' => 'জানুয়ারি',
        'february' => 'ফেব্রুয়ারি',
        'march' => 'মার্চ',
        'april' => 'এপ্রিল',
        'may' => 'মে',
        'june' => 'জুন',
        'july' => 'জুলাই',
        'august' => 'আগষ্ট',
        'september' => 'সেপ্টেম্বর',
        'october' => 'অক্টোবার',
        'november' => 'নভেম্বর',
        'december' => 'ডিসেম্বর',
    ];

    return $banglaMonth[$month];
}


function formatDateTime(?string $str_datetime, $format = 'd M Y h:i A'): string
{
    if (!$str_datetime) {
        return '-';
    }

    $dt = Carbon::parse($str_datetime);

    return $dt->format($format);
}

function formatDate(?string $str_date, $format = null): string
{
    $format = $format ?? 'd M Y';

    return formatDateTime($str_date, $format);
}


function roundFormat(float|int|string|null $value, $decimal = 2): string
{
    return number_format(roundValue($value, $decimal), $decimal);
}

function roundValue(float|int|string|null $value, $decimal = 2): float
{
    return round(floatval($value ?? 0), $decimal);
}

function whereProductTypeWater($q): void
{
    $q->where('product_type', PRODUCT_WATER);
}
