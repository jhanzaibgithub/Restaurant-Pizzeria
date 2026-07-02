
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
            <li class="{{ Request::is('admin/delivery-man/list') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.delivery-man.list') }}">{{ translate('All Delivery Men') }}</a>
            </li>
            <li class="{{ Request::is('admin/delivery-man/reviews/list') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.delivery-man.reviews.list') }}">{{ translate('Ratings') }}</a>
            </li>
            <li class="{{ Request::is('admin/delivery-man/add') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.delivery-man.add') }}">{{ translate('Add New') }}</a>
            </li>
            <li class="{{ Request::is('admin/delivery-man/pending/list') || Request::is('admin/delivery-man/denied/list') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.delivery-man.pending') }}">{{ translate('New Joining Request') }}</a>
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
