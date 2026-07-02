<div class="border-bottom"></div>
@foreach($conversationSidebar as $sidebarItem)
        @php($user = $sidebarItem['user'])
        @php($unchecked = $sidebarItem['unchecked'])
        <div
            class="sidebar_primary_div d-flex border-bottom pb-2 pt-2 pl-md-1 pl-0 justify-content-between align-items-center customer-list {{$unchecked!=0?'conv-active':''}}"
            onclick="viewConvs('{{route('admin.message.view',[$user->id])}}','customer-{{$user->id}}')"
            style="cursor: pointer; border-radius: 10px;margin-top: 2px;"
            id="customer-{{$user->id}}">
            <div class="avatar avatar-lg avatar-circle">
                <img class="avatar-img" style="width: 54px;height: 54px"
                     src="{{asset('/storage/profile/'.$user['image'])}}"
                     onerror="this.src='{{asset('assets/admin')}}/img/160x160/img1.jpg'"
                     alt="Image Description">
            </div>
            <h5 class="sidebar_name mb-0 mr-3 d-none d-md-block">
                {{$user['f_name'].' '.$user['l_name']}} <span
                    class="{{$unchecked!=0?'badge badge-info':''}}" id="counter-{{$user->id}}">{{$unchecked!=0?$unchecked:''}}</span>
            </h5>
        </div>
@endforeach
