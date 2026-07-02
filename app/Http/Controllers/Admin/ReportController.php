<?php

namespace App\Http\Controllers\Admin;

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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;


class ReportController extends Controller
{
    public function __construct(
        private Order       $order,
        private OrderDetail $order_detail,
        private WalletTransaction $wallet_transaction,
    )
    {
    }


    /**
     * @return Renderable
     */
    public function order_index(): Renderable
    {
        if (session()->has('from_date') == false) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }

        $from = session('from_date');
        $to = session('to_date');
        $statusCounts = $this->order
            ->whereBetween('created_at', [$from, $to])
            ->select('order_status', DB::raw('COUNT(*) as total'))
            ->groupBy('order_status')
            ->pluck('total', 'order_status');
        $total = max((int) $statusCounts->sum(), 0.01);
        $delivered = (int) ($statusCounts['delivered'] ?? 0);
        $returned = (int) ($statusCounts['returned'] ?? 0);
        $failed = (int) ($statusCounts['failed'] ?? 0);
        $canceled = (int) ($statusCounts['canceled'] ?? 0);

        return view('admin-views.report.order-index', compact('total', 'delivered', 'returned', 'failed', 'canceled'));
    }
    

    /**
     * @param Request $request
     * @return Renderable
     */
    public function earning_index(Request $request): Renderable
    {
        $from = $request->from ? Carbon::parse($request->from)->startOfDay() : Carbon::now()->startOfMonth();
        $to = $request->to ? Carbon::parse($request->to)->endOfDay() : Carbon::now()->endOfMonth();

        if ($request->from > $request->to) {
            Toastr::warning(translate('Invalid date range!'));
            return back();
        }

        $applyDateFilter = (bool) ($request->from && $request->to);
        if ($applyDateFilter) {
            session()->put('from_date', $from);
            session()->put('to_date', $to);
        }

        $totals = $this->deliveredTotals($from, $to, $applyDateFilter);
        $total_tax = $totals['total_tax'];
        $total_sold = $totals['total_sold'];

        if (session()->has('from_date') == false || !($request->from && $request->to)) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }

           
        $previousEndDate = $from->copy()->subDay();
        $previousStartDate = $previousEndDate->copy()->subDays($to->diffInDays($from) + 1);

        $previousTotalSold = $this->deliveredOrders($previousStartDate, $previousEndDate)->sum('order_amount');

        // Calculate growth
        $growth = 0;
        if ($previousTotalSold > 0) {
            $growth = (($total_sold - $previousTotalSold) / $previousTotalSold) * 100;
        }
        
        $data = $this->dashboard_data();
        
        // dd($data);
        return view('admin-views.report.earning-index', compact('growth','total_tax', 'total_sold', 'from', 'to','data'));
    }


    /**
     * @param Request $request
     * @return Renderable
     */
    public function customer_index(Request $request): Renderable
    {
        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();

        if ($request->from > $request->to) {
            Toastr::warning(translate('Invalid date range!'));
        }

        $applyDateFilter = (bool) ($request->from && $request->to);
        if ($applyDateFilter) {
            session()->put('from_date', $from);
            session()->put('to_date', $to);
        }

        $totals = $this->deliveredTotals($from, $to, $applyDateFilter);
        $total_tax = $totals['total_tax'];
        $total_sold = $totals['total_sold'];

        if (session()->has('from_date') == false) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }

        $branches = DB::table('branches')->select('id', 'name')->get();

        $data = $this->dashboard_data();

        return view('admin-views.report.customer-report', compact('total_tax', 'total_sold', 'from', 'to', 'branches', 'data'));
    }
// ___________________Start customer and Branch Report_____
    /**
     * @param Request $request
     * @return RedirectResponse
     */
     public function set_date(Request $request): RedirectResponse
    {
        $fromDate = Carbon::parse($request['from'])->startOfDay();
        $toDate = Carbon::parse($request['to'])->endOfDay();
    
        session()->put('from_date', $fromDate);
        session()->put('to_date', $toDate);
    
        return back();
    }

     /**
     * @return Renderable
     */
    public function deliveryman_report(): Renderable
    {
        $orders = $this->order->with(['customer', 'branch'])->paginate(25);
        $deliveryMen = DB::table('delivery_men')->select('id', 'f_name', 'l_name')->get();
        return view('admin-views.report.driver-index', compact('orders', 'deliveryMen'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deliveryman_filter(Request $request): JsonResponse
    {
        $fromDate = Carbon::parse($request->formDate)->startOfDay();
        $toDate = Carbon::parse($request->toDate)->endOfDay();

        $orders = $this->order
            ->where(['delivery_man_id' => $request['delivery_man']])
            ->where(['order_status' => 'delivered'])
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->get();

        return response()->json([
            'view' => view('admin-views.order.partials._table', compact('orders'))->render(),
            'delivered_qty' => $orders->count()
        ]);
    }

    /**
     * @return Renderable
     */
    public function product_report(): Renderable
    {
        $branches = DB::table('branches')->select('id', 'name')->get();
        $products = DB::table('products')->select('id', 'name')->get();
        return view('admin-views.report.product-report', compact('branches', 'products'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function product_report_filter(Request $request): JsonResponse
    {
        $fromDate = Carbon::parse($request->from)->startOfDay();
        $toDate = Carbon::parse($request->to)->endOfDay();

        $details = $this->order_detail
            ->with(['order.customer'])
            ->whereHas('order', function ($query) use ($request, $fromDate, $toDate) {
                $query->when($request['branch_id'] != 'all', function ($query) use ($request) {
                    $query->where('branch_id', $request['branch_id']);
                })->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->when($request['product_id'] != 'all', function ($query) use ($request) {
                $query->where('product_id', $request['product_id']);
            })
            ->latest()
            ->get();

        $data = [];
        $total_sold = 0;
        $total_qty = 0;
        foreach ($details as $detail) {
            $price = Helpers::variation_price(json_decode($detail->product_details, true), $detail['variations']) - $detail['discount_on_product'];
            $ord_total = $price * $detail['quantity'];
            $data[] = [
                'order_id' => $detail['order_id'],
                'date' => $detail->order?->created_at ?? $detail['created_at'],
                'customer' => $detail->order?->customer,
                'price' => $ord_total,
                'quantity' => $detail['quantity'],
            ];
            $total_sold += $ord_total;
            $total_qty += $detail['quantity'];
        }

        session()->put('export_data', $data);

        return response()->json([
            'order_count' => count($data),
            'item_qty' => $total_qty,
            'order_sum' => \App\CentralLogics\Helpers::set_symbol($total_sold),
            'view' => view('admin-views.report.partials._table', compact('data'))->render(),
        ]);
    }

    /**
     * @return mixed
     */
    public function export_product_report(): mixed
    {
        if (session()->has('export_data')) {
            $data = session('export_data');
        } else {
            $details = $this->order_detail->with(['order.customer'])->latest()->get();
            $data = [];
            $total_sold = 0;
            $total_qty = 0;
            foreach ($details as $detail) {
                $price = Helpers::variation_price(json_decode($detail->product_details, true), $detail['variations']) - $detail['discount_on_product'];
                $ord_total = $price * $detail['quantity'];
                $data[] = [
                    'order_id' => $detail['order_id'],
                    'date' => $detail->order?->created_at ?? $detail['created_at'],
                    'customer' => $detail->order?->customer,
                    'price' => $ord_total,
                    'quantity' => $detail['quantity'],
                ];
                $total_sold += $ord_total;
                $total_qty += $detail['quantity'];
            }
        }

        $pdf = PDF::loadView('admin-views.report.partials._report', compact('data'));
        return $pdf->download('report_' . rand(00001, 99999) . '.pdf');
    }

    /**
     * @return Renderable
     */
    public function sale_report(): Renderable
    {
        $branches = DB::table('branches')->select('id', 'name')->get();
        return view('admin-views.report.sale-report', compact('branches'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sale_filter(Request $request): JsonResponse
    {
        $fromDate = Carbon::parse($request->from)->startOfDay();
        $toDate = Carbon::parse($request->to)->endOfDay();

        $details = $this->order_detail
            ->select([
                'order_details.order_id',
                'order_details.created_at',
                'order_details.quantity',
                DB::raw('((order_details.price - order_details.discount_on_product) * order_details.quantity) as price'),
            ])
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$fromDate, $toDate])
            ->when($request['branch_id'] != 'all', function ($query) use ($request) {
                $query->where('orders.branch_id', $request['branch_id']);
            })
            ->latest('order_details.created_at')
            ->get();

        $data = $details->map(fn($detail) => [
            'order_id' => $detail['order_id'],
            'date' => $detail['created_at'],
            'price' => $detail['price'],
            'quantity' => $detail['quantity'],
        ])->all();
        $total_sold = $details->sum('price');
        $total_qty = $details->sum('quantity');

        return response()->json([
            'order_count' => count($data),
            'item_qty' => $total_qty,
            'order_sum' => Helpers::set_symbol($total_sold),
            'view' => view('admin-views.report.partials._table', compact('data'))->render(),
        ]);
    }

    /**
     * @return mixed
     */
    public function export_sale_report(): mixed
    {
        $data = session('export_sale_data');
        $pdf = PDF::loadView('admin-views.report.partials._report', compact('data'));

        return $pdf->download('sale_report_' . rand(00001, 99999) . '.pdf');
    }
    
    // ___________________Start Branch Report_____
/**
     * @param Request $request
     * @return Renderable
     */
    public function branch_index(Request $request): Renderable
    {
        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();
        $branchId = $request->branch;

        if ($request->from > $request->to) {
            Toastr::warning(translate('Invalid date range!'));
        }

        $applyDateFilter = (bool) ($request->from && $request->to);
        if ($applyDateFilter) {
            session()->put('from_date', $from);
            session()->put('to_date', $to);
        }

        $totals = $this->deliveredTotals($from, $to, $applyDateFilter, $branchId);
        $total_tax = $totals['total_tax'];
        $total_sold = $totals['total_sold'];
        $total_earnings = $total_sold - $total_tax; // Assuming earnings are sales minus tax

        // Calculate growth - for demonstration purposes, let's assume it's compared to the previous period
        $previous_period_sales = $this->order->where('order_status', 'delivered')
            ->whereBetween('created_at', [$from->copy()->subMonth(), $to->copy()->subMonth()])
            ->sum('order_amount');
        $growth = $previous_period_sales ? (($total_sold - $previous_period_sales) / $previous_period_sales) * 100 : 0;

        // Get monthly data
        $sold = [];
        $tax = [];
        for ($i = 1; $i <= 12; $i++) {
            $month_start = Carbon::createFromDate($from->year, $i, 1)->startOfMonth();
            $month_end = Carbon::createFromDate($from->year, $i, 1)->endOfMonth();
            $monthlyTotals = $this->deliveredTotals($month_start, $month_end, true, $branchId);
            $sold[$i] = $monthlyTotals['total_sold'];
            $tax[$i] = $monthlyTotals['product_tax'];
        }

        if (!session()->has('from_date')) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }

        $branches = DB::table('branches')->select('id', 'name')->get();

        return view('admin-views.report.branch-report', compact('total_tax', 'total_sold', 'total_earnings', 'growth', 'from', 'to', 'sold', 'tax', 'branches'));
    }
    
    public function dashboard_data()
    {
        $start_date = session()->has('from_date') ? Carbon::parse(session()->get('from_date'))->startOfDay() : Carbon::now()->startOfDay();
        $end_date = session()->has('to_date') ? Carbon::parse(session()->get('to_date'))->endOfDay() : Carbon::now()->endOfDay();
        
        $sold = [];
        $tax = [];
        $total_sold = $this->deliveredOrders($start_date, $end_date)->sum('order_amount');
        $labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        
        $daysDifference = $start_date->diffInDays($end_date);
        
        if ($daysDifference <= 364) {
            $labels = [];
            
            if ($daysDifference == 0) {
                $dateFormat = 'H:i';
                $interval = 'hour';
            } elseif ($daysDifference <= 7) {
                $dateFormat = 'D';
                $interval = 'day';
            } elseif ($daysDifference <= 31) {
                $dateFormat = 'd';
                $interval = 'day';
            } else {
                $dateFormat = 'M';
                $interval = 'month';
            }
            
            $currentDate = $start_date->copy();
            while ($currentDate->lte($end_date)) {
                $label = $currentDate->format($dateFormat);

                $rangeStart = $currentDate->copy()->startOf($interval);
                $rangeEnd = $currentDate->copy()->endOf($interval);
                $totals = $this->deliveredTotals($rangeStart, $rangeEnd);
                $sold[$label] = $totals['total_sold'];
                $tax[$label] = $totals['product_tax'];
                
                $labels[] = $label;
    
                if ($interval === 'hour') {
                    $currentDate->addHour();
                } elseif ($interval === 'day') {
                    $currentDate->addDay();
                } else { // month
                    $currentDate->addMonthNoOverflow();
                }
            }
        } else {
            for ($i = 1; $i <= 12; $i++) {
                $monthStart = Carbon::createFromDate($start_date->year, $i, 1)->startOfMonth();
                $monthEnd = Carbon::createFromDate($start_date->year, $i, 1)->endOfMonth();
                $totals = $this->deliveredTotals($monthStart, $monthEnd);
                $sold[$i] = $totals['total_sold'];
                $tax[$i] = $totals['product_tax'];
            }
        }
    
        $dash_data['total_sold'] = $total_sold;
        $dash_data['sold'] = $sold;
        $dash_data['tax'] = $tax;
        $dash_data['labels'] = $labels;
        $dash_data['start_date'] = $start_date;
        $dash_data['end_date'] = $end_date;
    
        return $dash_data;
    }

    private function deliveredTotals(Carbon $from, Carbon $to, bool $applyDate = true, mixed $branchId = null): array
    {
        $orderTotals = $this->deliveredOrders($from, $to, $applyDate, $branchId)
            ->selectRaw('COALESCE(SUM(order_amount), 0) as total_sold, COALESCE(SUM(total_tax_amount), 0) as product_tax')
            ->first();

        $addOnTax = $this->deliveredOrderDetails($from, $to, $applyDate, $branchId)
            ->sum('order_details.add_on_tax_amount');

        $productTax = (float) ($orderTotals->product_tax ?? 0);

        return [
            'total_sold' => (float) ($orderTotals->total_sold ?? 0),
            'product_tax' => $productTax,
            'add_on_tax' => (float) $addOnTax,
            'total_tax' => $productTax + (float) $addOnTax,
        ];
    }

    private function deliveredOrders(Carbon $from, Carbon $to, bool $applyDate = true, mixed $branchId = null): Builder
    {
        return $this->order->newQuery()
            ->where('order_status', 'delivered')
            ->when($applyDate, fn($query) => $query->whereBetween('created_at', [$from, $to]))
            ->when($this->hasBranchFilter($branchId), fn($query) => $query->where('branch_id', $branchId));
    }

    private function deliveredOrderDetails(Carbon $from, Carbon $to, bool $applyDate = true, mixed $branchId = null): QueryBuilder
    {
        return DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->where('orders.order_status', 'delivered')
            ->when($applyDate, fn($query) => $query->whereBetween('orders.created_at', [$from, $to]))
            ->when($this->hasBranchFilter($branchId), fn($query) => $query->where('orders.branch_id', $branchId));
    }

    private function hasBranchFilter(mixed $branchId): bool
    {
        return filled($branchId) && $branchId !== 'all_branches' && $branchId !== 'all';
    }

}
