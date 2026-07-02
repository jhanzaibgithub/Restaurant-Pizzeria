<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Support\Renderable;


class SMSModuleController extends Controller
{
    public function __construct(
        private BusinessSetting $business_setting
    )
    {
    }

    /**
     * @return Renderable
     */
    public function sms_index(): Renderable
    {
        $smsConfigs = [
            'twilio_sms' => $this->defaultSmsConfig('twilio_sms', Helpers::get_business_settings('twilio_sms')),
            'nexmo_sms' => $this->defaultSmsConfig('nexmo_sms', Helpers::get_business_settings('nexmo_sms')),
        ];

        return view('admin-views.business-settings.sms-index', compact('smsConfigs'));
    }

    /**
     * @param Request $request
     * @param $module
     * @return RedirectResponse
     */
    public function sms_update(Request $request, $module): RedirectResponse
    {
        if ($module == 'twilio_sms') {
            $this->business_setting->updateOrInsert(['key' => 'twilio_sms'], [
                'key' => 'twilio_sms',
                'value' => json_encode([
                    'status' => $request->input('status', 0),
                    'sid' => $request->input('sid', ''),
                    'messaging_service_sid' => $request->input('messaging_service_sid', ''),
                    'token' => $request->input('token', ''),
                    'from' => $request->input('from', ''),
                    'otp_template' => $request->input('otp_template', ''),
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        } elseif ($module == 'nexmo_sms') {
            $this->business_setting->updateOrInsert(['key' => 'nexmo_sms'], [
                'key' => 'nexmo_sms',
                'value' => json_encode([
                    'status' => $request->input('status', 0),
                    'api_key' => $request->input('api_key', ''),
                    'api_secret' => $request->input('api_secret', ''),
                    'signature_secret' => '',
                    'private_key' => '',
                    'application_id' => '',
                    'from' => $request->input('from', ''),
                    'otp_template' => $request->input('otp_template', '')
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        } elseif ($module == '2factor_sms') {
            $this->business_setting->updateOrInsert(['key' => '2factor_sms'], [
                'key' => '2factor_sms',
                'value' => json_encode([
                    'status' => $request['status'],
                    'api_key' => $request['api_key'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        } elseif ($module == 'msg91_sms') {
            $this->business_setting->updateOrInsert(['key' => 'msg91_sms'], [
                'key' => 'msg91_sms',
                'value' => json_encode([
                    'status' => $request['status'],
                    'template_id' => $request['template_id'],
                    'authkey' => $request['authkey'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        } elseif ($module == 'signalwire_sms') {
            $this->business_setting->updateOrInsert(['key' => 'signalwire_sms'], [
                'key' => 'signalwire_sms',
                'value' => json_encode([
                    'status' => $request['status'],
                    'project_id' => $request['project_id'],
                    'token' => $request['token'],
                    'space_url' => $request['space_url'],
                    'from' => $request['from'],
                    'otp_template' => $request['otp_template'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($request->input('status', 0) == 1) {
            $config = $this->defaultSmsConfig('twilio_sms', Helpers::get_business_settings('twilio_sms'));
            if ($module != 'twilio_sms') {
                $this->business_setting->updateOrInsert(['key' => 'twilio_sms'], [
                    'key' => 'twilio_sms',
                    'value' => json_encode([
                        'status' => 0,
                        'sid' => $config['sid'],
                        'token' => $config['token'],
                        'from' => $config['from'],
                        'otp_template' => $config['otp_template'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $config = $this->defaultSmsConfig('nexmo_sms', Helpers::get_business_settings('nexmo_sms'));
            if ($module != 'nexmo_sms') {
                $this->business_setting->updateOrInsert(['key' => 'nexmo_sms'], [
                    'key' => 'nexmo_sms',
                    'value' => json_encode([
                        'status' => 0,
                        'api_key' => $config['api_key'],
                        'api_secret' => $config['api_secret'],
                        'signature_secret' => '',
                        'private_key' => '',
                        'application_id' => '',
                        'from' => $config['from'],
                        'otp_template' => $config['otp_template']
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $config = Helpers::get_business_settings('2factor_sms');
            if (isset($config) && $module != '2factor_sms') {
                $this->business_setting->updateOrInsert(['key' => '2factor_sms'], [
                    'key' => '2factor_sms',
                    'value' => json_encode([
                        'status' => 0,
                        'api_key' => $config['api_key'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $config = Helpers::get_business_settings('msg91_sms');
            if (isset($config) && $module != 'msg91_sms') {
                $this->business_setting->updateOrInsert(['key' => 'msg91_sms'], [
                    'key' => 'msg91_sms',
                    'value' => json_encode([
                        'status' => 0,
                        'template_id' => $config['template_id'],
                        'authkey' => $config['authkey'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $config = Helpers::get_business_settings('signalwire_sms');
            if (isset($config) && $module != 'signalwire_sms') {
                $this->business_setting->updateOrInsert(['key' => 'signalwire_sms'], [
                    'key' => 'signalwire_sms',
                    'value' => json_encode([
                        'status' => 0,
                        'project_id' => $config['project_id'],
                        'token' => $config['token'],
                        'space_url' => $config['space_url'],
                        'from' => $config['from'],
                        'otp_template' => $config['otp_template'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return back();
    }

    private function defaultSmsConfig(string $module, $config = null): array
    {
        $defaults = [
            'twilio_sms' => [
                'status' => 0,
                'sid' => '',
                'messaging_service_sid' => '',
                'token' => '',
                'from' => '',
                'otp_template' => '',
            ],
            'nexmo_sms' => [
                'status' => 0,
                'api_key' => '',
                'api_secret' => '',
                'signature_secret' => '',
                'private_key' => '',
                'application_id' => '',
                'from' => '',
                'otp_template' => '',
            ],
        ];

        return array_merge($defaults[$module] ?? ['status' => 0], is_array($config) ? $config : []);
    }
}
