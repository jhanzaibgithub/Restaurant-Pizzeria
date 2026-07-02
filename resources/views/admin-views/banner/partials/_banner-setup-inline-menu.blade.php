
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
    <div class="inline-page-menu">

        <ul class="list-unstyled">
            <li class="{{ Request::is('admin/banner/*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.banner.list') }}">{{ translate('Banner') }}</a>
            </li>
            <li class="{{ Request::is('admin/coupon/*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.coupon.add-new') }}">{{ translate('Coupon') }}</a>
            </li>
            <li class="{{ Request::is('admin/notification/*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.notification.add-new') }}">{{ translate('Send_Notifications') }}</a>
            </li>
        </ul>
    </div>
