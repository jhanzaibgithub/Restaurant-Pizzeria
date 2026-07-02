<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\CustomerLogic;
use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\Http\Controllers\Controller;
use App\Model\TableOrder;
use App\Model\DeliveryHistory;
use App\Model\Branch;
use App\Model\Order;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\CentralLogics\WoltService;
use Illuminate\Support\Facades\Validator;
use App\Model\Product;
use App\Model\Translation;
use App\Model\Tag;
use App\Model\ProductByBranch;
use App\Model\Category;
use App\Model\AddOn;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use App\Model\Kitchen;


class BranchController extends Controller
{
    public function __construct(
        private Branch          $branch,
        private Order           $order,
        private DeliveryHistory $delivery_history,
        private User            $user,
        private WoltService     $woltService,
        private TableOrder      $table_order,
        private Product          $product,
        private Translation     $translation,
        private ProductByBranch $product_by_branch,
        private Category          $category,


    ) {}


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_profile(Request $request): JsonResponse
    {
        $branch = $request->branch;

        return response()->json($branch, 200);
    }


    public function order_track(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $branch = $request->branch;

        $order = $this->order->where(['id' => $request['order_id'], 'branch_id' => $branch['id']])->first();
        if (!isset($order)) {
            return response()->json([
                'errors' => [
                    ['code' => 'order', 'message' => translate('Order not found!')]
                ]
            ], 404);
        }

        return response()->json(OrderLogic::track_order($request['order_id']), 200);
    }

    public function addDuplicateProduct(Request $request)
    {
        // Validate the request input
        $request->validate([
            'id' => 'required|exists:products,id',
        ]);

        $branch = $request->branch;

        $product = Product::where('branch_id', $branch->id)->findOrFail($request->id);

        if (!$product) {
            return response()->json(['error' => 'product id not found']);
        }

        // Find the product by its ID
        $PerioductBranch =  $this->product_by_branch->where('product_id', $request->id)->first();

        $newProduct = $product->replicate();
        $newProductBranch = $PerioductBranch->replicate();


        // Save the new product to generate a new ID
        $newProduct->save();
        $newProductBranch->save();

        return response()->json([
            'message' => 'Product duplicate successfully.',
        ]);
    }


    public function get_product(Request $request)
    {
        $branch = $request->branch;

        $products = Product::where('branch_id', $branch->id)->get();

        // Modify the response structure by replacing 'data' with 'food'
        return response()->json([
            'food' => ProductResource::collection($products)
        ]);
    }

    public function store(Request $request): JsonResponse
    {

        try {
            info($request->all());
            $validator = Validator::make($request->all(), [
                'category_id' => 'required',
                'image' => 'required',
                'price' => 'required|numeric',
                'kitchen_id' => 'required',
                'product_type' => 'required|in:veg,non_veg',
                'discount_type' => 'nullable',
            ], [
                'category_id.required' => translate('category is required!'),
            ]);

            // Check for validation errors
            if ($validator->fails()) {
                return response()->json([
                    'errors' => Helpers::error_processor($validator),
                    'message' => translate('Validation failed'),
                ], 422);
            }


            // Calculate discount based on type
            if ($request['discount_type'] == 'percent') {
                $dis = ($request['price'] / 100) * $request['discount'];
            } else {
                $dis = $request['discount'];
            }

            // Ensure the discount is not higher or equal to the price
            if ($request['price'] <= $dis) {
                $validator->getMessageBag()->add('unit_price', translate('Discount cannot be more or equal to the price!'));
            }

            // If validation fails, return errors
            if ($request['price'] <= $dis || $validator->fails()) {
                return response()->json(['errors' => Helpers::error_processor($validator)]);
            }

            // Handle translations
            $data = is_string($request->translations) ? json_decode($request->translations, true) : $request->translations;

            // Product data initialization
            $product = $this->product;
            $product->name = $data;


            foreach ($data as $translation) {
                if ($translation['locale'] === 'en') {
                    $product->name = $translation['name'];
                    $product->description = $translation['description'];
                }
            }


            // Handle categories and subcategories
            $category = [];
            if ($request->category_id != null) {
                $category[] = ['id' => $request->category_id, 'position' => 1];
            }
            if ($request->sub_category_id != null) {
                $category[] = ['id' => $request->sub_category_id, 'position' => 2];
            }
            if ($request->sub_sub_category_id != null) {
                $category[] = ['id' => $request->sub_sub_category_id, 'position' => 3];
            }

            $product->category_ids = json_encode($category);

            // Handle choice options
            $choice_options = [];
            $product->choice_options = json_encode($choice_options);

            $options = is_string($request->options) ? json_decode($request->options, true) : $request->options;

            $variations = [];
            if (isset($options) && is_array($options)) {
                foreach ($options as $option) {

                    $temp_variation = [];
                    $temp_variation['name'] = $option['name'];
                    $temp_variation['type'] = $option['type'];
                    $temp_variation['min'] = $option['min'] ?? 0;
                    $temp_variation['max'] = $option['max'] ?? 0;
                    $temp_variation['required'] = $option['required'] ?? 'off';

                    // Validate minimum and maximum values
                    if ($option['min'] > 0 && $option['min'] >= $option['max']) {
                        $validator->getMessageBag()->add('name', translate('maximum_value_can_not_be_smaller_or_equal_then_minimum_value'));
                        return response()->json(['errors' => Helpers::error_processor($validator)]);
                    }

                    // Validate 'type' field
                    if ($temp_variation['type'] !== 'multi' && $temp_variation['type'] !== 'single') {
                        $validator->getMessageBag()->add('type', translate('invalid_type_error_message')); // Replace with your desired message
                        return response()->json(['errors' => Helpers::error_processor($validator)]);
                    }

                    // Validate presence of 'values' field
                    if (!isset($option['values']) || !is_array($option['values'])) {
                        $validator->getMessageBag()->add('name', translate('please_add_options_for') . ' ' . $option['name']);
                        return response()->json(['errors' => Helpers::error_processor($validator)]);
                    }

                    // Validate 'max' field against the number of values
                    if ($option['max'] > count($option['values'])) {
                        $validator->getMessageBag()->add('name', translate('please_add_more_options_or_change_the_max_value_for') . ' ' . $option['name']);
                        return response()->json(['errors' => Helpers::error_processor($validator)]);
                    }

                    // Process the 'values' field
                    $temp_value = [];
                    foreach ($option['values'] as $value) {  // No need to call array_values() here, just loop through
                        $temp_option = [];
                        if (isset($value['label'])) {
                            $temp_option['label'] = $value['label'];
                        }
                        $temp_option['optionPrice'] = $value['optionPrice'];
                        $temp_value[] = $temp_option;
                    }

                    // Add values to temp_variation
                    $temp_variation['values'] = $temp_value;

                    // Add the current variation to the variations array
                    $variations[] = $temp_variation;
                }
            }

            $product->variations = json_encode($variations);
            $product->branch_id = $request->branch->id;
            $product->kitchen_id = $request->kitchen_id;
            $product->status   = 1;
            $product->price = $request->price;
            $product->tags = json_encode(json_decode($request->tags));
            $product->category_id = $request->category_id;
            $product->product_type = $request->product_type;
            $product->image = Helpers::upload('product/', 'png', $request->file('image'));
            $product->available_time_starts = $request->available_time_starts;
            $product->available_time_ends = $request->available_time_ends;
            $product->stock = $request->stock;
            if ($request->tax_type) {
                $product->tax = $request->tax;
            } else {
                $product->tax = 0;
            }

            $product->tax_type = $request->tax_type ? $request->tax_type : "percent";
            $product->discount = $request->discount_type == 'amount' ? $request->discount : $request->discount;
            $product->discount_type = $request->discount_type;
            $product->attributes = $request->has('attribute_id') ? json_encode($request->attribute_id) : json_encode([]);
            $product->add_ons = $request->has('addon_ids') ? (is_array($request->addon_ids) ? json_encode($request->addon_ids) : $request->addon_ids) : json_encode([]);
            $product->status =  1;

            $product->save();

            // Save main branch product
            $main_branch_product = $this->product_by_branch;
            $main_branch_product->product_id = $product->id;
            $main_branch_product->price = $request->price;
            $main_branch_product->discount_type = $request->discount_type;
            $main_branch_product->discount = $request->discount;
            $main_branch_product->branch_id = $request->branch->id;
            $main_branch_product->is_available = 1;
            $main_branch_product->variations = $variations;
            $main_branch_product->save();

            // Handle translations
            $translationData = [];
            foreach ($data as $translation) {
                if (isset($translation['name']) && $translation['name']) {
                    $translationData[] = [
                        'translationable_type' => 'App\Model\Product',
                        'translationable_id' => $product->id,
                        'locale' => $translation['locale'],
                        'key' => 'name',
                        'value' => $translation['name'],
                    ];
                }

                if (isset($translation['description']) && $translation['description']) {
                    $translationData[] = [
                        'translationable_type' => 'App\Model\Product',
                        'translationable_id' => $product->id,
                        'locale' => $translation['locale'],
                        'key' => 'description',
                        'value' => strip_tags($translation['description']),
                    ];
                }
            }

            // Insert translations into the database
            if (!empty($translationData)) {
                $this->translation->insert($translationData);
            }

            // Return success response
            return response()->json([
                'message' => trans('messages.product_added_successfully', ['product' => $product]),
                'product' => $product
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function addStock(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id', // Ensure the product ID exists
            'amount' => 'required|integer|min:1', // Ensure the amount is a positive integer
        ]);

        // Retrieve the product by its ID
        $product = Product::find($validated['product_id']);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Update the stock for the product
        $product->stock = $product->stock + $validated['amount'];
        $product->save();

        return response()->json([
            'message' => 'Stock added successfully',
            // 'product' => $product,
        ]);
    }


    public function update(Request $request): JsonResponse
    {
        info($request->all());

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:products,id',
        ]);

        // Check for validation errors
        if ($validator->fails()) {
            return response()->json([
                'errors' => Helpers::error_processor($validator),
                'message' => translate('Validation failed'),
            ], 422);
        }

        // Calculate discount based on type
        if ($request['discount_type'] == 'percent') {
            $dis = ($request['price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        // Ensure the discount is not higher or equal to the price
        if ($request['price'] <= $dis) {
            $validator->getMessageBag()->add('unit_price', translate('Discount cannot be more or equal to the price!'));
        }

        // If validation fails, return errors
        if ($request['price'] <= $dis || $validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        // Decode translations
        $data = is_string($request->translations) ? json_decode($request->translations, true) : $request->translations;

        // Find the product and update translations
        $product = $this->product->find($request->id);

        if (!$product) {
            return response()->json(['error' => 'Product not found']);
        }

        foreach ($data as $translation) {
            if ($translation['locale'] === 'en') {
                $product->name = $translation['name'];
                $product->description = $translation['description'];
            }
        }

        // Handle category assignments
        $category = [];
        if ($request->category_id != null) {
            $category[] = ['id' => $request->category_id, 'position' => 1];
        }
        if ($request->sub_category_id != null) {
            $category[] = ['id' => $request->sub_category_id, 'position' => 2];
        }
        if ($request->sub_sub_category_id != null) {
            $category[] = ['id' => $request->sub_sub_category_id, 'position' => 3];
        }

        // Update product categories
        $product->category_ids = json_encode($category);

        // Handle product variations
        $choice_options = [];
        $product->choice_options = json_encode($choice_options);

        $variations = [];
        $options = is_string($request->options) ? json_decode($request->options, true) : $request->options;

        if (isset($options) && is_array($options)) {
            foreach ($options as $option) {
                $temp_variation = [];
                $temp_variation['name'] = $option['name'];
                $temp_variation['type'] = $option['type'];
                $temp_variation['min'] = $option['min'] ?? 0;
                $temp_variation['max'] = $option['max'] ?? 0;
                $temp_variation['required'] = $option['required'] ?? 'off';

                // Validate minimum and maximum values
                if ($option['min'] > 0 && $option['min'] >= $option['max']) {
                    $validator->getMessageBag()->add('name', translate('maximum_value_can_not_be_smaller_or_equal_then_minimum_value'));
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }

                // Validate 'type' field
                if ($temp_variation['type'] !== 'multi' && $temp_variation['type'] !== 'single') {
                    $validator->getMessageBag()->add('type', translate('invalid_type_error_message')); // Replace with your desired message
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }

                // Validate presence of 'values' field
                if (!isset($option['values']) || !is_array($option['values'])) {
                    $validator->getMessageBag()->add('name', translate('please_add_options_for') . ' ' . $option['name']);
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }

                // Validate 'max' field against the number of values
                if ($option['max'] > count($option['values'])) {
                    $validator->getMessageBag()->add('name', translate('please_add_more_options_or_change_the_max_value_for') . ' ' . $option['name']);
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }

                // Process the 'values' field
                $temp_value = [];
                foreach ($option['values'] as $value) {  // No need to call array_values() here, just loop through
                    $temp_option = [];
                    if (isset($value['label'])) {
                        $temp_option['label'] = $value['label'];
                    }
                    $temp_option['optionPrice'] = $value['optionPrice'];
                    $temp_value[] = $temp_option;
                }

                // Add values to temp_variation
                $temp_variation['values'] = $temp_value;

                // Add the current variation to the variations array
                $variations[] = $temp_variation;
            }
        }

        $product->variations = json_encode($variations);
        $product->price = $request->price ?? $product->price;
        $product->category_id = $request->category_id ?? $product->category_id;
        $product->product_type = $request->product_type ?? $product->product_type;
        $product->tags = json_encode(json_decode($request->tags));
        $product->image = $request->hasFile('image') ? Helpers::upload('product/', 'png', $request->file('image')) : $product->image;
        $product->available_time_starts = $request->available_time_starts ?? $product->available_time_starts;
        $product->available_time_ends = $request->available_time_ends ?? $product->available_time_ends;
        $product->stock = $request->stock ?? $product->stock;

        // Handle tax and discount
        if ($request->tax_type) {
            $product->tax = $request->tax ?? $product->tax;
        } else {
            $product->tax = 0;
        }

        $product->tax_type = $request->tax_type ? $request->tax_type : "percent";
        $product->discount = $request->discount_type == 'amount' ? $request->discount : $product->discount;
        $product->discount_type = $request->discount_type ?? $product->discount_type;
        $product->attributes = $request->has('attribute_id') ? json_encode($request->attribute_id) : $product->attributes;
        $product->add_ons = $request->has('addon_ids') ? (is_array($request->addon_ids) ? json_encode($request->addon_ids) : $request->addon_ids) : $product->add_ons;
        $product->status = 1;

        // Save product
        $product->save();

        // Save main branch product
        $main_branch_product = $this->product_by_branch->where('product_id', $product->id)->first();
        $main_branch_product->price = $request->price ?? $product->price;
        $main_branch_product->discount_type = $product->discount_type;
        $main_branch_product->discount = $request->discount ?? $product->discount;
        $main_branch_product->branch_id = $request->branch->id;
        $main_branch_product->is_available = 1;
        $main_branch_product->variations = $variations;
        $main_branch_product->save();

        // Handle translations
        $translationData = [];
        foreach ($data as $translation) {
            if (isset($translation['name']) && $translation['name']) {
                $translationData[] = [
                    'translationable_type' => 'App\Model\Product',
                    'translationable_id' => $product->id,
                    'locale' => $translation['locale'],
                    'key' => 'name',
                    'value' => $translation['name'],
                ];
            }

            if (isset($translation['description']) && $translation['description']) {
                $translationData[] = [
                    'translationable_type' => 'App\Model\Product',
                    'translationable_id' => $product->id,
                    'locale' => $translation['locale'],
                    'key' => 'description',
                    'value' => strip_tags($translation['description']),
                ];
            }
        }

        // Insert translations into the database
        if (!empty($translationData)) {
            $this->translation->where('translationable_id', $product->id)->delete();
            $this->translation->insert($translationData);
        }

        return response()->json(['message' => trans('messages.product_updated_successfully')], 200);
    }


    public function get_attributes(Request $request)
    {
        $branch = $request->branch;
        $addOn = AddOn::orderBy('created_at', 'desc')->get();

        $categories = Category::with(['translations'])
            ->where('status', 1)
            ->where('branch_id', $branch->id)
            ->get();

        $kitchens = Kitchen::where('status', 1)
            ->where('branch_id', $branch->id)
            ->get();

        // Return transformed categories using the CategoryResource
        return response()->json([
            'category' => CategoryResource::collection($categories), // Apply the resource collection
            'addon' => $addOn,
            'kitchens' => $kitchens
        ]);
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update_order_status(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'status' => 'required',
            'preparation_time' => 'required_if:status,processing'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $branch = $request->branch;

        $order = $this->order->find($request['order_id']);
        $customer = $order->customer;
        $fcm_token = $customer ? $customer->cm_firebase_token : '';

        //kitchen order notification
        if ($request->status == 'processing') {

            $order->preparation_time = $request->preparation_time ?? 0;
            $order->processing = now();
            $order->save();

            try {
                $result = $this->woltService->createDelivery($order);

                if (isset($result['url'])) {
                    $order->wolt_tracking_url = $result['url'];
                    $order->wolt_driver = 1;
                    $order->save();
                }
            } catch (\Exception $e) {
                //   info($e->getMessage());
            }
        }
        $table_order = $this->table_order->where(['id' => $order->table_order_id])->first();

        if ($request->status == 'canceled') {

            if (isset($table_order->id)) {
                $orders = $this->order->where(['table_order_id' => $table_order->id])->get();
                $status = 1;
                foreach ($orders as $order) {
                    if ($order->order_status != 'canceled') {
                        $status = 0;
                        break;
                    }
                }

                if ($status == 1) {
                    $table_order->branch_table_token_is_expired = 1;
                    $table_order->save();
                }
            }
        }

        $this->order->where(['id' => $request['order_id'], 'branch_id' => $branch['id']])->update([
            'order_status' => $request['status']
        ]);

        $value = Helpers::order_status_update_message($request->status);

        try {
            if ($value) {
                $data = [
                    'title' => translate('Order'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                    'type' => 'order_status',
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
            }
        } catch (\Exception $e) {
            // info($e->getMessage());
        }

        return response()->json(['message' => translate('Status updated')], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_order_details(Request $request): JsonResponse
    {
        $branch = $request->branch;

        $order = $this->order->with(['details'])->where(['branch_id' => $branch['id'], 'id' => $request['order_id']])->first();
        $details = isset($order->details) ? Helpers::order_details_formatter($order->details) : null;
        foreach ($details as $det) {
            $det['delivery_time'] = $order->delivery_time;
            $det['delivery_date'] = $order->delivery_date;
            $det['preparation_time'] = $order->preparation_time;
        }

        return response()->json($details, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_all_orders(Request $request): JsonResponse
    {

        $branch = $request->branch;

        $orders = $this->order
            ->with(['delivery_address', 'customer', 'details'])
            ->withCount('details as item_count')
            ->where(['branch_id' => $branch['id']])
            ->whereNotIn('order_status', ['delivered', 'canceled'])
            ->get();

        $orders->transform(function ($order) {
            $order['details'] = Helpers::order_details_formatter($order->details);
            return $order;
        });

        return response()->json($orders, 200);
    }

    public function update_branch_status(Request $request): JsonResponse
    {
        $branch = $request->branch;

        $status = $request['status'] ?? $branch['status'];
        $rush_mode = $request['rush_mode'] ?? $branch['rush_mode'];

        $this->branch->where(['id' => $branch['id']])->update(['status' => $status, 'rush_mode' => $rush_mode]);

        return response()->json(['message' => translate('successfully updated!')], 200);
    }

    public function update_order_time(Request $request): JsonResponse
    {
        $branch = $request->branch;
        $this->order->where(['id' => $request['order_id'], 'branch_id' => $branch['id']])->update([
            'order_status' => 'pending',
            'created_at' => now(),
        ]);

        return response()->json(['message' => translate('successfully updated!')], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update_fcm_token(Request $request): JsonResponse
    {

        $branch = $request->branch;

        $this->branch->where(['id' => $branch['id']])->update(['fcm_token' => $request['fcm_token']]);

        return response()->json(['message' => translate('successfully updated!')], 200);
    }


    public function add_category(Request $request)
    {
        $branch = $request->branch;

        // Handle translations
        $data = is_string($request->translations) ? json_decode($request->translations, true) : $request->translations;

        // Product data initialization
        $category = new $this->category();
        $category->name = $data;
        $category->branch_id = $branch->id;
        $category->image = Helpers::upload('category/', 'png', $request->file('image'));
        $category->banner_image = Helpers::upload('banner/', 'png', $request->file('banner_image'));

        // Set the English name as the main name
        foreach ($data as $translation) {
            if ($translation['locale'] === 'en') {
                $category->name = $translation['name'];
            }
        }

        // Check for uniqueness
        if ($this->category->where('name', $category->name)->exists()) {
            return response()->json(['message' => 'Category name must be unique.'], 400);
        }

        $category->save();

        $translationData = [];
        foreach ($data as $translation) {
            if (isset($translation['name']) && $translation['name']) {
                $translationData[] = [
                    'translationable_type' => 'App\Model\Category',
                    'translationable_id' => $category->id, // ID is now available
                    'locale' => $translation['locale'],
                    'key' => 'name',
                    'value' => $translation['name'],
                ];
            }
        }

        // Insert translations into the database
        if (!empty($translationData)) {
            $this->translation->insert($translationData);
        }

        return response()->json(['message' => 'Category added successfully!']);
    }

    public function update_category(Request $request)
    {
        $branch = $request->branch;

        // Fetch the category by ID
        $category = $this->category->find($request->category_id);

        // Check if the category exists
        if (!$category) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        // Handle translations
        $data = is_string($request->translations) ? json_decode($request->translations, true) : $request->translations;

        // Product data initialization (updating the name)
        $category->name = $data;

        // Set the English name as the main name
        foreach ($data as $translation) {
            if ($translation['locale'] === 'en') {
                $category->name = $translation['name'];
            }
        }

        // Check for uniqueness (excluding the current category)
        if ($this->category->where('name', $category->name)->where('id', '!=', $category->id)->exists()) {
            return response()->json(['message' => 'Category name must be unique.'], 400);
        }

        $category->branch_id = $branch->id;

        if ($request->hasFile('image')) {
            // Check if a new file is uploaded
            $category->image = Helpers::upload('category/', 'png', $request->file('image'));
        }

        if ($request->hasFile('banner_image')) {
            // Check if a new banner image file is uploaded
            $category->banner_image = Helpers::upload('banner/', 'png', $request->file('banner_image'));
        }

        $category->save();

        // Prepare the translation data for the updated category
        $translationData = [];
        foreach ($data as $translation) {
            if (isset($translation['name']) && $translation['name']) {
                // Update or insert translations
                $translationData[] = [
                    'translationable_type' => 'App\Model\Category',
                    'translationable_id' => $category->id, // ID is now available
                    'locale' => $translation['locale'],
                    'key' => 'name',
                    'value' => $translation['name'],
                ];
            }
        }

        // Delete existing translations for the category
        $this->translation->where('translationable_id', $category->id)->delete();

        // Insert new translations into the database
        if (!empty($translationData)) {
            $this->translation->insert($translationData);
        }

        return response()->json(['message' => 'Category updated successfully!']);
    }

    public function delete_category(Request $request)
    {
        // Find the category by ID
        $category = $this->category->find($request->id);

        // Check if the category exists
        if (!$category) {
            return response()->json(['error' => 'Category not found.'], 404);
        }

        // Optionally, delete related translations if necessary
        $category->translations()->delete();

        // Delete the category
        $category->delete();

        // Return success response
        return response()->json(['message' => 'Category deleted successfully!']);
    }

    public function add_sub_category(Request $request)
    {

        $branch = $request->branch;

        // Handle translations
        $data = is_string($request->translations) ? json_decode($request->translations, true) : $request->translations;

        // Product data initialization
        $category = new $this->category();
        $category->name = $data;
        $category->branch_id = $branch->id;
        $category->parent_id = $request->category_id;

        if ($request->hasFile('image')) {
            // Check if a new file is uploaded
            $category->image = Helpers::upload('category/', 'png', $request->file('image'));
        }

        // Set the English name as the main name
        foreach ($data as $translation) {
            if ($translation['locale'] === 'en') {
                $category->name = $translation['name'];
            }
        }

        // Check for uniqueness
        if ($this->category->where('name', $category->name)->exists()) {
            return response()->json(['message' => 'Category name must be unique.'], 400);
        }

        $category->save();

        $translationData = [];
        foreach ($data as $translation) {
            if (isset($translation['name']) && $translation['name']) {
                $translationData[] = [
                    'translationable_type' => 'App\Model\Category',
                    'translationable_id' => $category->id, // ID is now available
                    'locale' => $translation['locale'],
                    'key' => 'name',
                    'value' => $translation['name'],
                ];
            }
        }

        // Insert translations into the database
        if (!empty($translationData)) {
            $this->translation->insert($translationData);
        }

        return response()->json(['message' => 'Sub category added successfully!']);
    }

    public function update_sub_category(Request $request)
    {
        $branch = $request->branch;

        // Fetch the subcategory by ID
        $subCategory = $this->category->find($request->sub_category_id);

        // Check if the subcategory exists
        if (!$subCategory) {
            return response()->json(['message' => 'Subcategory not found.'], 404);
        }

        // Handle translations
        $data = is_string($request->translations) ? json_decode($request->translations, true) : $request->translations;

        // Initialize the name with translations
        $subCategory->name = $data;

        // Set the English name as the main name
        foreach ($data as $translation) {
            if ($translation['locale'] === 'en') {
                $subCategory->name = $translation['name'];
            }
        }

        // Check for uniqueness (excluding the current subcategory)
        if ($this->category->where('name', $subCategory->name)->where('id', '!=', $subCategory->id)->exists()) {
            return response()->json(['message' => 'Subcategory name must be unique.'], 400);
        }

        $subCategory->branch_id = $branch->id;

        if ($request->hasFile('image')) {
            // Check if a new file is uploaded
            $subCategory->image = Helpers::upload('category/', 'png', $request->file('image'));
        }

        if ($request->hasFile('banner_image')) {
            $subCategory->banner_image = Helpers::upload('subcategory-banner/', 'png', $request->file('banner_image'));
        }

        $subCategory->save();

        // Prepare the translation data for the updated subcategory
        $translationData = [];
        foreach ($data as $translation) {
            if (isset($translation['name']) && $translation['name']) {
                $translationData[] = [
                    'translationable_type' => 'App\Model\Category',
                    'translationable_id' => $subCategory->id,
                    'locale' => $translation['locale'],
                    'key' => 'name',
                    'value' => $translation['name'],
                ];
            }
        }

        // Delete existing translations for the subcategory
        $this->translation->where('translationable_id', $subCategory->id)->delete();

        // Insert new translations into the database
        if (!empty($translationData)) {
            $this->translation->insert($translationData);
        }

        return response()->json(['message' => 'Subcategory updated successfully!']);
    }
}
