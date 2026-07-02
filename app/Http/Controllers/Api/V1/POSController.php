<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use Carbon\Carbon;
use App\Model\AddOn;
use App\Model\Admin;
use App\Model\Order;
use App\Model\Table;
use App\Model\Branch;
use App\Model\Product;
use App\Model\Category;
use App\Model\DeliveryMan;
use App\Model\OrderDetail;
use App\Model\Notification;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Model\BusinessSetting;
use App\Model\ProductByBranch;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\RedirectResponse;
use App\Http\Resources\CategoryResource;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Arr;

class POSController extends Controller
{
    public function __construct(
        private Category        $category,
        private Order           $order,
        private User            $user,
        private Table           $table,
        private Product         $product,
        private Admin           $admin,
        private Branch          $branch,
        private ProductByBranch $product_by_Branch,
        private DeliveryMan     $delivery_man
    ) {}

    public function getCategory(Request $request)
    {
        $branch = $request->branch;
        $categories = $this->category->with(['childes' => function ($query) {
            $query->where('parent_id', '!=', 0);
        }])->where(['branch_id' => $branch->id, 'parent_id' => 0])->get();

        return response()->json(['category' => CategoryResource::collection($categories)]);
    }

    public function getTables(Request $request)
    {

        $branch = $request->branch;

        $tables = $this->table->where(['branch_id' => $branch->id])->get();

        return response()->json(['tables' => $tables]);
    }

    public function get_customers(Request $request): JsonResponse
    {
        $key = explode(' ', $request['q']); // Splitting the query into keywords
        $data = $this->user
            ->leftJoin('customer_addresses', 'users.id', '=', 'customer_addresses.user_id') // Left join to include all users
            ->where(function ($q) use ($key) {
                if (!empty($key)) {
                    foreach ($key as $value) {
                        $q->orWhere('users.f_name', 'like', "%{$value}%")
                            ->orWhere('users.l_name', 'like', "%{$value}%")
                            ->orWhere('users.phone', 'like', "%{$value}%");
                    }
                }
            })
            ->whereNull('users.user_type') // Ensure only customers are fetched
            ->get([
                DB::raw('users.id, CONCAT(users.f_name, " ", users.l_name, " (", users.phone ,")") as text'),
                'customer_addresses.id as address_id', // Select the address ID
                'customer_addresses.address as address',
                'customer_addresses.longitude as longitude',
                'customer_addresses.latitude as latitude',
            ]);

        // Restructure data to group addresses under the user
        $groupedData = [];
        foreach ($data as $item) {
            if (!isset($groupedData[$item->id])) {
                $groupedData[$item->id] = [
                    'id' => $item->id,
                    'text' => $item->text,
                    'addresses' => []
                ];
            }

            // Check if the address values are all null
            if ($item->address_id !== null || $item->address !== null || $item->longitude !== null || $item->latitude !== null) {
                $groupedData[$item->id]['addresses'][] = [
                    'id' => $item->address_id,
                    'address' => $item->address,
                    'longitude' => $item->longitude,
                    'latitude' => $item->latitude
                ];
            }
        }

        $formattedData = array_values($groupedData);

        return response()->json($formattedData);
    }




    public function customer_store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'email' => 'required|email|unique:users,email',
        ]);

        try {
            // Create the user
            $this->user->create([
                'f_name' => $validated['f_name'],
                'l_name' => $validated['l_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => bcrypt('password'), // default password, consider replacing with user-defined
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully.',
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation exception and return proper message
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->validator->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the customer.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function place_order(Request $request): JsonResponse
    {
        try {
            $branch = $request->branch;

            $validated = $request->validate([
                'cart' => 'required|array|min:1',
                'payment_method' => 'required|string|in:pay_after_eating,cash,card',
                'coupon_discount_title' => 'nullable|string',
                'coupon_code' => 'nullable|string',
                'table_id' => 'nullable|integer',
                'order_amount' => 'required',
            ]);

            if (isset($request->people_number) && ($request->people_number > 99 || $request->people_number < 1)) {
                return response()->json(['error' => 'Enter a valid number of people'], 422);
            }

            $cart = $request->cart;
            $total_tax_amount = 0;
            $total_addon_price = 0;
            $product_price = 0;
            $order_details = [];

            // Generate Order ID
            $order_id = 100000 + $this->order->all()->count() + 1;
            if ($this->order->find($order_id)) {
                $order_id = $this->order->orderBy('id', 'DESC')->first()->id + 1;
            }

            $order = $this->order;
            $order->id = $order_id;
            $order->user_id = $request->user_id ?? null;
            $order->coupon_discount_title = $request->coupon_discount_title;
            $order->payment_status = $request->payment_method == 'pay_after_eating' ? 'unpaid' : 'paid';
            $order->order_status = $request->has('table_id') ? 'confirmed' : 'delivered';
            $order->order_type = $request->order_type;
            $order->coupon_code = $request->coupon_code;
            $order->payment_method = $request->payment_method;
            $order->transaction_reference = $request->transaction_reference;
            $order->delivery_charge = 0;
            $order->delivery_address_id = $request->delivery_address_id;
            $order->delivery_date = Carbon::now()->format('Y-m-d');
            $order->delivery_time = Carbon::now()->format('H:i:s');
            $order->order_note = null;
            $order->checked = 1;
            $order->created_at = now();
            $order->updated_at = now();

            foreach ($cart as $c) {
                $product = $this->product->find($c['id']);

                if (!$product || $product->stock < $c['quantity']) {
                    return response()->json([
                        'message' => "Insufficient stock for product: {$product->name}",
                    ], 422);
                }

                $product->stock -= $c['quantity'];
                $product->save();

                $product_subtotal = ($c['price']) * $c['quantity'];
                $discount_on_product = ($c['discount'] ?? 0) * $c['quantity'];
                $variation = is_string($c['variation']) ? json_decode($c['variation'], true) : $c['variation'];

                $addon_ids = [];
                $addon_qtys = [];
                if (isset($c['add_ons']) && is_array($c['add_ons'])) {
                    foreach ($c['add_ons'] as $addon) {
                        $addon_ids[] = $addon['id'] ?? null;
                        $addon_qtys[] = $addon['qty'] ?? 0;
                    }
                }

                $addon_data = Helpers::calculate_addon_price(AddOn::whereIn('id', $addon_ids ?? [])->get(), $addon_qtys ?? []);
                $tax_amount = Helpers::tax_calculate($product, $c['price']);

                $order_details[] = [
                    'product_id' => $c['id'],
                    'product_details' => $product,
                    'quantity' => $c['quantity'],
                    'price' => $c['price'],
                    'complimentary' => $c['complimentary'] ?? null,
                    'comment' => $c['comment'] ?? null,
                    'tax_amount' => $tax_amount,
                    'discount_on_product' => $discount_on_product,
                    'variation' => json_encode($variation),
                    'add_on_ids' => json_encode($addon_ids),
                    'add_on_qtys' => json_encode($addon_qtys),
                    'add_on_prices' => json_encode($c['add_on_prices'] ?? []),
                    'add_on_taxes' => json_encode($c['add_on_tax'] ?? []),
                    'add_on_tax_amount' => $c['addon_total_tax'] ?? 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $total_tax_amount += $tax_amount * $c['quantity'];
                $total_addon_price += $addon_data['total_add_on_price'] ?? 0;
                $product_price += $product_subtotal - $discount_on_product;
            }

            $total_price = $product_price + $total_addon_price;
            if (isset($cart['extra_discount'])) {
                $extra_discount = ($cart['extra_discount_type'] === 'percent' && $cart['extra_discount'] > 0)
                    ? (($total_price * $cart['extra_discount']) / 100)
                    : $cart['extra_discount'];
                $total_price -= $extra_discount;
            }

            // $total_tax_amount = isset($cart['tax']) ? (($total_price * $cart['tax']) / 100) : $total_tax_amount;

            $total_tax_amount = $request['total_tax_amount'] ?? 0.00;


            $order->extra_discount = $extra_discount ?? 0;
            $order->total_tax_amount = $total_tax_amount;
            $order->order_amount = $request->order_amount;
            $order->discount_type = $request->discount_type;
            $order->discount = $request->discount;
            $order->branch_id = $branch->id;
            $order->table_id = $request->table_id ?? null;
            $order->number_of_people = $request->people_number ?? null;
            $order->save();

            foreach ($order_details as &$item) {
                $item['order_id'] = $order->id;
            }

            OrderDetail::insert($order_details);

            $order = $this->order
                ->with([
                    'table',
                    'details.product.kitchen.printer',
                    'details.product.translations',
                ])
                ->find($order_id);

            info('order data', [$order->translation]);

            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            foreach ($order->details as $detail) {
                $detail->add_on_qtys = is_string($detail->add_on_qtys) ? json_decode($detail->add_on_qtys, true) : $detail->add_on_qtys;
                $detail->variation = is_string($detail->variation) ? json_decode($detail->variation, true) : $detail->variation;
                $detail->add_on_taxes = is_string($detail->add_on_taxes) ? json_decode($detail->add_on_taxes, true) : $detail->add_on_taxes;
                $detail->add_on_prices = is_string($detail->add_on_prices) ? json_decode($detail->add_on_prices, true) : $detail->add_on_prices;
                $detail->add_on_ids = is_string($detail->add_on_ids) ? json_decode($detail->add_on_ids, true) : $detail->add_on_ids;
                $detail->product_details = is_string($detail->product_details) ? json_decode($detail->product_details, true) : $detail->product_details;
                $detail->tax_amount = $total_tax_amount;

                $productDetails = $detail->product_details;

                if (isset($productDetails['variations'])) {
                    $productDetails['variations'] = is_string($productDetails['variations']) ? json_decode($productDetails['variations'], true) : $productDetails['variations'];
                }

                if (isset($productDetails['category_ids'])) {
                    $productDetails['category_ids'] = is_string($productDetails['category_ids']) ? json_decode($productDetails['category_ids'], true) : $productDetails['category_ids'];
                }

                if (isset($productDetails['add_ons'])) {
                    if (is_string($productDetails['add_ons'])) {
                        $decodedOnce = json_decode($productDetails['add_ons'], true);
                        $productDetails['add_ons'] = is_string($decodedOnce) ? json_decode($decodedOnce, true) : $decodedOnce;
                    }
                }
                if (isset($productDetails['tags'])) {
                    $productDetails['tags'] = is_string($productDetails['tags']) ? json_decode($productDetails['tags'], true) : $productDetails['tags'];
                }
                if (isset($productDetails['attributes'])) {
                    $productDetails['attributes'] = is_string($productDetails['attributes']) ? json_decode($productDetails['attributes'], true) : $productDetails['attributes'];
                }

                if (isset($productDetails['translations']) && !empty($productDetails['translations'])) {
                    $formattedTranslations = [
                        'name' => [],
                        'description' => [],
                    ];

                    foreach ($productDetails['translations'] as $translation) {
                        if (isset($translation['key']) && $translation['key'] === 'name') {
                            $formattedTranslations['name'][] = [
                                'id' => $translation['id'],
                                'locale' => $translation['locale'],
                                'value' => $translation['value'],
                            ];
                        } elseif (isset($translation['key']) && $translation['key'] === 'description') {
                            $formattedTranslations['description'][] = [
                                'id' => $translation['id'],
                                'locale' => $translation['locale'],
                                'value' => $translation['value'],
                            ];
                        }
                    }

                    unset($productDetails['translations']);

                    $productDetails['translations'] = $formattedTranslations;
                } else {
                    $productDetails['translations'] = [
                        'name' => [],
                        'description' => [],
                    ];
                }

                $detail->product_details = $productDetails;

                // $detail->product->category_ids = json_decode($detail->product->category_ids, true);
                $detail->product->category_ids = is_string($detail->product->category_ids) ? json_decode($detail->product->category_ids, true) : $detail->product->category_ids;

                // $detail->product->tags = json_decode($detail->product->tags, true);
                $detail->product->tags = is_string($detail->product->tags) ? json_decode($detail->product->tags, true) : $detail->product->tags;

                $detail->product->variations = is_string($detail->product->variations) ? json_decode($detail->product->variations, true) : $detail->product->variations;
                $detail->product->attributes = is_string($detail->product->attributes) ? json_decode($detail->product->attributes, true) : $detail->product->attributes;
                $detail->product->choice_options = is_string($detail->product->choice_options) ? json_decode($detail->product->choice_options, true) : $detail->product->choice_options;

                if (is_string($detail->product->add_ons)) {
                    $add_on_ids = is_array(json_decode($detail->product->add_ons, true)) ? json_decode($detail->product->add_ons, true) : [];
                    $detail->product->add_ons = AddOn::whereIn('id', $add_on_ids)->get();
                }
                // $detail->product->translations = is_string($detail->product->translations) ? json_decode($detail->product->translations, true) : $detail->product->translations;

                if (isset($detail->product->translations) && !empty($detail->product->translations)) {
                    $formattedTranslations = [
                        'name' => [],
                        'description' => [],
                    ];

                    foreach ($detail->product->translations as $translation) {
                        if (isset($translation['key']) && $translation['key'] === 'name') {
                            $formattedTranslations['name'][] = [
                                'id' => $translation['id'],
                                'locale' => $translation['locale'],
                                'value' => $translation['value'],
                            ];
                        } elseif (isset($translation['key']) && $translation['key'] === 'description') {
                            $formattedTranslations['description'][] = [
                                'id' => $translation['id'],
                                'locale' => $translation['locale'],
                                'value' => $translation['value'],
                            ];
                        }
                    }

                    unset($detail->product->translations);

                    $detail->product->translations = $formattedTranslations;
                } else {
                    $detail->product->translations = [
                        'name' => [],
                        'description' => [],
                    ];
                }
            }

            $orderArray = $order->toArray();

            info('Final order response:', $orderArray);

            if ($request->order_type === "dine_in") {
                $table = $this->table->where('branch_id', $branch->id)
                    ->where('id', $request->table_id)
                    ->first();

                if (!$table) {
                    return response()->json(['message' => 'Table not found'], 404);
                }

                if ($table->is_available == 0) {
                    return response()->json(['message' => 'Table is already booked'], 409);
                }

                $table->update(['is_available' => 0]);
            }

            return response()->json([
                'message' => 'Order placed successfully',
                'order' => $orderArray,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to place order', 'details' => $e->getMessage()], 500);
        }
    }

    public function update_order(Request $request): JsonResponse
    {
        info('Request data:', $request->all());
        try {
            // Validate request data
            $validated = $request->validate([
                'order_id' => 'required|integer',
                'cart' => 'nullable|array|min:1',
                'payment_method' => 'nullable|string|in:pay_after_eating,cash,card',
                'coupon_discount_title' => 'nullable|string',
                'coupon_code' => 'nullable|string',
                'table_id' => 'nullable|integer',
                'order_amount' => 'nullable|numeric',
                'order_status' => 'nullable|string',
            ]);

            $order = $this->order->find($validated['order_id']);
            $branch = $request->branch;
            info('Branch data:', [$branch]);

            if (isset($request->people_number) && ($request->people_number > 99 || $request->people_number < 1)) {
                return response()->json(['error' => 'Enter a valid number of people'], 422);
            }

            // Update order details
            $order->coupon_discount_title = $request->coupon_discount_title ?? $order->coupon_discount_title;
            $order->coupon_code = $request->coupon_code ?? $order->coupon_code;
            $order->payment_method = $request->payment_method ?? $order->payment_method;
            $order->payment_status = $request->payment_method === 'pay_after_eating' ? 'unpaid' : $order->payment_status;
            $order->order_amount = $request->order_amount ?? $order->order_amount;
            $order->number_of_people = $request->people_number ?? $order->number_of_people;
            $order->table_id = $request->table_id ?? $order->table_id;
            $order->order_status = $request->order_status ?? $order->order_status;

            // Handle cart updates if provided
            if ($request->has('cart')) {
                $cart = $request->cart;
                $total_tax_amount = 0;
                $total_addon_price = 0;
                $product_price = 0;

                // Fetch all products in one query
                $productIds = array_column($cart, 'id');
                $productIds = array_filter($productIds, fn($id) => is_numeric($id)); // ✅ Ensure numeric values

                if (!empty($productIds)) {
                    $products = $this->product->whereIn('id', $productIds)->get()->keyBy('id');
                } else {
                    return response()->json(['error' => 'Invalid product selection.'], 422);
                }


                foreach ($cart as $c) {
                    $product = $products[$c['id']] ?? null;

                    if (!$product || $product->stock < $c['quantity']) {
                        return response()->json([
                            'message' => "Insufficient stock for product: {$product->name}",
                        ], 422);
                    }

                    // Find existing order detail for this product in the same order
                    $orderDetail = OrderDetail::where('order_id', $order->id)
                        ->where('product_id', $c['id'])
                        ->first();
                    // Get the list of product IDs from the cart
                    $cartProductIds = array_column($cart, 'id');

                    // Delete products from order_details that are not in the cart
                    OrderDetail::where('order_id', $order->id)
                        ->whereNotIn('product_id', $cartProductIds)
                        ->delete();




                    // If product already exists in order, update it
                    if ($orderDetail) {
                        $updatedQty = $c['quantity'] - $orderDetail->quantity;
                        $product->stock -= $updatedQty;
                        $product->save();

                        $orderDetail->quantity = $c['quantity'];
                        $orderDetail->price = $c['price'];
                        $orderDetail->complimentary = $c['complimentary'] ?? $orderDetail->complimentary;
                        $orderDetail->comment = $c['comment'] ?? $orderDetail->comment;
                        $orderDetail->tax_amount = Helpers::tax_calculate($product, $c['price']);
                        $orderDetail->discount_on_product = ($c['discount'] ?? 0) * $c['quantity'];
                        $orderDetail->variation = json_encode(is_string($c['variation']) ? json_decode($c['variation'], true) : $c['variation']);
                        $orderDetail->add_on_ids = json_encode(array_column($c['add_ons'] ?? [], 'id'));
                        $addOnQtys = array_column($c['add_ons'] ?? [], 'qty');
                        $orderDetail->add_on_qtys = !empty(array_filter($addOnQtys, fn($qty) => $qty !== null)) ? json_encode($addOnQtys) : json_encode([]);
                        $orderDetail->add_on_prices = json_encode(array_column($c['add_ons'] ?? [], 'price'));
                        $orderDetail->add_on_taxes = json_encode(array_column($c['add_ons'] ?? [], 'tax') ?? []);
                        $orderDetail->add_on_tax_amount = $c['addon_total_tax'] ?? 0;
                        $orderDetail->updated_at = now();
                        $orderDetail->save(); // ✅ Save one by one
                    } else {
                        // ✅ Create a new OrderDetail entry
                        $newOrderDetail = new OrderDetail();
                        $newOrderDetail->order_id = $order->id;
                        $newOrderDetail->product_id = $c['id'];
                        $newOrderDetail->product_details = json_encode($product); // Store product details as JSON
                        $newOrderDetail->quantity = $c['quantity'];
                        $newOrderDetail->price = $c['price'];
                        $newOrderDetail->complimentary = $c['complimentary'] ?? null;
                        $newOrderDetail->comment = $c['comment'] ?? null;
                        $newOrderDetail->tax_amount = Helpers::tax_calculate($product, $c['price']);
                        $newOrderDetail->discount_on_product = ($c['discount'] ?? 0) * $c['quantity'];
                        $newOrderDetail->variation = json_encode(is_string($c['variation']) ? json_decode($c['variation'], true) : $c['variation']);

                        $newOrderDetail->add_on_ids = json_encode(array_column($c['add_ons'] ?? [], 'id'));
                        $newOrderDetail->add_on_qtys = json_encode(array_column($c['add_ons'] ?? [], 'qty'));
                        $newOrderDetail->add_on_prices = json_encode(array_column($c['add_ons'] ?? [], 'price'));
                        $newOrderDetail->add_on_taxes = json_encode(array_column($c['add_ons'] ?? [], 'tax') ?? []);
                        $newOrderDetail->add_on_tax_amount = $c['addon_total_tax'] ?? 0;
                        $newOrderDetail->created_at = now();
                        $newOrderDetail->updated_at = now();

                        $newOrderDetail->save(); // ✅ Save after assigning all fields

                        // ✅ Log new order detail for debugging
                        info('New Order Detail Inserted:', $newOrderDetail->toArray());

                        // ✅ Deduct stock for newly added product
                        $product->stock -= $c['quantity'];
                        $product->save();
                    }



                    // Fetching AddOns safely
                    $addon_ids = array_column($c['add_ons'] ?? [], 'id');
                    $addon_ids = array_filter($addon_ids, fn($id) => is_numeric($id)); // ✅ Ensure numeric values

                    if (!empty($addon_ids)) {
                        $total_addon_price += Helpers::calculate_addon_price(
                            AddOn::whereIn('id', $addon_ids)->get(),
                            $c['add_on_qtys'] ?? []
                        )->total_add_on_price ?? 0;
                    }

                    $addon_ids = array_column($c['add_ons'] ?? [], 'id');
                    $total_addon_price += Helpers::calculate_addon_price(AddOn::whereIn('id', $addon_ids)->get(), $c['add_on_qtys'] ?? [])->total_add_on_price ?? 0;
                    $product_price += ($c['price'] * $c['quantity']) - (($c['discount'] ?? 0) * $c['quantity']);
                }


                // Update totals
                $total_price = $product_price + $total_addon_price;

                if (isset($cart['extra_discount'])) {
                    $extra_discount = ($cart['extra_discount_type'] === 'percent' && $cart['extra_discount'] > 0)
                        ? (($total_price * $cart['extra_discount']) / 100)
                        : $cart['extra_discount'];
                    $total_price -= $extra_discount;
                }

                $total_tax_amount = isset($cart['tax']) ? (($total_price * $cart['tax']) / 100) : $total_tax_amount;

                $order->extra_discount = $extra_discount ?? 0;
                $order->total_tax_amount = $total_tax_amount;
                $order->save();
            }

            // Fetch updated order with details
            $order = $this->order
                ->with([
                    'table',
                    'details.product.kitchen.printer',
                    'details.product.translations',
                ])
                ->find($order->id);

            foreach ($order->details as $detail) {
                $detail->add_on_qtys = is_string($detail->add_on_qtys) ? json_decode($detail->add_on_qtys, true) : $detail->add_on_qtys;
                $detail->variation = is_string($detail->variation) ? json_decode($detail->variation, true) : $detail->variation;
                $detail->add_on_taxes = is_string($detail->add_on_taxes) ? json_decode($detail->add_on_taxes, true) : $detail->add_on_taxes;
                $detail->add_on_prices = is_string($detail->add_on_prices) ? json_decode($detail->add_on_prices, true) : $detail->add_on_prices;
                $detail->add_on_ids = is_string($detail->add_on_ids) ? json_decode($detail->add_on_ids, true) : $detail->add_on_ids;
                $detail->product_details = is_string($detail->product_details) ? json_decode($detail->product_details, true) : $detail->product_details;
                $productDetails = $detail->product_details;

                if (isset($productDetails['variations'])) {
                    $productDetails['variations'] = is_string($productDetails['variations']) ? json_decode($productDetails['variations'], true) : $productDetails['variations'];
                }

                if (isset($productDetails['category_ids'])) {
                    $productDetails['category_ids'] = is_string($productDetails['category_ids']) ? json_decode($productDetails['category_ids'], true) : $productDetails['category_ids'];
                }

                if (isset($productDetails['add_ons'])) {
                    if (is_string($productDetails['add_ons'])) {
                        $decodedOnce = json_decode($productDetails['add_ons'], true);
                        $productDetails['add_ons'] = is_string($decodedOnce) ? json_decode($decodedOnce, true) : $decodedOnce;
                    }
                }
                if (isset($productDetails['tags'])) {
                    $productDetails['tags'] = is_string($productDetails['tags']) ? json_decode($productDetails['tags'], true) : $productDetails['tags'];
                }
                if (isset($productDetails['attributes'])) {
                    $productDetails['attributes'] = is_string($productDetails['attributes']) ? json_decode($productDetails['attributes'], true) : $productDetails['attributes'];
                }

                if (isset($productDetails['translations']) && !empty($productDetails['translations'])) {
                    $formattedTranslations = [
                        'name' => [],
                        'description' => [],
                    ];

                    foreach ($productDetails['translations'] as $translation) {
                        if (isset($translation['key']) && $translation['key'] === 'name') {
                            $formattedTranslations['name'][] = [
                                'id' => $translation['id'],
                                'locale' => $translation['locale'],
                                'value' => $translation['value'],
                            ];
                        } elseif (isset($translation['key']) && $translation['key'] === 'description') {
                            $formattedTranslations['description'][] = [
                                'id' => $translation['id'],
                                'locale' => $translation['locale'],
                                'value' => $translation['value'],
                            ];
                        }
                    }

                    unset($productDetails['translations']);

                    $productDetails['translations'] = $formattedTranslations;
                } else {
                    $productDetails['translations'] = [
                        'name' => [],
                        'description' => [],
                    ];
                }

                $detail->product_details = $productDetails;

                $detail->product->category_ids = is_string($detail->product->category_ids) ? json_decode($detail->product->category_ids, true) : $detail->product->category_ids;
                $detail->product->tags = is_string($detail->product->tags) ? json_decode($detail->product->tags, true) : $detail->product->tags;
                $detail->product->variations = is_string($detail->product->variations) ? json_decode($detail->product->variations, true) : $detail->product->variations;
                $detail->product->attributes = is_string($detail->product->attributes) ? json_decode($detail->product->attributes, true) : $detail->product->attributes;
                $detail->product->choice_options = is_string($detail->product->choice_options) ? json_decode($detail->product->choice_options, true) : $detail->product->choice_options;

                // Fixing add_ons fetching in product details
                if (is_string($detail->product->add_ons)) {
                    $add_on_ids = json_decode($detail->product->add_ons, true);

                    if (is_array($add_on_ids) && !empty($add_on_ids)) {
                        $add_on_ids = array_filter($add_on_ids, fn($id) => is_numeric($id)); // ✅ Filter invalid IDs
                        $detail->product->add_ons = AddOn::whereIn('id', $add_on_ids)->get();
                    } else {
                        $detail->product->add_ons = [];
                    }
                }

                if (isset($detail->product->translations) && !empty($detail->product->translations)) {
                    $formattedTranslations = [
                        'name' => [],
                        'description' => [],
                    ];

                    foreach ($detail->product->translations as $translation) {
                        if (isset($translation['key']) && $translation['key'] === 'name') {
                            $formattedTranslations['name'][] = [
                                'id' => $translation['id'],
                                'locale' => $translation['locale'],
                                'value' => $translation['value'],
                            ];
                        } elseif (isset($translation['key']) && $translation['key'] === 'description') {
                            $formattedTranslations['description'][] = [
                                'id' => $translation['id'],
                                'locale' => $translation['locale'],
                                'value' => $translation['value'],
                            ];
                        }
                    }

                    unset($detail->product->translations);

                    $detail->product->translations = $formattedTranslations;
                } else {
                    $detail->product->translations = [
                        'name' => [],
                        'description' => [],
                    ];
                }
            }

            $orderArray = $order->toArray();

            if ($request->order_type === "dine_in") {
                $table = $this->table->where('branch_id', $branch->id)
                    ->where('id', $request->table_id)
                    ->first();

                if (!$table) {
                    return response()->json(['message' => 'Table not found'], 404);
                }

                if ($table->is_available == 0) {
                    return response()->json(['message' => 'Table is already booked'], 409);
                }

                $table->update(['is_available' => 0]);
            }

            return response()->json([
                'message' => 'Order updated successfully',
                'order' => $orderArray,
            ], 200);
        } catch (\Exception $e) {
            // Log the error with line number
            \Log::error('Error in update_order: ' . $e->getMessage() . ' on line ' . $e->getLine());
            return response()->json(['error' => 'Failed to update order', 'details' => $e->getMessage()], 500);
        }
    }

    public function order_print(Request $request)
    {
        $order_id = $request->order_id;

        try {
            // Fetch the order with details
            $order = $this->order
                ->with([
                    'table.group', // Include the group relation
                    'customer', // Include customer
                    'customer.addresses', // Include customer addresses
                    'details.product.kitchen.printer',
                    'details.product.translations',
                ])
                ->find($order_id);

            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            foreach ($order->details as $detail) {
                $detail->add_on_qtys = is_string($detail->add_on_qtys) ? json_decode($detail->add_on_qtys, true) : $detail->add_on_qtys;
                $detail->variation = is_string($detail->variation) ? json_decode($detail->variation, true) : $detail->variation;
                $detail->add_on_taxes = is_string($detail->add_on_taxes) ? json_decode($detail->add_on_taxes, true) : $detail->add_on_taxes;
                $detail->add_on_prices = is_string($detail->add_on_prices) ? json_decode($detail->add_on_prices, true) : $detail->add_on_prices;
                $detail->add_on_ids = is_string($detail->add_on_ids) ? json_decode($detail->add_on_ids, true) : $detail->add_on_ids;
                $detail->product_details = is_string($detail->product_details) ? json_decode($detail->product_details, true) : $detail->product_details;
                $productDetails = $detail->product_details;

                if (isset($productDetails['variations'])) {
                    $productDetails['variations'] = is_string($productDetails['variations']) ? json_decode($productDetails['variations'], true) : $productDetails['variations'];
                }

                if (isset($productDetails['category_ids'])) {
                    $productDetails['category_ids'] = is_string($productDetails['category_ids']) ? json_decode($productDetails['category_ids'], true) : $productDetails['category_ids'];
                }

                if (isset($productDetails['add_ons'])) {
                    if (is_string($productDetails['add_ons'])) {
                        $decodedOnce = json_decode($productDetails['add_ons'], true);
                        $productDetails['add_ons'] = is_string($decodedOnce) ? json_decode($decodedOnce, true) : $decodedOnce;
                    }
                }
                if (isset($productDetails['tags'])) {
                    $productDetails['tags'] = is_string($productDetails['tags']) ? json_decode($productDetails['tags'], true) : $productDetails['tags'];
                }
                if (isset($productDetails['attributes'])) {
                    $productDetails['attributes'] = is_string($productDetails['attributes']) ? json_decode($productDetails['attributes'], true) : $productDetails['attributes'];
                }
                // Calculate the final price based on discount type
                if (isset($productDetails['discount_type']) && $productDetails['discount_type'] == 'amount') {
                    $dis = $productDetails['discount'];
                    $final_price = $productDetails['price'] - $dis;
                    $productDetails['price'] = $final_price;
                } elseif (isset($productDetails['discount_type']) && $productDetails['discount_type'] == 'percent') {
                    $dis = ($productDetails['price'] / 100) * $productDetails['discount'];
                    $final_price = $productDetails['price'] - $dis;
                    $productDetails['price'] = $final_price;
                }

                if (isset($productDetails['translations']) && !empty($productDetails['translations'])) {
                    $formattedTranslations = [
                        'name' => [],
                        'description' => [],
                    ];

                    foreach ($productDetails['translations'] as $translation) {
                        if (isset($translation['key']) && $translation['key'] === 'name') {
                            $formattedTranslations['name'][] = [
                                'id' => $translation['id'],
                                'locale' => $translation['locale'],
                                'value' => $translation['value'],
                            ];
                        } elseif (isset($translation['key']) && $translation['key'] === 'description') {
                            $formattedTranslations['description'][] = [
                                'id' => $translation['id'],
                                'locale' => $translation['locale'],
                                'value' => $translation['value'],
                            ];
                        }
                    }

                    unset($productDetails['translations']);

                    $productDetails['translations'] = $formattedTranslations;
                } else {
                    $productDetails['translations'] = [
                        'name' => [],
                        'description' => [],
                    ];
                }

                $detail->product_details = $productDetails;

                $detail->product->category_ids = is_string($detail->product->category_ids) ? json_decode($detail->product->category_ids, true) : $detail->product->category_ids;
                $detail->product->tags = is_string($detail->product->tags) ? json_decode($detail->product->tags, true) : $detail->product->tags;
                $detail->product->variations = is_string($detail->product->variations) ? json_decode($detail->product->variations, true) : $detail->product->variations;
                $detail->product->attributes = is_string($detail->product->attributes) ? json_decode($detail->product->attributes, true) : $detail->product->attributes;
                $detail->product->choice_options = is_string($detail->product->choice_options) ? json_decode($detail->product->choice_options, true) : $detail->product->choice_options;

                if (is_string($detail->product->add_ons)) {
                    $add_on_ids = is_array(json_decode($detail->product->add_ons, true)) ? json_decode($detail->product->add_ons, true) : [];
                    $detail->product->add_ons = AddOn::whereIn('id', $add_on_ids)->get();
                }

                if (isset($detail->product->translations) && !empty($detail->product->translations)) {
                    $formattedTranslations = [
                        'name' => [],
                        'description' => [],
                    ];

                    foreach ($detail->product->translations as $translation) {
                        if (isset($translation['key']) && $translation['key'] === 'name') {
                            $formattedTranslations['name'][] = [
                                'id' => $translation['id'],
                                'locale' => $translation['locale'],
                                'value' => $translation['value'],
                            ];
                        } elseif (isset($translation['key']) && $translation['key'] === 'description') {
                            $formattedTranslations['description'][] = [
                                'id' => $translation['id'],
                                'locale' => $translation['locale'],
                                'value' => $translation['value'],
                            ];
                        }
                    }

                    unset($detail->product->translations);

                    $detail->product->translations = $formattedTranslations;
                } else {
                    $detail->product->translations = [
                        'name' => [],
                        'description' => [],
                    ];
                }
            }

            $orderArray = $order->toArray();

            // Add group name to the response
            $orderArray['table']['group_name'] = $order->table->group->name ?? null;
            // Fix: Ensure customer address is an array before processing
            if (isset($orderArray['customer'])) {
                $addresses = is_array($orderArray['customer']['addresses']) ? $orderArray['customer']['addresses'] : [];

                // Filter addresses based on delivery_address_id
                $filteredAddress = array_filter($addresses, function ($address) use ($orderArray) {
                    return isset($address['id']) && $address['id'] == $orderArray['delivery_address_id'];
                });

                // Ensure it is formatted as an indexed array
                $orderArray['customer']['customer_address'] = array_values($filteredAddress);

                // Remove the full addresses list
                unset($orderArray['customer']['addresses']);
            }

            return response()->json([
                'message' => 'Order details fetched successfully',
                'order' => $orderArray,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch order details', 'details' => $e->getMessage()], 500);
        }
    }
    public function order_list(Request $request)
    {
        $branch = $request->branch;

        $from = $request->from;
        $to = $request->to;

        // Mark unprocessed orders as checked
        $this->order->where(['checked' => 0])->update(['checked' => 1]);

        // Build the query
        $query = $this->order->with([
            'table.group',
            'customer.addresses', // Include customer addresses
            'branch',
            'details'
        ])
            ->where('branch_id', $branch->id);

        // Apply date range filter if provided
        if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }

        // Fetch paginated orders
        $orders = $query->latest()->paginate(Helpers::getPagination());

        $data = Helpers::order_data_formatting($orders->items(), true);

        // Include table group data and customer addresses in the response
        foreach ($data as &$order) {
            if (isset($order['table'])) {
                $order['table']['group'] = $order['table']['group'] ?? null;
            }
            if (isset($order['customer'])) {
                $addresses = $order['customer']['addresses']->toArray();
                $order['customer']['customer_address'] = array_values(array_filter($addresses, function ($address) use ($order) {
                    return $address['id'] == $order['delivery_address_id'];
                }));
                unset($order['customer']['addresses']);
            }
        }


        // Return JSON response for the API
        return response()->json([
            'orders' => $data,
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'last_page' => $orders->lastPage(),
            ],
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function update_order_status(Request $request): JsonResponse
    {
        // Validate the request data
        $request->validate([
            'order_id' => 'required|exists:orders,id', // Ensure the order exists
            'order_status' => 'required|string', // Define allowed statuses
        ]);

        // Fetch the order
        $order = Order::findOrFail($request->order_id); // Use findOrFail to handle invalid IDs

        // Update the order status
        $order->order_status = $request->order_status;
        $order->save();

        return response()->json([
            'message' => 'Order status updated successfully.',
            'order' => $order,
        ], 200);
    }
}
