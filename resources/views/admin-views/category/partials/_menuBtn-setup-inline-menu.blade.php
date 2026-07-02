
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
         <li class="{{ Request::is('admin/product/list') || Request::is('admin/product/add-new') || Request::is('admin/product/edit*')|| Request::is('admin/attribute/*')||Request::is('admin/reviews/*') ? 'active' : '' }}">
             <a class="menu-link" href="{{ route('admin.product.list') }}">{{ translate('Product') }}</a>
         </li>
         <li class="{{ Request::is('admin/category/bulk-import') ? 'active' : '' }}">
             <a class="menu-link" href="{{ route('admin.category.bulk-import') }}">{{ translate('BulkImport&Export') }}</a>
         </li>
     </ul>
     <div class="form-group mb-0 mr-5">
        <a href="{{route('admin.product.add-new')}}" class="btn btn-warning text-white">
           {{translate('add_New_Product')}} <i class="tio-add"></i>
        </a>
      </div>

  </div>
  <script type="text/javascript">
     $(document).ready(function () {
         var defaultStartDate = moment().subtract(30, 'days');
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
                 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
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
                 monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                 firstDay: 1
             },
             linkedCalendars: true,
             autoUpdateInput: true,
             showCustomRangeLabel: true,
             alwaysShowCalendars: true
         };

         $('#config-demo').daterangepicker(options, function (start, end, label) {
             console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
         });

     });
 </script>
