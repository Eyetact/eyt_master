  <script src="{{ URL::asset('assets/js/common/commonMethods.js') }}"></script>
  <script>
      //1. pass the php variables into js
      //  ---- Admin ----
      var attributeId = @json($attribute->id);
      @if ($attribute->id != null)
          var updateRoute = @json(route('attribute.update', ['attribute' => $attribute->id]));
      @else
          var storeRoute = @json(route('attribute.store'));
      @endif

      $(document).ready(function() {
          /**
           * THIS ACTION HANDLER IS TO HANDLE THE SUBMIT BUTTON OF STORE-FRONT FORM
           */
          $('#attributeCreate').submit(function(e) {
              e.preventDefault(); // Prevent default form submission
              //compare the conditions to set the desired url(update | create new)
              var attributeId = attributeId; // This comes from the Blade template
              var url = attributeId == null ? storeRoute : updateRoute;
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

                          manageMessageResponse("role_form_modal", response,
                              "success", 3000);
                          $("#attributeCreate")[0].reset();
                      } else {
                          manageMessageResponse("role_form_modal", response,
                              "danger",
                              3000);
                          $("#attributeCreate")[0].reset();
                      }
                  },
                  error: function(xhr, status, error) {
                      var response = JSON.parse(xhr.responseText);
                      if (xhr.status === 422) {
                          var errors = xhr.responseJSON.errors;
                          displayValidationErrorsFields(
                              errors);
                          $("#attributeCreate")[0].reset();
                      } else {
                          //   alert("hhh");
                          manageMessageResponse("role_form_modal", response.message, "danger",
                              3000);
                          $("#attributeCreate")[0].reset();
                      }
                  }
              });


          });

      });
  </script>
