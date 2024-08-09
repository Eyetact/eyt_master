  <script src="{{ URL::asset('assets/js/common/commonMethods.js') }}"></script>
  <script>
      /**
       * THIS FILE IS RESPONSIBLE FOR HANDELING THE EVENTS RELATED TO SUB MENUS IN THE MODULE MANAGER SECTION
       * IT INCLUDE: (SUB, SHARED AND ADDABLE MODULES)
       */
      $(document).ready(function() {

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
          $(document).on('change', '#moduleCreateSub', function() {
              $("#moduleCreateSub").validate({
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
          $("#sub").on('change', function() {
              // Run validation on keyup and change events
              $('input[required]').on('keyup change', function() {
                  validateForm('#moduleCreateSub', '.add-sub-module');
              });

              // Initial validation check
              validateForm('#moduleCreateSub', '.add-sub-module');

          });

          $('#moduleCreateSub').submit(function(e) {
              e.preventDefault(); // Prevent default form submission
              // check for some additiona validation
              if ($('#attr_id').val() <= 0) {
                  Swal.fire({
                      icon: "error",
                      title: "The parent module dos not have attribute ...",
                      text: "Something went wrong!",
                      footer: '<a href="{{ url('attribute') }}">Create ?</a>'
                  });
                  return;
              } else {
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
                      url: '{{ route('module_manager.storSubPost') }}',
                      data: formData,
                      processData: false,
                      contentType: false,
                      dataType: 'json',

                      success: function(response) {
                          if (response.status === true) {
                              manageMessageResponse("addMenuLabel", response,
                                  "success",
                                  3000, '#moduleCreateSub');
                              manageSubModuleCreationResponse(response.message);
                              addNewStoreFrontModuleElementToList(response.data, "admin");


                          } else {
                              manageMessageResponse("addMenuLabel", response,
                                  "danger",
                                  3000, '#moduleCreateSub');
                              manageSubModuleCreationResponse(response.message);
                              addNewStoreFrontModuleElementToList(response.data, "admin");


                          }
                      },
                      error: function(xhr, status, error) {
                          var response = JSON.parse(xhr.responseText);
                          if (xhr.status === 422) {
                              var errors = xhr.responseJSON.errors;
                              displayValidationErrorsFields(
                                  errors, 'sub');

                              manageSubModuleCreationResponse(response.message);
                              $("#moduleCreateSub")[0].reset();
                          } else {

                              manageMessageResponse("addMenuLabel", response.message,
                                  "danger",
                                  3000);
                              manageSubModuleCreationResponse(response.message);
                              $("#moduleCreateSub")[0].reset();
                          }
                      }
                  });
              }


          });

      });

      /**
       * @argument data
       * THIS FUNCTION IS TO MANAGE THE RESPONSE REALTED TO CREATE NEW LABEL IN THE ADMIN FORM
       */
      function manageSubModuleCreationResponse(data) {
          $('#sub').prop('checked', false);
          $('.label-con').show();
          $('.sub-con').show();
          $('.main-form').show();
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
          formFields) {
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
          }

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
  </script>
