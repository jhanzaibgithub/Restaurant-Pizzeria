
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
            <li class="{{ Request::is('admin/kitchen/list') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.kitchen.list') }}">{{ translate('All Chef List') }}</a>
            </li>
            <li class="{{ Request::is('admin/kitchen/add-new') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.kitchen.add-new') }}">{{ translate('Add New Chef') }}</a>
            </li>
        </ul>
    </div>
