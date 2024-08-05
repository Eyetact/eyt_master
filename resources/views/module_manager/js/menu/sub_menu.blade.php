  <script src="{{ URL::asset('assets/js/common/commonMethods.js') }}"></script>
  <script>
      /**
       * THIS FILE IS RESPONSIBLE FOR HANDELING THE EVENTS RELATED TO SUB MENUS IN THE MODULE MANAGER SECTION
       * IT INCLUDE: (SUB, SHARED AND ADDABLE MODULES)
       */
      $(document).ready(function() {
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
                                  "success", 3000);
                              $("#moduleCreateSub")[0].reset();
                          } else {
                              manageMessageResponse("addMenuLabel", response,
                                  "danger",
                                  3000);
                              $("#moduleCreateSub")[0].reset();
                          }
                      },
                      error: function(xhr, status, error) {
                          var response = JSON.parse(xhr.responseText);
                          if (xhr.status === 422) {
                              var errors = xhr.responseJSON.errors;
                              displayValidationErrorsFields(
                                  errors, 'sub');
                              $("#moduleCreateSub")[0].reset();
                          } else {

                              manageMessageResponse("addMenuLabel", response.message,
                                  "danger",
                                  3000);
                              $("#moduleCreateSub")[0].reset();
                          }
                      }
                  });
              }


          });

      });
  </script>
