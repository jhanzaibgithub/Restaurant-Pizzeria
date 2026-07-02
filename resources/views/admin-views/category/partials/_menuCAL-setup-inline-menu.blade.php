
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
            <li class="{{ Request::is('admin/category/add') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.category.add') }}">{{ translate('category') }}</a>
            </li>
            <li class="{{ Request::is('admin/category/add-sub-category') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.category.add-sub-category') }}">{{ translate('Sub Category') }}</a>
            </li>
            <li class="{{ Request::is('admin/addon*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.addon.add-new') }}">{{ translate('Addon') }}</a>
            </li>
            <li class="{{ Request::is('admin/product/list') || Request::is('admin/product/edit*')|| Request::is('admin/attribute/*')||Request::is('admin/reviews/*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.product.list') }}">{{ translate('Product') }}</a>
            </li>
            <li class="{{ Request::is('admin/category/bulk-import') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.category.bulk-import') }}">{{ translate('BulkImport&Export') }}</a>
            </li>
        </ul>


     </div>

