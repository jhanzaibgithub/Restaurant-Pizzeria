<!-- Header -->
<div class="card-header d-flex justify-content-between align-items-center gap-10">
    <h2 class="mb-0">{{translate('Top_Selling')}}</h2>

    <!-- <a href="{{route('admin.product.list')}}" class="btn-link">{{translate('View_All')}}</a> -->
    <div class="row d-flex justify-content-around">
        <div class="col-md-3">
            <div class="card card-sellingP" style="width: 90px; height: 95px;" onclick="showDataDine('Dine In')" id="DineInCard">
                <div class="card-body rounded" style="border: dashed; color: #f1f1f2;">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img class="img-fluid" style="max-height: 70px;" src="{{ asset('assets/admin/img/dashboard/dashboard_product_grey.png') }}" alt="" />
                        <div style="font-size: smaller; font-weight: 700; color: #7e8299;" class="card-title">Products</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-sellingP" style="width: 90px; height: 95px;" onclick="showDataTake('Take Away')" id="takeAwayCard">
                <div class="card-body rounded" style="border: dashed; color: #f1f1f2;">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div>
                            <img class="img-fluid" style="max-height: 70px;" src="{{ asset('assets/admin/img/dashboard/dashboard_meal_grey.png') }}" alt="" />
                        </div>
                        <div style="font-size: smaller; font-weight: 700; color: #7e8299;" class="card-title">Meals</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-sellingP" style="width: 90px; height: 95px;" onclick="showDataDelivery('Home Delivery')" id="homeDeliveryCard">
                <div class="card-body rounded" style="border: dashed; color: #f1f1f2;">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img class="img-fluid" style="max-height: 70px;" src="{{ asset('assets/admin/img/dashboard/dashboard_branch_grey.png') }}" alt="" />
                        <div style="font-size: smaller; font-weight: 700; color: #7e8299;" class="card-title">Branches</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div id="allProductsData" class="col-12 d-flex"></div>
</div>
<div class="row">
    <div id="allMealsData" class="col-12 d-flex"></div>
</div>
<div class="row">
    <div id="homeDeliveryData" class="col-12 d-flex"></div>
</div>
