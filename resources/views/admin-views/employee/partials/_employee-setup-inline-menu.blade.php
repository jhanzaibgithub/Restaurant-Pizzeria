
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
    <div class="inline-page-menu d-flex flex-row justify-content-between align-items-center">
        <ul class="list-unstyled">
            <li class="{{ Request::is('admin/employee/list') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.employee.list') }}">{{ translate('All Employees') }}</a>
            </li>
            <li class="{{ Request::is('admin/custom-role*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.custom-role.create') }}">{{ translate('Employee Role') }}</a>
            </li>
            <li class="{{ Request::is('admin/employee/add-new') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.employee.add-new') }}">{{ translate('Add New') }}</a>
            </li>


        </ul>
    </div>
