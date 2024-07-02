  <!-- Jquery js-->
  <script src="{{ URL::asset('assets/js/jquery-3.5.1.min.js') }}"></script>

  <!-- Jquery Validate JS -->
  <script src="{{ asset('assets/plugins/forn-wizard/js/jquery.validate.min.js') }}"></script>

  <!-- Bootstrap4 js-->
  <script src="{{ URL::asset('assets/plugins/bootstrap/popper.min.js') }}"></script>
  <script src="{{ URL::asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

  <!--Othercharts js-->
  <script src="{{ URL::asset('assets/plugins/othercharts/jquery.sparkline.min.js') }}"></script>

  <!-- Circle-progress js-->
  <script src="{{ URL::asset('assets/js/circle-progress.min.js') }}"></script>

  <!-- Toastr JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script>
      toastr.options = {
          "closeButton": true,
          "debug": false,
          "newestOnTop": false,
          "progressBar": true,
          "positionClass": "toast-bottom-center",
          "preventDuplicates": false,
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "5000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
      }
  </script>
  <!-- Jquery-rating js-->
  <script src="{{ URL::asset('assets/plugins/rating/jquery.rating-stars.js') }}"></script>
  @yield('js')
  <!-- Custom js-->
  <script src="{{ URL::asset('assets/js/custom.js') }}"></script>
