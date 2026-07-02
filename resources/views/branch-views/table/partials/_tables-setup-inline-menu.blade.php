
<style>
       ul.list-unstyled li.active a {
            color: #FE6524;
            position: relative;
        }

        ul.list-unstyled li.active a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -10px;
            width: 100%;
            height: 2px;
            background-color: currentColor;
        }

        .form-li {
            margin-right: 3%;
            /* margin-left: auto; */
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
            <li class="{{ Request::is('branch/table/list') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('branch.table.list') }}">{{ translate('Table_List') }}</a>
            </li>
            <li class="{{ Request::is('branch/table/index') ? 'active' : ''}}">
                <a class="menu-link" href="{{ route('branch.table.index') }}">{{ translate('Avalibility') }}</a>
            </li>
            <li class="{{ Request::is('branch/promotion/create') || Request::is('admin/promotion/edit*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('branch.promotion.create') }}">{{ translate('Add New') }}</a>
            </li>

             <!-- <li class="form-li">
                <form action="#" id="form-data" method="GET">
                        <div class="dropdown show">
                            <a class="btn d-flex justify-content-between" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 200px; padding: 8px; background-color: transparent; border: 1px solid #ccc;">
                                <span>Filters</span>
                                <span><img src="{{asset('assets/admin/img/icons/filter.png')}}" alt="Custom Icon" style="height: 16px; width: 16px;"></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="width:200px">
                                <div class="dropdown-item">
                                    <label for="startDate" class="form-label" onclick="toggleDateInput('startDate', event)">
                                        <span>Start Date</span>
                                        <img src="{{asset('assets/admin/img/icons/dropdown-icon.png')}}" alt="Custom Icon" style="height: 16px; width: 16px;">
                                    </label>
                                    <div class="date-input" id="startDateInput">
                                        <input type="date" class="form-control" id="startDate" />
                                    </div>
                                    <hr class="dropdown-divider" />
                                </div>
                                <div class="dropdown-item">
                                    <label for="endDate" class="form-label" onclick="toggleDateInput('endDate', event)">
                                        <span>End Date</span>
                                        <img src="{{asset('assets/admin/img/icons/dropdown-icon.png')}}" alt="Custom Icon" style="height: 16px; width: 16px;">
                                    </label>
                                    <div class="date-input" id="endDateInput">
                                        <input type="date" class="form-control" id="endDate" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
            </li> -->
        </ul>
    </div>
    <script>
        function toggleDateInput(inputType, event) {
            var dateInput = document.getElementById(inputType + "Input");
            dateInput.style.display = dateInput.style.display === "none" ? "block" : "none";
            event.stopPropagation(); // Stop the event from propagating to the dropdown
        }
    </script>
