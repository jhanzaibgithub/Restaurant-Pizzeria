
<style>
       ul.list-unstyled li.active a {
            color: #FE6524;
            position: relative;
        }

        ul.list-unstyled li.active a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -15px;
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
    <div class="inline-page-menu d-flex flex-row justify-content-between align-items-center">
        <ul class="list-unstyled">
            <li class="{{ Request::is('admin/customer/list') || Request::is('admin/customer/view*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.customer.list') }}">{{ translate('All Customers') }}</a>
            </li>
            <li class="{{ Request::is('admin/customer/settings') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.customer.settings') }}">{{ translate('Settings') }}</a>
            </li>
            <li class="{{ Request::is('admin/customer/wallet/report') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.customer.wallet.report') }}">{{ translate('Wallet') }}</a>
            </li>
            <li class="{{ Request::is('admin/customer/wallet/add-fund') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.customer.wallet.add-fund') }}">{{ translate('Fund') }}</a>
            </li>
            <li class="{{ Request::is('admin/customer/loyalty-point/report') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.customer.loyalty-point.report') }}">{{ translate('Loyality Points') }}</a>
            </li>
            <li class="{{ Request::is('admin/customer/subscribed-email*') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('admin.customer.subscribed_emails') }}">{{ translate('Subscribed Emails') }}</a>
            </li>
        </ul>
        <div class="form-group mb-0 mr-2">
            <form action="{{ route('admin.customer.list') }}" method="get" id="dateRangeForm">
                <input type="hidden" name="from" id="from" value="">
                        <input type="hidden" name="to" id="to" value="">
                        <div class="input-group">
                        <input type="text" name="date_range" class="form-control" id="config-demo" value="">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <img src="{{asset('assets/admin/img/icons/filter.png')}}" alt="" srcset="" style="width: 80%;">
                                </span>
                            </div>
                    </div>
            </form>
        </div>
     </div>
     <script type="text/javascript">
        $(document).ready(function() {
            var defaultStartDate = moment().subtract(0, 'days');
            var defaultEndDate = moment();
            var options = {
                startDate: defaultStartDate,
                endDate: defaultEndDate,
                showDropdowns: true,
                showWeekNumbers: true,
                showISOWeekNumbers: true,
                timePicker: false,
                autoApply: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                        .endOf('month')
                    ]
                },
                locale: {
                    direction: 'ltr',
                    format: 'MM/DD/YYYY',
                    separator: ' - ',
                    applyLabel: 'Apply',
                    cancelLabel: 'Cancel',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',
                        'September', 'October', 'November', 'December'
                    ],
                    firstDay: 1
                },
                linkedCalendars: true,
                autoUpdateInput: true,
                showCustomRangeLabel: true,
                alwaysShowCalendars: true
            };

            $('#config-demo').daterangepicker(options, function(start, end, label) {
                console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format(
                    'YYYY-MM-DD') + ' (predefined range: ' + label + ')');
            }).on('apply.daterangepicker', function (ev, picker) {
                    $('#from').val(picker.startDate.format('YYYY-MM-DD'));
                    $('#to').val(picker.endDate.format('YYYY-MM-DD'));
                    $('#dateRangeForm').submit();
                });

        });
    </script>