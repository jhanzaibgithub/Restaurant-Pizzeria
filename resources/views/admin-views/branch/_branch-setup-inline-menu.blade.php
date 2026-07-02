
<style>
       ul.list-unstyled li.active a {
            color: #FE6524;
            position: relative;
        }

        ul.list-unstyled li.active a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -20px;
            width: 100%;
            height: 2px;
            background-color: currentColor;
        }

        .form-li {
            margin-right: 3%;
            margin-left: auto;
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
            <li class="{{ Request::is('admin/branch/list') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.branch.list') }}">{{ translate('All Branches') }}</a>
            </li>
            <li class="{{ Request::is('admin/branch/add-new') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.branch.add-new') }}">{{ translate('Add New Branch') }}</a>
            </li>

        </ul>
    </div>
