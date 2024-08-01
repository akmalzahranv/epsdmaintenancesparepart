@extends('layouts.main')
@section('title', __('Stock Order'))
@section('custom-css')
    <link rel="stylesheet" href="/plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
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
        
    <!-- <div class="col-lg-3 col-6">
                <a href="#" data-toggle="modal" data-target="#stock-form" onclick="stockForm(1)">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <p>Stock</p>
                            <h3>In</h3>
                        </div>
                        <div class="icon">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </a>
            </div> -->
        <div class="card">
            <div class="card-header">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#stock-form" onclick="stockForm(1)"><i class="fas fa-plus"></i> Stock In</button>
                <div class="card-tools">
                    <form>
                        <div class="input-group input-group">
                            <input type="text" class="form-control" name="search" placeholder="Search" value="{{ Request::get('search') }}">
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
                @if(!empty(Request::get('search')))
                <div class="pb-3">
                    <span>Hasil pencarian:</span> <span class="font-weight-bold">"{{ Request::get('search') }}"</span>
                </div>
                @endif
                <div class="table-responsive">
                    <table id="table" class="table table-sm table-bordered table-hover table-striped">
                        <thead>
                            <tr class="text-center">                                
                                <th>{{ __('Product Code') }}</th>
                                <th>{{ __('Product Name') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Shelf Name') }}</th>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Date') }}</th>                                
                                <th>{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(count($order) > 0)
                            @foreach($order as $key => $d)                         
                                <tr>                                    
                                    <td class="text-center">{{ $d->product_code }}</td>
                                    <td>{{ $d->product_name }}</td>
                                    <td class="text-center">{{ $d->product_amount }}</td>
                                    <td class="text-center">{{ $d->shelf_name }}</td>
                                    <td class="text-center">{{ $d->username }}</td>
                                    <td class="text-center">{{ date('d/m/Y H:i:s', strtotime($d->created_at)) }}</td>                                    
                                    <td>
                                    <select class="form-control select2 status-select" style="width: 100%;"onchange="updateStockStatus({                                        
                                        soid: '{{ $d->stock_order_id }}',            
                                        pid: '{{ $d->product_id }}',      
                                        pcode: '{{ $d->product_code }}',      
                                        pname: '{{ $d->product_name }}',   
                                        pusername: '{{ $d->username }}',   
                                        pamount: '{{ $d->product_amount }}',
                                        shelf_id: '{{ $d->shelf_id }}',                                                                                
                                        status: this.value
                                    })">
                                        <option value="Requested" {{ $d->status == 'Requested' ? 'selected' : '' }}>Requested</option>
                                        <option value="Ordered" {{ $d->status == 'Ordered' ? 'selected' : '' }}>Ordered</option>
                                        <option value="Receive" {{ $d->status == 'Receive' ? 'selected' : '' }}>Receive</option>
                                    </select>
                                </td>
                                    <td class="text-center">
                                    <!-- <button title="Edit Produk" type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#add-product" onclick="editProduct({{ json_encode($d) }})"><i class="fas fa-edit"></i></button>                                                                          -->
                                    <button title="Hapus Produk" type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delete-product" onclick="deleteProduct({{ json_encode($d) }})"><i class="fas fa-trash"></i></button></td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="text-center">
                                <td colspan="9">{{ __('No data.') }}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div>
        {{ $order->links("pagination::bootstrap-4") }}
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
                        <img width="150px" src="/img/barcode_scanner.png"/>
                    </div>
                    <div class="card">
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
                    </div>
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
                                        <input type="text" class="form-control" id="prequester" name="prequester">
                                    </div>
                                </div>        
                                <div class="form-group row" id="shelf_hide">
                                    <label for="shelf_id" class="col-sm-4 col-form-label">Shelf <span style="color: red;"> *</span></label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2" style="width: 100%;" id="shelf_id" name="shelf_id">
                                        </select>
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
                            <label for="specification" class="col-sm-4 col-form-label">{{ __('Specification / Item No.') }}<span style="color: red;"> *</span></label>
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
                    <form role="form" id="delete" action="{{ route('products.stock.order.delete') }}" method="post">
                        @csrf
                        @method('delete')
                        <input type="hidden" id="delete_id" name="id">
                    </form>
                    <div>
                        <p>Anda yakin ingin menghapus stock order ini <span id="pcode" class="font-weight-bold"></span>?</p>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Batal') }}</button>
                    <button id="button-save" type="button" class="btn btn-danger" onclick="$('#delete').submit();">{{ __('Ya, hapus') }}</button>
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
        $(function () {
            $('.select2').select2({
            theme: 'bootstrap4'
            });
        });        

        $('#sort').on('change', function() {
            $("#sorting").submit();
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

        $('#pcode').on('input', function() {
            $("#form").hide();
            $("#button-update").hide();
        });

        function resetForm(){
            $('#form').trigger("reset");
            $('#pcode').val('');
            $("#button-update").hide();
            $('#pcode').prop("disabled", false);
            $('#button-check').prop("disabled", false);
        }

        function stockForm(type=1){
            $("#form").hide();
            resetForm();
            $("#type").val(type);
            if(type == 1){
                $('#modal-title').text("Stock In");
                $('#button-update').text("Stock In");
            } else {
                $('#modal-title').text("Stock Out");
                $('#button-update').text("Stock Out");
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

        function loader(status=1){
            if(status == 1){
                $('#loader').show();
            } else {
                $('#loader').hide();
            }
        }

        function productCheck(){
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
                            $('#pid').val(data.data.product_id);
                            $('#pcode').val(data.data.product_code);
                            $('#pname').val(data.data.product_name);
                            $('#pquantity').val(data.data.quantity);
                            $('#prequester').val(data.data.requester);
                            $('#sname').val(data.data.shelf_name || 'Kosong');
                            if (data.data.shelf_name === "" || data.data.shelf_name === null) {
                                getShelf();
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

        function updateStockStatus(data) {
            $.ajax({
                url: '/products/stockOrder/status', 
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

        function deleteProduct(data){
            $('#delete_id').val(data.stock_order_id);
            $('#pcode').text(data.product_code);
        }
    </script>
@endsection 