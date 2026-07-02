<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Branch;
use App\Model\ChefBranch;
use App\Model\Order;
use App\Model\Kitchen;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\TableOrder;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Storage;


use function App\CentralLogics\translate;

class KitchenController extends Controller
{
    public function __construct(
        private ChefBranch $chef_branch,
        private Branch     $branch,
        private Order      $order,
        private User       $user,
        private OrderDetail $order_detail,
		private Kitchen    $kitchen

    )
    {
    }
	
	public function getKitchen(Request $request) {
		
		$branch = $request->branch;
		
		$kitchens = Kitchen::where('branch_id', $branch->id)->where('status', 1)->get();

		foreach ($kitchens as $kitchen) {
			$kitchen->image = env('APP_URL') . Storage::url('kitchen/' . $kitchen->image);
		}

		return response()->json(['kitchens' => $kitchens]);
	}
	
	public function add(Request $request)
	{	
		
		$validatedData = $request->validate([
			'title' => 'required|string|max:255',
		]);

		$branch = $request->branch;
			
		if ($request->hasFile('image')) {
			$imagePath = Helpers::upload('kitchen/', 'png', $request->file('image'));
		} 

		$kitchen = Kitchen::create([
			'title' => $validatedData['title'],
			'image' => $imagePath ?? null,
			'branch_id' => $branch->id
		]);

		return response()->json(['message' => 'kitchen added successfully.'], 200);
	}

	public function update(Request $request)
	{	
		$validatedData = $request->validate([
			'title' => 'required|string|max:255',
		]);

		if ($request->hasFile('image')) {
			$imagePath = Helpers::upload('kitchen/', 'png', $request->file('image'));
		}
		
		$kitchen = Kitchen::findorFail($request->id);
		
		$kitchen->update([
			'title' => $request->title,
			'image' => $imagePath ?? $kitchen->image,
		]);

		return response()->json(['message' => 'kitchen update successfully.'], 200);
	}

	public function delete(Request $request){

		$kitchen = Kitchen::findorFail($request->id);

		$kitchen->delete();

		return response()->json(['message' => 'kitchen delete succussully'],200);
	}
	
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_order_list(Request $request): JsonResponse
    {
        $limit = is_null($request['limit']) ? 10 : $request['limit'];
        $offset = is_null($request['offset']) ? 1 : $request['offset'];

        $chef_branch = $this->chef_branch->where('user_id', auth()->user()->id)->first();
        $branch_id = $this->branch->where('id', $chef_branch->branch_id)->first();

        $orders = $this->order->with('table')
            ->whereIn('order_status', ['confirmed', 'cooking'])
            ->where('branch_id', $branch_id->id)
            ->latest()
            ->paginate($limit, ['*'], 'page', $offset);

        return response()->json($orders, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $chef_branch = $this->chef_branch->where('user_id', auth()->user()->id)->first();
        $branch_id = $this->branch->where('id', $chef_branch->branch_id)->first();

        $search = $request['search'];
        $key = explode(' ', $request['search']);

        $orders = $this->order
            ->where('branch_id', $branch_id->id)
            ->whereIn('order_status', ['confirmed', 'cooking', 'done'])
            ->when($search != null, function ($query) use ($key) {
                foreach ($key as $value) {
                    $query->Where('id', 'like', "%{$value}%");
                }
            })
            ->latest()
            ->paginate(Helpers::getPagination());

        return response()->json($orders, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function filter_by_status(Request $request): JsonResponse
    {
        $limit = is_null($request['limit']) ? 10 : $request['limit'];
        $offset = is_null($request['offset']) ? 1 : $request['offset'];

        $chef_branch = $this->chef_branch->where('user_id', auth()->user()->id)->first();
        $branch_id = $this->branch->where('id', $chef_branch->branch_id)->first();

        $order_status = $request->order_status;
        if ($order_status == 'cooking') {
            $orders = $this->order
                ->where(['order_status' => $order_status, 'branch_id' => $branch_id->id])
                ->orderBy('created_at', 'ASC')
                ->paginate($limit, ['*'], 'page', $offset);

        } else {
            $orders = $this->order
                ->where(['order_status' => $order_status, 'branch_id' => $branch_id->id])
                ->latest()
                ->paginate($limit, ['*'], 'page', $offset);
        }

        return response()->json($orders, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_order_details(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $order = $this->order->with('table')->where(['id' => $request->order_id])->first();
        if (isset($order)) {
            $details = $this->order_detail->where(['order_id' => $order->id])->get();
            $details = isset($details) ? Helpers::order_details_formatter($details) : null;

            return response()->json([
                'order' => $order,
                'details' => $details
            ], 200);

        } else {
            return response()->json([
                'message' => 'no order found'
            ]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function change_status(Request $request): JsonResponse
    {
        $order = $this->order->find($request->order_id);
        $order->order_status = $request->order_status;

        //send notification to deliveryman after done
        if ($request->order_status == 'done') {
            $fcm_token = null;
            if (isset($order->delivery_man)) {
                $fcm_token = $order->delivery_man->fcm_token;
            }
            try {
                $data = [
                    'title' => translate('Order'),
                    'description' => translate('cooking done'),
                    'order_id' => $order->id,
                    'image' => '',
                    'type' => '',
                ];
                if (!is_null($fcm_token)) {
                    Helpers::send_push_notif_to_device($fcm_token, $data);
                }
            } catch (\Exception $e) {
                Toastr::warning(translate('Push notification failed for DeliveryMan!'));
            }
        }
        $isUpdate = $order->update();

        if ($isUpdate) {
            return response()->json(['orders' => $order, 'message' => translate('Order status updated!')], 200);
        }

        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => translate('Status did not changed')]
            ]
        ], 401);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update_fcm_token(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $kitchen = $this->user->find(auth()->user()->id);

        if (!isset($kitchen)) {
            return response()->json([
                'errors' => [
                    ['code' => 'Kitchen', 'message' => translate('Invalid token!')]
                ]
            ], 401);
        }

        $kitchen->cm_firebase_token = $request->token;
        $kitchen->update();

        return response()->json(['kitchen' => $kitchen, 'message' => translate('successfully updated!')], 200);
    }

    /**
     * @return JsonResponse
     */
    public function get_profile(): JsonResponse
    {
        $kitchen = $this->user->find(auth()->user()->id);
        $chef_branch = $this->chef_branch->where('user_id', auth()->user()->id)->first();
        if (!$kitchen || !$chef_branch) {
            return response()->json([
                'errors' => [
                    ['code' => 'Kitchen', 'message' => translate('Profile not found!')]
                ]
            ], 404);
        }

        $branch = $this->branch->where('id', $chef_branch->branch_id)->first();

        return response()->json([
            'profile' => $kitchen,
            'branch' => $branch
        ], 200);
    }

}
