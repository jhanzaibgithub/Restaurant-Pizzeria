<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\Branch;
use App\Model\Category;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\Review;
use App\User;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;

class DashboardController extends Controller
{
    public function __construct(
        private Order       $order,
        private OrderDetail $order_detail,
        private Admin       $admin,
        private Review      $review,
        private User        $user,
        private Product     $product,
        private Category    $category,
        private Branch      $branch
    )
    {
    }

    public function create(){
        return view('admin-views.home');
    }

    /**
     * @param $id
     * @return string
     */
    public function fcm($id): string
    {
        $fcm_token = $this->admin->find(auth('admin')->id())?->fcm_token;
        if (!$fcm_token) {
            return "Admin FCM token not found";
        }

        $data = [
            'title' => 'New auto generate message arrived from admin dashboard',
            'description' => $id,
            'order_id' => '',
            'image' => '',
            'type' => 'order_status',
        ];
        Helpers::send_push_notif_to_device($fcm_token, $data);

        return "Notification sent to admin";
    }

    /**
     * @return Renderable
     */
    public function dashboard(Request $request): Renderable
    {
        
        $top_sell_products = $this->order_detail
            ->with(['product','product.rating'])
            ->whereHas('order', function ($query) {
                $query->where('order_status', 'delivered');
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

        $branchTotals = DB::table('orders')
            ->leftJoin('order_details', 'order_details.order_id', '=', 'orders.id')
            ->where('orders.order_status', 'delivered')
            ->whereNotNull('orders.branch_id')
            ->select('orders.branch_id')
            ->selectRaw('COUNT(DISTINCT orders.id) as total_delivered_orders')
            ->selectRaw('COALESCE(SUM(order_details.quantity), 0) as total_product_count')
            ->groupBy('orders.branch_id')
            ->get()
            ->keyBy('branch_id');

        $branches = $this->branch
            ->whereIn('id', $branchTotals->keys())
            ->get()
            ->map(function ($branch) use ($branchTotals) {
                $total = $branchTotals[$branch->id];
                return [
                    'branch' => $branch,
                    'total_delivered_orders' => (int) $total->total_delivered_orders,
                    'total_product_count' => (int) $total->total_product_count,
                ];
            })
            ->all();

        $data = self::order_stats_data();

        $data['customer'] = $this->user->count();
        $data['product'] = $this->product->count();
        $data['order'] = $this->order->count();
        $data['category'] = $this->category->where('parent_id', 0)->count();
        $data['branch'] = $this->branch->count();

        $data['top_sell_products'] = $top_sell_products;
        $data['meal_products'] = $meal_products;
        $data['branches'] = $branches;

        $order_statistics_chart = [];
        $from = Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');
        
        $order_statistics_chart_data = $this->order->where(['order_status' => 'delivered'])
        ->select(
            DB::raw('COUNT(id) as total'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month')
        )
        ->whereBetween('created_at', [$from, $to])
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();

        foreach ($order_statistics_chart_data as $dataPoint) {
            $year = $dataPoint->year;
            $month = $dataPoint->month;
            $total = $dataPoint->total;
    
            $order_statistics_chart[] = [
                'year' => $year,
                'month' => $month,
                'total' => $total,
            ];
        }
        $orderData = array_fill(0, 12, 0);
        foreach ($order_statistics_chart as $dataPoint) {
            $monthIndex = $dataPoint['month'] - 1;
            $orderData[$monthIndex] = $dataPoint['total'];
        }

        $data['order_statistics_chart'] = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'data' => $orderData,
        ];

        $earning = [];
        $earning_data = $this->order->where([
            'order_status' => 'delivered',
        ])->select(
            DB::raw('(count(id)) as total'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )
            ->whereBetween('created_at', [Carbon::parse(now())->startOfYear(), Carbon::parse(now())->endOfYear()])
            ->groupby('year', 'month')->get()->toArray();


        for ($inc = 1; $inc <= 12; $inc++) {
            $earning[$inc] = 0;
            foreach ($earning_data as $match) {
                if ($match['month'] == $inc) {
                    $earning[$inc] += $match['total'];
                }
            }
        }

        $order_statistics_chart = [];
        $order_statistics_chart_data = $this->order->where(['order_status' => 'delivered'])
            ->select(
                DB::raw('(count(id)) as total'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month')
            )
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

        $data['recent_orders'] = $this->order->latest()->take(5)->get();
        $heatmapOrders = $this->order
            ->with(['delivery_address:id,latitude,longitude'])
            ->where('order_type', '!=', 'pos')
            ->where('order_type', '!=', 'take_away')
            ->where('order_type', '!=', 'dine_in')
            ->where('order_type', '!=', 'sadqa')
            ->get(['id', 'delivery_address_id']);
        $mapApiClientKey = DB::table('business_settings')->where('key', 'map_api_client_key')->value('value');
        
        $analytics = [];
        $total_earning;
        $total_products;
        $total_employees;
        $total_branches;


        $CALfrom = $request->input('from');
        $CALto = $request->input('to');

        // Earning States
        $orderQuery = $this->order->where(['order_status' => 'delivered'])->where('payment_status', 'paid');
        if ($CALfrom && $CALto) {
            $orderQuery->whereBetween('created_at', [$CALfrom, $CALto]);
        }
        $analytics['total_earning'] = $orderQuery->sum('order_amount');
        $currentMonthStart = $request->input('from') ?? now()->startOfMonth();
        $currentMonthEnd = $request->input('to') ?? now()->endOfMonth();
        $previousMonthStart = $request->input('from') ?? now()->subMonth()->startOfMonth();
        $previousMonthEnd = $request->input('to') ?? now()->subMonth()->endOfMonth();
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


        // Products State
        $productQuery = $this->product->where(['status' => 1]);
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

        // Employee state
        $employeeQuery = $this->admin->where('admin_role_id', '!=' ,1);
        if ($CALfrom && $CALto) {
            $employeeQuery->whereBetween('created_at', [$CALfrom, $CALto]);
        }
        $analytics['total_employees'] = $employeeQuery->count('id');
        $employeeMonthProducts = $productQuery
                                    ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
                                    ->count('created_at');
        $employeeMonthProducts = $productQuery
                                    ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
                                    ->count('created_at');
        $employeeTrend = $employeeMonthProducts >= $employeeMonthProducts ? 'up' : 'down';
        $analytics['employee_trend'] = $employeeTrend;


        // Branch State
        $branchQuery = $this->admin->where('admin_role_id', '!=' ,1);
        if ($CALfrom && $CALto) {
            $branchQuery->whereBetween('created_at', [$CALfrom, $CALto]);
        }
        $analytics['total_branches'] = $branchQuery->count('id');
        $branchMonthProducts = $productQuery
                ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
                ->count('created_at');
        $branchMonthProducts = $productQuery
                ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
                ->count('created_at');
        $branchTrend = $branchMonthProducts >= $branchMonthProducts ? 'up' : 'down';
        $analytics['branch_trend'] = $branchTrend;

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
  
        return view('admin-views.dashboard', compact('data', 'earning', 'order_statistics_chart', 'analytics','positiveCount','negativeCount','totalReviews', 'heatmapOrders', 'mapApiClientKey'));
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
            'view' => view('admin-views.partials._dashboard-order-stats', compact('data'))->render()
        ], 200);
    }

    /**
     * @return array
     */
    public function order_stats_data(): array
    {
        $today = session()->has('statistics_type') && session('statistics_type') == 'today' ? 1 : 0;
        $this_month = session()->has('statistics_type') && session('statistics_type') == 'this_month' ? 1 : 0;

        $total_orders = $this->order->count();
        $baseQuery = $this->order
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            });

        $statusCounts = (clone $baseQuery)
            ->select('order_status', DB::raw('COUNT(*) as total'))
            ->groupBy('order_status')
            ->pluck('total', 'order_status');

        $pending = (clone $baseQuery)->where(['order_status' => 'pending'])->notSchedule()->count();
        $confirmed = (int) ($statusCounts['confirmed'] ?? 0);
        $processing = (int) ($statusCounts['processing'] ?? 0);
        $out_for_delivery = (int) ($statusCounts['out_for_delivery'] ?? 0);
        $canceled = (int) ($statusCounts['canceled'] ?? 0);
        $delivered = (int) ($statusCounts['delivered'] ?? 0);
        $returned = (int) ($statusCounts['returned'] ?? 0);
        $failed = (int) ($statusCounts['failed'] ?? 0);
        $all = (clone $baseQuery)
            ->count();
        $percent = fn ($value) => $total_orders > 0 ? ($value * 100 / $total_orders) : 0;

        return [
            'pending' => $pending,
            'pending_per'=>$percent($pending),
            'confirmed' => $confirmed,
            'confirmed_per'=>$percent($confirmed),
            'processing' => $processing,
            'processing_per'=>$percent($processing),
            'out_for_delivery' => $out_for_delivery,
            'out_for_delivery_per'=>$percent($out_for_delivery),
            'canceled' => $canceled,
            'canceled_per'=>$percent($canceled),
            'delivered' => $delivered,
            'delivered_per'=>$percent($delivered),
            'all' => $all,
            'returned' => $returned,
            'returned_per'=>$percent($returned),
            'failed' => $failed,
            'failed_per'=>$percent($failed)
        ];
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function order_statistics(Request $request): JsonResponse
    {
        $dateType = $request->type;
        $deliveryType = $request->deliveryType;

        $order_data = array();
        if ($dateType == 'yearEarn') {
            $number = 12;
            $from = Carbon::now()->startOfYear()->format('Y-m-d');
            $to = Carbon::now()->endOfYear()->format('Y-m-d');

            $monthEarnData = $this->order->where(['order_status' => 'delivered'])
                ->select(
                    DB::raw('SUM(order_amount) as total'),
                    DB::raw('YEAR(created_at) year, MONTH(created_at) month')
                )
            //->whereBetween('created_at', [$from, $to])
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
                ->select(
                    DB::raw('(count(id)) as total'),
                    DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day')
                )
            //->whereBetween('created_at', [$from, $to])
                ->whereBetween('created_at', [Carbon::parse(now())->startOfYear(), Carbon::parse(now())->endOfYear()])
                ->groupby('created_at')
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

        } elseif ($dateType == 'monthRegisterCustomer') {
                $number = 12;
                $from = Carbon::now()->startOfYear()->format('Y-m-d');
                $to = Carbon::now()->endOfYear()->format('Y-m-d');
    
                $monthRegisterCustomerData =  $this->user->where('user_type',null)
                    ->whereBetween('created_at', [$from, $to])
                    ->select(
                        DB::raw('count(id) as total'),
                        DB::raw('YEAR(created_at) year, MONTH(created_at) month')
                    )
                //->whereBetween('created_at', [$from, $to])
                    ->whereBetween('created_at', [Carbon::parse(now())->startOfYear(), Carbon::parse(now())->endOfYear()])
                    ->groupby('year', 'month')->get()->toArray();
    
                for ($inc = 1; $inc <= $number; $inc++) {
                    $order_data[$inc] = 0;
                    foreach ($monthRegisterCustomerData as $match) {
                        if ($match['month'] == $inc) {
                            $order_data[$inc] = $match['total'];
                        }
                    }
                }
                $key_range = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

        }  elseif ($dateType == 'monthBranches') {
            $number = 12;
            $from = Carbon::now()->startOfYear()->format('Y-m-d');
            $to = Carbon::now()->endOfYear()->format('Y-m-d');

            $monthRegisterBranchData =  $this->branch->whereBetween('created_at', [$from, $to])
                ->select(
                    DB::raw('count(id) as total'),
                    DB::raw('YEAR(created_at) year, MONTH(created_at) month')
                )
            //->whereBetween('created_at', [$from, $to])
                ->whereBetween('created_at', [Carbon::parse(now())->startOfYear(), Carbon::parse(now())->endOfYear()])
                ->groupby('year', 'month')->get()->toArray();

            for ($inc = 1; $inc <= $number; $inc++) {
                $order_data[$inc] = 0;
                foreach ($monthRegisterBranchData as $match) {
                    if ($match['month'] == $inc) {
                        $order_data[$inc] = $match['total'];
                    }
                }
            }
            $key_range = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

        }elseif ($dateType == 'dineOrder') {
            $number = 12;
            $from = Carbon::now()->startOfYear()->format('Y-m-d');
            $to = Carbon::now()->endOfYear()->format('Y-m-d');

            $dineOrder = $this->order->DineIn()
                ->select(
                    DB::raw('SUM(order_amount) as total'),
                    DB::raw('YEAR(created_at) year, MONTH(created_at) month')
                )
            //->whereBetween('created_at', [$from, $to])
                ->whereBetween('created_at', [Carbon::parse(now())->startOfYear(), Carbon::parse(now())->endOfYear()])
                ->groupby('year', 'month')->get()->toArray();

            for ($inc = 1; $inc <= $number; $inc++) {
                $order_data[$inc] = 0;
                foreach ($dineOrder as $match) {
                    if ($match['month'] == $inc) {
                        $order_data[$inc] = $match['total'];
                    }
                }
            }

            $key_range = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

        }elseif ($dateType == 'takeawayOrder') {
            $number = 12;
            $from = Carbon::now()->startOfYear()->format('Y-m-d');
            $to = Carbon::now()->endOfYear()->format('Y-m-d');

            $dineOrder = $this->order->TakeAway()
                ->select(
                    DB::raw('SUM(order_amount) as total'),
                    DB::raw('YEAR(created_at) year, MONTH(created_at) month')
                )
            //->whereBetween('created_at', [$from, $to])
                ->whereBetween('created_at', [Carbon::parse(now())->startOfYear(), Carbon::parse(now())->endOfYear()])
                ->groupby('year', 'month')->get()->toArray();

            for ($inc = 1; $inc <= $number; $inc++) {
                $order_data[$inc] = 0;
                foreach ($dineOrder as $match) {
                    if ($match['month'] == $inc) {
                        $order_data[$inc] = $match['total'];
                    }
                }
            }

            $key_range = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
        }elseif ($dateType == 'delieveryOrder') {
            $number = 12;
            $from = Carbon::now()->startOfYear()->format('Y-m-d');
            $to = Carbon::now()->endOfYear()->format('Y-m-d');

            $dineOrder = $this->order->NotTakeAway()->NotDineIn()->NotPos()
                ->select(
                    DB::raw('SUM(order_amount) as total'),
                    DB::raw('YEAR(created_at) year, MONTH(created_at) month')
                )
            //->whereBetween('created_at', [$from, $to])
                ->whereBetween('created_at', [Carbon::parse(now())->startOfYear(), Carbon::parse(now())->endOfYear()])
                ->groupby('year', 'month')->get()->toArray();

            for ($inc = 1; $inc <= $number; $inc++) {
                $order_data[$inc] = 0;
                foreach ($dineOrder as $match) {
                    if ($match['month'] == $inc) {
                        $order_data[$inc] = $match['total'];
                    }
                }
            }

            $key_range = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
        }elseif ($dateType == 'dineOrders') {
            $number = 12;
            $from = Carbon::now()->startOfYear()->format('Y-m-d');
            $to = Carbon::now()->endOfYear()->format('Y-m-d');

            $monthEarnData = $this->order
                ->where(['order_type' => 'dine_in'])
                ->select(
                    DB::raw('SUM(order_amount) as total'),
                    DB::raw('YEAR(created_at) year, MONTH(created_at) month')
                )
            //->whereBetween('created_at', [$from, $to])
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
        }elseif ($dateType == 'takeawayOrders') {
            $number = 12;
            $from = Carbon::now()->startOfYear()->format('Y-m-d');
            $to = Carbon::now()->endOfYear()->format('Y-m-d');

            $monthEarnData = $this->order
                ->where(['order_type' => 'takeaway'])
                ->select(
                    DB::raw('SUM(order_amount) as total'),
                    DB::raw('YEAR(created_at) year, MONTH(created_at) month')
                )
            //->whereBetween('created_at', [$from, $to])
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

        }elseif ($dateType == 'deliveryOrder') {
            $number = 12;
            $from = Carbon::now()->startOfYear()->format('Y-m-d');
            $to = Carbon::now()->endOfYear()->format('Y-m-d');

            $monthEarnData = $this->order
                ->where(['order_type' => 'delivery'])
                ->select(
                    DB::raw('SUM(order_amount) as total'),
                    DB::raw('YEAR(created_at) year, MONTH(created_at) month')
                )
            //->whereBetween('created_at', [$from, $to])
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

        }elseif (isset($dateType) && isset($deliveryType)) {
            
            $number = 12;
            $from = Carbon::now()->startOfYear()->format('Y-m-d');
            $to = Carbon::now()->endOfYear()->format('Y-m-d');

            $monthEarnData = $this->order
                ->where(['order_type' => $dateType])
                ->where(['order_status' => $deliveryType])
                ->select(
                    DB::raw('SUM(order_amount) as total'),
                    DB::raw('YEAR(created_at) year, MONTH(created_at) month')
                )
            //->whereBetween('created_at', [$from, $to])
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
                ->whereBetween('created_at', [Carbon::parse(now())->startOfYear(), Carbon::parse(now())->endOfYear()])
                ->groupBy('created_at')
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
        
            $product_data = array_fill_keys($key_range, 0);

            $products = DB::table('orders')
                ->join('order_details', 'order_details.order_id', '=', 'orders.id')
                ->where('orders.order_status', 'pending')
                ->whereBetween('orders.created_at', [Carbon::parse(now())->startOfYear(), Carbon::parse(now())->endOfYear()])
                ->selectRaw('DAY(orders.created_at) as day, SUM(order_details.quantity) as total')
                ->groupBy(DB::raw('DAY(orders.created_at)'))
                ->get();

            foreach ($products as $product) {
                $product_data[(int) $product->day] = (int) $product->total;
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
            //->whereBetween('created_at', [$from, $to])
                ->whereBetween('created_at', [Carbon::parse(now())->startOfYear(), Carbon::parse(now())->endOfYear()])
                ->groupby('created_at')
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
}
