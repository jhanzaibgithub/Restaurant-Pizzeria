<div class="card mb-3">
            <div class="card-header d-flex justify-content-between flex-wrap gap-2">
                @php($earningGraphData = $data ?? ['total_sold' => 0, 'sold' => array_fill(1, 12, 0), 'tax' => array_fill(1, 12, 0)])
                <h6 class="d-flex align-items-center gap-2 mb-0">
                    {{translate('Total_Sale')}} ({{date('Y')}}) :
                    <span class="h4 mb-0"> {{ \App\CentralLogics\Helpers::set_symbol($earningGraphData['total_sold']) }}</span>
                </h6>
                <div class="col-sm-4 col-md-6 col-lg-8 d-flex justify-content-end">
                    <div>
                        <button type="button"  class="btnExport" data-toggle="dropdown"
                           aria-expanded="false">
                            {{ translate('export') }}
                            <i class="tio-download-to"></i>     
                        </button>   
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a type="submit" class="dropdown-item d-flex align-items-center gap-2"
                                href="{{ route('admin.pos.export-excel') }}?from={{ $from }}&to={{ $to }}">
                                <img width="14" src="{{ asset('assets/admin/img/icons/excel.png') }}"
                                alt="">
                                {{ translate('Excel') }}
                                </a>
                            </li>
                        </ul>
                    </div>    
                </div>   
            </div>
  

            @php($sold = $earningGraphData['sold'])
            @php($tax = $earningGraphData['tax'])

            <!-- Body -->
            <div class="card-body">
                <!-- Bar Chart -->
                <div class="chartjs-custom" style="height: 360px">
                    <canvas class="js-chart"
                            data-hs-chartjs-options='{
                        "type": "line",
                        "data": {
                           "labels": ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
                           "datasets": [{
                            "data": [{{$sold[1]}},{{$sold[2]}},{{$sold[3]}},{{$sold[4]}},{{$sold[5]}},{{$sold[6]}},{{$sold[7]}},{{$sold[8]}},{{$sold[9]}},{{$sold[10]}},{{$sold[11]}},{{$sold[12]}}],
                            "backgroundColor": ["rgba(55, 125, 255, 0)", "rgba(255, 255, 255, 0)"],
                            "borderColor": "green",
                            "borderWidth": 2,
                            "pointRadius": 0,
                            "pointBorderColor": "#fff",
                            "pointBackgroundColor": "green",
                            "pointHoverRadius": 0,
                            "hoverBorderColor": "#fff",
                            "hoverBackgroundColor": "#377dff"
                          },
                          {
                            "data": [{{$tax[1]}},{{$tax[2]}},{{$tax[3]}},{{$tax[4]}},{{$tax[5]}},{{$tax[6]}},{{$tax[7]}},{{$tax[8]}},{{$tax[9]}},{{$tax[10]}},{{$tax[11]}},{{$tax[12]}}],
                            "backgroundColor": ["rgba(0, 201, 219, 0)", "rgba(255, 255, 255, 0)"],
                            "borderColor": "#ec9a3c",
                            "borderWidth": 2,
                            "pointRadius": 0,
                            "pointBorderColor": "#fff",
                            "pointBackgroundColor": "#ec9a3c",
                            "pointHoverRadius": 0,
                            "hoverBorderColor": "#fff",
                            "hoverBackgroundColor": "#00c9db"
                          }]
                        },
                        "options": {
                          "gradientPosition": {"y1": 200},
                           "scales": {
                              "yAxes": [{
                                "gridLines": {
                                  "color": "#e7eaf3",
                                  "drawBorder": false,
                                  "zeroLineColor": "#e7eaf3"
                                },
                                "ticks": {
                                  "min": 0,
                                  "max": {{\App\CentralLogics\Helpers::max_earning()}},
                                  "stepSize": {{round(\App\CentralLogics\Helpers::max_earning()/5)}},
                                  "fontColor": "#97a4af",
                                  "fontFamily": "Open Sans, sans-serif",
                                  "padding": 10,
                                  "postfix": " {{\App\CentralLogics\Helpers::currency_symbol()}}"
                                }
                              }],
                              "xAxes": [{
                                "gridLines": {
                                  "display": false,
                                  "drawBorder": false
                                },
                                "ticks": {
                                  "fontSize": 12,
                                  "fontColor": "#97a4af",
                                  "fontFamily": "Open Sans, sans-serif",
                                  "padding": 5
                                }
                              }]
                          },
                          "tooltips": {
                            "prefix": "",
                            "postfix": "",
                            "hasIndicator": true,
                            "mode": "index",
                            "intersect": false,
                            "lineMode": true,
                            "lineWithLineColor": "rgba(19, 33, 68, 0.075)"
                          },
                          "hover": {
                            "mode": "nearest",
                            "intersect": true
                          }
                        }
                      }'>
                    </canvas>
                </div>
            </div>
        
    </div>
