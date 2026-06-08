<?php

namespace App\Helpers;

use App\Models\BusinessSetting;
use App\Models\CompanySetting;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class Helper
{
    public static function dashboard_route()
    {
        $user = User::find(Auth::user()->id);
        $route = $user->role->role . '.dashboard';
        return $route;
    }
    public static function getLogoLight()
    {
        return CompanySetting::first()->light_logo ?? asset('assets/img/logo/ba-logo-2.png');
    }
    public static function getLogoDark()
    {
        return CompanySetting::first()->dark_logo ?? asset('assets/img/logo/ba-logo-2.png');
    }
    public static function getFavicon()
    {
        return CompanySetting::first()->favicon ?? asset('assets/img/favicon/ba-favicon.png');
    }
    public static function getCompanyName()
    {
        return CompanySetting::first()->company_name ?? env('APP_NAME');
    }
    public static function getCompanyAddress()
    {
        return CompanySetting::first()->address ?? 'Karachi, Pakistan';
    }
    public static function getCompanyPhone()
    {
        return CompanySetting::first()->phone ?? '';
    }
    public static function getCompanyEmail()
    {
        return CompanySetting::first()->email ?? '';
    }
    public static function getTimezone()
    {
        $systemSetting = SystemSetting::with('timezone')->first();
        return $systemSetting->timezone->name ?? env('APP_TIMEZONE', 'UTC');
    }
    public static function getDefaultLanguage()
    {
        $systemSetting = SystemSetting::with('language')->first();
        return $systemSetting->language->iso_code ?? 'en';
    }
    public static function getfooterText()
    {
        return SystemSetting::first()->footer_text ?? 'All Copyrights Reserved';
    }
    public static function getMaxUploadSize()
    {
        $sizeInKB = SystemSetting::first()->max_upload_size ?? 2048; // Stored in KB

        return self::humanReadableSize($sizeInKB * 1024); // Convert KB to Bytes
    }

    // Helper function to format size into KB, MB, GB, etc.
    public static function humanReadableSize($bytes, $decimals = 2)
    {
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . $sizes[$factor];
    }


    // example use of currency format {{ \App\Helpers\Helper::formatCurrency($price) }}
    public static function formatCurrency($amount)
    {
        $currencySetting = SystemSetting::first();

        $formattedAmount = number_format((float) $amount, 2, '.', ',');

        if (!$currencySetting) {
            return $formattedAmount;
        }

        $symbol = $currencySetting->currency_symbol;
        $position = $currencySetting->currency_symbol_position;

        if ($position === 'prefix') {
            return $symbol . $formattedAmount;
        }

        return $formattedAmount . $symbol;
    }

    public static function renderRecaptcha($formId, $action = 'register')
    {
        if (config('captcha.version') === 'v3') {
            return self::renderRecaptchaV3($formId, $action);
        }
    }

    private static function renderRecaptchaV3($formId, $action)
    {
        $siteKey = config('captcha.sitekey');
        return <<<HTML
            <script src="https://www.google.com/recaptcha/enterprise.js?render={$siteKey}"></script>
            <script>
                function handleRecaptcha(formId, action) {
                    document.getElementById(formId).addEventListener('submit', function(e) {
                        e.preventDefault();
                        grecaptcha.enterprise.ready(async () => {
                            try {
                                const token = await grecaptcha.enterprise.execute('{$siteKey}', { action: action });
                                const form = document.getElementById(formId);
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'g-recaptcha-response';
                                input.value = token;
                                form.appendChild(input);
                                form.submit();
                            } catch (error) {
                                console.error('Error executing reCAPTCHA:', error);
                            }
                        });
                    });
                }

                document.addEventListener('DOMContentLoaded', function() {
                    handleRecaptcha('{$formId}', '{$action}');
                });
            </script>
        HTML;
    }

    public static function numberToWords($num)
    {
        // 🔹 clean input (fix 0.00 issue)
        $num = (float) $num;

        if ($num == 0) {
            return "Zero Only";
        }

        return ucfirst(trim(self::convert((int)$num))) . " Only";
    }

    private static function convert($num)
    {
        $ones = [
            0 => "",
            1 => "one",
            2 => "two",
            3 => "three",
            4 => "four",
            5 => "five",
            6 => "six",
            7 => "seven",
            8 => "eight",
            9 => "nine",
            10 => "ten",
            11 => "eleven",
            12 => "twelve",
            13 => "thirteen",
            14 => "fourteen",
            15 => "fifteen",
            16 => "sixteen",
            17 => "seventeen",
            18 => "eighteen",
            19 => "nineteen"
        ];

        $tens = [
            2 => "twenty",
            3 => "thirty",
            4 => "forty",
            5 => "fifty",
            6 => "sixty",
            7 => "seventy",
            8 => "eighty",
            9 => "ninety"
        ];

        if ($num < 20) {
            return $ones[$num];
        }

        if ($num < 100) {
            return $tens[intval($num / 10)] . " " . $ones[$num % 10];
        }

        if ($num < 1000) {
            return $ones[intval($num / 100)] . " hundred " . self::convert($num % 100);
        }

        if ($num < 100000) {
            return self::convert(intval($num / 1000)) . " thousand " . self::convert($num % 1000);
        }

        if ($num < 10000000) {
            return self::convert(intval($num / 100000)) . " lakh " . self::convert($num % 100000);
        }

        return self::convert(intval($num / 10000000)) . " crore " . self::convert($num % 10000000);
    }
}
