<div class="card-body pt-3" id="items">
    <div class="pos-item-wrap justify-content-center">

        @foreach ($products as $product)
        @include('admin-views.pos._single_product', [
        'product' => $product,
        ])
        @endforeach
    </div>
</div>
<!-- End POS Products -->

<div class="p-3 d-flex justify-content-end">
    {!! $products->withQueryString()->links() !!}
</div>