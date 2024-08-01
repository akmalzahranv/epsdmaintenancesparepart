@extends('layouts.main')
@section('title', __('Products'))
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
                <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-product" onclick="addProduct()"><i class="fas fa-plus"></i> Add New Product</button> -->
                <div class="card-tools">
                    <form>
                        <div class="input-group input-group">
                            <input type="text" class="form-control" name="q" placeholder="Search">
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
            <div class="card-body row">
                <!-- <div class="form-group row col-sm-6">
                    <label for="sort" class="col-sm-2 col-form-label">Sort Name</label>
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
                    <div class="col-sm-2"></div>
                </div>
                <div class="form-group row col-sm-6">
                    <label for="sort" class="col-sm-2 col-form-label">Category</label>
                    <div class="col-sm-8">
                        <form id="category_filter_form" action="" method="get">
                            <input type="hidden" name="q" value="{{ Request::get('q') }}">
                            <select class="form-control select2" style="width: 100%;" id="category_filter" name="category">
                                <option value="" {{ Request::get('category') == '' ? 'selected' : '' }}>.:: Select Category ::.</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->category_id }}" {{ Request::get('category') == $category->category_id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <div class="col-sm-2"></div>
                </div>        -->
                <div class="form-group row col-sm-6">
                    <label for="sort" class="col-sm-2 col-form-label">Line</label>
                    <div class="col-sm-8">
                        <form id="line_filter_form" action="" method="get">
                            <input type="hidden" name="q" value="{{ Request::get('q') }}">
                            <select class="form-control select2" style="width: 100%;" id="line_filter" name="line">
                                <option value="" {{ Request::get('line') == '' ? 'selected' : '' }}>.:: Select Line ::.</option>
                                @foreach($lines as $line)
                                    <option value="{{ $line->line_id }}" {{ Request::get('line') == $line->line_id ? 'selected' : '' }}>{{ $line->line_name }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <div class="col-sm-2"></div>
                </div>        
                <div class="form-group row col-sm-6">
                    <label for="sort" class="col-sm-2 col-form-label">Machine</label>
                    <div class="col-sm-8">
                        <form id="machine_filter_form" action="" method="get">
                            <input type="hidden" name="q" value="{{ Request::get('q') }}">
                            <select class="form-control select2" style="width: 100%;" id="machine_filter" name="line">
                                <option value="" {{ Request::get('machine') == '' ? 'selected' : '' }}>.:: Select Machine ::.</option>
                                @foreach($machines as $machine)
                                    <option value="{{ $machine->machine_id }}" {{ Request::get('machine') == $machine->machine_id ? 'selected' : '' }}>{{ $machine->machine_name }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <div class="col-sm-2"></div>
                </div>         
                <br><br><br>
                <div class="table-responsive">
                    <table id="table" class="table table-sm table-bordered table-hover table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>No.</th>
                                <th>{{ __('Action') }}</th>   
                                <th>{{ __('Barcode') }}</th>
                                <th>{{ __('Product Name') }}</th>
                                <th >{{ __('Problem Details') }}</th>
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
                                <th>{{ __('Stock') }}</th>                                                             
                            </tr>
                        </thead>
                        <tbody>
                        @if(count($products) > 0)
                            @foreach($products as $key => $d)
                            @php
                                $data = [
                                            "no"        => $products->firstItem() + $key,
                                            "pid"       => $d->product_id,
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
                                            "machine_name" => $d->machine_name,
                                            "prequest_date" => $d->request_date,
                                            "prequester" => $d->requester,
                                            "porder_date" => $d->order_date === null ? "-" : $d->order_date,
                                            "psupplier" => $d->supplier === null ? "-" : $d->supplier,
                                            "pestimate_time" => $d->estimate_time === null || $d->estimate_time === '0000-00-00' ? "-" : $d->estimate_time,
                                            "parrival_time" => $d->arrival_time === null || $d->arrival_time === '0000-00-00' ? "-" : $d->arrival_time,
                                            "pinstallation_planning_schedule" => $d->installation_planning_schedule === '0000-00-00' || $d->installation_planning_schedule === null ? "-" : $d->installation_planning_schedule,
                                            "pinstallation_date" => $d->installation_date === '0000-00-00' || $d->installation_date === null ? "-" : $d->installation_date,     
                                        ];
                            @endphp
                            <tr>
                                <td style="min-width: 140px;" class="text-center">{{ $data['no'] }}</td>
                                <td style="min-width: 140px;" class="text-center">
                                <button title="Edit Produk" type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#add-product" onclick="editProduct({{ json_encode($data) }})"><i class="fas fa-edit"></i></button>                                     
                                <button title="Lihat Barcode" type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#lihat-barcode" onclick="barcode({{ json_encode($data['pcode']) }})"><i class="fas fa-barcode"></i></button>@if(Auth::user()->role == 0)
                                <button title="Hapus Produk" type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delete-product" onclick="deleteProduct({{ json_encode($data) }})"><i class="fas fa-trash"></i></button>@endif</td>                                
                                <td style="min-width: 140px;" class="text-center">{{ $data['pcode'] }}</td>
                                <td style="min-width: 140px;">{{ $data['pname'] }}</td>
                                <td style="min-width: 140px;">{{ $data['pdetails'] }}</td>
                                <td style="min-width: 140px;">{{ $data['pspecification'] }}</td>
                                <td style="min-width: 140px;">{{ $data['pmaker'] }}</td>
                                <td style="min-width: 140px;">{{ $data['pitem_no'] }}</td>
                                <td style="min-width: 140px;" class="text-center">{{ $data['pquantity'] }}</td>
                                <td style="min-width: 140px;">{{ $data['shelf_name'] }}</td>
                                <td style="min-width: 140px;">{{ $data['category_name'] }}</td>
                                <td style="min-width: 140px;">{{ $data['line_name'] }}</td>
                                <td style="min-width: 140px;">{{ $data['machine_name'] }}</td>
                                <td style="min-width: 140px;" class="text-center">{{ $data['prequest_date'] }}</td>
                                <td style="min-width: 140px;">{{ $data['prequester'] }}</td>
                                <td style="min-width: 140px;" class="text-center">{{ $data['porder_date'] }}</td>
                                <td style="min-width: 140px;">{{ $data['psupplier'] }}</td>
                                <td style="min-width: 140px;" class="text-center">{{ $data['pestimate_time'] }}</td>
                                <td style="min-width: 140px;" class="text-center">{{ $data['parrival_time'] }}</td>
                                <td style="min-width: 140px;" class="text-center">{{ $data['pinstallation_planning_schedule'] }}</td>
                                <td style="min-width: 140px;" class="text-center">{{ $data['pinstallation_date'] }}</td>         
                                <td style="min-width: 140px;"  class="text-center">
                                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#stock-form" onclick="stockForm(1, {{ json_encode($data['pcode']) }})"><i class="fas fa-plus"></i></button>
                                    <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#stock-form" onclick="stockForm(0, {{ json_encode($data['pcode']) }})"><i class="fas fa-minus"></i></button>
                                    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#stock-form" onclick="stockForm(1)"><i class="fas fa-plus"></i> Stock In</button> -->
                                </td>                                                                                      
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
        {{ $products->appends(request()->except('page'))->links("pagination::bootstrap-4") }}
        </div>
    </div>
    <div class="modal fade" id="stock-form">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modal-title" class="modal-title">{{ __('Stock In') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <img width="140px" src="/img/barcode_scanner.png"/>
                    </div>
                    <!-- <div class="card">
                        <div class="card-body">
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" id="pcode" name="pcode" min="0" placeholder="Product Code">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" id="button-check" onclick="productCheck()">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div id="loader" class="card">
                        <div class="card-body text-center">
                            <div class="spinner-border text-danger" style="width: 3rem; height: 3rem;" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div id="form" class="card">
                        <div class="card-body">
                            <form role="form" id="stock-update" method="post">
                                @csrf
                                <input type="hidden" id="pid" name="pid">
                                <input type="hidden" id="type" name="type">
                                <div class="form-group row">
                                    <label for="pcodee" class="col-sm-4 col-form-label">{{ __('Product Code') }}</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="pcodee" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="pname" class="col-sm-4 col-form-label">{{ __('Product Name') }}</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="pname" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="pquantity" class="col-sm-4 col-form-label">{{ __('Amount Now') }}</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" id="pquantity" name="pquantity" min="1" value="1" disabled>
                                    </div>
                                </div>        
                                <div class="form-group row">
                                    <label for="sname" class="col-sm-4 col-form-label">{{ __('Shelf Name') }}</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="sname" disabled>                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="prequester" class="col-sm-4 col-form-label">{{ __('User Name') }}</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="prequester" name="prequester" min="1">
                                    </div>
                                </div>         
                                <div class="form-group row" id="shelf_hide">
                                    <label for="shelf_id" class="col-sm-4 col-form-label">Shelf <span style="color: red;"> *</span></label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2" style="width: 100%;" id="shelf_id" name="shelf_id">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" id="installation_datee">
                                    <label for="installation_date" class="col-sm-4 col-form-label">{{ __('Installation Date') }}</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control" id="installation_date" name="installation_date">
                                    </div>
                                </div>     
                                <div class="form-group row">
                                    <label for="pamount" class="col-sm-4 col-form-label">{{ __('Amount') }}</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" id="pamount" name="pamount" min="1" value="1">
                                    </div>
                                </div>                                
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
                    <button id="button-update" type="button" class="btn btn-primary" onclick="stockUpdate()">{{ __('Stock In') }}</button>
                </div>
            </div>
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
                    <form role="form" id="save" action="{{ route('products.save') }}" method="post">
                        @csrf
                        <input type="hidden" id="save_id" name="id">                                       
                        <div class="form-group row">
                            <label for="product_name" class="col-sm-4 col-form-label">{{ __('Product Name') }}<span style="color: red;"> *</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="product_name" name="product_name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="problem_details" class="col-sm-4 col-form-label">{{ __('Problem Details') }}<span style="color: red;"> *</span></label>
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
                            <label for="item_no" class="col-sm-4 col-form-label">{{ __('Item No') }}<span style="color: red;"> *</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="item_no" name="item_no">
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <label for="quantity" class="col-sm-4 col-form-label">{{ __('Quantity') }} <span style="color: red;"> *</span></label>
                            <div class="col-sm-8"> -->
                                <input type="hidden" class="form-control" id="quantity" name="quantity">
                            <!-- </div>
                        </div> -->
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
                        <div class="form-group row">
                            <label for="order_date" class="col-sm-4 col-form-label">{{ __('Order Date') }}</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="order_date" name="order_date">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="supplier" class="col-sm-4 col-form-label">{{ __('Supplier') }}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="supplier" name="supplier">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="estimate_time" class="col-sm-4 col-form-label">{{ __('Estimate Time') }}</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="estimate_time" name="estimate_time">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="arrival_time" class="col-sm-4 col-form-label">{{ __('Arrival Time') }}</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="arrival_time" name="arrival_time">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="installation_planning_schedule" class="col-sm-4 col-form-label">{{ __('Installation Planning Schedule') }}</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="installation_planning_schedule" name="installation_planning_schedule">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="installation_date" class="col-sm-4 col-form-label">{{ __('Installation Date') }}</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="installation_date" name="installation_date">
                            </div>
                        </div>
                        <div id="barcode_preview_container" class="form-group row">
                            <label class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-8">
                                <img id="barcode_preview"/>
                            </div>
                        </div>                
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button id="button-save" type="button" class="btn btn-primary" onclick="$('#save').submit();">{{ __('Tambahkan') }}</button>
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
                    <form role="form" id="delete" action="{{ route('products.delete') }}" method="post">
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
                    <button id="button-save" type="button" class="btn btn-danger" onclick="document.getElementById('delete').submit();">{{ __('Ya, hapus') }}</button>
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
            $('.select2').select2({
            theme: 'bootstrap4'
            });
        });    

        $(function () {
            $('#form').hide();
            $('#shelf').hide();
            loader(0);
            $('.select2').select2({
                theme: 'bootstrap4'
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        function loader(status=1){
            if(status == 1){
                $('#loader').show();
            } else {
                $('#loader').hide();
            }
        }

        function enableStockInput(){
            $('#button-update').prop("disabled", false);
            $("#button-update").show();
            $('#form').show();
        }

        function disableStockInput(){
            $('#button-update').prop("disabled", true);
            $("#button-update").hide();
            $('#form').hide();
        }

        $('#pcode').on('input', function() {
            $("#form").hide();
            $("#button-update").hide();
        });

        $('#sort').on('change', function() {
            $("#sorting").submit();
        });

        $('#category_filter').change(function() {
            $('#category_filter_form').submit();
        });

        $('#line_filter').change(function() {
            $('#line_filter_form').submit();
        });

        $('#machine_filter').change(function() {
            $('#machine_filter_form').submit();
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
                    $('#shelf_id').empty();
                    $('#shelf_id').append('<option value="">.:: Select Shelf ::.</option>');
                    $.each(data, function(key, value) {
                        var isDisabled = value.used_in_products || value.used_in_products_wip;
                        var optionText = value.shelf_name + (isDisabled ? ' (Shelf full)' : '');
                        var optionHtml = '<option value="' + value.shelf_id + '" ' + (isDisabled ? 'disabled' : '') + (value.shelf_id == val ? ' selected' : '') + '>' + optionText + '</option>';
                        $('#shelf_id').append(optionHtml);
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

        function stockForm(type, pcode){
            $("#form").hide();
            resetForm();
            $("#type").val(type);
            if(type == 1){
                $('#modal-title').text("Stock In");
                $('#installation_datee').hide();
                $('#button-update').text("Stock In");                                   
                if(pcode){
                $('#pcode').val(pcode);
                productCheck("1");    
                }
            } else {
                $('#modal-title').text("Stock Out");
                $('#button-update').text("Stock Out");
                if(pcode){
                $('#pcode').val(pcode);
                productCheck("0");
                }                    
            }
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

        function getShelff(val, exceptProductId = null) {
            $.ajax({
                url: '/products/shelf',
                type: "GET",
                data: {
                    "format": "json",
                    "except_product_id": exceptProductId
                },
                dataType: "json",
                success: function(data) {
                    $('#shelf_id').empty();
                    $('#shelf_id').append('<option value="">.:: Select Shelf ::.</option>');
                    $.each(data, function(key, value) {
                        var isDisabled = value.used_in_products || value.used_in_products_wip;
                        var optionText = value.shelf_name + (isDisabled ? ' (Shelf full)' : '');
                        var optionHtml = '<option value="' + value.shelf_id + '" ' + (isDisabled ? 'disabled' : '') + (value.shelf_id == val ? ' selected' : '') + '>' + optionText + '</option>';
                        $('#shelf_id').append(optionHtml);
                    });
                }
            });
        }

        function productCheck(type_order){
            var pcode = $('#pcode').val();
            if(pcode.length > 0){
                loader();
                $('#form').hide();
                $('#pcode').prop("disabled", true);
                $('#button-check').prop("disabled", true);
                $.ajax({
                    url: '/products/check/'+pcode,
                    type: "GET",
                    data: {"format": "json"},
                    dataType: "json",
                    success:function(data) {
                        loader(0);
                        if(data.status == 1){
                            if (type_order == "1") {
                                $('#pid').val(data.data.product_id);
                                $('#pcodee').val(data.data.product_code);
                                $('#pname').val(data.data.product_name);
                                $('#pquantity').val(data.data.quantity);
                                $('#prequester').val(data.data.requester);
                                $('#sname').val(data.data.shelf_name || 'Kosong');                                
                            } else {
                                $('#pid').val(data.data.product_id);
                                $('#pcodee').val(data.data.product_code);
                                $('#pname').val(data.data.product_name);
                                $('#pquantity').val(data.data.quantity);                                
                                $('#sname').val(data.data.shelf_name || 'Kosong');                                
                            }
                            if (data.data.shelf_name === "" || data.data.shelf_name === null) {
                                getShelff();
                            } else {
                                $('#shelf').hide();
                                $('#shelf_hide').hide();                                
                            }
                            enableStockInput();
                        } else {                             
                            disableStockInput();                             
                            toastr.error("Product Code tidak dikenal!");
                        }
                        $('#pcode').prop("disabled", false);
                        $('#button-check').prop("disabled", false);
                    }, error:function(){
                        $('#pcode').prop("disabled", false);
                        $('#button-check').prop("disabled", false);
                    }
                });
            } else {
                toastr.error("Product Code belum diisi!");
            }
        }

        function stockUpdate(){
            loader();
            $('#pcode').prop("disabled", true);
            $('#button-check').prop("disabled", true);
            $('#button-update').prop("disabled", true);
            disableStockInput();
            var data = {
                product_id:$('#pid').val(),
                amount:$('#pamount').val(),
                shelf:$('#shelf_id').val(),
                type:$('#type').val(),
                username:$('#prequester').val(),
                installation_date:$('#installation_date').val(),
            }
            
            $.ajax({
                url: '/products/stockUpdate',
                type: "post",
                data: JSON.stringify(data),
                dataType: "json",
                contentType: 'application/json',
                success:function(data) {
                    loader(0);
                    if(data.status == 1){
                        toastr.success(data.message);
                        resetForm();
                    } else {
                        toastr.error(data.message);
                        enableStockInput();
                        $('#pcode').prop("disabled", false);
                        $('#button-check').prop("disabled", false);
                    }
                }, error:function(){
                    loader(0);
                    toastr.error("Unknown error! Please try again later!");
                    resetForm();
                }
            });
        }

        function resetForm(){
            $('#save').trigger("reset");
            $('#barcode_preview_container').hide();
        }

        function addProduct(){
            $('#modal-title').text("Add New Product");
            $('#button-save').text("Tambahkan");
            resetForm();
            getCategory();
        }

        function editProduct(data){
            $('#modal-title').text("Edit Product");
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
            $('#order_date').val(data.porder_date);
            $('#supplier').val(data.psupplier);
            $('#estimate_time').val(data.pestimate_time);
            $('#arrival_time').val(data.parrival_time);
            $('#installation_planning_schedule').val(data.pinstallation_planning_schedule);
            $('#installation_date').val(data.pinstallation_date);
            $('#status').val(data.pstatus);
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
