@extends('layouts.main')
@section('title', __('Product line'))
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
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-line" onclick="addLine()"><i class="fas fa-plus"></i> Add New Line</button>
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
                <table id="table" class="table table-sm table-bordered table-hover table-striped">
                <thead>
                    <tr class="text-center">
                        <th>No.</th>
                        <th>{{ __('Line Name') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @if(count($line) > 0)
                    @foreach($line as $key => $d)
                    @php
                        $data = ["line_id" => $d->line_id, "line_name" => $d->line_name];
                    @endphp
                    <tr>
                        <td class="text-center">{{ $line->firstItem() + $key }}</td>
                        <td>{{ $data['line_name'] }}</td>
                        <td class="text-center">                    
                        </button> @if(Auth::user()->role == 0)
                        <button title="Edit Line" type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#add-line" onclick="editLine({{ json_encode($data) }})"><i class="fas fa-edit"></i></button> 
                        <button title="Hapus Produk" type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delete-line" onclick="deleteLine({{ json_encode($data) }})"><i class="fas fa-trash"></i></button>@endif</td>
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
        {{ $line->links("pagination::bootstrap-4") }}
        </div>
    </div>
    @if(Auth::user()->role == 0)
    <div class="modal fade" id="add-line">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modal-title" class="modal-title">{{ __('Add New Line') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" id="save" action="{{ route('products.line.save') }}" method="post">
                        @csrf
                        <input type="hidden" id="line_id" name="line_id">
                        <div class="form-group row">
                            <label for="line_name" class="col-sm-4 col-form-label">{{ __('Line Name') }}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="line_name" name="line_name">
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
    <div class="modal fade" id="delete-line">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modal-title" class="modal-title">{{ __('Delete Line') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" id="delete" action="{{ route('products.line.delete') }}" method="post">
                        @csrf
                        @method('delete')
                        <input type="hidden" id="delete_id" name="delete_id">
                    </form>
                    <div>
                        <p>Anda yakin ingin menghapus line <span id="delete_name" class="font-weight-bold"></span>?</p>
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
    <script>
        function resetForm(){
            $('#save').trigger("reset");
            $('#line_id').val('');
        }

        function addLine(){
            resetForm();
            $('#modal-title').text("Add New Line");
            $('#button-save').text("Add");
        }

        function editLine(data){
            resetForm();
            $('#modal-title').text("Edit Line");
            $('#button-save').text("Simpan");
            $('#line_id').val(data.line_id);
            $('#line_name').val(data.line_name);
        }

        function deleteLine(data){
            $('#delete_id').val(data.line_id);
            $('#delete_name').text(data.line_name);
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