{{--  ***********************Start Here... Newly Added to Dashboard First 8 Small Cards*****************************  --}}



<div class="col-sm-6 col-md-4 col-lg-3 ">
    <!-- Card 1 Pending -->
    <a href="{{route('admin.orders.list',['pending'])}}" class="card">
        <div class="nd-card-body">
            <div>
                <img class="nd--card-img" src="{{ asset('assets/admin/img/icons/d1pending.png') }}"
                     alt="">
            </div>
            <div class="nd--card-text">
                <h3 class="nd--card-title">{{translate('pending')}} {{translate('Orders')}}<span>{{$data['pending']}}</span></h3>
                <hr>
                <h6 class="nd--card-subtitle"><span class="text-success">{{round($data['pending_per'])}}% </span>then last week</h6>
            </div>

        </div>
    </a>
</div>
<div class="col-sm-6 col-md-4 col-lg-3 ">
    <!-- Card 2 Processing -->
    <a href="{{route('admin.orders.list',['processing'])}}" class="card">
        <div class="nd-card-body">
            <div>
                <img class="nd--card-img" src="{{ asset('assets/admin/img/icons/d1processing.png') }}"
                     alt="">
            </div>
            <div class="nd--card-text">
                <h3 class="nd--card-title">{{translate('processing')}} {{translate('Orders')}} <span>{{$data['processing']}}</span></h3>
                <hr>
                <h6 class="nd--card-subtitle"><span class="text-success">{{round($data['processing_per'])}}% </span>then last week</h6>
            </div>

        </div>
    </a>
</div>
<div class="col-sm-6 col-md-4 col-lg-3 ">
    <!-- Card 3 Delivered -->
    <a href="{{route('admin.orders.list',['delivered'])}}" class="card" >
        <div class="nd-card-body">
            <div>
                <img class="nd--card-img" src="{{ asset('assets/admin/img/icons/d1delivered.png') }}"
                     alt="">
            </div>
            <div class="nd--card-text">
                <h3 class="nd--card-title">{{translate('delivered')}} {{translate('Orders')}}<span>{{$data['delivered']}}</span></h3>
                <hr>
                <h6 class="nd--card-subtitle"><span class="text-success">{{round($data['delivered_per'])}}% </span>then last week</h6>
            </div>

        </div>
    </a>
</div>
<div class="col-sm-6 col-md-4 col-lg-3 ">
    <!-- Card 4 Returned -->
    <a href="{{route('admin.orders.list',['returned'])}}" class="card">
        <div class="nd-card-body">
            <div>
                <img class="nd--card-img" src="{{ asset('assets/admin/img/icons/d1returned.png') }}"
                     alt="">
            </div>
            <div class="nd--card-text">
                <h3 class="nd--card-title">{{translate('returned')}} {{translate('Orders')}}<span>{{$data['returned']}}</span></h3>
                <hr>
                <h6 class="nd--card-subtitle"><span class="text-danger">{{round($data['returned_per'])}}% </span>then last week</h6>
            </div>

        </div>
    </a>
</div>
<div class="col-sm-6 col-md-4 col-lg-3 ">
    <!-- Card 5 Confirmed -->
    <a href="{{route('admin.orders.list',['confirmed'])}}" class="card">
        <div class="nd-card-body">
            <div>
                <img class="nd--card-img" src="{{ asset('assets/admin/img/icons/d1returned.png') }}"
                     alt="">
            </div>
            <div class="nd--card-text">
                <h3 class="nd--card-title">{{translate('confirmed')}} {{translate('Orders')}}<span>{{$data['confirmed']}}</span></h3>
                <hr>
                <h6 class="nd--card-subtitle"><span class="text-danger">{{round($data['confirmed_per'])}}% </span>then last week</h6>
            </div>

        </div>
    </a>
</div>
<div class="col-sm-6 col-md-4 col-lg-3 ">
    <!-- Card 6 Out of Delivery -->
    <a href="{{route('admin.orders.list',['out_for_delivery'])}}" class="card">
        <div class="nd-card-body">
            <div>
                <img class="nd--card-img" src="{{ asset('assets/admin/img/icons/d1out_of_delivery.png') }}"
                     alt="">
            </div>
            <div class="nd--card-text">
                <h3 class="nd--card-title">{{translate('out_for_delivery')}}<span>{{$data['out_for_delivery']}}</span></h3>
                <hr>
                <h6 class="nd--card-subtitle"><span class="text-danger">{{round($data['out_for_delivery_per'])}}% </span>then last week</h6>
            </div>

        </div>
    </a>
</div>
<div class="col-sm-6 col-md-4 col-lg-3 ">
    <!-- Card 7 Cancelled -->
    <a href="{{route('admin.orders.list',['canceled'])}}" class="card">
        <div class="nd-card-body">
            <div>
                <img class="nd--card-img" src="{{ asset('assets/admin/img/icons/d1cancelled.png') }}"
                     alt="">
            </div>
            <div class="nd--card-text">
                <h3 class="nd--card-title">{{translate('canceled')}} {{translate('Orders')}}<span>{{$data['canceled']}}</span></h3>
                <hr>
                <h6 class="nd--card-subtitle"><span class="text-danger">{{round($data['canceled_per'])}}% </span>then last week</h6>
            </div>

        </div>
    </a>
</div>

<div class="col-sm-6 col-md-4 col-lg-3 ">
    <!-- Card 8 Failed to Deliver -->
    <a href="{{route('admin.orders.list',['failed'])}}" class="card">
        <div class="nd-card-body">
            <div>
                <img class="nd--card-img" src="{{ asset('assets/admin/img/icons/d1failed_to_deliver.png') }}"
                     alt="">
            </div>
            <div class="nd--card-text">
                <h3 class="nd--card-title">Failed To Deliver<span>0</span></h3>
                <hr>
                <h6 class="nd--card-subtitle"><span class="text-danger">0% </span>then last week</h6>
            </div>

        </div>
    </a>
</div>