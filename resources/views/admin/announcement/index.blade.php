@extends('admin.layouts.master')
@section('title', 'Announcement Management')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold  mb-0">Announcement Management</h4>
        {{-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">Home</a>
                </li>
                <li class="breadcrumb-item active">Data</li>
            </ol>
        </nav> --}}
    </div>
    <a href="#" class="btn rounded-pill btn-primary add-announcement" data-bs-toggle="modal"
        data-bs-target="#announcementModal">Add
        Announcement</a>
</div>
<div class="card">
    <h5 class="card-header">Announcement List</h5>
    <div class="table-responsive p-25 text-nowrap ">
        <table class="table table-striped w-100" id="announcement_list">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">

            </tbody>
        </table>
    </div>
</div>
{{-- modal --}}
<div class="modal fade" id="announcementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel4">Add Announcement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="announcementForm">
                    @csrf
                    <input type="hidden" name="id" id="id" value="">
                    <div class="row g-2">
                        <div class="col mb-2">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" id="title" class="form-control" name="title"
                                placeholder="Enter Title" />
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-2">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" cols="30" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="row g2">
                        <div class="col mb-3">
                            <label for="image" class="form-label">Upload Image</label>
                            <input class="form-control" type="file" id="image" name="image" accept="image/*">
                        </div>
                        <div class="image-preview">

                        </div>
                    </div>
                    <div class="d-flex align-content-center justify-content-end mt-2">
                        <button type="button" class="btn btn-info" style="margin-right: 15px;" data-bs-dismiss="modal"
                            aria-label="Close">Close</button>
                        <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                    </div>

            </div>
            </form>
        </div>

    </div>
</div>

{{-- end modal --}}

{{-- status modal --}}
<div class="modal fade" id="announcementStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel4">Change Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="changeStatusForm">
                    <input type="hidden" name="id" id="engineer_id" value="">
                    <div class="row g-2">
                        <label for="status" class="form-label">Status</label>
                        <select for="status" class="form-select" id="status" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="d-flex align-content-center justify-content-end mt-2">
                        <button type="button" class="btn btn-info mr-3" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="status_submit">Save</button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
{{-- end status modal --}}
@endsection
@push('js')
<script>
$(document).ready(function () {
    table = $('#announcement_list').dataTable({
            "oLanguage": {
                "sEmptyTable": "No Data Available"
            },
            language: {
                paginate: {
                    next: '<i class="tf-icon bx bx-chevrons-right"></i>', // or '→'
                    previous: '<i class="tf-icon bx bx-chevrons-left"></i>' // or '←'
                }
            },
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('announcement.list') }}",
                "type": "POST",
                data: function (d) {

                    d._token = $('meta[name="_token"]').attr('content')
                    // d.status = $("#status-filter").val()

                },

            },
            "columns": [
                {
                    "name": "title",
                    "data": "title"
                },
                {
                    "name": "created_at",
                    // "data": "created_at"
                    "render": function (data, type, row) {
                        return new Date(row.created_at).toLocaleDateString('en-GB')
                    }
                },
                
                {
                    "name": "action",
                    "render": function (data, type, row) {
                        return ` <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item edit-announcement" href="javascript:void(0);" data-bs-toggle="modal" data-id="${row.id}">Edit</a>
                            <a class="dropdown-item delete-announcement" href="javascript:void(0);" data-bs-toggle="modal" data-id="${row.id}">Delete</a>
                        </div>
                        </div>`;
                    }
                }
            ],

            "columnDefs": [{
                orderable: false,
                targets: [-1]
            },
            {
                searchable: false,
                targets: [-1]
            }
        ],
    });
    $('#announcementForm').validate({
        rules: {
            title: {
                required: true
            },
            description: {
                required: true
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass("select2-hidden-accessible")) {
                error.insertAfter(element.siblings('span.select2'));
            } else if (element.hasClass("floating-input")) {
                element.closest('.form-floating-label').addClass("error-cont").append(
                    error);
                //error.insertAfter();
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            loadButton('#submit');
            let formData = new FormData(form);
            $.ajax({
                type: "POST",
                url: "{{ route('announcement.store') }}",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function (data) {
                    loadButton('#submit');
                    if (data.success == "1") {
                        $("#announcementModal").modal('hide');
                        $('#announcementForm')[0].reset();
                        notifySuccess(data.message);
                        table.fnDraw();
                    } else {
                        if (data.error != "") {
                            notifyWarning(data.error[0]);
                        } else {
                            notifyWarning(data.message);
                        }
                    }
                }
            })
        }
    })
    $(document).on('click', '.delete-announcement', function () {
        let id = $(this).attr('data-id');
        if(id != "")
        {
            deleteModal('/announcement/delete',id,'table')
        }else{
            notifyWarning('Something went wrong')
        }
    });
    $(document).on('click', '.edit-announcement', function () {
        let id = $(this).attr('data-id');
        if(id != "")
        {
            $.ajax({
                type: "POST",
                url: "{{ route('announcement.get') }}",
                data: {id},
                success: function (data) {
                    if (data.success == "1") {
                        $('#id').val(data.data.id);
                        $('#title').val(data.data.title);
                        $('#description').val(data.data.description);
                        if(data.data.attachment != "" && data.data.attachment != null)
                        {
                            $('.image-preview').html(`<img src="${data.data.attachment}" id="attachment" alt="Attachment">`)
                        }else{
                            $('.image-preview').html('');
                        }
                        $("#announcementModal").modal('show');
                    } else {
                        if (data.error != "") {
                            notifyWarning(data.error[0]);
                        } else {
                            notifyWarning(data.message);
                        }
                    }
                }
            })
        }else{
            notifyWarning('Something went wrong')
        }
    })
    document.getElementById("image").addEventListener("change", function(event) {
        const file = event.target.files[0]; // Get the selected file
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('.image-preview').html(`<img src="${e.target.result}" id="attachment" alt="Attachment">`)
            };
            reader.readAsDataURL(file); // Convert image to Base64
        }
    });

    // remove values
    $('.add-announcement').click(function () {
        $('#id').val('');
        $('#announcementForm')[0].reset();
        $('.image-preview').html('');
    })
   
});

</script>
@endpush
