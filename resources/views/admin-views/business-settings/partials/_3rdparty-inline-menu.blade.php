
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
        <li class="{{Request::is('admin/business-settings/web-app/third-party/payment-method')? 'active': ''}}"><a class="menu-link" href="{{route('admin.business-settings.web-app.payment-method')}}">{{translate('Payment_Methods')}}</a></li>
            <li class="{{Request::is('admin/business-settings/web-app/third-party/mail-config')? 'active': ''}}"><a class="menu-link" href="{{route('admin.business-settings.web-app.mail-config')}}">{{translate('Mail_Config')}}</a></li>
            <li class="{{Request::is('admin/business-settings/web-app/third-party/sms-module')? 'active': ''}}"><a class="menu-link" href="{{route('admin.business-settings.web-app.sms-module')}}">{{translate('SMS_Config')}}</a></li>
            <li class="{{Request::is('admin/business-settings/web-app/third-party/map-api-settings')? 'active': ''}}"><a class="menu-link" href="{{route('admin.business-settings.web-app.third-party.map_api_settings')}}">{{translate('Google_Map_APIs')}}</a></li>
            <li class="{{Request::is('admin/business-settings/web-app/third-party/recaptcha')? 'active': ''}}"><a class="menu-link" href="{{route('admin.business-settings.web-app.third-party.recaptcha_index')}}">{{translate('Recaptcha')}}</a></li>
            <li class="{{Request::is('admin/business-settings/web-app/third-party/fcm-index')? 'active': ''}}"><a class="menu-link" href="{{route('admin.business-settings.web-app.third-party.fcm-index')}}">{{translate('Push_Notification')}}</a></li>
            <li class="{{Request::is('admin/business-settings/web-app/third-party/social-login')? 'active': ''}}"><a class="menu-link" href="{{route('admin.business-settings.web-app.third-party.social-login')}}">{{translate('Social_Login')}}</a></li>
            <li class="{{Request::is('admin/business-settings/web-app/third-party/chat')? 'active': ''}}"><a class="menu-link" href="{{route('admin.business-settings.web-app.third-party.chat')}}">{{translate('chat')}}</a></li>
        </ul>
    </div>
    <script>
        function toggleDateInput(inputType, event) {
            var dateInput = document.getElementById(inputType + "Input");
            dateInput.style.display = dateInput.style.display === "none" ? "block" : "none";
            event.stopPropagation(); 
        }
    </script>
