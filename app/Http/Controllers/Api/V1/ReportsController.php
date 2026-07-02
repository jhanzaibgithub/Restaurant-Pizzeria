<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\WalletTransaction;
use Barryvdh\DomPDF\Facade as PDF;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Log;

class ReportsController extends Controller
{
    public function __construct(
        private Order       $order,
        private OrderDetail $order_detail,
        private WalletTransaction $wallet_transaction,
    ) {}

    public function earningIndex(Request $request)
    {
        $request->validate([
            'from' => 'nullable|date',
            'to' => 'nullable|date',
        ]);

        if ($request->from && $request->to) {
            $from = Carbon::parse($request->from)->startOfDay();
            $to = Carbon::parse($request->to)->endOfDay();
        } elseif ($request->filter == 'weekly') {
            // Current week's start and end dates
            $from = Carbon::now()->startOfWeek();
            $to = Carbon::now()->endOfWeek();
        } elseif ($request->filter == 'monthly') {
            // Current month's start and end dates
            $from = Carbon::now()->startOfMonth();
            $to = Carbon::now()->endOfMonth();
        } elseif ($request->filter == 'last_year') {
            // Last calendar year's start and end dates
            $from = Carbon::now()->subYear()->startOfYear();
            $to = Carbon::now()->subYear()->endOfYear();
        } else {
            // Default behavior: Use the current month
            $from = Carbon::now()->startOfMonth();
            $to = Carbon::now()->endOfMonth();
        }


        // Validate date range
        if ($from->greaterThan($to)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid date range: "from" date is greater than "to" date.'
            ], 422);
        }

        // Fetch orders for the date range
        $orders = $this->order->where('order_status', 'delivered')
            ->whereBetween('created_at', [$from, $to])
            ->where('branch_id', $request->branch->id)
            ->get();

        // Calculate total sales, taxes, and earnings
        $total_sold = $orders->sum('order_amount');
        $product_tax = $orders->sum('total_tax_amount');
        $add_on_tax_amount = $orders->sum(function ($order) {
            return $order->details->sum('add_on_tax_amount');
        });
        $total_tax = $product_tax + $add_on_tax_amount;
        $total_earnings = $total_sold - $total_tax;

        // Previous period data for growth calculation
        $previousEndDate = $from->copy()->subDay();
        $dateDiff = $to->diffInDays($from) + 1;
        $previousStartDate = $previousEndDate->copy()->subDays($dateDiff - 1);

        $previousOrders = $this->order->where('order_status', 'delivered')
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->get();
        $previousTotalSold = $previousOrders->sum('order_amount');

        // Calculate growth percentage
        $growth = 0;
        if ($previousTotalSold > 0) {
            $growth = (($total_sold - $previousTotalSold) / $previousTotalSold) * 100;
        }

        // Generate dashboard data
        $data = $this->dashboard_data($from->toDateTimeString(), $to->toDateTimeString());

        // Return the sales report
        return response()->json([
            'status' => 'success',
            'data' => [
                'growth' => $growth,
                'total_earning' => $total_earnings,
                'total_tax' => $total_tax,
                'total_sold' => $total_sold,
                'from' => $from->toDateTimeString(),
                'to' => $to->toDateTimeString(),
                'dashboard_data' => $data,
            ]
        ]);
    }


    public function dashboard_data($from = null, $to = null)
    {
        $start_date = Carbon::parse($from)->startOfDay();
        $end_date = Carbon::parse($to)->endOfDay();
    
        $sold = [];
        $labels = [];
        $daysDifference = $start_date->diffInDays($end_date);
    
        if ($daysDifference <= 7) {
            // Weekly data: Daily breakdown
            $dateFormat = 'D'; // Day names (Mon, Tue, etc.)
            $interval = 'day';
        } elseif ($daysDifference <= 31) {
            // Monthly data: Daily breakdown
            $dateFormat = 'd'; // Day numbers (01, 02, etc.)
            $interval = 'day';
        } else {
            // Last year or longer: Monthly breakdown
            $dateFormat = 'M'; // Month names (Jan, Feb, etc.)
            $interval = 'month';
        }
    
        $currentDate = $start_date->copy();
        while ($currentDate->lte($end_date)) {
            $label = $currentDate->format($dateFormat);
    
            // Sum of order amounts for the current interval
           $orderAmount = Order::where('order_status', 'delivered')
    ->where('created_at', '>=', $currentDate->startOf($interval)->toDateTimeString())
    ->where('created_at', '<=', $currentDate->endOf($interval)->toDateTimeString())
    ->sum('order_amount');

    
            $labels[] = $label; // Add label to labels
            $am = $label . $orderAmount;
            // $sold[] = $am; // Add label-value pair to sold list
            $sold[] = [
                'label' => $label, 
                'value' => $orderAmount];

    
            // Increment the date based on the interval
            if ($interval === 'day') {
                $currentDate->addDay();
            } else {
                $currentDate->addMonthNoOverflow();
            }
        }
    
        // Prepare dashboard data
        return [
            'total_sold' => array_sum(array_column($sold, 'value')), // Sum of all sold values
            'sold' => $sold, // List of label-value pairs
            'labels' => $labels, // List of labels
            'start_date' => $start_date->toDateTimeString(),
            'end_date' => $end_date->toDateTimeString(),
        ];
    }





    //Sales filter Api
    public function sales_filter(Request $request): JsonResponse
{
    // Validate input dates and pagination parameters
    $request->validate([
        'from' => 'nullable|date',
        'to' => 'nullable|date',
        'limit' => 'nullable|integer|min:1',
        'offset' => 'nullable|integer|min:0',
    ]);

    $branch = $request->branch;
    $from = $request->from ? Carbon::parse($request->from)->startOfDay() : Carbon::minValue();
    $to = $request->to ? Carbon::parse($request->to)->endOfDay() : Carbon::now();
    $limit = $request->input('limit', 10); // Default limit is 10
    $offset = $request->input('offset', 0); // Default offset is 0

    // Check if from > to
    if ($from->greaterThan($to)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid date range: "from" date is greater than "to" date.'
        ], 422);
    }

    // Fetch all orders based on filters (no pagination for total calculations)
    $ordersQuery = $this->order->whereBetween('created_at', [$from, $to])
        ->where('branch_id', $branch->id);

    $total_orders = $ordersQuery->count(); // Total count of orders for metadata

    // Calculate total item quantity and amount
    $total_item_qty = 0;
    $total_amount = 0;

    $ordersQuery->with('details')->get()->each(function ($order) use (&$total_item_qty, &$total_amount) {
        foreach ($order->details as $detail) {
            $price = $detail['price'] - $detail['discount_on_product'];
            $ord_total = $price * $detail['quantity'];

            // Accumulate totals for all orders
            $total_item_qty += $detail['quantity'];
            $total_amount += $ord_total;
        }
    });

    // Apply pagination only to the table data
    $paginatedOrders = $ordersQuery->with('details')->skip($offset)->take($limit)->get();

    // Initialize table data
    $data = [];
    $sl = $offset + 1; // Start SL with offset + 1

    foreach ($paginatedOrders as $order) {
        $order_total_qty = 0;
        $order_total_amount = 0;

        foreach ($order->details as $detail) {
            $price = $detail['price'] - $detail['discount_on_product'];
            $ord_total = $price * $detail['quantity'];
            $order_total_qty += $detail['quantity'];
            $order_total_amount += $ord_total;
        }

        // Add order-level data to the table
        $data[] = [
            'sl' => $sl++,
            'order' => '#' . $order->id,
            'date' => $order->created_at->format('d M, Y'),
            'qty' => $order_total_qty,
            'total_payment' => number_format($order_total_amount, 2),
        ];
    }

    // Pagination metadata
    $pagination = [
        'total' => $total_orders,
        'limit' => $limit,
        'offset' => $offset,
        'current_page' => floor($offset / $limit) + 1,
        'total_pages' => ceil($total_orders / $limit),
    ];

    // Return JSON response
    return response()->json([
        'status' => 'success',
        'data' => [
            'total_orders' => $total_orders,
            'total_item_qty' => $total_item_qty,
            'total_amount' => number_format($total_amount, 2),
            'pagination' => $pagination,
            'table' => $data
        ]
    ]);
}
    //Order's Report
    public function set_date(Request $request): JsonResponse
    {
        // Sanitize and validate input
        $request->merge([
            'from' => trim($request->from),
            'to' => trim($request->to),
        ]);

        $request->validate([
            'from' => 'required',
            'to' => 'required',
        ]);

        // Log received input
        Log::info('Received parameters:', $request->all());

        // Parse dates
        $fromDate = Carbon::parse($request->from)->startOfDay();
        $toDate = Carbon::parse($request->to)->endOfDay();

        // Return JSON response
        return response()->json([
            'status' => 'success',
            'message' => 'Date range updated successfully.',
            'data' => [
                'from_date' => $fromDate->toDateTimeString(),
                'to_date' => $toDate->toDateTimeString(),
            ],
        ]);
    }



    public function order_summary(Request $request): JsonResponse
{
    // Validate and parse input dates
    $request->validate([
        'from' => 'nullable|date',
        'to' => 'nullable|date',
        'limit' => 'nullable|integer|min:1',
        'offset' => 'nullable|integer|min:0',
    ]);

    $from = $request->from ? Carbon::parse($request->from)->startOfDay() : Carbon::minValue();
    $to = $request->to ? Carbon::parse($request->to)->endOfDay() : Carbon::now();

    // Check if from > to
    if ($from->greaterThan($to)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid date range: "from" date is greater than "to" date.'
        ], 422);
    }

    // Set default values for limit and offset if not provided
    $limit = $request->input('limit', 10); // Default limit is 10
    $offset = $request->input('offset', 0); // Default offset is 0

    // Ensure valid limit and offset
    $limit = max($limit, 1); // Minimum limit is 1
    $offset = max($offset, 0); // Minimum offset is 0

    // Fetch paginated orders within the date range
    $ordersQuery = \App\Model\Order::whereBetween('created_at', [$from, $to])
        ->with('customer', 'details');

    $total_orders_count = $ordersQuery->count(); // Total count of orders for pagination metadata
    $orders = $ordersQuery->skip($offset)->take($limit)->get(); // Apply LIMIT and OFFSET

    // Initialize totals
    $add_on_tax_amount = 0;
    $total_sold = 0;

    $revenue_by_status = [
        'dine_in' => 0,
        'takeaway' => 0,
        'home_delivery' => 0,
        'other' => 0,
    ];

    $order_records = [];
    foreach ($orders as $order) {
        foreach ($order->details as $detail) {
            // Decode product_details JSON if it's a string
            $product_details = is_string($detail['product_details'])
                ? json_decode($detail['product_details'], true)
                : $detail['product_details'];

            // Format the product details using the helper function
            $formatted_product_details = Helpers::order_data_formatting($product_details);

            $price = $detail['price'] - $detail['discount_on_product'];
            $total_payment = $price * $detail['quantity'];
            $total_sold += $total_payment;
            $add_on_tax_amount += $detail['add_on_tax_amount'];

            // Categorize revenue based on order status
            switch ($order->order_status) {
                case 'dine_in':
                    $revenue_by_status['dine_in'] += $total_payment;
                    break;
                case 'takeaway':
                    $revenue_by_status['takeaway'] += $total_payment;
                    break;
                case 'home_delivery':
                    $revenue_by_status['home_delivery'] += $total_payment;
                    break;
                default:
                    $revenue_by_status['other'] += $total_payment;
                    break;
            }

            $order_records[] = [
                'customer' => $order->customer->f_name ?? 'Unknown', // Adjust to actual relation
                'id' => $order->id,
                'menu' => $formatted_product_details, // Use the formatted product details
                'total_payment' => $total_payment,
                'status' => ucfirst($order->order_status),
                'created_at' => $order->created_at->toDateTimeString(),
            ];
        }
    }

    $product_tax = $orders->sum('total_tax_amount');
    $total_tax = $product_tax + $add_on_tax_amount;
    $total_earnings = $total_sold - $total_tax;

    // Calculate revenue percentages
    $status_counts = [
        'total_revenue' => $total_earnings,
        'dine_in' => $orders->where('order_status', 'dine_in')->count(),
        'takeaway' => $orders->where('order_status', 'takeaway')->count(),
        'home_delivery' => $orders->where('order_status', 'home_delivery')->count(),
    ];

    $status_percentages = [
        'dine_in' => round(($status_counts['dine_in'] / max($total_orders_count, 1)) * 100, 2),
        'takeaway' => round(($status_counts['takeaway'] / max($total_orders_count, 1)) * 100, 2),
        'home_delivery' => round(($status_counts['home_delivery'] / max($total_orders_count, 1)) * 100, 2),
        'total_revenue_percentage' => round(($total_earnings / max($total_sold, 1)) * 100, 2), // Prevent division by zero
    ];

    // Pagination metadata
    $pagination = [
        'total' => $total_orders_count,
        'limit' => $limit,
        'offset' => $offset,
        'current_page' => floor($offset / $limit) + 1,
        'total_pages' => ceil($total_orders_count / $limit),
    ];

    // Return JSON response with pagination
    return response()->json([
        'status' => 'success',
        'data' => [
            'from_date' => $from->toDateTimeString(),
            'to_date' => $to->toDateTimeString(),
            'statuses' => $status_counts,
            'status_percentages' => $status_percentages,
            'pagination' => $pagination, // Include pagination metadata
            'orders' => $order_records, // Paginated order data
        ],
    ]);
}

    


    

    


    //Produnct's report
    public function product_report_filter(Request $request): JsonResponse
    {
        // Validate and parse dates
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date',
            'branch_id' => 'nullable|string',
            'product_id' => 'nullable|string'
        ]);

        $fromDate = Carbon::parse($request->from)->startOfDay();
        $toDate = Carbon::parse($request->to)->endOfDay();

        // Retrieve orders based on filters
        $orders = $this->order->when($request['branch_id'] !== 'all', function ($query) use ($request) {
            $query->where('branch_id', $request['branch_id']);
        })
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->latest()
            ->get();

        $data = [];
        $total_sold = 0;
        $total_qty = 0;

        foreach ($orders as $order) {
            foreach ($order->details as $detail) {
                if ($request['product_id'] !== 'all' && $detail['product_id'] != $request['product_id']) {
                    continue;
                }

                $price = Helpers::variation_price(json_decode($detail->product_details, true), $detail['variations']) - $detail['discount_on_product'];
                $ord_total = $price * $detail['quantity'];
                $data[] = [
                    'order_id' => $order['id'],
                    'date' => $order['created_at']->toDateTimeString(),
                    'customer' => $order->customer,
                    'price' => $ord_total,
                    'quantity' => $detail['quantity'],
                ];
                $total_sold += $ord_total;
                $total_qty += $detail['quantity'];
            }
        }

        // Return JSON response
        return response()->json([
            'status' => 'success',
            'order_count' => count($data),
            'item_qty' => $total_qty,
            'order_sum' => Helpers::set_symbol($total_sold),
            'data' => $data
        ]);
    }


    //product's report
    public function branch_index(Request $request): JsonResponse
    {
        $request->validate([
            'from' => 'nullable|date',
            'to' => 'nullable|date',
            'branch_id' => 'nullable',
        ]);

        $from = $request->from ? Carbon::parse($request->from)->startOfDay() : Carbon::minValue();
        $to = $request->to ? Carbon::parse($request->to)->endOfDay() : Carbon::now();
        $branchId = $request->branch;

        if ($request->from && $request->to && $request->from > $request->to) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid date range!',
            ], 422);
        }

        Log::info('Filters:', [
            'from' => $from,
            'to' => $to,
            'branch_id' => $branchId,
        ]);

        $query = $this->order->where('order_status', 'delivered')
            ->when($request->from && $request->to, function ($q) use ($from, $to) {
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->when($branchId && $branchId != 'all_branches', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->with(['details', 'branch']);

        $orders = $query->get();
        Log::info('Orders Retrieved:', $orders->toArray());

        $add_on_tax_amount = 0;
        foreach ($orders as $order) {
            foreach ($order->details as $detail) {
                $add_on_tax_amount += $detail->add_on_tax_amount;
            }
        }

        $product_tax = $orders->sum('total_tax_amount');
        $total_tax = $product_tax + $add_on_tax_amount;
        $total_sold = $orders->sum('order_amount');
        $total_earnings = $total_sold - $total_tax;

        Log::info('Calculated Totals:', [
            'total_tax' => $total_tax,
            'total_sold' => $total_sold,
            'total_earnings' => $total_earnings,
        ]);

        $previous_period_sales = $this->order->where('order_status', 'delivered')
            ->whereBetween('created_at', [$from->copy()->subMonth(), $to->copy()->subMonth()])
            ->sum('order_amount');
        $growth = $previous_period_sales ? (($total_sold - $previous_period_sales) / $previous_period_sales) * 100 : 0;

        $sold = [];

        for ($i = 1; $i <= 12; $i++) {
            $month_start = Carbon::createFromDate($from->year, $i, 1)->startOfMonth();
            $month_end = Carbon::createFromDate($from->year, $i, 1)->endOfMonth();
            Log::info('Monthly range:', [
                'month' => $i,
                'start' => $month_start,
                'end' => $month_end,
            ]);

            $monthly_orders = $this->order->where('order_status', 'delivered')
                ->whereBetween('created_at', [$month_start, $month_end])
                ->when($branchId && $branchId != 'all_branches', function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });
            $sold[$i] = $monthly_orders->sum('order_amount');
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_tax' => $total_tax,
                'total_sold' => $total_sold,
                'total_earnings' => $total_earnings,
                'growth' => $growth,
                'from_date' => $from->toDateTimeString(),
                'to_date' => $to->toDateTimeString(),
                'monthly_data' => [
                    'sold' => $sold,
                ],
            ],
        ]);
    }


    //deliveryman's report
    public function deliveryman_filter(Request $request): JsonResponse
    {
        // Validate request inputs
        $request->validate([
            'fromDate' => 'required|date',
            'toDate' => 'required|date',
            'delivery_man' => 'required|exists:delivery_men,id'
        ]);

        $fromDate = Carbon::parse($request->fromDate)->startOfDay();
        $toDate = Carbon::parse($request->toDate)->endOfDay();

        // Fetch orders with necessary relationships
        $orders = $this->order
            ->where(['delivery_man_id' => $request['delivery_man']])
            ->where(['order_status' => 'delivered'])
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->with(['customer', 'branch']) // Load related data
            ->get();

        // Prepare data for the response
        $data = $orders->map(function ($order, $index) {
            return [
                'order_id' => $order->id,
                'date' => $order->created_at->format('d M Y'),
                'customer' => $order->customer->name ?? 'Guest',
                'branch' => $order->branch->name ?? 'N/A',
                'total' => Helpers::set_symbol($order->order_amount),
                'order_status' => $order->order_status,
            ];
        });

        // Return JSON response
        return response()->json([
            'status' => 'success',
            'delivered_qty' => $orders->count(),
            'data' => $data
        ]);
    }
}
