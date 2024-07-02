@extends('layouts.master')
@section('css')
    <!-- Data table css -->
    <link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}"  rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
    <!-- Slect2 css -->
    <link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

@endsection
@section('page-header')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">Modules</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Settings</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Modules</a></li>
            </ol>
        </div>
        <div class="page-rightheader">
            <div class="btn btn-list">
                <a href="{{route('module.create')}}" class="btn btn-info" data-toggle="tooltip" title="" data-original-title="Add new"><i class="fe fe-plus mr-1"></i> Add new </a>
            </div>
        </div>
    </div>
    <!--End Page header-->
@endsection
@section('content')
    <!-- Row -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Modules Data</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap draggable-table" id="module_table">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Occupation</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>April Douglas</td>
                                    <td>Health Educator</td>
                                </tr>
                                <tr>
                                    <td>Salma Mcbride</td>
                                    <td>Mental Health Counselor</td>
                                </tr>
                                <tr>
                                    <td>Kassandra Donovan</td>
                                    <td>Makeup Artists</td>
                                </tr>
                                <tr>
                                    <td>Yosef Hartman</td>
                                    <td>Theatrical and Performance</td>
                                </tr>
                                <tr>
                                    <td>Ronald Mayo</td>
                                    <td>Plant Etiologist</td>
                                </tr>
                                <tr>
                                    <td>Trey Woolley</td>
                                    <td>Maxillofacial Surgeon</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->

    </div>
    </div><!-- end app-content-->
    </div>
@endsection
@section('js')
    <!-- INTERNAL Data tables -->
    <script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/datatables.js')}}"></script>
    <script src="{{URL::asset('assets/js/popover.js')}}"></script>

    <!-- INTERNAL Sweet alert js -->
    <script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/sweet-alert.js')}}"></script>


    <!-- INTERNAL Select2 js -->
    <script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>

    <script type="text/javascript">
        var table = $('#module_table').DataTable({
            // processing: true,
            // serverSide: true,
            {{--ajax: "{{ route('module.index') }}",--}}
            {{--columns: [{--}}
            {{--    data: 'DT_RowIndex',--}}
            {{--    name: 'DT_RowIndex',--}}
            {{--    orderable: false,--}}
            {{--    searchable: false--}}
            {{--},--}}
            {{--    {--}}
            {{--        data: 'name',--}}
            {{--        name: 'name'--}}
            {{--    },--}}
            {{--    {--}}
            {{--        data: 'description',--}}
            {{--        name: 'description'--}}
            {{--    },--}}
            {{--    {--}}
            {{--        data: 'action',--}}
            {{--        name: 'action',--}}
            {{--        orderable: false,--}}
            {{--        searchable: false--}}
            {{--    }],--}}
            {{--order: [--}}
            {{--    [1, 'asc']--}}
            {{--]--}}

        });

        (function () {
            "use strict";

            const table = document.getElementById("module_table");
            const tbody = table.querySelector("tbody");

            var currRow = null,
                dragElem = null,
                mouseDownX = 0,
                mouseDownY = 0,
                mouseX = 0,
                mouseY = 0,
                mouseDrag = false;

            function init() {
                bindMouse();
            }

            function bindMouse() {
                document.addEventListener("mousedown", (event) => {
                    if (event.button != 0) return true;

                    let target = getTargetRow(event.target);
                    if (target) {
                        currRow = target;
                        addDraggableRow(target);
                        currRow.classList.add("is-dragging");

                        let coords = getMouseCoords(event);
                        mouseDownX = coords.x;
                        mouseDownY = coords.y;

                        mouseDrag = true;
                    }
                });

                document.addEventListener("mousemove", (event) => {
                    if (!mouseDrag) return;

                    let coords = getMouseCoords(event);
                    mouseX = coords.x - mouseDownX;
                    mouseY = coords.y - mouseDownY;

                    moveRow(mouseX, mouseY);
                });

                document.addEventListener("mouseup", (event) => {
                    if (!mouseDrag) return;

                    currRow.classList.remove("is-dragging");
                    table.removeChild(dragElem);

                    dragElem = null;
                    mouseDrag = false;
                });
            }

            function swapRow(row, index) {
                let currIndex = Array.from(tbody.children).indexOf(currRow),
                    row1 = currIndex > index ? currRow : row,
                    row2 = currIndex > index ? row : currRow;

                tbody.insertBefore(row1, row2);
            }

            function moveRow(x, y) {
                dragElem.style.transform = "translate3d(" + x + "px, " + y + "px, 0)";

                let dPos = dragElem.getBoundingClientRect(),
                    currStartY = dPos.y,
                    currEndY = currStartY + dPos.height,
                    rows = getRows();

                for (var i = 0; i < rows.length; i++) {
                    let rowElem = rows[i],
                        rowSize = rowElem.getBoundingClientRect(),
                        rowStartY = rowSize.y,
                        rowEndY = rowStartY + rowSize.height;

                    if (
                        currRow !== rowElem &&
                        isIntersecting(currStartY, currEndY, rowStartY, rowEndY)
                    ) {
                        if (Math.abs(currStartY - rowStartY) < rowSize.height / 2)
                            swapRow(rowElem, i);
                    }
                }
            }

            function addDraggableRow(target) {
                dragElem = target.cloneNode(true);
                dragElem.classList.add("draggable-table__drag");
                dragElem.style.height = getStyle(target, "height");
                if($('body').hasClass('dark-mode')){
                    dragElem.style.background = '#565985';
                }else{
                    dragElem.style.background = '#ffffff';
                }
                for (var i = 0; i < target.children.length; i++) {
                    let oldTD = target.children[i],
                        newTD = dragElem.children[i];
                    newTD.style.width = getStyle(oldTD, "width");
                    newTD.style.height = getStyle(oldTD, "height");
                    newTD.style.padding = getStyle(oldTD, "padding");
                    newTD.style.margin = getStyle(oldTD, "margin");
                }

                table.appendChild(dragElem);

                let tPos = target.getBoundingClientRect(),
                    dPos = dragElem.getBoundingClientRect();
                dragElem.style.bottom = dPos.y - tPos.y - tPos.height + "px";
                dragElem.style.left = "-1px";

                document.dispatchEvent(
                    new MouseEvent("mousemove", {
                        view: window,
                        cancelable: true,
                        bubbles: true
                    })
                );
            }

            function getRows() {
                return table.querySelectorAll("tbody tr");
            }

            function getTargetRow(target) {
                let elemName = target.tagName.toLowerCase();

                if (elemName == "tr") return target;
                if (elemName == "td") return target.closest("tr");
            }

            function getMouseCoords(event) {
                return {
                    x: event.clientX,
                    y: event.clientY
                };
            }

            function getStyle(target, styleName) {
                let compStyle = getComputedStyle(target),
                    style = compStyle[styleName];

                return style ? style : null;
            }

            function isIntersecting(min0, max0, min1, max1) {
                return (
                    Math.max(min0, max0) >= Math.min(min1, max1) &&
                    Math.min(min0, max0) <= Math.max(min1, max1)
                );
            }

            init();
        })();


    </script>
@endsection
