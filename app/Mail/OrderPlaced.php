<?php

namespace App\Mail;

use App\Model\BusinessSetting;
use App\Model\Order;
use App\Model\SocialMedia;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $order_id;

    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order_id = $this->order_id;
        $order = Order::with(['details', 'restaurant'])->find($order_id);
        $settings = BusinessSetting::whereIn('key', [
            'phone',
            'email_address',
            'business_name',
            'restaurant_name',
            'logo',
            'address',
        ])->pluck('value', 'key');
        $company_phone = $settings['phone'] ?? '';
        $company_email = $settings['email_address'] ?? '';
        $company_name = $settings['business_name'] ?? $settings['restaurant_name'] ?? 'restaurant-pizzeria';
        $logo = $settings['logo'] ?? '';
        $company_address = $settings['address'] ?? '';
        $social_media = SocialMedia::active()->get();

        return $this->view('email-templates.customer-order-placed', compact(
            'order_id',
            'order',
            'company_phone',
            'company_email',
            'company_name',
            'logo',
            'company_address',
            'social_media'
        ));
    }
}
