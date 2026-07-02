 <style>

       ul.list-unstyled li.active a {
            color: #FE6524;
            position: relative;
        display: block;
        padding-bottom: 10px;
        }

        ul.list-unstyled li.active a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0px;
            width: 100%;
            height: 2px;
            background-color: currentColor;
            transition: background-color 0.3s;
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

        <ul style="font-size: 14px" class="list-unstyled">
            <li class=" {{ Request::is('admin/business-settings/restaurant*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.business-settings.restaurant.restaurant-setup') }}">{{ translate('Business_Setup') }}</a>
            </li>
            <li class="{{ Request::is('admin/business-settings/page-setup*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.business-settings.page-setup.about-us') }}">{{ translate('Page_Setup') }}</a>
            </li>
            <li class="{{ Request::is('admin/business-settings/web-app/social-media') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.business-settings.web-app.third-party.social-media') }}">{{ translate('Social_Media') }}</a>
            </li>
            <li class="{{ Request::is('admin/business-settings/web-app/third-party*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.business-settings.web-app.payment-method') }}">{{ translate('3rd_Party') }}</a>
            </li>
            <li class="{{ Request::is('admin/business-settings/web-app/delivery-system*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.business-settings.web-app.delivery-system') }}">{{ translate('Delivery System') }}</a>
            </li>
            <li class="{{ Request::is('admin/business-settings/web-app/system-setup*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.business-settings.web-app.system-setup.language.index') }}">{{ translate('System_Setup') }}</a>
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
