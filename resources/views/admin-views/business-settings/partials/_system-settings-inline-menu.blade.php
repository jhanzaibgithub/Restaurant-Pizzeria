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

        <li class="{{Request::is('admin/business-settings/web-app/system-setup/language*')? 'active' : ''}}">
            <a class="menu-link "
             href="{{route('admin.business-settings.web-app.system-setup.language.index')}}">
                {{translate('Language Setup')}}
            </a>
        </li>
        <li class=" {{Request::is('admin/business-settings/web-app/system-setup/app-setting*')? 'active' : ''}}">
            <a class="menu-link"
             href="{{route('admin.business-settings.web-app.system-setup.app_setting')}}">
                {{translate('App Settings')}}
            </a>
        </li>
        <li class="{{Request::is('admin/business-settings/web-app/system-setup/firebase-message-config*')? 'active' : ''}}">
            <a class="menu-link "
             href="{{route('admin.business-settings.web-app.system-setup.firebase_message_config_index')}}">
                {{translate('Firebase Configuration')}}
            </a>
        </li>
        <li class="{{Request::is('admin/business-settings/web-app/system-setup/db-index*')? 'active' : ''}}" >
            <a class="menu-link"
              href="{{route('admin.business-settings.web-app.system-setup.db-index')}}">
                {{translate('Clean Database')}}
            </a>
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

