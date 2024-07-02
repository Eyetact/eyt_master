		<!-- Back to top -->
		<a href="#top" id="back-to-top"><i class="fe fe-chevrons-up"></i></a>

		<!-- Jquery js-->
		<script src="{{URL::asset('assets/js/jquery-3.5.1.min.js')}}"></script>

        <!-- Jquery Validate JS -->
        <script src="{{ asset('assets/plugins/forn-wizard/js/jquery.validate.min.js') }}"></script>

		<!-- Bootstrap4 js-->
		<script src="{{URL::asset('assets/plugins/bootstrap/popper.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Nestable/2012-10-15/jquery.nestable.min.js" integrity="sha512-a3kqAaSAbp2ymx5/Kt3+GL+lnJ8lFrh2ax/norvlahyx59Ru/1dOwN1s9pbWEz1fRHbOd/gba80hkXxKPNe6fg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<!--Othercharts js-->
		<script src="{{URL::asset('assets/plugins/othercharts/jquery.sparkline.min.js')}}"></script>

		<!-- Circle-progress js-->
		<script src="{{URL::asset('assets/js/circle-progress.min.js')}}"></script>

		<!-- Jquery-rating js-->
		<script src="{{URL::asset('assets/plugins/rating/jquery.rating-stars.js')}}"></script>

		<!--Sidemenu js-->
		<script src="{{URL::asset('assets/plugins/sidemenu/sidemenu.js')}}"></script>

		<!-- P-scroll js-->
		<script src="{{URL::asset('assets/plugins/p-scrollbar/p-scrollbar.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/p-scrollbar/p-scroll1.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/p-scrollbar/p-scroll.js')}}"></script>
		<script src="{{URL::asset('assets/js/switcher.js')}}"></script>
        <!-- Toastr JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
		@yield('js')
        @stack('script')
		<!-- Simplebar JS -->
		<script src="{{URL::asset('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
		<!-- Custom js-->
		<script src="{{URL::asset('assets/js/custom.js')}}"></script>

        <script type="text/javascript">
        /* multiple select */
        $('select[multiple]').select2({
            dir: "ltr"
        });
            @if(Session::get('success'))
                toastr.success("{{ Session::get('success') }}");
            @endif

            $(document.body).on('change','#ModeChangeDropdown',function (){
                console.log($(this).val());
               console.log('Mode Changed');
            });

            // function setFocus(on) {
            //     var element = document.activeElement;
            //     if (on) {
            //         setTimeout(function () {
            //             element.parentNode.classList.add("focus");
            //         });
            //     } else {
            //         let box = document.querySelector(".input-box");
            //         box.classList.remove("focus");
            //         $("input").each(function () {
            //             var $input = $(this);
            //             var $parent = $input.closest(".input-box");
            //             if ($input.val()) $parent.addClass("focus");
            //             else $parent.removeClass("focus");
            //         });
            //     }
            // }
            $("input[type='text'], input[type='number'], input[type='password'], input[type='email'], input[type='tel']").on( "focus, blur", function() {
                var $input = $(this);
                if($input.val() != ''){
                    $input.parents(".input-box").addClass("focus");
                }else{
                    $input.parents(".input-box").removeClass("focus");
                }
            } );
            $(document.body).on('focus',"input[type='text'], input[type='number'], input[type='password'], input[type='email'], input[type='tel']",function (){
                var $input = $(this);
                if($input.val() != ''){
                    $input.parents(".input-box").addClass("focus");
                }else{
                    $input.parents(".input-box").removeClass("focus");
                }
            })
            $(document.body).on('keyup',"input[type='text'], input[type='number'], input[type='password'], input[type='email'], input[type='tel']",function (){
                var $input = $(this);
                if($input.val() != ''){
                    $input.parents(".input-box").addClass("focus");
                }else{
                    $input.parents(".input-box").removeClass("focus");
                }
            })
            $("input[type='text'], input[type='number'], input[type='password'], input[type='email'], input[type='tel']").on( "keyup", function() {
                var $input = $(this);
                if($input.val() != ''){
                    $input.parents(".input-box").addClass("focus");
                }else{
                    $input.parents(".input-box").removeClass("focus");
                }
            } );
            $("input[type='text'], input[type='number'], input[type='password'], input[type='email'], input[type='tel']").each(function(index, element) {
                var value = $(element).val();
                if(value != ''){
                    $(this).parents('.input-box').addClass('focus');
                }else{
                    $(this).parents('.input-box').removeClass('focus');
                }
            });
            $(document.body).on('click','.theme-setting',function () {
                console.log('changed');
                setTimeout(function () {
                    themeUpdate();
                },100);
            })
            function themeUpdate(){
                var bodyClassList = $('body').prop('classList');
                var classListString = Array.from(bodyClassList).join(' ');
                $.ajax({
                    type: 'POST',
                    url: "{{ route('update.theme') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'classes': classListString,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(data) {
                    },
                    error: function(data) {
                    }
                });
            }
            @php
                $classes = \App\Helpers\Helper::getThemeClasses();
                $classesList = explode(' ',$classes);
            @endphp
            @foreach($classesList as $item)
            $('.{{$item}}-radio').prop('checked',true);
            @endforeach
        </script>
