<?php

namespace App\Http\Controllers\Branch;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\Branch;
use App\Model\Order;
use App\Model\Product;
use App\Model\OrderDetail;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Support\Renderable;

class DashboardController extends Controller
{
    public function __construct(
        private Order  $order,
        private Branch $branch,
        private OrderDetail $order_detail,
        private Product $product,
    )
    {
    }


    /**
     * @return Renderable
     */
    public function dashboard(Request $request): Renderable
    {
        $data = self::order_stats_data();

        $from = Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');

        $earning = [];
        $earning_data = $this->order->where([
            'order_status' => 'delivered',
            'branch_id' => auth('branch')->id()
        ])->select(
            DB::raw('IFNULL(sum(order_amount),0) as sums'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )
            ->whereBetween('created_at', [Carbon::parse(now())->startOfYear(), Carbon::parse(now())->endOfYear()])
            ->groupby('year', 'month')->get()->toArray();

        for ($inc = 1; $inc <= 12; $inc++) {
            $earning[$inc] = 0;
            foreach ($earning_data as $match) {
                if ($match['month'] == $inc) {
                    $earning[$inc] = Helpers::set_price($match['sums']);
                }
            }
        }

        $order_statistics_chart = [];
        $order_statistics_chart_data = $this->order
            ->where('branch_id', auth('branch')->id())
            ->select(
                DB::raw('(count(id)) as total'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month')
            )
//            ->whereBetween('created_at', [$from, $to])
            ->whereBetween('created_at', [Carbon::parse(now())->startOfYear(), Carbon::parse(now())->endOfYear()])
            ->groupby('year', 'month')->get()->toArray();

        for ($inc = 1; $inc <= 12; $inc++) {
            $order_statistics_chart[$inc] = 0;
            foreach ($order_statistics_chart_data as $match) {
                if ($match['month'] == $inc) {
                    $order_statistics_chart[$inc] = $match['total'];
                }
            }
        }

        $data['recent_orders'] = $this->order->latest()
            ->where('branch_id', auth('branch')->id())
            ->take(5)
            ->get();


        $analytics = [];
        $total_earning;
        $total_products;
        $total_employees;
        $total_branches;


        $CALfrom = $request->input('from');
        $CALto = $request->input('to');
       
        // Earning States
        $orderQuery = $this->order->where('branch_id', auth('branch')->id())
            ->where(['order_status' => 'delivered'])
            ->where('payment_status', 'paid');

        if ($CALfrom && $CALto) {
            $orderQuery->whereBetween('created_at', [$CALfrom, $CALto]);
        }
        $analytics['total_earning'] = $orderQuery->sum('order_amount');
        $currentMonthStart = $CALfrom ?? now()->startOfMonth();
        $currentMonthEnd =  $CALto ?? now()->endOfMonth();
        $previousMonthStart = $CALfrom ?? now()->subMonth()->startOfMonth();
        $previousMonthEnd = $CALto ?? now()->subMonth()->endOfMonth();
        $currentMonthEarnings = $this->order
                                    ->where('order_status', 'delivered')
                                    ->where('payment_status', 'paid')
                                    ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
                                    ->sum('order_amount');
        $previousMonthEarnings = $this->order
                                    ->where('order_status', 'delivered')
                                    ->where('payment_status', 'paid')
                                    ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
                                    ->sum('order_amount');
        $earningsTrend = $currentMonthEarnings >= $previousMonthEarnings ? 'up' : 'down';
        $analytics['earnings_trend'] = $earningsTrend;

        // Order count against branch
        $totalOrderQuery = $this->order->where('branch_id', auth('branch')->id());

        if ($CALfrom && $CALto) {
            $totalOrderQuery->whereBetween('created_at', [$CALfrom, $CALto]);
        }
        $analytics['total_orders'] = $totalOrderQuery->count('id');
        $currentMonthTotalOrders = $this->order
                                    ->where('order_status', 'delivered')
                                    ->where('payment_status', 'paid')
                                    ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
                                    ->count('id');
        $previousMonthTotalOrders = $this->order
                                    ->where('order_status', 'delivered')
                                    ->where('payment_status', 'paid')
                                    ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
                                    ->count('id');
        $totalOrderTrend = $currentMonthTotalOrders >= $previousMonthTotalOrders ? 'up' : 'down';
        $analytics['total_order_trend'] = $totalOrderTrend;

        // Products State
        $productQuery = $this->product
            ->where('branch_id', auth('branch')->id())
            ->where(['status' => 1]);
        if ($CALfrom && $CALto) {
            $productQuery->whereBetween('created_at', [$CALfrom, $CALto]);
        }
        $analytics['total_products'] = $productQuery->count('id');
        $currentMonthProducts = $productQuery
                                    ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
                                    ->count('created_at');
        $previousMonthProducts = $productQuery
                                    ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
                                    ->count('created_at');
        $productTrend = $currentMonthProducts >= $previousMonthProducts ? 'up' : 'down';
        $analytics['product_trend'] = $productTrend;

        $top_sell_products = $this->order_detail
        ->with(['product','product.rating'])
        ->whereHas('order', function ($query) {
            $query->where('order_status', 'delivered')
            ->where('branch_id', auth('branch')->id());
        })
        ->select('product_id', DB::raw('SUM(quantity) as count'))
        ->groupBy('product_id')
        ->orderBy("count", 'desc')
        ->take(6)
        ->get();

        $meal_products = $this->order_detail
            ->with(['product','product.rating'])
            ->whereHas('order', function ($query) {
                $query->where('order_status', 'delivered');
            })
            ->whereHas('product', function ($query) {
                $query->where('set_menu', 1);
            })
            ->select('product_id', DB::raw('SUM(quantity) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
            ->take(6)
            ->get();

        $data['top_sell_products'] = $top_sell_products;
        $data['meal_products'] = $meal_products;

        $reviewsCount = DB::table('reviews')
        ->select(
            DB::raw('SUM(CASE WHEN rating >= 3 THEN 1 ELSE 0 END) as positive_count'),
            DB::raw('SUM(CASE WHEN rating < 3 THEN 1 ELSE 0 END) as negative_count'),
            DB::raw('count(id) as total')
        )
        ->first();

        $positiveCount = $reviewsCount->positive_count ?? 0;
        $negativeCount = $reviewsCount->negative_count ?? 0;
        $totalReviews = $reviewsCount->total ?? 0;
        $heatmapOrders = DB::table('orders')
            ->join('customer_addresses', 'orders.delivery_address_id', '=', 'customer_addresses.id')
            ->where('orders.branch_id', auth('branch')->id())
            ->whereNotIn('order_type', ['pos', 'take_away', 'dine_in', 'sadqa'])
            ->whereNotNull('orders.delivery_address_id')
            ->whereNotNull('customer_addresses.latitude')
            ->whereNotNull('customer_addresses.longitude')
            ->select('customer_addresses.latitude', 'customer_addresses.longitude', DB::raw('COUNT(*) as weight'))
            ->groupBy('customer_addresses.latitude', 'customer_addresses.longitude')
            ->get()
            ->map(function ($item) {
                return [
                    'delivery_address' => [
                        'latitude' => $item->latitude,
                        'longitude' => $item->longitude,
                    ],
                    'weight' => (int) $item->weight,
                ];
            });
        $mapApiClientKey = BusinessSetting::where('key', 'map_api_client_key')->value('value');

        return view('branch-views.dashboard', compact('data', 'earning', 'order_statistics_chart','analytics', 'positiveCount','negativeCount','totalReviews', 'heatmapOrders', 'mapApiClientKey'));
    }

    /**
     * @return Renderable
     */
    public function settings(): Renderable
    {
        return view('branch-views.settings');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function settings_update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required'
        ]);

        $branch = $this->branch->find(auth('branch')->id());
        if (!$branch) {
            Toastr::error(translate('Branch not found!'));
            return back();
        }

        if ($request->has('image')) {
            $image_name = Helpers::update('branch/', $branch->image, 'png', $request->file('image'));
        } else {
            $image_name = $branch['image'];
        }

        $branch->name = $request->name;
        $branch->image = $image_name;
        $branch->phone = $request->phone;
        $branch->save();

        Toastr::success(translate('Branch updated successfully!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function settings_password_update(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|same:confirm_password|min:8|max:255',
            'confirm_password' => 'required|max:255',
        ]);

        $branch = $this->branch->find(auth('branch')->id());
        if (!$branch) {
            Toastr::error(translate('Branch not found!'));
            return back();
        }

        $branch->password = bcrypt($request['password']);
        $branch->save();

        Toastr::success(translate('Branch password updated successfully!'));
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function order_stats(Request $request): JsonResponse
    {
        session()->put('statistics_type', $request['statistics_type']);
        $data = self::order_stats_data();

        return response()->json([
            'view' => view('branch-views.partials._dashboard-order-stats', compact('data'))->render()
        ], 200);
    }

    /**
     * @return array
     */
    public function order_stats_data(): array
    {
        $today = session()->has('statistics_type') && session('statistics_type') == 'today' ? 1 : 0;
        $this_month = session()->has('statistics_type') && session('statistics_type') == 'this_month' ? 1 : 0;

        $statusCounts = $this->order
            ->where(['branch_id' => auth('branch')->id()])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->select('order_status', DB::raw('COUNT(*) as total'))
            ->groupBy('order_status')
            ->pluck('total', 'order_status');

        $all = (int) $statusCounts->sum();

        $data = [
            'pending' => (int) ($statusCounts['pending'] ?? 0),
            'confirmed' => (int) ($statusCounts['confirmed'] ?? 0),
            'processing' => (int) ($statusCounts['processing'] ?? 0),
            'out_for_delivery' => (int) ($statusCounts['out_for_delivery'] ?? 0),
            'delivered' => (int) ($statusCounts['delivered'] ?? 0),
            'all' => $all,
            'returned' => (int) ($statusCounts['returned'] ?? 0),
            'failed' => (int) ($statusCounts['failed'] ?? 0),
        ];

        return $data;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function order_statistics(Request $request): JsonResponse
    {
        $dateType = $request->type;

        $order_data = array();
        if ($dateType == 'yearEarn') {
            $number = 12;
            $from = Carbon::now()->startOfYear()->format('Y-m-d');
            $to = Carbon::now()->endOfYear()->format('Y-m-d');

            $monthEarnData = $this->order->where(['order_status' => 'delivered', 'branch_id' => auth('branch')->id()])
                ->select(
                    DB::raw('SUM(order_amount) as total'),
                    DB::raw('YEAR(created_at) year, MONTH(created_at) month')
                )
                ->whereBetween('created_at', [Carbon::parse(now())->startOfYear(), Carbon::parse(now())->endOfYear()])
                ->groupby('year', 'month')->get()->toArray();
            for ($inc = 1; $inc <= $number; $inc++) {
                $order_data[$inc] = 0;
                foreach ($monthEarnData as $match) {
                    if ($match['month'] == $inc) {
                        $order_data[$inc] = $match['total'];
                    }
                }
            }
            $key_range = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

        } elseif ($dateType == 'MonthOrder') {
            $from = date('Y-m-01');
            $to = date('Y-m-t');
            $number = date('d', strtotime($to));
            $key_range = range(1, $number);

            $orders = $this->order->where(['order_status' => 'delivered'])
                ->where('branch_id', auth('branch')->id())
                ->select(
                    DB::raw('(count(id)) as total'),
                    DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day')
                )
                ->whereBetween('created_at', [Carbon::parse(now())->startOfMonth(), Carbon::parse(now())->endOfMonth()])
                ->groupby('year', 'month', 'day')
                ->get()
                ->toArray();

            for ($inc = 1; $inc <= $number; $inc++) {
                $order_data[$inc] = 0;
                foreach ($orders as $match) {
                    if ($match['day'] == $inc) {
                        $order_data[$inc] += $match['total'];
                    }
                }
            }

        } elseif ($dateType == 'WeekOrder') {
            Carbon::setWeekStartsAt(Carbon::SUNDAY);
            Carbon::setWeekEndsAt(Carbon::SATURDAY);

            $from = Carbon::now()->startOfWeek();
            $to = Carbon::now()->endOfWeek();
            $orders = $this->order->where(['order_status' => 'delivered', 'branch_id' => auth('branch')->id()])
                ->select(DB::raw('(count(id)) as total'), DB::raw('DATE(created_at) as order_date'))
                ->whereBetween('created_at', [$from, $to])
                ->groupBy('order_date')
                ->pluck('total', 'order_date');

            $date_range = CarbonPeriod::create($from, $to)->toArray();
            $key_range = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
            $order_data = [];
            foreach ($date_range as $date) {

                $order_data[] = (int) ($orders[$date->format('Y-m-d')] ?? 0);
            }
        }

        $label = $key_range;
        $order_data_final = $order_data;

        $data = array(
            'orders_label' => $label,
            'orders' => array_values($order_data_final),
        );

        return response()->json($data);
    }


        /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sale_growth_statistics(Request $request): JsonResponse
    {
        $dateType = $request->type;
        
        if ($dateType == 'monthOrders') {
            $from = date('Y-m-01');
            $to = date('Y-m-t');
            $number = date('d', strtotime($to));
            $key_range = range(1, $number);

            $orders = $this->order->where(['order_status' => 'delivered'])
                ->select(
                    DB::raw('(count(id)) as total'),
                    DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day')
                )
                ->whereBetween('created_at', [Carbon::parse(now())->startOfMonth(), Carbon::parse(now())->endOfMonth()])
                ->groupBy('year', 'month', 'day')
                ->get()
                ->toArray();
            
            for ($inc = 1; $inc <= $number; $inc++) {
                $order_data[$inc] = 0;
                foreach ($orders as $match) {
                    if ($match['day'] == $inc) {
                        $order_data[$inc] += $match['total'];
                    }
                }
            }

            $label = $key_range;
            $order_data_final = $order_data;

            $data = array(
                'orders_label' => $label,
                'orders' => array_values($order_data_final),
            );

        } elseif ($dateType == 'monthProducts') {
            $from = date('Y-m-01');
            $to = date('Y-m-t');
            $number = date('d', strtotime($to));
            $key_range = range(1, $number);
        
            $products = $this->order_detail
                ->whereHas('order', function ($query) {
                    $query->where('order_status', 'pending')
                        ->where('branch_id', auth('branch')->id())
                        ->whereBetween('created_at', [Carbon::parse(now())->startOfMonth(), Carbon::parse(now())->endOfMonth()]);
                })
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->select(DB::raw('DAY(orders.created_at) as day'), DB::raw('SUM(order_details.quantity) as total'))
                ->groupBy('day')
                ->pluck('total', 'day');
        
            $product_data = array_fill_keys($key_range, 0);
        
            foreach ($products as $day => $total) {
                $product_data[(int) $day] = $total;
            }
        
            $label = $key_range;
            $product_data_final = array_values($product_data);
        
            $data = [
                'orders_label' => $label,
                'orders' => $product_data_final,
            ];
        } elseif ($dateType == 'monthMeals') {
            $from = date('Y-m-01');
            $to = date('Y-m-t');
            $number = date('d', strtotime($to));
            $key_range = range(1, $number);

            $orders = $this->order->where(['order_status' => 'delivered', 'order_type' => 'dine_in'])
                ->select(
                    DB::raw('(count(id)) as total'),
                    DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day')
                )
                ->whereBetween('created_at', [Carbon::parse(now())->startOfMonth(), Carbon::parse(now())->endOfMonth()])
                ->groupby('year', 'month', 'day')
                ->get()
                ->toArray();

            for ($inc = 1; $inc <= $number; $inc++) {
                $order_data[$inc] = 0;
                foreach ($orders as $match) {
                    if ($match['day'] == $inc) {
                        $order_data[$inc] += $match['total'];
                    }
                }
            }

            $label = $key_range;
            $order_data_final = $order_data;

            $data = array(
                'orders_label' => $label,
                'orders' => array_values($order_data_final),
            );
        }

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function earning_statistics(Request $request): JsonResponse
    {
        $dateType = $request->type;

        $earning_data = array();
        if ($dateType == 'yearEarn') {

            $earning = [];
            $earning_data = $this->order->where([
                'order_status' => 'delivered', 'branch_id' => auth('branch')->id()
            ])->select(
                DB::raw('IFNULL(sum(order_amount),0) as sums'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month')
            )
                ->whereBetween('created_at', [Carbon::parse(now())->startOfYear(), Carbon::parse(now())->endOfYear()])
                ->groupby('year', 'month')->get()->toArray();
            for ($inc = 1; $inc <= 12; $inc++) {
                $earning[$inc] = 0;
                foreach ($earning_data as $match) {
                    if ($match['month'] == $inc) {
                        $earning[$inc] = Helpers::set_price($match['sums']);
                    }
                }
            }
            $key_range = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
            $order_data = $earning;

        } elseif ($dateType == 'MonthEarn') {
            $from = date('Y-m-01');
            $to = date('Y-m-t');
            $number = date('d', strtotime($to));
            $key_range = range(1, $number);

            $earning = $this->order
                ->where(['order_status' => 'delivered', 'branch_id' => auth('branch')->id()])
                ->select(DB::raw('IFNULL(sum(order_amount),0) as sums'), DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day'))
                ->whereBetween('created_at', [Carbon::parse(now())->startOfMonth(), Carbon::parse(now())->endOfMonth()])
                ->groupby('year', 'month', 'day')
                ->get()
                ->toArray();

            for ($inc = 1; $inc <= $number; $inc++) {
                $earning_data[$inc] = 0;
                foreach ($earning as $match) {
                    if ($match['day'] == $inc) {
                        $earning_data[$inc] += $match['sums'];
                    }
                }
            }

            $order_data = $earning_data;
        } elseif ($dateType == 'WeekEarn') {
            Carbon::setWeekStartsAt(Carbon::SUNDAY);
            Carbon::setWeekEndsAt(Carbon::SATURDAY);

            $from = Carbon::now()->startOfWeek();
            $to = Carbon::now()->endOfWeek();
            $orders = $this->order
                ->where(['order_status' => 'delivered', 'branch_id' => auth('branch')->id()])
                ->select(DB::raw('IFNULL(sum(order_amount),0) as total'), DB::raw('DATE(created_at) as order_date'))
                ->whereBetween('created_at', [$from, $to])
                ->groupBy('order_date')
                ->pluck('total', 'order_date');

            $date_range = CarbonPeriod::create($from, $to)->toArray();
            $key_range = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
            $order_data = [];
            foreach ($date_range as $date) {
                $order_data[] = (float) ($orders[$date->format('Y-m-d')] ?? 0);
            }
        }

        $label = $key_range;
        $earning_data_final = $order_data;

        $data = array(
            'earning_label' => $label,
            'earning' => array_values($earning_data_final),
        );

        return response()->json($data);
    }

}

