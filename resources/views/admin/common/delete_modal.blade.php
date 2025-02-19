{{-- delete modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            {{-- <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel4">Delete Modal</h5>
            </div> --}}
            <form id="commonDeleteForm">
                <div class="modal-body d-flex flex-column justify-content-center align-items-center">
                    <button type="button" class="btn-close delete-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
                    <input type="hidden" name="id" id="delete_id" value="">
                    <input type="hidden" name="" id="delete_url" value="">
                    <input type="hidden" name="" id="delete_type" value="">
                    <img src="{{ asset('assets/img/images/delete.svg') }}" height="250px" alt="">
                    <div class="row g-2">
                        <h3 class="text-center">Are you sure want to delete this?</h3>
                        <label  class="text-center mt-0 mb-3">You will not be able to recover them afterwards</label>
                    </div>
                    <div class="d-flex align-content-center justify-content-end mt-2">
                        <button type="button" class="btn btn-info px-4" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-primary px-3" id="delete_submit">Continue</button>
                    </div>

                </div>
            </form>
        </div>

    </div>
</div>
<style>
    .delete-close-btn{
        right: 8px;
        top: 8px;
        position: absolute;
    }
    #delete_submit{
        margin-left: 10px;
    }
</style>

<script>
    function deleteModal(url,id,type)
    {
        if(url != "" && id != "" )
        {
            $('#deleteModal #delete_id').val(id);
            $('#deleteModal #delete_url').val(url);
            $('#deleteModal #delete_type').val(type);
            $('#deleteModal').modal('show');
        }
    }

    $('#commonDeleteForm').validate({
        rules: {
            
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
            loadButton('#delete_submit');
            let formData = new FormData(form);
            let url = $('#delete_url').val();
            let type = $('#delete_type').val();
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function (data) {
                    loadButton('#delete_submit');
                    if(type == "table")
                    {
                        table.fnDraw();
                    }else{
                        location.reload();
                    }
                    if (data.success == "1") {
                        $("#deleteModal").modal('hide');
                        $('#commonDeleteForm')[0].reset();
                        notifySuccess(data.message);
                    } else {
                        notifyWarning(data.message);
                    }
                }
            })
        }
    })
</script>