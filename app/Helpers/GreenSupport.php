<?php

use Illuminate\Support\Str;

if (!function_exists('active_setting')) {
    function active_setting($active): bool
    {
        $settingType = setting('settingtype') ?? 'generalsetting';

        return $settingType == $active;
    }
}

if (!function_exists('green_site_logo')) {
    function green_site_logo(): string
    {
        $siteLogo = setting('site_logo') ?? 'logo.png';
        return asset('img/' . $siteLogo);
    }
}

if (!function_exists('green_number_format')) {
    function green_number_format($amount): string
    {
        return setting('currency_symbol') . number_format($amount, 2);
    }
}

if (!function_exists('green_invoice_no')) {
    function green_invoice_no($value): string
    {
        return 'INV-'.Str::padLeft($value, 6, 0);
    }
}

if (!function_exists('green_quotation_no')) {
    function green_quotation_no($value): string
    {
        return 'QUO-'.Str::padLeft($value, 6, 0);
    }
}

if (!function_exists('tax_name_generate')) {
    function tax_name_generate($tax): string
    {
        return $tax->name . ' (' . $tax->percent . '%)';
    }
}
