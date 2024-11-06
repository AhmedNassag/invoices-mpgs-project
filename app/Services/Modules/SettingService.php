<?php

namespace App\Services\Modules;

use App\Http\Requests\SettingRequest;
use App\Libraries\GreenSupport;
use App\Services\Notifications\SettingNotificationService;
use Illuminate\Support\Facades\Artisan;

class SettingService
{

    public function storeSettingType(): void
    {
        $settingtype = request('settingtype', 'generalsetting');

        setting(['settingtype' => $settingtype]);
    }

    public function storeSettingInformation(SettingRequest $request): void
    {
        $settingtype = setting('settingtype');

        if ($settingtype == 'generalsetting') {
            $this->storeGeneralSetting($request);

            app(SettingNotificationService::class)->generalSettingUpdatedToPermissionUser($request->user());
        }

        if ($settingtype == 'emailsetting') {
            $this->storeEmailSetting($request);
        }

        if ($settingtype == 'paymentsetting') {
            if ($request->get('paymentgateway') === 'stripe') {
                $this->storeStripePaymentSetting($request);
            }

            if ($request->get('paymentgateway') === 'razorpay') {
                $this->storeRazorPayPaymentSetting($request);
            }

            if ($request->get('paymentgateway') === 'mpgs') {
                $this->storeMpgsPaymentSetting($request);
            }
        }
    }

    private function storeGeneralSetting($request): void
    {
        $generalSettings['site_name'] = request('site_name');
        $generalSettings['email'] = request('email');
        $generalSettings['phone'] = request('phone');
        $generalSettings['address'] = request('address');
        $generalSettings['copyright_by'] = request('copyright_by');
        $generalSettings['currency_symbol'] = request('currency_symbol');
        $generalSettings['currency_code'] = request('currency_code');
        $generalSettings['site_sidebar'] = request('site_sidebar');

        if ($request->hasFile('site_logo')) {
            $generalSettings['site_logo'] = $request->site_logo->hashName();
            $generalSettings['site_favicon'] = $generalSettings['site_logo'];
            $request->site_logo->move(public_path('img'), $generalSettings['site_logo']);
        } else {
            unset($generalSettings['site_logo']);
        }

        if (isset($generalSettings['timezone'])) {
            GreenSupport::setEnv('APP_TIMEZONE', $generalSettings['timezone']);
            Artisan::call('config:clear');
        }

        GreenSupport::setEnv('APP_NAME', $generalSettings['site_name']);

        setting($generalSettings);
    }

    private function storeEmailSetting($request): void
    {
        $emailSettings = [
            'mail_host' => $request->get('mail_host'),
            'mail_port' => $request->get('mail_port'),
            'mail_username' => $request->get('mail_username'),
            'mail_password' => $request->get('mail_password'),
            'mail_encryption' => $request->get('mail_encryption'),
            'mail_from_address' => $request->get('mail_from_address')
        ];

        if (isset($emailSettings['mail_host'])) {
            GreenSupport::setEnv('MAIL_HOST', $emailSettings['mail_host']);
        }
        if (isset($emailSettings['mail_port'])) {
            GreenSupport::setEnv('MAIL_PORT', $emailSettings['mail_port']);
        }
        if (isset($emailSettings['mail_username'])) {
            GreenSupport::setEnv('MAIL_USERNAME', $emailSettings['mail_username']);
        }
        if (isset($emailSettings['mail_password'])) {
            GreenSupport::setEnv('MAIL_PASSWORD', $emailSettings['mail_password']);
        }
        if (isset($emailSettings['mail_encryption'])) {
            GreenSupport::setEnv('MAIL_ENCRYPTION', $emailSettings['mail_encryption']);
        }
        if (isset($emailSettings['mail_from_address'])) {
            GreenSupport::setEnv('MAIL_FROM_ADDRESS', $emailSettings['mail_from_address']);
        }
        Artisan::call('config:clear');

        setting($emailSettings);
    }

    private function storeStripePaymentSetting($request): void
    {
        $paymentSettings = [
            'stripe_key' => $request->get('stripe_key'),
            'stripe_secret' => $request->get('stripe_secret'),
        ];

        if (isset($paymentSettings['stripe_key'])) {
            GreenSupport::setEnv('STRIPE_KEY', $paymentSettings['stripe_key']);
        }
        if (isset($paymentSettings['stripe_secret'])) {
            GreenSupport::setEnv('STRIPE_SECRET', $paymentSettings['stripe_secret']);
        }


        Artisan::call('config:clear');

        setting($paymentSettings);
    }


    private function storeRazorPayPaymentSetting($request): void
    {
        $paymentSettings = [
            'razorpay_key' => $request->get('razorpay_key'),
            'razorpay_secret' => $request->get('razorpay_secret')
        ];

        if (isset($paymentSettings['razorpay_key'])) {
            GreenSupport::setEnv('RAZORPAY_KEY', $paymentSettings['razorpay_key']);
        }
        if (isset($paymentSettings['razorpay_secret'])) {
            GreenSupport::setEnv('RAZORPAY_SECRET', $paymentSettings['razorpay_secret']);
        }


        Artisan::call('config:clear');

        setting($paymentSettings);
    }

    private function storeMpgsPaymentSetting($request): void
    {
        $paymentSettings = [
            'MERCHANT_ID' => $request->get('MERCHANT_ID'),
            'API_PASSWORD' => $request->get('API_PASSWORD')
        ];

        if (isset($paymentSettings['MERCHANT_ID'])) {
            GreenSupport::setEnv('MERCHANT_ID', $paymentSettings['MERCHANT_ID']);
        }
        if (isset($paymentSettings['API_PASSWORD'])) {
            GreenSupport::setEnv('API_PASSWORD', $paymentSettings['API_PASSWORD']);
        }


        Artisan::call('config:clear');

        setting($paymentSettings);
    }

}