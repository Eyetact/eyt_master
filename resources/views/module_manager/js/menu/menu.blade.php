{{-- @include('includes.js.headers') --}}
<script>
    $(document).ready(function() {



        $(document).on('change', '.module', function() {
            var id = $(this).find(':selected').val();

            var parent = $(this).parent().parent().parent().parent().find('#attr_id');
            $.ajax({
                url: '{{ url('/') }}/attribute-by-module2/' + id,
                success: function(response) {
                    console.log(response);
                    // $('.child-drop').remove()
                    parent.html(`${response}`);
                    $('.subbb').show();
                }
            });
        })


        $(document).on('change', '#attr_id', function() {
            var id = $(this).find(':selected').val();

            if (id != undefined) {
                $('.form-subb').show();

            }

        })

        $(document).on('change', '#shared', function() {
            var id = $(this).val();
            console.log(id);
            if ($(this).is(':checked')) {
                $('.added').show();
            } else {
                $('.added').hide();

            }

        })


        $("#addMenuLabel").on('shown.bs.modal', function() {
            // alert('aaa')
            $('.label-form').hide();
            $('.sub-form').hide();


            $("#label").on('change', function() {
                if ($(this).is(':checked')) {
                    $('.sub-form').hide();
                    $('.main-form').hide();
                    $('.label-form').show();
                    $('#sub').val(0).prop('checked', false)
                    $('.sub-con').hide();
                } else {
                    $('.label-form').hide();
                    $('.sub-form').hide();
                    $('.main-form').show();
                    $('.sub-con').show();

                }

            });

            $("#sub").on('change', function() {
                if ($(this).is(':checked')) {
                    $('.label-form').hide();
                    $('.main-form').hide();
                    $('.sub-form').show();
                    $('#label').val(0).prop('checked', false)
                    $('.label-con').hide();

                } else {
                    $('.sub-form').hide();
                    $('.label-form').hide();
                    $('.label-con').show();
                    $('.main-form').show();

                }

            });
        });



        $('.fast').hide();

        $(".fast-btn").click(function() {
            $(this).hide();
            $(".fast").toggle();
        });
        // console.log($(".storfront_nested_form").find('li:first').find('.dd-handle:first'));
        // $(".storfront_nested_form").find('li:first').find('.dd-handle:first').trigger('mousedown');

        // $('#storfront_form_edit').submit(function(e) {
        //     e.preventDefault(); // Prevent the form from submitting the traditional way

        //     // Serialize the form data
        //     var formData = $(this).serialize();

        //  var edit_sid = $(this).find('#sid').val();
        // Send an AJAX request
        //     $.ajax({
        //         type: 'POST',
        //         url: '' + '/' +
        //             edit_sid, // Replace with your actual route
        //         data: formData,
        //         success: function(response) {
        //             // Handle the success response
        //             console.log('AJAX request succeeded:', response);
        //             $('#addMenuModal').modal(
        //                 'hide'); // Hide the modal after successful submission
        //             location.reload();
        //         },
        //         error: function(xhr, status, error) {
        //             // Handle the error response
        //             console.error('AJAX request failed:', status, error);

        //             if (xhr.status === 422) {
        //                 // If it's a validation error, display the errors in the modal
        //                 var errors = xhr.responseJSON.errors;
        //                 displayValidationErrors(errors);
        //             } else {
        //                 // Handle other types of errors as needed
        //                 alert('An unexpected error occurred. Please try again.');
        //             }
        //         }
        //     });
        // });


        /**
         * THIS ACTION HANDLER IS TO HANDLE THE SUBMIT BUTTON OF STORE-FRONT FORM 
         */
        $('#storefront-form').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

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
                url: '{!! route('module_manager.storeFront') !!}',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',

                success: function(response) {
                    if (response.status === true) {

                        manageMessageResponse("FrontForm", "storefront", response,
                            "success", 3000);
                    } else {
                        manageMessageResponse("FrontForm", "storefront", response, "danger",
                            3000);
                    }
                },
                error: function(xhr, status, error) {
                    var response = JSON.parse(xhr.responseText);
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        displayValidationErrorsFields(
                            errors, 'storefront');
                    } else {

                        manageMessageResponse("FrontForm", response.message, "danger",
                            3000);
                    }
                }
            });


        });


        /**
         * THIS ACTION HANDLER IS TO HANDLE THE SUBMIT BUTTON OF ADMIN FORM 
         */

        $('#admin-form').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

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
                url: '{!! route('module_manager.store') !!}', // Replace with your actual route
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === true) {

                        manageMessageResponse("addMenuLabel", "admin", response, "success",
                            3000);
                    } else {
                        manageMessageResponse("addMenuLabel", "admin", response, "danger",
                            3000);
                    }

                },
                error: function(xhr, status, error) {
                    var response = JSON.parse(xhr.responseText);
                    // Handle the error response
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        displayValidationErrorsFields(
                            errors, 'admin');
                    } else {

                        manageMessageResponse("addMenuLabel", response.message, "danger",
                            3000);
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

        $("#storfront_div").hide();
        $("#admin_div").hide();

        $("#storfront_edit_div").hide();
        $("#admin_edit_div").hide();

        $('body').on('change', '#module_type', function() {
            console.log($(this).val())
            if ($(this).val() == "storfront") {
                $("#admin_div").hide();
                $("#storfront_div").show();
            } else if ($(this).val() == "admin") {
                $("#storfront_div").hide();
                $("#admin_div").show();
            } else {
                $("#storfront_div").hide();
                $("#admin_div").hide();
            }
        });

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
        function manageMessageResponse(formType, listType = "storefront", response, resultType, timeout) {
            // 1. Hide the pop-up form
            $('#' + formType).modal('hide');

            // 2. If the message is success
            if (resultType === 'success') {
                // Add the new inserted row in the field
                addNewStoreFrontModuleElementToList(response.data, listType);
                // Display the success or danger message
                $("#" + resultType + "Message").text(response.message);
                // increase the counter
                increaseCounter(formType);
            } else
                $("#" + resultType + "Message").text(response);

            // 3. Display the success or danger modal
            $("#" + resultType + "Modal").modal('show');

            // 4. Hide the success or danger modal after a timeout
            setTimeout(function() {
                $("#" + resultType + "Modal").modal('hide');
            }, timeout);
        }



        // $('#storfront_nestable').on('mousedown', '.dd-handle', function(event) {
        //     var type = '#storfront_form_edit';

        //     $("#storfront_edit_div").show();
        //     $("#admin_edit_div").hide();

        //     $('#storfront_nestable .dd-handle').removeClass('selected-item');
        //     $('#admin_nestable .dd-handle').removeClass('selected-item');
        //     $(this).addClass('selected-item');

        //     var singleData = $(this).parent().data("json");
        //     console.log(singleData);

        //     console.log("PMD isDeleted", singleData.is_deleted)
        //     // alert("aaa")
        //     if (singleData.is_deleted == 1) {
        //         $(this).addClass('deleted-item');
        //     } else {
        //         $(this).removeClass('deleted-item');
        //     }

        //     enabledDisabledStoreFrontFormField(type, singleData)

        //     $("#storfront_form_edit #sid").val(singleData.id);
        //     $("#storfront_form_edit #sname").val(singleData.name);
        //     $("#storfront_form_edit #scode").val(singleData.code);
        //     $("#storfront_form_edit #spath").val(singleData.path);
        //     $("#storfront_form_edit #sis_enable").prop('checked', singleData.status);
        //     $("#storfront_form_edit #sinclude_in_menu").prop('checked', singleData.include_in_menu);
        //     $("#storfront_form_edit #smeta_title").val(singleData.meta_title);
        //     $("#storfront_form_edit #smeta_description").val(singleData.meta_description);
        //     $("#storfront_form_edit #screated_date").val(singleData.created_date);
        //     $("#storfront_form_edit #sassigned_attributes").val(singleData.assigned_attributes);
        // });

        $('#storfront_nestable').on('mousedown', '.dd-handle', function(event) {
            var type = '#storfront_form_edit';

            $("#admin_edit_div").hide();
            $("#storfront_edit_div").show();

            $('#admin_nestable .dd-handle').removeClass('selected-item');
            $('#storfront_nestable .dd-handle').removeClass('selected-item');
            $(this).addClass('selected-item');

            var singleData = $(this).parent().data("json");
            var path = $(this).parent().data("path");
            console.log("PMD 1", singleData)
            // alert(singleData.module_id)

            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: path,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    $('.editc').html(response);

                },
                error: function(error) {
                    console.error(error);
                }
            });



            console.log("PMD isDeleted", singleData.is_deleted)

            enabledDisabledAdminFormField(type, singleData)

            $("#admin_form_edit #aid").val(singleData.id);
            $("#admin_form_edit #aname").val(singleData.name);
            $("#admin_form_edit #acode").val(singleData.code);
            $("#admin_form_edit #apath").val(singleData.path);
            $("#admin_form_edit #ais_enable").prop('checked', singleData.status);
            $("#admin_form_edit #ainclude_in_menu").prop('checked', singleData.include_in_menu);
            $("#admin_form_edit #acreated_date").val(singleData.created_date);
            $("#admin_form_edit #aassigned_attributes").val(singleData.assigned_attributes);
        });


        $('#admin_nestable').on('mousedown', '.dd-handle', function(event) {
            var type = '#admin_form_edit';

            $("#admin_edit_div").show();
            $("#storfront_edit_div").hide();

            $('#admin_nestable .dd-handle').removeClass('selected-item');
            $('#storfront_nestable .dd-handle').removeClass('selected-item');
            $(this).addClass('selected-item');

            var singleData = $(this).parent().data("json");
            var path = $(this).parent().data("path");
            console.log("PMD 1", singleData)
            // alert(singleData.module_id)

            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: path,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    $('.editc').html(response);

                },
                error: function(error) {
                    console.error(error);
                }
            });



            console.log("PMD isDeleted", singleData.is_deleted)

            enabledDisabledAdminFormField(type, singleData)

            $("#admin_form_edit #aid").val(singleData.id);
            $("#admin_form_edit #aname").val(singleData.name);
            $("#admin_form_edit #acode").val(singleData.code);
            $("#admin_form_edit #apath").val(singleData.path);
            $("#admin_form_edit #ais_enable").prop('checked', singleData.status);
            $("#admin_form_edit #ainclude_in_menu").prop('checked', singleData.include_in_menu);
            $("#admin_form_edit #acreated_date").val(singleData.created_date);
            $("#admin_form_edit #aassigned_attributes").val(singleData.assigned_attributes);
        });

        $('body').on('change', '.storfront_nested_form .nestable', function() {
            var menu = $("#storfront_menu_list");
            var jsonResult = convertMenuToJson(menu, 'storfront-menu');

            $("#storfront_json").text(JSON.stringify(jsonResult, null, 2));

            var data = {
                type: "storfront",
                storfront_json: JSON.stringify(jsonResult, null, 2)
            }
            saveMenu(data);
        });

        $('body').on('change', '.admin_nested_form .nestable', function() {
            var menu = $("#admin_menu_list");
            var jsonResult = convertMenuToJson(menu, 'admin-menu');

            $("#admin_json").text(JSON.stringify(jsonResult, null, 2));
            var data = {
                type: "",
                admin_json: JSON.stringify(jsonResult, null, 2)
            }
            saveMenu(data);
        });

        function saveMenu(menuData) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '{{ route('module_manager.menu_update') }}',
                type: 'POST',
                data: menuData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // if (response.success) {
                    //     alert('Menu change successfully.');
                    // }
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }


        $('#storfront-form').validate({
            rules: {
                name: {
                    required: true,
                },
                code: {
                    required: true,
                },
                path: {
                    required: true,
                },
                created_date: {
                    required: true,
                },
                assigned_attributes: {
                    required: true,
                }
            },
            messages: {
                name: {
                    required: "Please enter the name.",
                },
                code: {
                    required: "Please enter the code.",
                },
                path: {
                    required: "Please enter the path.",
                },
                created_date: {
                    required: "Please enter the created date.",
                },
                assigned_attributes: {
                    required: "Please enter the assigned attributes.",
                }
            },
            errorElement: 'div',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                error.insertAfter(element);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).addClass('is-valid').removeClass('is-invalid');
            },
            submitHandler: function(form) {
                // Use AJAX to submit the form data
                // $.ajax({
                //     url: "{{ route('module_manager.storeFront') }}"
                //     method: 'post',
                //     data: $(form).serialize(),
                //     success: function(response) {
                //         // Handle success
                //         if (response.success) {
                //             $('#successToast .toast-body').text(response
                //                 .message);
                //             $('#successToast').toast('show');
                //         } else {
                //             $('#errorToast .toast-body').text(response.message);
                //             $('#errorToast').toast('show');
                //         }
                //     },
                //     error: function(response) {
                //         // Handle server error
                //         $('#errorToast .toast-body').text(
                //             'An error occurred. Please try again.');
                //         $('#errorToast').toast('show');
                //     }
                // });
            }
        });

        $('#admin_form').validate({
            rules: {
                module: {
                    required: true,
                },
                name: {
                    required: true,
                },
                code: {
                    required: true,
                },
                path: {
                    required: true,
                },
                created_date: {
                    required: true,
                }
            }
        });

        $("#moduleCreateSub").on("submit", function(event) {
            if ($('#attr_id').val() <= 0) {
                Swal.fire({
                    icon: "error",
                    title: "The parent module dos not have attribute ...",
                    text: "Something went wrong!",
                    footer: '<a href="{{ url('attribute') }}">Create ?</a>'
                });

                event.preventDefault();

                return;
            } else {
                $("#moduleCreateSub").submit()
            }
            event.preventDefault();
        });

        // $('#moduleCreateSub').validate({
        //     rules: {
        //         module: {
        //             required: true,
        //         },
        //         name: {
        //             required: true,
        //         },
        //         code: {
        //             required: true,
        //         },
        //         path: {
        //             required: true,
        //         },
        //         attr_id: {
        //             required: true,

        //         },
        //         messages: {

        //             attr_id: "Please enter a valid email address",

        //         }
        //     },

        // });

        $("#storfront_li").click(function() {
            $("#admin_div").hide();
            $("#storfront_div").show();
        });

        // Storefront remove event
        $('body').on('click', '#remove-store-front-menu', function() {
            var type = '#storfront_form_edit';
            let modelID = $(this).data('id');
            let isDeleted = 1;
            updateIsdeleted(modelID);
        });

        // Storefront restore event
        $('body').on('click', '#restore-store-front-menu', function() {
            var type = '#storfront_form_edit';
            let menuId = $("#storfront_form_edit #sid").val();
            let isDeleted = 0;
            updateIsdeleted(type, menuId, isDeleted);
        });

        // Admin remove event
        $('body').on('click', '#remove-admin-menu', function() {
            let modelID = $(this).data('id');
            let isDelete = 1;
            updateIsdeleted(modelID, isDelete);
        });

        // Admin restore event
        $('body').on('click', '#restore-admin-menu', function() {
            let modelID = $(this).data('id');
            let isDelete = 0;
            updateIsdeleted(modelID, isDelete);
        });

        function convertMenuToJson(menu, includeClass, parentId = 0) {
            var result = [];
            menu.children("li").each(function(index) {
                var menuItem = $(this).find('.' + includeClass);
                var id = menuItem.val();
                var sequence = index;

                var jsonItem = {
                    id: id,
                    parent: parentId,
                    sequence: sequence,
                };

                var subMenu = $(this).children("ol");
                if (subMenu.length > 0) {
                    jsonItem.children = convertMenuToJson(subMenu, includeClass, id);
                }
                result.push(jsonItem);
            });

            return result;
        }

        function updateIsdeleted(modelID, isDelete) {
            var deleteText =
                "Data would be restored, Are You Sure ?"
            if (isDelete == 1) {
                deleteText =
                    "Data would be there till 30 days and Once deleted, you will not be able to recover this details !"
            }
            swal({
                title: "Are you sure?",
                text: deleteText,
                icon: "warning",
                showCancelButton: true,
                type: "warning",
                // confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, I am sure!',
                cancelButtonText: "No, cancel it!",
                // closeOnConfirm: false,
                // closeOnCancel: false,
                dangerMode: true,
            }, function(willDelete) {
                if (willDelete) {
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: '{{ route('module_manager.deleteORRestore') }}',
                        type: 'POST',
                        data: {
                            model_id: modelID,
                            is_delete: isDelete
                        },
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(response) {
                            swal({
                                title: response.msg
                            }, function(result) {
                                location.reload();
                            });
                        },
                        error: function(error) {
                            console.error(error);
                        }
                    });
                }
            });
        }

        function enabledDisabledStoreFrontFormField(type, data, menuId) {
            if (data.is_deleted == 1) {
                let isDeletedHtml = `<span class="tag tag-deleted tag-red">Deleted</span>`;
                $('#storfront_menu_list li[data-id="' + menuId + '"] .storfront-menu:first').after(
                    isDeletedHtml)

                let selectedItem = $('#storfront_menu_list li[data-id="' + menuId + '"]');
                let selectedItemDataAttr = selectedItem.data("json");
                if (selectedItemDataAttr && selectedItemDataAttr.is_deleted == 0) {
                    selectedItemDataAttr.is_deleted = 1
                }
                selectedItem.attr('json', selectedItemDataAttr);

                $(type + ' input').prop("disabled", true);
                $(type + ' input[type=checkbox]').attr('disabled', true);
                $(type + ' textarea').attr("disabled", true);
                $(type + ' #restore-store-front-menu').attr("disabled", false);
                $(type + ' #submit-store-front-menu').attr("disabled", false);

                $(type + ' #restore-store-front-menu').removeClass("d-none");
                $(type + ' #remove-store-front-menu').addClass("d-none");
            } else {
                $('#storfront_menu_list li[data-id="' + menuId + '"]').find(".tag-deleted").remove()

                let selectedItem = $('#storfront_menu_list li[data-id="' + menuId + '"]');
                let selectedItemDataAttr = selectedItem.data("json");
                if (selectedItemDataAttr && selectedItemDataAttr.is_deleted == 1) {
                    selectedItemDataAttr.is_deleted = 0
                }
                selectedItem.attr('json', selectedItemDataAttr);

                $(type + ' input').prop("disabled", false);
                $(type + ' input[type=checkbox]').attr('disabled', false);
                $(type + ' textarea').attr("disabled", false);

                $(type + ' #remove-store-front-menu').removeClass("d-none");
                $(type + ' #restore-store-front-menu').addClass("d-none");
            }
        }

        function enabledDisabledAdminFormField(type, data, menuId) {
            if (data.is_deleted == 1) {
                let isDeletedHtml = `<span class="tag tag-deleted tag-red">Deleted</span>`;
                $('#admin_menu_list li[data-id="' + menuId + '"] .admin-menu:first').after(isDeletedHtml)

                let selectedItem = $('#admin_menu_list li[data-id="' + menuId + '"]');
                let selectedItemDataAttr = selectedItem.data("json");
                if (selectedItemDataAttr && selectedItemDataAttr.is_deleted == 0) {
                    selectedItemDataAttr.is_deleted = 1
                }
                selectedItem.attr('json', selectedItemDataAttr);

                $(type + ' input').prop("disabled", true);
                $(type + ' input[type=checkbox]').attr('disabled', true);
                $(type + ' textarea').attr("disabled", true);
                $(type + ' #restore-admin-menu').attr("disabled", false);
                $(type + ' #submit-admin-menu').attr("disabled", false);

                $(type + ' #restore-admin-menu').removeClass("d-none");
                $(type + ' #remove-admin-menu').addClass("d-none");
            } else {
                $('#admin_menu_list li[data-id="' + menuId + '"]').find(".tag-deleted").remove()

                let selectedItem = $('#admin_menu_list li[data-id="' + menuId + '"]');
                let selectedItemDataAttr = selectedItem.data("json");
                if (selectedItemDataAttr && selectedItemDataAttr.is_deleted == 1) {
                    selectedItemDataAttr.is_deleted = 0
                }
                selectedItem.attr('json', selectedItemDataAttr);

                $(type + ' input').prop("disabled", false);
                $(type + ' input[type=checkbox]').attr('disabled', false);
                $(type + ' textarea').attr("disabled", false);

                $(type + ' #remove-admin-menu').removeClass("d-none");
                $(type + ' #restore-admin-menu').addClass("d-none");
            }
        }

        $(document).on('click', '.sub-add', function(event) {
            event.preventDefault();
            var path = $(this).data('path');
            // alert(id)
            $.ajax({
                url: path,
                success: function(response) {
                    console.log(path);
                    $(".modal-body").html(response);
                    $(".modal-title").html("Add sub Module");
                    $("#role_form_modal").modal('show');
                    $('.dropify').dropify();
                }
            });
        });
    });
</script>
