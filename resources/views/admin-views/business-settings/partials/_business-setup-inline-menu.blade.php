
<style>
     ul.list-unstyled {
        font-size: 11px;
    }

    @media (min-width: 1200px) {
        ul.list-unstyled {
            font-size: 12px;
        }
    }
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
        <li class="{{Request::is('admin/business-settings/restaurant/restaurant-setup')? 'active': ''}}">
            <a class="menu-link" href="{{route('admin.business-settings.restaurant.restaurant-setup')}}">{{translate('Business_Settings')}}</a>
        </li>
            <li class="{{Request::is('admin/business-settings/restaurant/main-branch-setup')? 'active' : ''}}">
                <a class="menu-link" href="{{route('admin.business-settings.restaurant.main-branch-setup')}}">{{translate('Main_Branch_Setup')}}</a>
            </li>
            <li class="{{Request::is('admin/business-settings/restaurant/time-schedule')? 'active' : ''}}">
                <a class="menu-link" href="{{route('admin.business-settings.restaurant.time_schedule_index')}}">{{translate('Restaurant_Availabilty_Time_Slot')}}</a>
            </li>
            <li class="{{Request::is('admin/business-settings/restaurant/delivery-fee-setup')? 'active' : ''}}">
                <a class="menu-link" href="{{route('admin.business-settings.restaurant.delivery-fee-setup')}}">{{translate('Delivery_Fee_Setup')}}</a>
            </li>
            <li class="{{Request::is('admin/business-settings/restaurant/cookies-setup')? 'active' : ''}}">
                <a class="menu-link" href="{{route('admin.business-settings.restaurant.cookies-setup')}}">{{translate('Cookies Setup')}}</a>
            </li>
            <li class="{{Request::is('admin/business-settings/restaurant/otp-setup')? 'active' : ''}}">
                <a class="menu-link" href="{{route('admin.business-settings.restaurant.otp-setup')}}">{{translate('Login and OTP Setup')}}</a>
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
