
<style>
       ul.list-unstyled li.active a {
            color: #FE6524;
            position: relative;
        }

        ul.list-unstyled li.active a::after {
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

        <ul class="list-unstyled">
        <li class="{{Request::is('admin/business-settings/page-setup/about-us')? 'active': ''}}">
            <a class="menu-link" href="{{route('admin.business-settings.page-setup.about-us')}}">{{translate('About Us')}}</a>
        </li>
            <li class="{{Request::is('admin/business-settings/page-setup/terms-and-conditions')?'active':''}}">
                <a class="menu-link" href="{{route('admin.business-settings.page-setup.terms-and-conditions')}}">{{translate('Terms and Condition')}}</a>
            </li>
            <li class="{{Request::is('admin/business-settings/page-setup/privacy-policy')?'active':''}}">
                <a class="menu-link" href="{{route('admin.business-settings.page-setup.privacy-policy')}}">{{translate('Privacy Policy')}}</a>
            </li>
            <li class="{{Request::is('admin/business-settings/page-setup/return-page*')?'active':''}}">
                <a class="menu-link" href="{{route('admin.business-settings.page-setup.return_page_index')}}">{{translate('Return Policy')}}</a>
            </li>
            <li class="{{Request::is('admin/business-settings/page-setup/refund-page*')?'active':''}}">
                <a class="menu-link" href="{{route('admin.business-settings.page-setup.refund_page_index')}}">{{translate('Refund Policy')}}</a>
            </li>
            <li class="{{Request::is('admin/business-settings/page-setup/cancellation-page*')?'active':''}}">
                <a class="menu-link" href="{{route('admin.business-settings.page-setup.cancellation_page_index')}}">{{translate('Cancellation Policy')}}</a>
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


