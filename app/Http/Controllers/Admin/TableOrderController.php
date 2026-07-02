<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\AddOn;
use App\Model\DeliveryMan;
use App\Model\Order;
use App\Model\Table;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Support\Renderable;


class TableOrderController extends Controller
{
    public function __construct(
        private Table $table,
        private Order $order,
        private DeliveryMan $delivery_man
    )
    {
    }


    /**
     * @param Request $request
     * @param $status
     * @return Renderable
     */
    public function order_list(Request $request, $status): Renderable
    {
        $query_param = [];
        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $orders = $this->order->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('order_status', 'like', "%{$value}%")
                        ->orWhere('transaction_reference', 'like', "%{$value}%");
                }
            })
                ->when($from && $to, function ($query) use ($from, $to) {
                    $query->whereBetween('created_at', [$from, $to]);
                });
            $query_param = ['search' => $request['search']];
        } else {
            if (!session()->has('branch_filter')) {
                session()->put('branch_filter', 0);
            }
            $this->order->where(['checked' => 0])->update(['checked' => 1]);

            //all branch
            if (session('branch_filter') == 0) {
                if ($status == 'all') {
                    $orders = $this->order->with(['customer', 'branch', 'table'])
                        ->when($from && $to, function ($query) use ($from, $to) {
                            $query->whereBetween('created_at', [$from, Carbon::parse($to)->endOfDay()]);
                        });
                } else {
                    $orders = $this->order->with(['customer', 'branch', 'table'])
                        ->where(['order_status' => $status])
                        ->when($from && $to, function ($query) use ($from, $to) {
                            $query->whereBetween('created_at', [$from, Carbon::parse($to)->endOfDay()]);
                        });
                }
            } //selected branch
            else {

                if ($status == 'all') {
                    $orders = $this->order->with(['customer', 'branch', 'table'])->where('branch_id', session('branch_filter'))
                        ->when($from && $to, function ($query) use ($from, $to) {
                            $query->whereBetween('created_at', [$from, Carbon::parse($to)->endOfDay()]);
                        });
                } else {
                    $orders = $this->order->with(['customer', 'branch', 'table'])
                        ->where(['order_status' => $status, 'branch_id' => session('branch_filter')])
                        ->when($from && $to, function ($query) use ($from, $to) {
                            $query->whereBetween('created_at', [$from, Carbon::parse($to)->endOfDay()]);
                        });
                }
            }
        }

        $statusCounts = $this->order->notPos()->dineIn()->notSchedule()
            ->whereIn('order_status', ['confirmed', 'cooking', 'done', 'completed', 'canceled'])
            ->when(!is_null($from) && !is_null($to), function ($query) use ($from, $to) {
                $query->whereBetween('created_at', [$from, Carbon::parse($to)->endOfDay()]);
            })
            ->select('order_status', DB::raw('COUNT(*) as total'))
            ->groupBy('order_status')
            ->pluck('total', 'order_status');

        $order_count = [
            'confirmed' => (int) ($statusCounts['confirmed'] ?? 0),
            'cooking' => (int) ($statusCounts['cooking'] ?? 0),
            'done' => (int) ($statusCounts['done'] ?? 0),
            'completed' => (int) ($statusCounts['completed'] ?? 0),
            'canceled' => (int) ($statusCounts['canceled'] ?? 0),
        ];
         // New Dashboard statistics
         $data=[];
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
         $order_statistics_chart = [];
         $order_statistics_chart_data = $this->order->where(['order_status' => 'delivered'])
             ->select(
                 DB::raw('(count(id)) as total'),
                 DB::raw('YEAR(created_at) year, MONTH(created_at) month')
             )
             //->whereBetween('created_at', [$from, $to])
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
        $orders = $orders->dineIn()->latest()->paginate(Helpers::getPagination())->appends($query_param);
        // dd($orders);
        return view('admin-views.table.order.list', compact('orders', 'search', 'status', 'from', 'to', 'order_count','order_statistics_chart'));
    }

    /**
     * @param $id
     * @return Renderable|RedirectResponse
     */
    public function order_details($id): Renderable|RedirectResponse
    {
        $order = $this->order->with(['details.product', 'customer', 'delivery_address', 'branch', 'delivery_man', 'table'])->where(['id' => $id])->first();

        if (!isset($order)) {
            Toastr::info(translate('No more orders!'));
            return back();
        }

        $delivery_man = $this->delivery_man->where(['is_active'=>1])
            ->where(function($query) use ($order) {
                $query->where('branch_id', $order->branch_id)
                    ->orWhere('branch_id', 0);
            })
            ->get();

        //remaining delivery time
        $delivery_date_time = $order['delivery_date'] . ' ' . $order['delivery_time'];
        $ordered_time = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime($delivery_date_time)));
        $remaining_time = $ordered_time->add($order['preparation_time'], 'minute')->format('Y-m-d H:i:s');
        $order['remaining_time'] = $remaining_time;
        $addonMap = $this->getOrderAddonMap($order);
        $deliveryOrigin = null;
        $deliveryCurrent = null;
        $address = $order->delivery_address;

        if ($order['order_status'] == 'out_for_delivery') {
            $deliveryHistoryQuery = \App\Model\DeliveryHistory::where([
                'deliveryman_id' => $order['delivery_man_id'],
                'order_id' => $order['id'],
            ]);
            $deliveryOrigin = (clone $deliveryHistoryQuery)->first();
            $deliveryCurrent = (clone $deliveryHistoryQuery)->latest()->first();
        }

        $timeZone = Helpers::get_business_settings('time_zone') ?? 'UTC';

        return view('admin-views.order.order-view', compact('order','delivery_man', 'addonMap', 'deliveryOrigin', 'deliveryCurrent', 'address', 'timeZone'));
    }

    /**
     * @param Request $request
     * @return Renderable
     */
    public function table_running_order(Request $request): Renderable
    {
        $branch_id = session('branch_filter') ? session('branch_filter') : 1;
        $table_id = $request->table_id;

        $tables = $this->table->with('order')->whereHas('order', function ($q) {
            $q->whereHas('table_order', function ($q) {
                $q->where('branch_table_token_is_expired', 0);
            });
        })
            ->where(['branch_id' => $branch_id])
            ->get();
        $branches = DB::table('branches')->select('id', 'name')->get();

        $orders = $this->order->with('table_order')
            ->whereHas('table_order', function ($q) {
                $q->where('branch_table_token_is_expired', 0);
            })
            ->where(['branch_id' => $branch_id])
            ->when(!is_null($table_id), function ($query) use ($table_id) {
                return $query->where('table_id', $table_id);
            })
            ->latest()
            ->paginate(Helpers::getPagination());

        return view('admin-views.table.order.table_running_order', compact('tables', 'orders', 'table_id', 'branches'));
    }

    /**
     * @param Request $request
     * @return Renderable
     */
    public function running_order_invoice(Request $request): Renderable
    {
        $table_id = $request->table_id;
        $orders = $this->order
            ->with(['table_order', 'details.product', 'delivery_address'])
            ->whereHas('table_order', function ($q) {
                $q->where('branch_table_token_is_expired', 0);
            })
            ->when(!is_null($table_id), function ($query) use ($table_id) {
                return $query->where('table_id', $table_id);
            })->get();
        $addonMap = $this->getOrdersAddonMap($orders);
        $businessSettings = DB::table('business_settings')
            ->whereIn('key', ['restaurant_name', 'address', 'phone'])
            ->pluck('value', 'key');

        return view('admin-views.table.order.running_order_invoice', compact('orders', 'table_id', 'addonMap', 'businessSettings'));
    }

    private function getOrderAddonMap(?Order $order)
    {
        if (!$order) {
            return collect();
        }

        return $this->getOrdersAddonMap(collect([$order]));
    }

    private function getOrdersAddonMap($orders)
    {
        $addonIds = $orders
            ->flatMap(function ($order) {
                return $order->details->flatMap(function ($detail) {
                    return json_decode($detail['add_on_ids'], true) ?: [];
                });
            })
            ->filter()
            ->unique()
            ->values();

        return $addonIds->isEmpty() ? collect() : AddOn::whereIn('id', $addonIds)->get()->keyBy('id');
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function branch_filter($id): RedirectResponse
    {
        session()->put('branch_filter', $id);
        return back();
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|string|RedirectResponse
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\InvalidArgumentException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
     */
    public function export_excel(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse|string|RedirectResponse
    {
        $status = $request->status;
        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];

        if (session()->has('branch_filter') == false) {
            session()->put('branch_filter', 0);
        }

        $orders = $this->order->query()
            ->dineIn()
            ->when($search, function ($q) use ($search) {
                $key = explode(' ', $search);
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('order_status', 'like', "%{$value}%")
                        ->orWhere('transaction_reference', 'like', "%{$value}%");
                }
            })
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()]);
            })
            ->when($status != 'all', function ($q) use ($status) {
                $q->where('order_status', $status);
            })
            ->when(session('branch_filter') != 0, function ($q) {
                $q->where('branch_id', session('branch_filter'));
            })
            ->latest();

        if (!$orders->exists()) {
            Toastr::warning('No Data Available');
            return back();
        }

        $data = function () use ($orders) {
            foreach ($orders->lazy() as $key => $order) {
                yield [
                    'SL' => ++$key,
                'Order ID' => $order->id,
                'Order Date' => date('d M Y h:m A', strtotime($order['created_at'])),
                'Customer Info' => $order['user_id'] == null ? 'Walk in Customer' : ($order->customer == null ? 'Customer Unavailable' : $order->customer['f_name'] . ' ' . $order->customer['l_name']),
                'Branch' => $order->branch ? $order->branch->name : 'Branch Deleted',
                'Total Amount' => Helpers::set_symbol($order['order_amount']),
                'Payment Status' => $order->payment_status == 'paid' ? 'Paid' : 'Unpaid',
                'Order Status' => $order['order_status'] == 'pending' ? 'Pending' : ($order['order_status'] == 'confirmed' ? 'Confirmed' : ($order['order_status'] == 'processing' ? 'Processing' : ($order['order_status'] == 'delivered' ? 'Delivered' : ($order['order_status'] == 'picked_up' ? 'Out For Delivery' : str_replace('_', ' ', $order['order_status']))))),
                ];
            }
        };

        return (new FastExcel($data()))->download('Order_Details.xlsx');
    }

}
