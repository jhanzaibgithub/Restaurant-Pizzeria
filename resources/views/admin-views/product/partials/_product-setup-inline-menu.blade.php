
<style>
       ul.product-list li.active a {
            color: #FE6524;
            position: relative;
        }

        ul.product-list li.active a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0px;
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

        <ul class="list-unstyled product-list">
            <li class="{{ Request::is('admin/product/list') || Request::is('admin/product/edit*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.product.list') }}">{{ translate('Product_List') }}</a>
            </li>
            <li class="{{ Request::is('admin/attribute*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.attribute.add-new') }}">{{ translate('Product_Attributes') }}</a>
            </li>
            <li class="{{ Request::is('admin/reviews*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.reviews.list') }}">{{ translate('Product_Reviews') }}</a>
            </li>
        </ul>
    </div>
    <script>
        function toggleDateInput(inputType, event) {
            var dateInput = document.getElementById(inputType + "Input");
            dateInput.style.display = dateInput.style.display === "none" ? "block" : "none";
            event.stopPropagation(); 
        }
    </script>
