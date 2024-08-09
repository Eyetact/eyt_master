{{-- @include('includes.js.headers') --}}
<script>
    //1. pass the php variables into js
    //  ---- Admin ----
    var adminMenuId = @json($menu->id);
    @if ($menu->id != null)
        var adminUpdateRoute = @json(route('module_manager.update', ['menu' => $menu->id]));
    @else
        var adminStoreRoute = @json(route('module_manager.store'));
    @endif

    // --- front ---
    var frontMenuId = @json($menu->id);
    @if ($menu->id != null)
        var frontUpdateRoute = @json(route('module_manager.update', ['menu' => $menu->id]));
    @else
        var frontStoreRoute = @json(route('module_manager.storeFront'));
    @endif

    $(document).ready(function() {

        $("#addMenuLabel").on('shown.bs.modal', function() {
            // Run validation on keyup and change events
            $('input[required]').on('keyup change', function() {
                validateForm('.admin-form', '.admin-form-submit');
            });

            // Initial validation check
            validateForm('.admin-form', '.admin-form-submit');

        });


        $("#label").on('shown.bs.modal change', function() {
            // Run validation on keyup and change events
            $('input[required]').on('keyup change', function() {
                validateForm('.add-label-form', '.admin-label-form-submit');
            });

            // // Initial validation check
            // validateForm('.add-label-form', '.admin-label-form-submit');

        });


        $("#FrontForm").on('shown.bs.modal', function() {
            // Run validation on keyup and change events
            $('input[required]').on('keyup change', function() {
                validateForm('.storefront-form', '.store-front-form-submit');
            });

            // Initial validation check
            validateForm('.storefront-form', '.store-front-form-submit');

        });


        jQuery.validator.addMethod("notEqual", function(value, element, param) {
            return this.optional(element) || value != param;
        }, "Please change the name, you can't enter id");

        jQuery.validator.addMethod("notEqual2", function(value, element, param) {
            return this.optional(element) || value != param;
        }, "Please change the name, you can't enter id");


        jQuery.validator.addMethod("notEqual3", function(value, element, param) {
            return this.optional(element) || value != param;
        }, "Please change the name, you can't enter id");

        jQuery.validator.addMethod("notEqual4", function(value, element, param) {
            return this.optional(element) || value != param;
        }, "Please change the name, you can't enter id");

        // validation listener in store-front
        $(document).on('change', '.storefront-form', function() {
            $(".storefront-form").validate({
                onkeyup: function(el, e) {
                    console.log("yes");
                    $(el).valid();
                },
                errorClass: "invalid-feedback is-invalid",
                validClass: 'valid-feedback is-valid',
                ignore: ":hidden",
                rules: {

                    code: {
                        required: true,
                        maxlength: 255,
                        notEqual: 'id',
                        notEqual2: 'ID',
                        notEqual3: 'iD',
                        notEqual4: 'Id',
                    },

                },
                messages: {},
                /*the following lines are for inserting the error message under the code input field*/
                errorPlacement: function(error, element) {
                    error.addClass('d-block');
                    error.insertAfter(element);
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                    $(element).addClass('is-valid');
                },
            });
        });

        // validation listener in admin-form
        $(document).on('change', '.admin-form', function() {
            $(".admin-form").validate({
                onkeyup: function(el, e) {
                    console.log("yes");
                    $(el).valid();
                },
                errorClass: "invalid-feedback is-invalid",
                validClass: 'valid-feedback is-valid',
                ignore: ":hidden",
                rules: {

                    code: {
                        required: true,
                        maxlength: 255,
                        notEqual: 'id',
                        notEqual2: 'ID',
                        notEqual3: 'iD',
                        notEqual4: 'Id',
                    },

                },
                messages: {},
                /*the following lines are for inserting the error message under the code input field*/
                errorPlacement: function(error, element) {
                    error.addClass('d-block');
                    error.insertAfter(element);
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                    $(element).addClass('is-valid');
                },
            });
        });

        //validation listener in add label module
        $(document).on('change', '.add-label-form', function() {
            $(".add-label-form").validate({
                onkeyup: function(el, e) {
                    console.log("yes");
                    $(el).valid();
                },
                errorClass: "invalid-feedback is-invalid",
                validClass: 'valid-feedback is-valid',
                ignore: ":hidden",
                rules: {

                    code: {
                        required: true,
                        maxlength: 255,
                        notEqual: 'id',
                        notEqual2: 'ID',
                        notEqual3: 'iD',
                        notEqual4: 'Id',
                    },

                },
                messages: {},
                /*the following lines are for inserting the error message under the code input field*/
                errorPlacement: function(error, element) {
                    error.addClass('d-block');
                    error.insertAfter(element);
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                    $(element).addClass('is-valid');
                },
            });
        });





        /**
         * THIS ACTION HANDLER IS TO HANDLE THE SUBMIT BUTTON OF STORE-FRONT FORM 
         */
        $('.storefront-form').submit(function(e) {
            e.preventDefault(); // Prevent default form submission
            // compare the conditions to set the desired url(update|create new)
            var menuId = menuId; // This comes from the Blade template
            var url = menuId == null ? frontStoreRoute : frontUpdateRoute;
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
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',

                success: function(response) {
                    if (response.status === true) {

                        manageMessageResponse("FrontForm", response,
                            "success", 3000, '.storefront-form', '');
                        addNewStoreFrontModuleElementToList(response.data, "storfront");

                    } else {

                        manageMessageResponse("FrontForm", "storfront", response,
                            "danger",
                            3000), '.storefront-form';
                        addNewStoreFrontModuleElementToList(response.data, "storfront");
                    }
                },
                error: function(xhr, status, error) {
                    var response = JSON.parse(xhr.responseText);

                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        displayValidationErrorsFields(
                            errors, 'storefront');
                        $(".storefront-form")[0].reset();
                    } else {

                        manageMessageResponse("FrontForm", response.message, "danger",
                            3000, '.storefront-form');
                    }
                }
            });


        });


        /**
         * THIS ACTION HANDLER IS TO HANDLE THE SUBMIT BUTTON OF ADMIN FORM 
         */

        $('.admin-form').submit(function(e) {
            e.preventDefault(); // Prevent default form submission
            // compare the conditions to set the desired url(update|create new)
            var menuId = menuId; // This comes from the Blade template
            var url = menuId == null ? adminStoreRoute : adminUpdateRoute;
            var formData = new FormData(this);
            // Setup CSRF token header
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                }
            });
            $.ajax({
                type: 'POST',
                url: url, // Replace with your actual route
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === true) {

                        manageMessageResponse("addMenuLabel", response,
                            "success",
                            3000, '.admin-form');
                        addNewStoreFrontModuleElementToList(response.data, "admin");

                    } else {
                        manageMessageResponse("addMenuLabel", response,
                            "danger",
                            3000, '.admin-form');
                        addNewStoreFrontModuleElementToList(response.data, "admin");
                    }

                },
                error: function(xhr, status, error) {
                    var response = JSON.parse(xhr.responseText);
                    // Handle the error response
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        displayValidationErrorsFields(
                            errors, 'admin');
                        $(".admin-form")[0].reset();
                    } else {

                        manageMessageResponse("addMenuLabel", response.message,
                            "danger",
                            3000, '.admin-form');
                    }
                }
            });
        });


        /**
         * THIS ACTION HANDLER IS TO HANDLE THE SUBMIT BUTTON OF CREATE LABEL FORM
         */
        $('.add-label-form').submit(function(e) {
            e.preventDefault(); // Prevent default form submission
            formData = new FormData(this);
            // Setup CSRF token header
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ route('module_manager.storelabel') }}', // Replace with your actual route
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === true) {

                        manageMessageResponse("addMenuLabel", response,
                            "success",
                            3000, '.add-label-form');
                        manageLabelCreationResponse(response.message);
                        addLabelElementToList(response.data);
                    } else {
                        manageMessageResponse("addMenuLabel", response,
                            "danger",
                            3000, '.add-label-form');
                        manageLabelCreationResponse(response.data);
                        addLabelElementToList(response.data);
                    }


                },
                error: function(xhr, status, error) {
                    var response = JSON.parse(xhr.responseText);
                    // Handle the error response
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        displayValidationErrorsFields(
                            errors, 'label');
                        $(".add-label-form")[0].reset();
                    } else {
                        manageMessageResponse("addMenuLabel", response,
                            "danger",
                            3000, '.add-label-form');
                        manageLabelCreationResponse(response.data);
                        addLabelElementToList(response.data);

                    }
                }
            });
        });



        // Function to display the error messages corresponding to each input field in the form
        function displayValidationErrorsFields(errors, formType) {
            $('.error-message').text('');

            $.each(errors, function(key, value) {
                var errorSpan = $("#" + key + "-" + formType + "-error");
                if (errorSpan.length) {
                    errorSpan.html(value[0]);
                    errorSpan.removeClass('d-none');
                }
            });
        }

        /**
         * @argument moduleType: string
         * THIS FUNCTION IS TO INCREASE THE COUNTER AFETR INSERT NEW MODULE(ADMIN | STORE) USING THE FORM
         */
        function increaseCounter(moduleType) {
            var currentValue = parseInt($('#' + moduleType + "-counter").text(), 10);
            var newValue = currentValue + 1;
            $('#' + moduleType + "-counter").text(newValue);
        }


        // Function to display validation errors in the modal
        function displayValidationErrors(errors) {
            var errorList = '<ul>';
            $.each(errors, function(key, value) {
                errorList += '<li>' + value[0] +
                    '</li>'; // Assuming you only want to display the first error message
            });
            errorList += '</ul>';

            // Display errors in the modal or wherever you want
            $('#validationErrors').removeClass('hide');
            $('#validationErrors').html(errorList);
            $('.modal-body').scrollTop(0);
        }


        /**
         * THIS FUNCTION IS TO INSERT NEW LIST ELEMENT INTO THE LIST
         * @argument data: object containing the module data
         **/
        function addNewStoreFrontModuleElementToList(data, formType) {
            // Construct HTML for the new list item using template literals
            var newListItemHtml = `
        <li class="dd-item no-pad" data-path="${data.path}" data-id="${data.module_id}" data-name="${data.name}" data-module="${data.module_id}" data-code="${data.code}">
            <div class="dd-handle">${data.name}</div>
        </li>`;

            // Append the new item to the #storfront_menu_list
            $("#" + formType + "_menu_list").append(newListItemHtml);
        }


        /**
         * THIS FUNCTION IS TO INSERT NEW LABEL ELEMENT INTO THE LIST
         * @argument data: object containing the module data
         **/
        function addLabelElementToList(data) {
            // Construct HTML for the new list item using template literals
            var newListItemHtml = `
            <li class="dd-item no-pad" data-name="${data.name}">
                <div class="dd-handle">${data.name}
                <span class="badge badge-danger">Label</span>
                </div>
            </li>
            `
            // Append the new item to the #storfront_menu_list
            $("#admin_menu_list").append(newListItemHtml);
        }

        /**@argument formType: #formModel | admin..
         * @argument response : the acutal returned data
         * @argument resultType: success | danger..
         * @argument timesout in mellisecond
         * 
         * THIS FUNCTION I TO MANAGE RESPONDING TO THE FORM
         * 1. IT HIDES THE POP UP FORM
         * 2. MANAGES THE MESSAGE (SUCCESS & DANGER) WITHIN A SPECIFIC TIME OF APPEARANCE
         * 3. INCREASE THE COUNTER OF MODULE NUMBER
         */
        function manageMessageResponse(formType, response, resultType, timeout,
            formFields, errorFields) {
            // 1. Hide the pop-up form
            $('#' + formType).modal('hide');

            // 2. If the message is success
            if (resultType === 'success') {
                // Display the success or danger message
                toastr.success(response.message, "Success");
                // increase the counter
                increaseCounter(formType);
                // empty the input fields
                $(formFields)[0].reset();
            } else {
                toastr.error(response, "Error");
                $(formFields)[0].reset();
            }

        }


        /**
         * @argument data
         * THIS FUNCTION IS TO MANAGE THE RESPONSE REALTED TO CREATE NEW LABEL IN THE ADMIN FORM
         */
        function manageLabelCreationResponse(data) {
            $('.checkbox-label-form').prop('checked', false);
            $('.label-con').show();
            $('.sub-con').show();
            $('.main-form').show();
            $('#addMenuLabel').modal('hide');
            // clearErrors();
            addLabelElementToList(data);
        }

        /**
         * CLEAR THE ERROR MESSAGES WHEN SUBMIT
         * @argument
         * */
        function clearErrors() {
            $('span.error-message').each(function() {
                $(this).text(''); // Clear the error text
                $(this).addClass('d-none');
            });
        }



    });
</script>
