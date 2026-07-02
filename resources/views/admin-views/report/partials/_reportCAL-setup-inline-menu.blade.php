
<style>
       ul.list-unstyled li.active a {
            color: #FE6524;
            position: relative;
        }

        ul.list-unstyled li.active a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -10px;
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
            <li class="{{ Request::is('admin/report/earning') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.report.earning') }}">{{ translate('Earnings') }}</a>
            </li>
            <li class="{{ Request::is('admin/report/sale-report') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.report.sale-report') }}">{{ translate('Sale') }}</a>
            </li>
            <li class="{{ Request::is('admin/report/order') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.report.order') }}">{{ translate('Orders') }}</a>
            </li>
            <li class="{{ Request::is('admin/report/product-report') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.report.product-report') }}">{{ translate('Product') }}</a>
            </li>
            <li class="{{ Request::is('admin/report/branch') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.report.branch') }}">{{ translate('Branch') }}</a>
            </li>
            <li class="{{ Request::is('admin/report/deliveryman-report') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.report.deliveryman_report') }}">{{ translate('Deliveryman') }}</a>
            </li>
        </ul>
    </div>

