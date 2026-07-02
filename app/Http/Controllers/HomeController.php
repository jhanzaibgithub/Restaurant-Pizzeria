<?php

namespace App\Http\Controllers;

use App\Model\BusinessSetting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /*$this->middleware('auth');*/
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        $restaurantLogo = BusinessSetting::where('key', 'logo')->value('value');

        return view('home', compact('restaurantLogo'));
    }

    /**
     * @return Factory|View|Application
     */
    public function about_us(): Factory|View|Application
    {
        $aboutUs = BusinessSetting::where('key', 'about_us')->value('value');
        $aboutUs = $this->adminPageContentOrFallback($aboutUs, $this->defaultAboutUsContent());

        return view('about-us', compact('aboutUs'));
    }

    /**
     * @return Factory|View|Application
     */
    public function terms_and_conditions(): Factory|View|Application
    {
        $termsAndConditions = BusinessSetting::where('key', 'terms_and_conditions')->value('value');
        $termsAndConditions = $this->adminPageContentOrFallback($termsAndConditions, $this->defaultTermsContent());

        return view('terms-and-conditions', compact('termsAndConditions'));
    }

    /**
     * @return Factory|View|Application
     */
    public function privacy_policy(): Factory|View|Application
    {
        $privacyPolicy = BusinessSetting::where('key', 'privacy_policy')->value('value');
        $privacyPolicy = $this->adminPageContentOrFallback($privacyPolicy, $this->defaultPrivacyContent());

        return view('privacy-policy', compact('privacyPolicy'));
    }

    private function adminPageContentOrFallback(?string $content, string $fallback): string
    {
        if (! $this->isEmptyAdminPageContent($content)) {
            return (string) $content;
        }

        return $fallback;
    }

    private function isEmptyAdminPageContent(?string $content): bool
    {
        $plainText = strtolower(trim(preg_replace(
            '/\s+/',
            ' ',
            html_entity_decode(strip_tags((string) $content))
        )));

        $placeholderValues = [
            '',
            'about us data',
            'privacy policy data',
            'terms and conditions data',
            'terms & conditions data',
            'about us',
            'privacy policy',
            'terms and conditions',
        ];

        return in_array($plainText, $placeholderValues, true);
    }

    private function defaultAboutUsContent(): string
    {
        return <<<'HTML'
            <article class="policy-content">
                <span class="content-kicker">About Restaurant Pizzeria</span>
                <h1>Restaurant operations, ordering, and delivery in one platform</h1>
                <p>Restaurant Pizzeria is built for restaurants that need a complete digital ecosystem: admin control, branch operations, customer ordering, driver delivery, order tracking, payments, notifications, and business reporting.</p>
                <p>The platform helps restaurants manage daily workflows from one place while keeping every role connected. Admin teams can monitor branches, menus, customers, drivers, orders, promotions, and analytics with practical tools designed for fast-moving food businesses.</p>
                <div class="content-grid">
                    <div>
                        <h2>For restaurant teams</h2>
                        <p>Manage branches, menus, orders, tables, kitchen flow, delivery assignment, and reports with clear operational visibility.</p>
                    </div>
                    <div>
                        <h2>For customers and drivers</h2>
                        <p>Support smooth ordering, real-time delivery updates, wallet activity, push notifications, and driver task management.</p>
                    </div>
                </div>
            </article>
        HTML;
    }

    private function defaultPrivacyContent(): string
    {
        return <<<'HTML'
            <article class="policy-content">
                <span class="content-kicker">Privacy Policy</span>
                <h1>How Restaurant Pizzeria handles platform information</h1>
                <p>Restaurant Pizzeria uses information needed to operate restaurant ordering, delivery, customer accounts, driver activity, payments, notifications, and support workflows.</p>
                <h2>Information we process</h2>
                <ul>
                    <li>Account details such as name, email, phone number, and account status.</li>
                    <li>Order details including selected products, delivery address, payment status, and order history.</li>
                    <li>Operational data for branches, drivers, delivery tracking, notifications, and reports.</li>
                </ul>
                <h2>How information is used</h2>
                <p>Data is used to process orders, coordinate restaurant and delivery operations, improve customer support, send important updates, maintain platform security, and provide business analytics to authorized restaurant administrators.</p>
                <h2>Data access</h2>
                <p>Access is limited to authorized users based on their role, such as admin, branch staff, delivery drivers, and customers. Restaurant Pizzeria is designed to support responsible handling of restaurant and customer information.</p>
            </article>
        HTML;
    }

    private function defaultTermsContent(): string
    {
        return <<<'HTML'
            <article class="policy-content">
                <span class="content-kicker">Terms and Conditions</span>
                <h1>Using the Restaurant Pizzeria platform</h1>
                <p>By using Restaurant Pizzeria, users agree to use the platform for lawful restaurant ordering, delivery, management, and related business operations.</p>
                <h2>Platform usage</h2>
                <ul>
                    <li>Restaurant administrators are responsible for maintaining accurate menus, prices, branch details, and operational settings.</li>
                    <li>Customers are responsible for providing correct account, order, payment, and delivery information.</li>
                    <li>Drivers and staff must use assigned tools only for approved operational tasks.</li>
                </ul>
                <h2>Orders and payments</h2>
                <p>Order acceptance, preparation, delivery, cancellation, refund, and payment rules may depend on the restaurant configuration and active business policies.</p>
                <h2>Service availability</h2>
                <p>Restaurant Pizzeria aims to support reliable restaurant operations, but access may vary because of maintenance, configuration changes, network conditions, or third-party service availability.</p>
            </article>
        HTML;
    }
}

