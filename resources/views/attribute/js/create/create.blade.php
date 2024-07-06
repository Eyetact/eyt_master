<!-- THIS SCRIPT IS TO HANDLE THE AJAX (ASYNCRONOUS) ACTIONS WHEN CREATING NEW ATTRIBUTE -->

<script>
    function attachFormSubmitHandler() {
        /**
         * THIS ACTION HANDLER IS TO HANDLE THE SUBMIT BUTTON OF CREATE NEW ATTRIBUTE FORM 
         */
        $('#attribute-create').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            // Setup CSRF token header
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                }
            });

            //AJAX request
            $.ajax({
                type: 'POST',
                url: '{!! route('attribute.store') !!}',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',

                success: function(response) {
                    console.log(response);
                    if (response.status === true) {

                        manageMessageResponse("role_form_modal", "", response,
                            "success", 3000);
                    } else {
                        manageMessageResponse("role_form_modal", "", response, "danger",
                            3000);
                    }
                },
                error: function(xhr, status, error) {
                    var response = JSON.parse(xhr.responseText);
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        displayValidationErrorsFields(
                            errors, 'attribute-create');
                    } else {
                        manageMessageResponse("role_form_modal", "", response.message, "danger",
                            3000);
                    }
                }
            });


        });



    }
</script>
