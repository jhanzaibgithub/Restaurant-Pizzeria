
<style>
       ul.list-unstyled li.active a {
            color: #FE6524;
            position: relative;
        }

        ul.list-unstyled li.active a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -12px;
            width: 100%;
            height: 2px;
            background-color: currentColor;
        }

        .form-li {
            margin-right: 3%;
            /* margin-left: auto; */
        }

        .date-input {
            display: none;
        }

        .dropdown-item label {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

    </style>
    <div class="inline-page-menu d-flex flex-row justify-content-between align-items-center">
        <ul class="list-unstyled">
            <li class="{{ Request::is('admin/customer/list') || Request::is('admin/customer/view*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.customer.list') }}">{{ translate('All Customers') }}</a>
            </li>
            <li class="{{ Request::is('admin/customer/settings') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.customer.settings') }}">{{ translate('Settings') }}</a>
            </li>
            <li class="{{ Request::is('admin/customer/wallet/report') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.customer.wallet.report') }}">{{ translate('Wallet') }}</a>
            </li>
            <li class="{{ Request::is('admin/customer/wallet/add-fund') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.customer.wallet.add-fund') }}">{{ translate('Fund') }}</a>
            </li>
            <li class="{{ Request::is('admin/customer/loyalty-point/report') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.customer.loyalty-point.report') }}">{{ translate('Loyality Points') }}</a>
            </li>
            <li class="{{ Request::is('admin/customer/subscribed-email*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.customer.subscribed_emails') }}">{{ translate('Subscribed Emails') }}</a>
            </li>
        </ul>
    </div>
