
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
         <li class="{{Request::is('admin/delivery-man/pending/list')?'active':''}}">
             <a class="menu-link" href="{{ route('admin.delivery-man.pending') }}">{{ translate('Pending Delivery Man') }}</a>
         </li>
         <li class="{{Request::is('admin/delivery-man/denied/list')?'active':''}}">
             <a class="menu-link" href="{{ route('admin.delivery-man.denied') }}">{{ translate('Denied Delivery Man') }}</a>
         </li>
    </ul>
 </div>
