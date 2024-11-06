<?php

namespace App\Http\Requests;

use App\Services\Modules\SettingService;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class SettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $settingType = request('settingtype', 'generalsetting');

        app(SettingService::class)->storeSettingType();

        if ($settingType === 'generalsetting') {
            return $this->generalSettingRules();
        }

        if ($settingType === 'emailsetting') {
            return $this->emailSettingRules();
        }

        if ($settingType === 'paymentsetting') {
            return $this->paymentSettingRules();
        }

        return [];
    }


    private function generalSettingRules(): array
    {
        return [
            'site_name' => 'required|string|max:100',
            'email' => 'required|string|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'copyright_by' => 'required|string',
            'site_logo' => 'nullable|mimes:jpeg,jpg,png,gif|max:3096',
            'timezone' => 'required|string',
            'currency_symbol' => 'required|string',
            'currency_code' => 'required|string',
            'site_sidebar' => 'required|string',
        ];
    }

    private function emailSettingRules(): array
    {
        return [
            'mail_host' => 'required|string|max:100',
            'mail_port' => 'required|numeric',
            'mail_username' => 'required|string|max:100',
            'mail_password' => 'required|string|max:100',
            'mail_encryption' => 'required|string|max:100',
            'mail_from_address' => 'required|string|max:100',
        ];
    }

    private function paymentSettingRules(): array
    {
        return [
            'stripe_key'      => 'nullable|required_if:stripe_status,5|max:300|string',
            'stripe_secret'   => 'nullable|required_if:stripe_status,5|max:300|string',
            'stripe_status'   => 'nullable|max:100',
            'razorpay_key'    => 'nullable|required_if:razorpay_status,5|max:300|string',
            'razorpay_secret' => 'nullable|required_if:razorpay_status,5|max:300|string',
            'razorpay_status' => 'nullable|max:100',
            'MERCHANT_ID'     => 'nullable|required_if:mpgs_status,5|max:300|string',
            'API_PASSWORD'    => 'nullable|required_if:mpgs_status,5|max:300|string',
            'mpgs_status'     => 'nullable|max:100',
        ];
    }

    /**
     * @param $validator
     * @return void
     */
    public function withValidator($validator): void
    {
        $settingType = request('settingtype', 'generalsetting');

        $validator->after(function ($validator) use ($settingType) {
            if ($settingType === 'emailsetting') {
                $this->smtpConnectionCheck($validator);
            }
        });
    }

    /**
     * @param $validator
     * @return void
     */
    private function smtpConnectionCheck($validator): void
    {
        if(
            !$this->get('mail_host') ||
            !$this->get('mail_port') ||
            !$this->get('mail_encryption') ||
            !$this->get('mail_username') ||
            !$this->get('mail_password') ||
            !$this->get('mail_from_address')
        ) {
            $validator->errors()->add('mail_host', "Please ensure the SMTP information provided is valid.");
            return;
        }

        $message = "";
        try {
            $dsn = 'smtp://'.$this->get('mail_host').':'.$this->get('mail_port').'?encryption='.$this->get('mail_encryption');

            // Create SMTP transport
            $transport = Transport::fromDsn($dsn);
            $transport->setUsername($this->get('mail_username'));
            $transport->setPassword($this->get('mail_password'));

            // Create a Mailer instance
            $mailer = new Mailer($transport);

            // Send a test email
            $email = (new Email())
                ->from($this->get('mail_from_address'))
                ->to(setting('email'))
                ->subject('Successful SMTP Connection Test')
                ->text('Just a quick note to let you know that our SMTP connection is up and running smoothly.');

            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $message = 'SMTP connection validation failed: ' . $e->getMessage();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        if (!blank($message)) {
            $validator->errors()->add('mail_host', $message);
        }
    }
}
