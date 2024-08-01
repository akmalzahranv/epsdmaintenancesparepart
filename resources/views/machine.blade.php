@extends('layouts.main')
@section('title', __('Product Categories'))
@section('custom-css')
    <link rel="stylesheet" href="/plugins/toastr/toastr.min.css">
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
                @if(Auth::user()->role == 0)
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-machine" onclick="addMachine()"><i class="fas fa-plus"></i> Add New Machine</button>
                @endif
                <div class="card-tools">
                    <form>
                        <div class="input-group input-group">
                            <input type="text" class="form-control" name="q" placeholder="Search">
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
            <div class="form-group row col-sm-3">
                    <label for="sort" class="col-sm-3 col-form-label">Line</label>
                    <div class="col-sm-9">
                        <form id="line_filter_form" action="" method="get">
                            <input type="hidden" name="q" value="{{ Request::get('q') }}">
                            <select class="form-control select2" style="width: 100%;" id="line_filter" name="line">
                                <option value="" {{ Request::get('line') == '' ? 'selected' : '' }}>.:: Select Line ::.</option>
                                @foreach($line as $lines)
                                    <option value="{{ $lines->line_id }}" {{ Request::get('line') == $lines->line_id ? 'selected' : '' }}>{{ $lines->line_name }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>      
                <table id="table" class="table table-sm table-bordered table-hover table-striped">
                <thead>
                    <tr class="text-center">
                        <th>No.</th>
                        <th>{{ __('Line') }}</th>
                        <th>{{ __('Machine Name') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @if(count($machine) > 0)
                    @foreach($machine as $key => $d)
                    @php
                        $data = ["machine_id" => $d->machine_id, "line_id" => $d->line_id, "line_name" => $d->line_name, "machine_name" => $d->machine_name];
                    @endphp
                    <tr>
                        <td class="text-center">{{ $machine->firstItem() + $key }}</td>
                        <td>{{ $data['line_name'] }}</td>
                        <td>{{ $data['machine_name'] }}</td>
                        <td class="text-center">                            
                            </button> @if(Auth::user()->role == 0)<button title="Edit Line" type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#add-machine" onclick="editMachine({{ json_encode($data) }})"><i class="fas fa-edit"></i></button> 
                            <button title="Hapus Produk" type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delete-machine" onclick="deleteMachine({{ json_encode($data) }})"><i class="fas fa-trash"></i></button>@endif</td>
                    </tr>
                    @endforeach
                @else
                    <tr class="text-center">
                        <td colspan="3">{{ __('No data.') }}</td>
                    </tr>
                @endif
                </tbody>
                </table>
            </div>
        </div>
        <div>
        {{ $machine->links("pagination::bootstrap-4") }}
        </div>
    </div>
    @if(Auth::user()->role == 0)
    <div class="modal fade" id="add-machine">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modal-title" class="modal-title">{{ __('Add New Machine') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" id="save" action="{{ route('products.machine.save') }}" method="post">
                        @csrf
                        <input type="hidden" id="machine_id" name="machine_id">                       
                        <div class="form-group row">
                            <label for="line" class="col-sm-4 col-form-label">Line</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;" id="line" name="line">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="machine_name" class="col-sm-4 col-form-label">{{ __('Machine Name') }}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="machine_name" name="machine_name">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button id="button-save" type="button" class="btn btn-primary" onclick="$('#save').submit();">{{ __('Add') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="delete-machine">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modal-title" class="modal-title">{{ __('Delete Machine') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" id="delete" action="{{ route('products.machine.delete') }}" method="post">
                        @csrf
                        @method('delete')
                        <input type="hidden" id="delete_id" name="delete_id">
                    </form>
                    <div>
                        <p>Anda yakin ingin menghapus machine <span id="delete_name" class="font-weight-bold"></span>?</p>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Batal') }}</button>
                    <button id="button-save" type="button" class="btn btn-danger" onclick="$('#delete').submit();">{{ __('Ya, hapus') }}</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</section>
@endsection
@section('custom-js')
    <script src="/plugins/toastr/toastr.min.js"></script>
    <script src="/plugins/select2/js/select2.full.min.js"></script>
    <script>        
        $('#line_filter').change(function() {
            $('#line_filter_form').submit();
        });

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
        function resetForm(){
            $('#save').trigger("reset");
            $('#machine_id').val('');
        }

        function addMachine(){
            resetForm();
            $('#modal-title').text("Add New Machine");
            $('#button-save').text("Add");
            getLine();
        }

        function editMachine(data){
            resetForm();
            $('#modal-title').text("Edit Machine");
            $('#button-save').text("Simpan");
            $('#machine_id').val(data.machine_id);            
            getLine(data.line_id);            
            $('#machine_name').val(data.machine_name);
        }

        function deleteMachine(data){
            $('#delete_id').val(data.machine_id);
            $('#delete_name').text(data.machine_name);
        }
    </script>
    <script src="/plugins/toastr/toastr.min.js"></script>
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