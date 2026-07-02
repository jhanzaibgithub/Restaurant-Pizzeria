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
         <li class="{{ Request::is('admin/category/bulk-import') ? 'active' : '' }}">
             <a class="menu-link" href="{{ route('admin.category.bulk-import') }}">{{ translate('Category_Bulk_Import') }}</a>
         </li>
         <li class="{{ Request::is('admin/category/bulk-export') ? 'active' : '' }}">
             <a class="menu-link" href="{{ route('admin.category.bulk-export') }}">{{ translate('Category_Bulk_Export') }}</a>
         </li>
         <li class="{{ Request::is('admin/product/bulk-import') ? 'active' : '' }}">
             <a class="menu-link" href="{{ route('admin.product.bulk-import') }}">{{ translate('Product_Bulk_Import') }}</a>
         </li>
         <li class="{{ Request::is('admin/product/bulk-export') ? 'active' : '' }}">
             <a class="menu-link" href="{{ route('admin.product.bulk-export') }}">{{ translate('Product_Bulk_Export') }}</a>
         </li>


     </ul>
 </div>
