@extends('layouts.main')
@section('title', __('Dashboard'))
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
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- <a href="#" data-toggle="modal" data-target="#stock-form" onclick="stockForm(1)"> -->
                <a {{ (Route::current()->getName() == 'products.wip')? 'active':''}}" href="{{ route('products.wip') }}">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <p>Product</p>
                            <h3>Order Product</h3>
                        </div>
                        <div class="icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-6">
                <!-- <a href="#" data-toggle="modal" data-target="#stock-form" onclick="stockForm(1)"> -->
                <a {{ (Route::current()->getName() == 'products.stock.order')? 'active':''}}" href="{{ route('products.stock.order') }}">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <p>Stock</p>
                            <h3>Product List</h3>
                        </div>
                        <div class="icon">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-6">
                <a href="#" data-toggle="modal" data-target="#stock-form" onclick="stockForm(0)">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <p>Stock</p>
                            <h3>Check Out</h3>
                        </div>
                        <div class="icon">
                            <i class="fas fa-box-open"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-6">
                <a href="{{ route('products.stock.history') }}">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <p>Stock</p>
                            <h3>History</h3>
                        </div>
                        <div class="icon">
                            <i class="fas fa-history"></i>
                        </div>
                    </div>
                </a>
            </div>
            <!-- <div class="col-lg-3 col-6">
                <a href="{{ route('products.categories') }}">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <p>Product</p>
                            <h3>Categories</h3>
                        </div>
                        <div class="icon">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                    </div>
                </a>
            </div> -->
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
                                <div class="form-group row">
                                    <label for="installation_date" class="col-sm-4 col-form-label">{{ __('Installation Date') }}</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control" id="installation_date" name="installation_date">
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
</section>
@endsection
@section('custom-js')
    <script src="/plugins/toastr/toastr.min.js"></script>
    <script src="/plugins/select2/js/select2.full.min.js"></script>
    <script>
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
                            // $('#prequester').val(data.data.requester);
                            $('#pquantity').val(data.data.quantity);
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
    </script>
@endsection
