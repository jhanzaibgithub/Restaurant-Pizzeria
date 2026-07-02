@foreach($customers as $key=>$customer)
    <tr class="">
        <td class="">
            {{$customers->firstitem()+$key}}
        </td>
        <td class="max-w300">
            <a class="text-muted media align-items-center gap-2" href="{{route('admin.customer.view',[$customer['id']])}}">
                <!-- <div class="avatar">
                    <img src="{{asset('/storage/profile')}}/{{$customer['image']}}" class="rounded-circle img-fit"
                         onerror="this.src='{{asset('assets/admin/img/160x160/img1.jpg')}}'">
                </div> -->
                <div class="media-body text-truncate">{{$customer['f_name']." ".$customer['l_name']}}</div>
            </a>
        </td>
        <td>
            <div><a href="mailto:{{$customer['email']}}" class="text-muted"><strong>{{$customer['email']}}</strong></a></div>
            <div><a class="text-muted" href="https://wa.me/{{$customer['phone']}}?text=Hi%27,%20from%restaurant-pizzeria" target="_blank"><span class="fab fa-whatsapp"></span> {{$customer['phone']}}</a></div>
        </td>
        <td>
            <label class="text-muted">
                {{$customer->orders_count ?? 0}}
            </label>
        </td>
        <td class="text-order_id">${{$customer->orders_sum_order_amount ?? 0}}</td>
        <td class="show-point-{{$customer['id']}}-table">
            {{$customer['point']}}
        </td>
        <td>
            <label class="switcher">
                <input id="{{$customer['id']}}" data-url="{{route('admin.customer.update_status', ['id' => $customer['id']])}}" onclick="status_change(this)" type="checkbox" class="switcher_input" {{$customer->is_active == 1? 'checked' : ''}}>
                <span class="switcher_control"></span>
            </label>
        </td>
        <td>
            <div class="d-flex justify-content-center gap-2">
                <a class="btn btn-secondary btn-sm square-btn"
                    href="{{route('admin.customer.view',[$customer['id']])}}">
                    <i style=" color: #A1A5B7;"class="tio-visible"></i>
                </a>
               <a class="btn btn-secondary btn-sm square-btn" href="javascript:" onclick="set_point_modal_data('{{route('admin.customer.set-point-modal-data',[$customer['id']])}}')">
                   <i style=" color: #A1A5B7;" class="tio-add-circle"></i>
              </a>
                <button class="btn btn-secondary btn-sm square-btn"  onclick="form_alert('customer-{{$customer['id']}}', '{{translate('delete_this_user')}}')" >
                    <i style=" color: #A1A5B7;" class="tio-delete"></i>
                </button>
                <form id="customer-{{$customer['id']}}" action="{{route('admin.customer.destroy',['id' => $customer['id']])}}" method="post">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </td>
    </tr>
<!--    <div class="modal fade" id="exampleModal-{{$customer['id']}}" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Internal Point</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="javascript:" method="POST" id="point-form-{{$customer['id']}}">
                    @csrf
                    <div class="modal-body">
                        <h5>
                            <label class="badge badge-soft-info">
                                {{$customer['f_name']}} {{$customer['l_name']}}
                            </label>
                            <label class="show-point-{{$customer['id']}}">
                                ( Available Point : {{$customer['point']}} )
                            </label>
                        </h5>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Add Point :</label>
                            <input type="number" min="1" value="1" max="1000000"
                                   class="form-control"
                                   name="point">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                        <button type="button"
                                onclick="add_point('point-form-{{$customer['id']}}','{{route('admin.customer.add-point',[$customer['id']])}}','{{$customer['id']}}')"
                                class="btn btn-primary">Add
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>-->
@endforeach
