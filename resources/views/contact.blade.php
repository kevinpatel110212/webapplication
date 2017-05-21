@extends('layouts.app')

@section('content')
<div class="container">
     <button type="button" id="add" onclick="$('#edit').modal('show');$('#addEdit')[0].reset();$('#id').val(null);" class="btn btn-box-tool"><i class="fa fa-plus"></i> Add </button>

<table class="table table-bordered" id="dataTableBuilder">
    <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
    </thead>
</table>
</div>

<div id="edit" class="modal fade" role="dialog">
    <form id="addEditUser" class="form-group" method="POST">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="form-errors"></div>
                            <div class = "form-group">
                                <label for = "name">Name</label>
                                <input type="text" class = "form-control" id="name" name="name">
                            </div>
                            <div class = "form-group">
                                <label for = "name">phone_number</label>
                                <input type="text" class = "form-control" id="phone_number" name="phone_number">
                            </div>
                           
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div id='tableCreate_external'></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="id" name="id">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script type="text/javascript">

    $(function() {
        $('#dataTableBuilder').DataTable({
            dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf'
            ],
            processing: true,
            serverSide: true,
            ajax: '{!! route('contactlistaddedit') !!}?datatable=yes',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'phone_number', name: 'phone_number'},
                {data: 'created_at', name: 'created_at'},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    });
     
    $('#edit').on('hidden.bs.modal', function () {
        resetTable();
    });

    function resetTable() {
            $('#edit').modal('hide');
            $('#dataTableBuilder').dataTable().fnDraw(false);
            $('#addEditUser')[0].reset();
        }
    
    function edit(id) {
        $.getJSON("{{ route('contactlistaddedit') }}", {id: id}, function (json) {
            $.each(json, function (key, value) {
                $('input[name="' + key + '"]').val(value);
                $('textarea[name="' + key + '"]').val(value);
                $("select[name=" + key + "]").val(value).trigger("change");
                $("select[name=" + key + "]").val(value);
            });
        });
        $('#edit').modal('show');
    }
    
    function destroyFinally(id, tableName) {
        if (confirm('Are you sure?')) {
            $.ajax({
                type: 'GET',
                url: "{{ route('contactlistaddedit') }}",
                data: {
                    id: id,
                    delete: 'yes',
                    tableName: tableName
                },
                dataType: "text",
                success: function () {
                   resetTable();
                }
            });
        } else {

        }
    }

    $('#addEditUser').submit(function (event) {
        $.ajax({
            type: "POST",
            url: "{{ route('contactlistaddedit') }}",
            data: $('form#addEditUser').serialize(),
            success: function () {
                resetTable();
            },
            error: function (jqXhr) {
                if (jqXhr.status === 422) {
                    $.each(jqXhr.responseJSON, function (key, value) {
                        $('input[name="' + key + '"]').notify(
                                value,
                                {position: "top"}
                        );
                        $('input[file="' + key + '"]').notify(
                                value,
                                {position: "top"}
                        );
                        $('textarea[name="' + key + '"]').notify(
                                value,
                                {position: "top"}
                        );
                        $("select[name=" + key + "]").notify(
                                value,
                                {position: "top"}
                        );
                        $("select[name=" + key + "]").notify(
                                value,
                                {position: "top"}
                        );
                    });
                } else {
                }
            }
        });
        event.preventDefault();
    });

</script>
@endpush