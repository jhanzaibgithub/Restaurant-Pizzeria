<?php

namespace App\Http\Controllers\Branch;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Product;
use App\Model\ProductByBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Renderable;
use Rap2hpoutre\FastExcel\FastExcel;

class ProductController extends Controller
{
    public function __construct(
        private Product         $product,
        private ProductByBranch $product_by_branch,
    ) {}


    /**
     * @param Request $request
     * @return Renderable
     */
    public function list(Request $request): Renderable
    {
        $query_param = [];
        $search = $request['search'];

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->product->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->product;
        }
        $products = $query
            ->with(['product_by_branch' => function ($query) {
                $query->select('id', 'product_id', 'branch_id', 'price', 'discount_type', 'discount', 'is_available');
            }])
            ->select('id', 'name', 'image', 'price', 'discount_type', 'discount', 'variations')
            ->orderBy('id', 'DESC')
            ->paginate(Helpers::getPagination())
            ->appends($query_param);

        return view('branch-views.product.list', compact('products', 'search'));
    }

    /**
     * @param $id
     * @return Renderable
     */
    public function set_price_index($id): Renderable
    {
        $product = $this->product->with(['translations', 'product_by_branch'])->find($id);
        return view('branch-views.product.set-price', compact('product'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function set_price_update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'price' => 'required',
            'discount_type' => 'required|in:percent,amount',
            'discount' => 'required',
        ], [
            'price.required' => translate('Product price is required!'),
            'discount_type.required' => translate('please select discount type!'),
            'discount.required' => translate('discount is required!')
        ]);

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['price'] <= $dis) {
            $validator->getMessageBag()->add('unit_price', translate('Discount can not be more or equal to the price!'));
        }

        if ($request['price'] <= $dis || $validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $price = $request['price'];
        $variations = [];

        if (isset($request->options)) {
            foreach (array_values($request->options) as $key => $option) {
                $temp_variation['name'] = $option['name'];
                $temp_variation['type'] = $option['type'];
                $temp_variation['min'] = $option['min'] ?? 0;
                $temp_variation['max'] = $option['max'] ?? 0;
                $temp_variation['required'] = $option['required'] ?? 'off';
                if ($option['min'] > 0 && $option['min'] >= $option['max']) {
                    $validator->getMessageBag()->add('name', translate('maximum_value_can_not_be_smaller_or_equal_then_minimum_value'));
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
                if (!isset($option['values'])) {
                    $validator->getMessageBag()->add('name', translate('please_add_options_for') . ' ' . $option['name']);
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
                if ($option['max'] > count($option['values'])) {
                    $validator->getMessageBag()->add('name', translate('please_add_more_options_or_change_the_max_value_for') . ' ' . $option['name']);
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
                $temp_value = [];

                foreach ($option['values'] as $value) {
                    if (isset($value['label'])) {
                        $temp_option['label'] = $value['label'];
                    }
                    $temp_option['optionPrice'] = $value['optionPrice'];
                    $temp_value[] = $temp_option;
                }
                $temp_variation['values'] = $temp_value;
                $variations[] = $temp_variation;
            }
        }

        $product_id = $id;
        $branch_product = [
            'product_id' => $product_id,
            'price' => $price,
            'discount_type' => $request['discount_type'],
            'discount' => $request['discount'],
            'branch_id' => auth('branch')->id(),
            'is_available' => 1,
            'variations' => $variations,
        ];

        $this->product_by_branch->updateOrCreate(
            [
                'product_id' => $branch_product['product_id'],
                'branch_id' => auth('branch')->id(),
            ],
            $branch_product
        );

        if (auth('branch')->id() == 1) {
            $product = $this->product->find($branch_product['product_id']);
            if ($product) {
                $product->price = $request['price'];
                $product->discount_type = $request['discount_type'];
                $product->discount = $request['discount'];
                $product->variations = json_encode($variations);
                $product->update();
            }
        }

        return response()->json([], 200);
    }
    public function search(Request $request): JsonResponse
    {
        $key = explode(' ', $request['search']);
        $products = $this->product->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })->get();

        return response()->json([
            'view' => view('branch-views.product.partials._table', compact('products'))->render()
        ]);
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function status(Request $request): JsonResponse
    {
        $product = $this->product->find($request->id);
        $branch_product = $this->product_by_branch->where(['product_id' => $product->id, 'branch_id' => auth('branch')->id()])->first();

        if (isset($branch_product)) {
            $data = [
                'price' => $branch_product->price,
                'discount_type' => $branch_product->discount_type,
                'discount' => $branch_product->discount,
                'product_id' => $product->id,
                'is_available' => $request->status,
            ];

            $this->product_by_branch->updateOrCreate([
                'product_id' => $data['product_id'],
                'branch_id' => auth('branch')->id()
            ], $data);
        } else {
            $variations = json_decode($product->variations, true);

            $data = [];
            if (count($variations) > 0) {
                foreach ($variations as $variation) {

                    if (isset($variation["price"])) {
                        return response()->json(['variation_message' => 'Please update your variation first!']);
                    }

                    $var[] = $variation;
                    $data = [
                        'product_id' => $product->id,
                        'price' => $product->price,
                        'discount_type' => $product->discount_type,
                        'discount' => $product->discount,
                        'branch_id' => auth('branch')->id(),
                        'is_available' => $request->status,
                        'variations' => $var,
                    ];
                }
            } else {
                $data = [
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'discount_type' => $product->discount_type,
                    'discount' => $product->discount,
                    'branch_id' => auth('branch')->id(),
                    'is_available' => $request->status,
                    'variations' => [],
                ];
            }

            $this->product_by_branch->updateOrCreate([
                'product_id' => $product->id,
                'branch_id' => auth('branch')->id()
            ], $data);
        }

        return response()->json(['success_message' => 'Status updated!']);
    }
    public function excel_import(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse|string
    {

        $search = $request['search'];
        $products = $this->product->when($search, function ($query) use ($search) {
            $key = explode(' ', $search);
            foreach ($key as $value) {
                $query->orWhere('id', 'like', "%{$value}%")
                    ->orWhere('name', 'like', "%{$value}%");
            };
        });

        $storage = function () use ($products) {
            foreach ($products->lazy() as $item) {
                $category_id = 0;
                $sub_category_id = 0;
                foreach (json_decode($item->category_ids, true) as $category) {
                    if ($category['position'] == 1) {
                        $category_id = $category['id'];
                    } elseif ($category['position'] == 2) {
                        $sub_category_id = $category['id'];
                    }
                }
                if (!isset($item->name)) {
                    $item->name = 'Demo Product';
                }
                if (!isset($item->description)) {
                    $item->description = 'No description available';
                }
                yield [
                    'Name' => $item->name,
                    'Description' => $item->description,
                    'Category ID' => $category_id,
                    'Sub Category ID' => $sub_category_id,
                    'Price' => $item->price,
                    'Tax' => $item->tax,
                    'Available Time Starts' => $item->available_time_starts,
                    'Available Time Ends' => $item->available_time_ends,
                    'Status' => $item->status,
                    'Discount' => $item->discount,
                    'Discount Type' => $item->discount_type,
                    'Tax Type' => $item->tax_type,
                    'Set Menu' => $item->set_menu,
                    'Product Type' => $item->product_type,
                ];
            }
        };
        return (new FastExcel($storage()))->download('products.xlsx');
    }
}
