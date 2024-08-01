@extends('layouts.main')
@section('title', __('List Order'))
@section('custom-css')
@section('custom-css')
    <link rel="stylesheet" href="/plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    
    <style>
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        #table {
            width: 100%;
            white-space: nowrap;
        }
        #table th,
        #table td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            min-width: 120px;
            max-width: 200px; 
        }
    </style>
@endsection
@section('content')
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
        </div>
        </div>
    </div>
    <section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-product" onclick="addProduct()"><i class="fas fa-plus"></i> Add New Order</button>
                <div class="card-tools">
                    <form>
                        <div class="input-group input-group">
                            <input type="text" class="form-control" name="q" placeholder="Search">
                            <input type="hidden" name="category" value="{{ Request::get('category') }}">
                            <input type="hidden" name="sort" value="{{ Request::get('sort') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group row col-sm-2">
                    <label for="sort" class="col-sm-4 col-form-label">Sort</label>
                    <div class="col-sm-8">
                        <form id="sorting" action="" method="get">
                            <input type="hidden" name="q" value="{{ Request::get('q') }}">
                            <input type="hidden" name="category" value="{{ Request::get('category') }}">
                            <select class="form-control select2" style="width: 100%;" id="sort" name="sort">
                                <option value="" {{ Request::get('sort') == null? 'selected':'' }}>None</option>
                                <option value="asc" {{ Request::get('sort') == 'asc'? 'selected':'' }}>Ascending</option>
                                <option value="desc" {{ Request::get('sort') == 'desc'? 'selected':'' }}>Descending</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div>
                    <p style=""><i style="color:orange" class="fa fa-certificate"></i> Change Status to Ordered</p>
                    <p style="margin-bottom:20px;"><i style="color:green" class="fa fa-circle"></i> Change Status to Received</p>
                </div>                
                <div class="table-responsive">
                <table id="table" class="table table-sm table-bordered table-hover table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>No.</th>
                                <th>{{ __('Action') }}</th>                                
                                <th>{{ __('Barcode') }}</th>
                                <th>{{ __('Product Name') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Problem Details') }}</th>
                                <th>{{ __('Specification') }}</th>
                                <th>{{ __('Maker') }}</th>
                                <th>{{ __('Item No') }}</th>
                                <th>{{ __('Quantity') }}</th>
                                <th>{{ __('Shelf') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Line') }}</th>
                                <th>{{ __('Machine') }}</th>
                                <th>
                                    {{ __('Request Date') }}
                                    <button type="button" class="btn btn-sm btn-link" id="date-sort-btn">
                                        <i class="fas fa-sort"></i>
                                    </button>
                                </th>
                                <th>{{ __('Requester') }}</th>
                                <th>{{ __('Order Date') }}</th>
                                <th>{{ __('Supplier') }}</th>
                                <th>{{ __('Estimate Time') }}</th>
                                <th>{{ __('Arrival Time') }}</th>
                                <th>{{ __('Installation Planning Schedule') }}</th>
                                <th>{{ __('Installation Date') }}</th>                                                               
                            </tr>
                        </thead>
                        <tbody>
                        @if(count($products) > 0)
                            @foreach($products as $key => $d)
                            @php
                                $data = [
                                            "no"        => $products->firstItem() + $key,
                                            "pid"       => $d->product_wip_id,
                                            "pcode"     => $d->product_code,
                                            "pname"     => $d->product_name,
                                            "pdetails"     => $d->problem_details,
                                            "pspecification"     => $d->specification,
                                            "pmaker"     => $d->maker === null ? "-" : $d->maker,
                                            "pitem_no"     => $d->item_no === null ? "-" : $d->item_no,
                                            "pquantity"   => $d->quantity,
                                            "shelf_id" => $d->shelf_id,
                                            "shelf_name" => $d->shelf_name,
                                            "category_id" => $d->category_id,
                                            "category_name" => $d->category_name,
                                            "line_id" => $d->line_id,
                                            "line_name" => $d->line_name,
                                            "machine_id" => $d->machine_id,
                                            "machine_name" => $d->machine_name === null ? "-" : $d->machine_name,
                                            "prequest_date" => $d->request_date,
                                            "prequester" => $d->requester,
                                            "porder_date" => $d->order_date === null ? "-" : $d->order_date,
                                            "psupplier" => $d->supplier === null ? "-" : $d->supplier,
                                            "pestimate_time" => $d->estimate_time === null || $d->estimate_time === '0000-00-00' ? "-" : $d->estimate_time,
                                            "parrival_time" => $d->arrival_time === null || $d->arrival_time === '0000-00-00' ? "-" : $d->arrival_time,
                                            "pinstallation_planning_schedule" => $d->installation_planning_schedule === '0000-00-00' || $d->installation_planning_schedule === null ? "-" : $d->installation_planning_schedule,
                                            "pinstallation_date" => $d->installation_date === '0000-00-00' || $d->installation_date === null ? "-" : $d->installation_date,     
                                            "pstatus" => $d->status,
                                        ];
                            @endphp
                            <tr>
                                <td style="min-width: 100px;" class="text-center">{{ $data['no'] }}</td>
                                <td style="min-width: 100px;" class="text-center">
                                <button title="Edit Produk" type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#add-product" onclick="editProduct({{ json_encode($data) }})"><i class="fas fa-edit"></i></button>                                     
                                <button title="Lihat Barcode" type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#lihat-barcode" onclick="barcode({{ json_encode($data['pcode']) }})"><i class="fas fa-barcode"></i></button>@if(Auth::user()->role == 0)
                                <button title="Hapus Produk" type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delete-product" onclick="deleteProduct({{ json_encode($data) }})"><i class="fas fa-trash"></i></button>@endif</td>
                                <td style="min-width: 100px;" class="text-center">{{ $data['pcode'] }}</td>
                                <td style="min-width: 100px;">
                                    {{ $data['pname'] }}                                    
                                        @if($data['pstatus'] == 'Requested')
                                            <button style="padding-left:5px; border: none; background: none;" data-toggle="modal" data-target="#change-status-ordered" onclick="changeStatusOrdered({{ json_encode($data) }})">
                                            <i style="color:orange" class="fa fa-certificate"></i>
                                            </button>
                                        @else
                                            <button style="padding-left:5px; border: none; background: none;" data-toggle="modal" data-target="#change-status-received" onclick="changeStatusReceived({{ json_encode($data) }})">
                                            <i style="color:green" class="fa fa-circle"></i>
                                        </button>                                       
                                        @endif                                                                  
                                </td>                                
                                <td style="min-width: 100px;"> {{ $data['pstatus']}}</td>
                                <td style="min-width: 100px;">{{ $data['pdetails'] }}</td>
                                <td style="min-width: 100px;">{{ $data['pspecification'] }}</td>
                                <td style="min-width: 100px;">{{ $data['pmaker'] }}</td>
                                <td style="min-width: 100px;">{{ $data['pitem_no'] }}</td>
                                <td style="min-width: 100px;" class="text-center">{{ $data['pquantity'] }}</td>
                                <td style="min-width: 100px;">{{ $data['shelf_name'] }}</td>
                                <td style="min-width: 100px;">{{ $data['category_name'] }}</td>
                                <td style="min-width: 100px;">{{ $data['line_name'] }}</td>
                                <td style="min-width: 100px;">{{ $data['machine_name'] }}</td>
                                <td style="min-width: 100px;" class="text-center">{{ $data['prequest_date'] }}</td>
                                <td style="min-width: 100px;">{{ $data['prequester'] }}</td>
                                <td style="min-width: 100px;" class="text-center">{{ $data['porder_date'] }}</td>
                                <td style="min-width: 100px;">{{ $data['psupplier'] }}</td>
                                <td style="min-width: 100px;" class="text-center">{{ $data['pestimate_time'] }}</td>
                                <td style="min-width: 100px;" class="text-center">{{ $data['parrival_time'] }}</td>
                                <td style="min-width: 100px;" class="text-center">{{ $data['pinstallation_planning_schedule'] }}</td>
                                <td style="min-width: 100px;" class="text-center">{{ $data['pinstallation_date'] }}</td>        
                                <!-- <td>{{ $data['pstatus'] }}</td> -->                                                                
                            </tr>
                            @endforeach
                        @else
                            <tr class="text-center">
                                <td colspan="24">{{ __('No data.') }}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div>
        <!-- {{ $products->appends(request()->except('page'))->links("pagination::bootstrap-4") }} -->
        </div>
    </div>    
    <div class="modal fade" id="add-product">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modal-title" class="modal-title">{{ __('Add New Order') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" id="save" action="{{ route('products.wip.save') }}" method="post">
                        @csrf
                        <input type="hidden" id="save_id" name="id">                                       
                        <div class="form-group row">
                            <label for="product_name" class="col-sm-4 col-form-label">{{ __('Product Name') }}<span style="color: red;"> *</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="product_name" name="product_name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="problem_details" class="col-sm-4 col-form-label">{{ __('Problem Details / Untuk Stock') }}<span style="color: red;"> *</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="problem_details" name="problem_details">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="specification" class="col-sm-4 col-form-label">{{ __('Specification') }}<span style="color: red;"> *</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="specification" name="specification">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="maker" class="col-sm-4 col-form-label">{{ __('Maker') }}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="maker" name="maker">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="item_no" class="col-sm-4 col-form-label">{{ __('Item No') }}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="item_no" name="item_no">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="quantity" class="col-sm-4 col-form-label">{{ __('Quantity') }} <span style="color: red;"> *</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="quantity" name="quantity">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="shelf" class="col-sm-4 col-form-label">Shelf <span style="color: red;"> *</span></label>
                            <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;" id="shelf" name="shelf">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="category" class="col-sm-4 col-form-label">Category <span style="color: red;"> *</span></label>
                            <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;" id="category" name="category">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="line" class="col-sm-4 col-form-label">Line <span style="color: red;"> *</span></label>
                            <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;" id="line" name="line">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="machine" class="col-sm-4 col-form-label">Machine <span style="color: red;"> *</span></label>
                            <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;" id="machine" name="machine">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="request_date" class="col-sm-4 col-form-label">{{ __('Request Date') }} <span style="color: red;"> *</span></label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="request_date" name="request_date">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="requester" class="col-sm-4 col-form-label">{{ __('Requester') }} <span style="color: red;"> *</span></label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control" id="requester" name="requester">
                            </div>
                        </div>
                        <div class="form-group row" id="order_date">
                            <label for="order_date" class="col-sm-4 col-form-label">{{ __('Order Date') }}</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="order_date" name="order_date">
                            </div>
                        </div>
                        <div class="form-group row" id="supplier">
                            <label for="supplier" class="col-sm-4 col-form-label">{{ __('Supplier') }}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="supplier" name="supplier">
                            </div>
                        </div>
                        <div class="form-group row" id="estimate_time">
                            <label for="estimate_time" class="col-sm-4 col-form-label">{{ __('Estimate Time') }}</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="estimate_time" name="estimate_time">
                            </div>
                        </div>
                        <div class="form-group row" id="arrival_time">
                            <label for="arrival_time" class="col-sm-4 col-form-label">{{ __('Arrival Time') }}</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="arrival_time" name="arrival_time">
                            </div>
                        </div>
                        <div class="form-group row" id="installation_planning_schedule">
                            <label for="installation_planning_schedule" class="col-sm-4 col-form-label">{{ __('Installation Planning Schedule') }}</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="installation_planning_schedule" name="installation_planning_schedule">
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <label for="installation_date" class="col-sm-4 col-form-label">{{ __('Installation Date') }}</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="installation_date" name="installation_date">
                            </div>
                        </div> -->
                        <input type="hidden" class="form-control" id="installation_date" name="installation_date">
                        <!-- <div id="barcode_preview_container" class="form-group row">
                            <label class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-8">
                                <img id="barcode_preview"/>
                            </div>
                        </div>                 -->
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button id="button-save" type="button" class="btn btn-primary" onclick="$('#save').submit();">{{ __('Tambahkan') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="change-status-ordered">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modal-title" class="modal-title">{{ __('Change Status to Ordered') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">            
                    <form role="form" id="save_ordered" action="{{ route('products.status.ordered') }}" method="post">
                        @csrf                        
                        <input type="hidden" id="ordered_save_id" name="ordered_save_id">    
                        <div class="form-group row">
                            <label for="ordered_product_code" class="col-sm-4 col-form-label">Product Code</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="ordered_product_code" name="ordered_product_code" disabled>
                            </div>
                        </div>                                                                            
                        <div class="form-group row">
                            <label for="ordered_order_date" class="col-sm-4 col-form-label">{{ __('Order Date') }}</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="ordered_order_date" name="ordered_order_date">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ordered_supplier" class="col-sm-4 col-form-label">{{ __('Supplier') }}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="ordered_supplier" name="ordered_supplier">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ordered_estimate_time" class="col-sm-4 col-form-label">{{ __('Estimate Time') }}</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="ordered_estimate_time" name="ordered_estimate_time">
                            </div>
                        </div>                                               
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Batal') }}</button>
                    <button id="button-save" type="button" class="btn btn-primary" onclick="$('#save_ordered').submit();">{{ __('Tambahkan') }}</button>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="change-status-received">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modal-title" class="modal-title">{{ __('Change Status to Received') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">            
                    <form role="form" id="save_received" action="{{ route('products.status.received') }}" method="post">
                        @csrf                        
                        <input type="hidden" id="received_save_id" name="received_save_id">    
                        <div class="form-group row">
                            <label for="received_product_code" class="col-sm-4 col-form-label">Product Code</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="received_product_code" name="received_product_code" disabled>
                            </div>
                        </div>                                                                            
                        <div class="form-group row">
                            <label for="received_arrival_time" class="col-sm-4 col-form-label">{{ __('Arrival Time') }}</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="received_arrival_time" name="received_arrival_time">
                            </div>
                        </div>                        
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Batal') }}</button>
                    <button id="button-save" type="button" class="btn btn-primary" onclick="$('#save_received').submit();">{{ __('Tambahkan') }}</button>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="delete-product">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modal-title" class="modal-title">{{ __('Delete Product') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" id="delete" action="{{ route('products.wip.delete') }}" method="post">
                        @csrf
                        @method('delete')
                        <input type="hidden" id="delete_id" name="id">
                    </form>
                    <div>
                        <p>Anda yakin ingin menghapus product code <span id="pcode" class="font-weight-bold"></span>?</p>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Batal') }}</button>
                    <button id="button-save" type="button" class="btn btn-danger" onclick="$('#delete').submit();">{{ __('Ya, hapus') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="lihat-barcode">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modal-title" class="modal-title">{{ __('Barcode') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <input type="hidden" id="pcode_print">
                        <img id="barcode"/>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Tutup') }}</button>
                    <button type="button" class="btn btn-primary" onclick="printBarcode()">{{ __('Print Barcode') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="wip-complete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modal-title" class="modal-title">{{ __('Selesai') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" id="complete" action="{{ route('products.wip.complete') }}" method="post">
                        @csrf
                        <input type="hidden" id="wip_id" name="wip_id">
                    </form>
                    <div>
                        <p>Anda yakin WIP product code <span id="wip_pcode" class="font-weight-bold"></span> sudah selesai?</p>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Batal') }}</button>
                    <button id="button-save" type="button" class="btn btn-success" onclick="$('#complete').submit();">{{ __('Ya') }}</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('custom-js')
    <script src="/plugins/toastr/toastr.min.js"></script>
    <script src="/plugins/select2/js/select2.full.min.js"></script>
    <script>        
        $(document).ready(function() {
            var currentDateSort = '{{ $dateSort }}';

            $('#date-sort-btn').click(function() {
                var newSort;
                if (currentDateSort === 'asc') {
                    newSort = 'desc';
                } else {
                    newSort = 'asc';
                }
                
                var url = new URL(window.location.href);
                url.searchParams.set('date_sort', newSort);
                                
                url.searchParams.delete('sort');

                window.location.href = url.toString();
            });
            
            if (currentDateSort === 'asc') {
                $('#date-sort-btn i').removeClass('fa-sort').addClass('fa-sort-up');
            } else if (currentDateSort === 'desc') {
                $('#date-sort-btn i').removeClass('fa-sort').addClass('fa-sort-down');
            }
        });

        $(function () {
            var user_id;
                $('.select2').select2({
                theme: 'bootstrap4'
            });

            $('#product_code').on('change', function() {
                var code = $('#product_code').val();
                if(code != null && code != ""){
                    $("#barcode_preview").attr("src", "/products/barcode/"+code);
                    $('#barcode_preview_container').show();
                }
            });
        });
        
        $(function () {
            var user_id;
            $('.select2').select2({
            theme: 'bootstrap4'
            });
        });

        $('#sort').on('change', function() {
            $("#sorting").submit();
        });

        function getCategory(val){
            $.ajax({
                url: '/products/categories',
                type: "GET",
                data: {"format": "json"},
                dataType: "json",
                success:function(data) {                    
                    $('#category').empty();
                    $('#category').append('<option value="">.:: Select Category ::.</option>');
                    $.each(data, function(key, value) {
                        if(value.category_id == val){
                            $('#category').append('<option value="'+ value.category_id +'" selected>'+ value.category_name +'</option>');
                        } else {
                            
                            $('#category').append('<option value="'+ value.category_id +'">'+ value.category_name +'</option>');
                        }
                    });
                }
            });
        }

        function getShelf(val, exceptProductId = null) {
            $.ajax({
                url: '/products/shelf',
                type: "GET",
                data: {
                    "format": "json",
                    "except_product_id": exceptProductId
                },
                dataType: "json",
                success: function(data) {
                    $('#shelf').empty();
                    $('#shelf').append('<option value="">.:: Select Shelf ::.</option>');
                    $.each(data, function(key, value) {
                        var isDisabled = value.used_in_products || value.used_in_products_wip;
                        var optionText = value.shelf_name + (isDisabled ? ' (Shelf full)' : '');
                        var optionHtml = '<option value="' + value.shelf_id + '" ' + (isDisabled ? 'disabled' : '') + (value.shelf_id == val ? ' selected' : '') + '>' + optionText + '</option>';
                        $('#shelf').append(optionHtml);
                    });
                }
            });
        }

        function getLine(val){
            $.ajax({
                url: '/products/line',
                type: "GET",
                data: {"format": "json"},
                dataType: "json",
                success:function(data) {                    
                    $('#line').empty();
                    $('#line').append('<option value="">.:: Select Line ::.</option>');
                    $.each(data, function(key, value) {
                        if(value.line_id == val){
                            $('#line').append('<option value="'+ value.line_id +'" selected>'+ value.line_name +'</option>');
                        } else {                            
                            $('#line').append('<option value="'+ value.line_id +'">'+ value.line_name +'</option>');
                        }
                    });
                }
            });
        }
        
        $('#line').on('change', function() {
            var lineId = $(this).val();
            getMachines(lineId);
        });

        function getMachines(lineId, val) {
                $.ajax({
                    url: '/products/machine',
                    type: 'GET',
                    data: { line: lineId, format: 'json' },
                    dataType: 'json',
                    success: function(data) {
                        $('#machine').empty();
                        $('#machine').append('<option value="">.:: Select Machine ::.</option>');
                        $.each(data, function(key, value) {
                            $('#machine').append('<option value="'+ value.machine_id +'">'+ value.machine_name +'</option>');
                        });
                    }
                });
            }    

        function getMachineEdit(lineId, machineId) {
            $.ajax({
                url: '/products/machine',
                type: 'GET',
                data: { line: lineId, format: 'json' },
                dataType: 'json',
                success: function(data) {
                    $('#machine').empty();
                    $('#machine').append('<option value="">.:: Select Machine ::.</option>');
                    $.each(data, function(key, value) {
                        if(value.machine_id == machineId) {
                            $('#machine').append('<option value="'+ value.machine_id +'" selected>'+ value.machine_name +'</option>');
                        } else {
                            $('#machine').append('<option value="'+ value.machine_id +'">'+ value.machine_name +'</option>');
                        }
                    });
                }
            });
        }

        function resetForm(){
            $('#save').trigger("reset");
        }    
        
        function resetFormOrdered(){
            $('#save_ordered').trigger("reset");
        }    

        function addProduct(){
            $('#modal-title').text("Add New Order");
            $('#button-save').text("Tambahkan");
            resetForm();
            getCategory();
            getShelf();
            getLine();
            getMachine();
        }

        function editProduct(data){
            $('#modal-title').text("Edit");
            $('#button-save').text("Simpan");
            resetForm();
            $('#save_id').val(data.pid);
            $('#product_code').val(data.pcode);
            $('#product_name').val(data.pname);
            $('#problem_details').val(data.pdetails);
            $('#specification').val(data.pspecification);
            $('#maker').val(data.pmaker);
            $('#item_no').val(data.pitem_no);
            $('#quantity').val(data.pquantity);            
            getShelf(data.shelf_id, data.pid);   
            getCategory(data.category_id);         
            getLine(data.line_id);         
            getMachineEdit(data.line_id, data.machine_id);               
            $('#request_date').val(data.prequest_date);   
            $('#requester').val(data.prequester);         
            $('#order_date').hide();      
            $('#order_date').val(data.porder_date);
            $('#supplier').hide();      
            $('#supplier').val(data.psupplier);
            $('#estimate_time').hide();      
            $('#estimate_time').val(data.pestimate_time);
            $('#arrival_time').hide();      
            $('#arrival_time').val(data.parrival_time);
            $('#installation_planning_schedule').hide();      
            $('#installation_planning_schedule').val(data.pinstallation_planning_schedule);
            $('#installation_date').hide();      
            $('#installation_date').val(data.pinstallation_date);
            $('#status').val(data.pstatus);
        }

        function changeStatusOrdered(data){
            $('#modal-title').text("Change Status");
            $('#button-save').text("Tambahkan");
            resetFormOrdered();
            $('#ordered_save_id').val(data.pid);
            $('#ordered_product_code').val(data.pcode);          
            $('#ordered_order_date').val(data.porder_date);
            $('#ordered_supplier').val(data.psupplier);
            $('#ordered_estimate_time').val(data.pestimate_time);                        
        }

        function changeStatusReceived(data){
            $('#modal-title').text("Change Status");
            $('#button-save').text("Tambahkan");
            resetFormOrdered();
            $('#received_save_id').val(data.pid);
            $('#received_product_code').val(data.pcode);          
            $('#received_order_date').val(data.porder_date);            
        }

        function updateStatus(data) {
            $.ajax({
                url: '/products/wip/status', 
                type: 'POST',
                data: {
                    datas: data,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {                    
                    toastr.success('Status updated successfully.');                    
                },
                error: function(xhr, status, error) {
                    var response = JSON.parse(xhr.responseText);
                    toastr.error('Failed to update status: ' + response.message + ' (' + response.error + ')');
                }
            });
        }

        function barcode(code){
            $("#pcode_print").val(code);
            $("#barcode").attr("src", "/products/barcode/"+code);
        }

        function printBarcode(){
            var code    = $("#pcode_print").val();
            var url     = "/products/barcode/"+code+"?print=true";
            window.open(url,'window_print','menubar=0,resizable=0');
        }

        function wipComplete(data){
            $('#wip_id').val(data.pid);
            $('#wip_pcode').text(data.pcode);
        }

        function deleteProduct(data){
            $('#delete_id').val(data.pid);
            $('#pcode').text(data.pcode);
        }
    </script>
    @if(Session::has('success'))
        <script>toastr.success('{!! Session::get("success") !!}');</script>
    @endif
    @if(Session::has('error'))
        <script>toastr.error('{!! Session::get("error") !!}');</script>
    @endif
    @if(!empty($errors->all()))
        <script>toastr.error('{!! implode("", $errors->all("<li>:message</li>")) !!}');</script>
    @endif
@endsection