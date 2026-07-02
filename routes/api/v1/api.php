<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api\V1', 'middleware' => 'localization'], function () {
    Route::get('/test-headers', function () {
        return response()->json(request()->headers->all());
    });
    Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
        Route::post('registration', 'CustomerAuthController@registration');
        Route::post('login', 'CustomerAuthController@login');
        Route::post('social-customer-login', 'CustomerAuthController@social_customer_login');

        Route::post('check-phone', 'CustomerAuthController@check_phone');
        Route::post('verify-phone', 'CustomerAuthController@verify_phone');

        Route::post('check-email', 'CustomerAuthController@check_email');
        Route::post('verify-email', 'CustomerAuthController@verify_email');

        Route::post('forgot-password', 'PasswordResetController@reset_password_request');
        Route::post('verify-token', 'PasswordResetController@verify_token');
        Route::put('reset-password', 'PasswordResetController@reset_password_submit');

        Route::group(['prefix' => 'delivery-man'], function () {
            Route::post('register', 'DeliveryManLoginController@registration');
            Route::post('login', 'DeliveryManLoginController@login');
        });


        Route::group(['prefix' => 'kitchen', 'middleware' => 'app_activate:' . APPS['kitchen_app']['software_id']], function () {
            Route::post('login', 'KitchenLoginController@login');
            Route::post('logout', 'KitchenLoginController@logout')->middleware('auth:kitchen_api');
        });

        Route::group(['prefix' => 'branch'], function () {
            Route::post('login', 'BranchLoginController@login');
        });

    });


    Route::get('wolt-promise', 'WoltServiceController@createShipment');
    Route::get('wolt-delivery', 'WoltServiceController@createDelivery');
    Route::post('wolt-webhook', 'WoltServiceController@handleWoltWebhook');

    Route::post('uber-webhook', 'UberEatsController@webhook');
    Route::post('/foodpanda/webhook', 'FoodPandaController@handleWebhook');


    Route::group(['prefix' => 'delivery-man', 'middleware' => 'deliveryman_is_active'], function () {
        Route::get('profile', 'DeliverymanController@get_profile');
        Route::get('current-orders', 'DeliverymanController@get_current_orders');
        Route::get('all-orders', 'DeliverymanController@get_all_orders');
        Route::post('record-location-data', 'DeliverymanController@record_location_data');
        Route::get('order-delivery-history', 'DeliverymanController@get_order_history'); // not used
        Route::put('update-order-status', 'DeliverymanController@update_order_status');
        Route::put('update-payment-status', 'DeliverymanController@order_payment_status_update');
        Route::get('order-details', 'DeliverymanController@get_order_details');
        //        Route::get('last-location', 'DeliverymanController@get_last_location');
        Route::put('update-fcm-token', 'DeliverymanController@update_fcm_token');
        Route::get('order-model', 'DeliverymanController@order_model');

        Route::group(['prefix' => 'bd-message'], function () {
            Route::post('get-message', 'ConversationController@get_order_message');
            Route::post('send', 'ConversationController@store_message');
        });

        Route::group(['prefix' => 'message'], function () {
            Route::post('get-message', 'ConversationController@get_order_message_for_dm');
            Route::post('send/{sender_type}', 'ConversationController@store_message_by_order');
        });

        Route::group(['prefix' => 'reviews', 'middleware' => ['auth:api']], function () {
            Route::get('/{delivery_man_id}', 'DeliveryManReviewController@get_reviews'); //not used
            Route::get('rating/{delivery_man_id}', 'DeliveryManReviewController@get_rating'); //not used
            //            Route::post('/submit', 'DeliveryManReviewController@submit_review');
        });
    });

    Route::group(['prefix' => 'banners'], function () {
        Route::get('/', 'BannerController@get_banners');
    });

    Route::group(['prefix' => 'products', 'middleware' => 'branch_adder'], function () {
        Route::get('latest', 'ProductController@get_latest_products');
        Route::get('popular', 'ProductController@get_popular_products');
        Route::get('set-menu', 'ProductController@get_set_menus');
        Route::get('search', 'ProductController@get_searched_products');
        Route::get('details/{id}', 'ProductController@get_product');
        Route::get('related-products/{product_id}', 'ProductController@get_related_products');
        Route::get('reviews/{product_id}', 'ProductController@get_product_reviews');
        Route::get('rating/{product_id}', 'ProductController@get_product_rating');
        Route::post('reviews/submit', 'ProductController@submit_product_review')->middleware('auth:api');
    });

    Route::group(['prefix' => 'customer', 'middleware' => ['auth:api', 'is_active']], function () {
        Route::get('info', 'CustomerController@info');
        Route::post('update-profile', 'CustomerController@update_profile');
        Route::put('cm-firebase-token', 'CustomerController@update_cm_firebase_token');
        Route::get('transaction-history', 'CustomerController@get_transaction_history');

        Route::namespace('Auth')->group(function () {
            Route::delete('remove-account', 'CustomerAuthController@remove_account');
        });

        Route::group(['prefix' => 'address'], function () {
            Route::get('list', 'CustomerController@address_list');
            Route::post('add', 'CustomerController@add_new_address');
            Route::put('update/{id}', 'CustomerController@update_address');
            Route::delete('delete', 'CustomerController@delete_address');
        });

        Route::group(['prefix' => 'order'], function () {
            Route::get('list', 'OrderController@get_order_list');
            Route::get('details', 'OrderController@get_order_details');
            // Route::post('place_order_testing', 'OrderController@place_order_testing');
            Route::post('place', 'OrderController@place_order');
            Route::put('cancel', 'OrderController@cancel_order');
            Route::get('track', 'OrderController@track_order');
            Route::put('payment-method', 'OrderController@update_payment_method');
            Route::post('');
        });

        Route::group(['prefix' => 'payment'], function () {
            Route::post('stripe-intent', 'StripePaymentController@create_intent');
        });
        // Chatting
        Route::group(['prefix' => 'message'], function () {
            //customer-admin
            Route::get('get-admin-message', 'ConversationController@get_admin_message');
            Route::post('send-admin-message', 'ConversationController@store_admin_message');
            //customer-deliveryman
            Route::get('get-order-message', 'ConversationController@get_message_by_order');
            Route::post('send/{sender_type}', 'ConversationController@store_message_by_order');
        });

        Route::group(['prefix' => 'wish-list'], function () {
            Route::get('/', 'WishlistController@wish_list')->middleware('branch_adder');
            Route::post('add', 'WishlistController@add_to_wishlist');
            Route::delete('remove', 'WishlistController@remove_from_wishlist');
        });

        Route::post('transfer-point-to-wallet', 'CustomerWalletController@transfer_loyalty_point_to_wallet');
        Route::get('wallet-transactions', 'CustomerWalletController@wallet_transactions');
        Route::get('loyalty-point-transactions', 'LoyaltyPointController@point_transactions');
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', 'CategoryController@get_categories');
        Route::get('childes/{category_id}', 'CategoryController@get_childes');
        Route::get('products/{category_id}', 'CategoryController@get_products')->middleware('branch_adder');
        Route::get('products/{category_id}/all', 'CategoryController@get_all_products')->middleware('branch_adder');
    });

    Route::get('apply', 'CouponController@apply');


    Route::group(['prefix' => 'branch', 'middleware' => 'branch.api'], function () {
        Route::get('profile', 'BranchController@get_profile');
        Route::get('all-orders', 'BranchController@get_all_orders');
        Route::put('update-order-status', 'BranchController@update_order_status');
        Route::get('order-details', 'BranchController@get_order_details');
        Route::put('update-fcm-token', 'BranchController@update_fcm_token');
        Route::put('update-branch-status', 'BranchController@update_branch_status');
        Route::get('products', 'BranchController@get_product');
        Route::post('store', 'BranchController@store');
        Route::post('/add-stock', 'BranchController@addStock');
        Route::post('update', 'BranchController@update');
        Route::get('/dropdowns', 'BranchController@get_attributes');
        Route::post('add-category', 'BranchController@add_category');
        Route::post('update-category', 'BranchController@update_category');
        Route::get('delete-category', 'BranchController@delete_category');
        Route::post('add-sub-category', 'BranchController@add_sub_category');
        Route::post('update-sub-category', 'BranchController@update_sub_category');
        Route::put('update-order-time', 'BranchController@update_order_time');
        Route::get('add-duplicate-product', 'BranchController@addDuplicateProduct')->name('duplicate');
        Route::post('/update-tax', 'BussinessSettingController@updateTax');
        Route::post('/update-branch-settings', 'BranchSettingsController@update_setting');
        Route::get('/branch-settings-dropdown', 'BranchSettingsController@settings_Dropdown');
        Route::get('/branch-settings', 'BranchSettingsController@get_business_settings');
        Route::get('/profile', 'ProfileController@get_profile');
        Route::post('/update-profile', 'ProfileController@update_profile');
        Route::post('/update-password', 'ProfileController@update_password');

        Route::group(['prefix' => 'delivery-man'], function () {
            Route::post('add', 'DeliverymanController@store');
       });

       Route::group(['prefix' => 'bd-message'], function () {
        	Route::post('get-message', 'ConversationController@get_order_message');
        	Route::post('send', 'ConversationController@store_message');
       });


        Route::group(['prefix' => 'pos', 'middleware' => 'branch.api'], function () {
            Route::get('/tables', 'POSController@getTables');
            Route::get('/category', 'POSController@getCategory');
            Route::get('product/latest', 'ProductController@get_latest_products');
            Route::post('place-order', 'POSController@place_order');
            Route::get('orders', 'POSController@order_list');
            Route::get('/order-print', 'POSController@order_print');
            Route::post('update-order', 'POSController@update_order');
            Route::post('customer-store', 'POSController@customer_store');
            Route::post('change-status', 'POSController@update_order_status');


            Route::get('customers', 'POSController@get_customers');
            Route::post('customer-address', 'CustomerAddressController@store_address');

            Route::group(['prefix' => 'supplier', 'middleware' => 'branch.api'], function () {
                Route::post('/add', 'SupplierController@store');
                Route::post('/update', 'SupplierController@update');
            });
			
			 Route::group(['prefix' => 'groups', 'middleware' => 'branch.api'], function () {
           		 Route::get('/', 'TableController@getTableGroup');
       		 });
        });

  
        Route::group(['prefix' => 'table', 'middleware' => 'branch.api'], function () {
			Route::post('/add', 'TableController@add');
			Route::post('/update', 'TableController@update');
			Route::get('delete', 'TableController@delete');
		});

		 Route::group(['prefix' => 'kitchen', 'middleware' => 'branch.api'], function () {
                Route::get('/', 'KitchenController@getKitchen');
                Route::post('/add', 'KitchenController@add');
                Route::post('update', 'KitchenController@update');
                Route::get('delete', 'KitchenController@delete');
         });

		  Route::group(['prefix' => 'message', 'middleware' => 'branch.api'], function () {
                Route::post('/add', 'BranchConversationController@store_admin_message');
                Route::get('/get_admin_message', 'BranchConversationController@get_admin_message');
          });

		 Route::group(['prefix' => 'printer', 'middleware' => 'branch.api'], function () {
                Route::get('/', 'PrinterController@getPrinters');
                Route::post('/add', 'PrinterController@add');
                Route::post('/update', 'PrinterController@update');
                Route::get('delete', 'PrinterController@delete');
         });

		  Route::group(['prefix' => 'group', 'middleware' => 'branch.api'], function () {
                Route::post('/add', 'GroupController@add');
                Route::post('/update', 'GroupController@update');
                Route::get('delete', 'GroupController@delete');
            });
		 Route::group(['prefix' => 'reports', 'middleware' => 'branch.api'], function () {
                Route::get('earning', 'ReportsController@earningIndex');
                Route::get('sale', 'ReportsController@sales_filter');
                Route::post('set-date', 'ReportsController@set_date');
                Route::get('order-summary', 'ReportsController@order_summary');
                Route::post('product-filter', 'ReportsController@product_report_filter');
                Route::get('branch-report', 'ReportsController@branch_index');
                Route::post('deliveryman-filter', 'ReportsController@deliveryman_filter');
         });

        Route::middleware('auth:api')->post('delivery-man/reviews/submit', 'DeliveryManReviewController@submit_review');
        Route::middleware('auth:api')->get('delivery-man/last-location', 'DeliverymanController@get_last_location');



       

        Route::group(['prefix' => 'addon', 'as' => 'addon.'], function () {
			Route::get('/', 'AddonController@index');
            Route::get('add-new', 'AddonController@index');
            Route::post('store', 'AddonController@store');
            Route::get('edit/{id}', 'AddonController@edit');
            Route::post('update', 'AddonController@update');
            Route::delete('delete/{id}', 'AddonController@delete');
            Route::get('status/{id}/{status}', 'AddonController@status');
        });


       

        Route::group(['prefix' => 'notifications'], function () {
            Route::get('/', 'NotificationController@get_notifications');
        });

       
       

        Route::get('pay-paypal', 'PaypalController@payWithpaypal');
        Route::post('paystack-pay', 'PaystackController@redirectToGateway');


       
    });

	  Route::group(['prefix' => 'config'], function () {
            Route::get('/', 'ConfigController@configuration');
            Route::get('table', 'TableConfigController@configuration');
      });

    //map api
    Route::group(['prefix' => 'mapapi'], function () {
        Route::get('place-api-autocomplete', 'MapApiController@place_api_autocomplete');
        Route::get('distance-api', 'MapApiController@distance_api');
        Route::get('place-api-details', 'MapApiController@place_api_details');
        Route::get('geocode-api', 'MapApiController@geocode_api');
        Route::post('get-nearest-branch', 'MapApiController@get_nearest_branch');
    });

    Route::post('subscribe-newsletter', 'CustomerController@subscribe_newsletter');

    Route::get('pages', 'PageController@index');

    Route::group(['prefix' => 'table', 'middleware' => 'app_activate:' . APPS['table_app']['software_id']], function () {
        Route::get('list', 'TableController@list');
        Route::get('product/type', 'TableController@filter_by_product_type');
        Route::get('promotional/page', 'TableController@get_promotional_page');
        Route::post('order/place', 'TableController@place_order');
        Route::get('order/details', 'TableController@get_order_details');
        Route::get('order/list', 'TableController@table_order_list');
        Route::post('reservation', 'TableReservationController@reserveTable');

    });

    Route::group(['prefix' => 'kitchen', 'middleware' => 'auth:kitchen_api', 'app_activate:' . APPS['kitchen_app']['software_id']], function () {
        Route::get('profile', 'KitchenController@get_profile');
        Route::get('order/list', 'KitchenController@get_order_list');
        Route::get('order/search', 'KitchenController@search');
        Route::get('order/filter', 'KitchenController@filter_by_status');
        Route::get('order/details', 'KitchenController@get_order_details');
        Route::put('order/status', 'KitchenController@change_status');
        Route::put('update-fcm-token', 'KitchenController@update_fcm_token');
    });
});
