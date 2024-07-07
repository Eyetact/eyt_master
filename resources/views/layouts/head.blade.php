  <!-- Title -->
  <title>Admitro - Admin Panel HTML template</title>

  <!--Favicon -->
  <link rel="icon" href="{{ URL::asset('assets/images/brand/favicon.ico') }}" type="image/x-icon" />

  <!--Bootstrap css -->
  <link href="{{ URL::asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

  <!-- Style css -->
  <link href="{{ URL::asset('assets/css/style.css') }}" rel="stylesheet" />
  <link href="{{ URL::asset('assets/css/dark.css') }}" rel="stylesheet" />
  <link href="{{ URL::asset('assets/css/skin-modes.css') }}" rel="stylesheet" />

  <!-- Animate css -->
  <link href="{{ URL::asset('assets/css/animated.css') }}" rel="stylesheet" />

  <!--Sidemenu css -->
  <link href="{{ URL::asset('assets/css/sidemenu.css') }}" rel="stylesheet">

  <!-- P-scroll bar css-->
  <link href="{{ URL::asset('assets/plugins/p-scrollbar/p-scrollbar.css') }}" rel="stylesheet" />

  <!---Icons css-->
  <link href="{{ URL::asset('assets/css/icons.css') }}" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
  @yield('css')
  @stack('styles')
  <!-- Simplebar css -->
  <link rel="stylesheet" href="{{ URL::asset('assets/plugins/simplebar/css/simplebar.css') }}">

  <!-- Color Skin css -->
  <link id="theme" href="{{ URL::asset('assets/colors/color1.css') }}" rel="stylesheet" type="text/css" />
  <link id="theme" href="{{ URL::asset('assets/switcher.css') }}" rel="stylesheet" type="text/css" />
  <link id="theme" href="{{ URL::asset('assets/demo.css') }}" rel="stylesheet" type="text/css" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

  <!-- session messages style-->
  <link href="{{ asset('assets/css/messages/messages.css') }}" rel="stylesheet">



  <style>
      .select2-container {
          display: block;
          width: 100% !important;
      }

      table#data_table tbody * {
          font-size: 14px;
          text-align: left;
          font-weight: normal;
      }

      input[name=serial_number] {
          display: none;
      }

      label[for=serial-number] {
          display: none;
      }

      input[name=main_code] {
          display: none;
      }

      label[for=code] {
          display: none;
      }

      .custom-control.custom-checkbox.ms-3.row.input-box {
          height: 0px !important;
          min-height: 0;
          overflow: hidden;
      }

      .per-box {
          border: 1px solid #ddd;
          margin-top: 20px;
      }

      .per-box h5 {
          padding: 10px;
          text-transform: capitalize;
          font-size: 12px;
          font-weight: bold;
      }

      .per-box .row {
          padding: 0px 15px;
      }
  </style>
