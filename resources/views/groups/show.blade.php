
    <style>
        .profile-upload {
            transition: .5s ease;
            opacity: 0;
            position: absolute;
            top: 50%;
            left: 65px;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            text-align: center;
        }

        .profile-upload i {
            font-size: 34px;
            color: #705ec8;
        }

        .img-container {
            position: relative;
            width: 17%;
        }

        .img-container:hover .profile-img {
            opacity: 0.3;
        }

        .img-container .profile-img {
            width: auto;
            height: 100%;
        }

        .img-container:hover .profile-upload {
            opacity: 1;
        }

        .profile-img {
            opacity: 1;
            display: block;
            transition: .5s ease;
            backface-visibility: hidden;
            max-height: 131px;
        }
        .dt-buttons.btn-group {
            float: left;
        }
    </style>
    <!--/app header-->
    <div class="main-proifle">
        <h4 class="pro-user-username mb-3 font-weight-bold">{{ $group->name }} </h4>

    </div>

    <div class="profile-cover">
        <div class="wideget-user-tab">
            <div class="tab-menu-heading p-0">
                <div class="tabs-menu1 px-3">
                    <ul class="nav">
                        <li><a href="#edit" class="active fs-14" data-toggle="tab"> Edit</a></li>
                        <li><a href="#customers" class=" fs-14" data-toggle="tab"> Customers</a></li>



                    </ul>
                </div>
            </div>
        </div>
    </div><!-- /.profile-cover -->
    <!-- Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="border-0">
                <div class="tab-content">
                    <div class="tab-pane active" id="edit">
                        <div class="card">
                            <form action="{{ route('groups.update', $group->id) }}" method="POST" id="editProfile"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-header">
                                    <div class="card-title">Edit group</div>
                                </div>
                                <div class="card-body">
                                    <div class="row">



                                        @if(auth()->user()->hasRole('super'))

                                        <div class="col-lg-6 col-sm-6">
                                            <div class="input-box">
                                                <label class="input-label">Name</label>
                                                <input type="text" class="google-input" name="name" id="name"
                                                    value="{{ $group->name }}" />
                                            </div>
                                            @error('name')
                                                <label id="name-error" class="error"
                                                    for="name">{{ $message }}</label>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6 col-sm-6">
                                        <div class="input-box">
                                            <select class="google-input" name="type" tabindex="null">
                                                <option value="" disabled>-- select type --</option>
                                                <option value="admin" {{ $group->type == 'admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="public_vendor" {{ $group->type == 'public_vendor' ? 'selected' : '' }}>Public Vendor</option>
                                            </select>
                                        </div>
                                        @error('type')
                                            <label id="type-error" class="error" for="type">{{ $message }}</label>
                                        @enderror
                                        </div>

                                        @else

                                        <div class="col-lg-12 col-sm-12">
                                            <div class="input-box">
                                                <label class="input-label">Name</label>
                                                <input type="text" class="google-input" name="name" id="name"
                                                    value="{{ $group->name }}" />
                                            </div>
                                            @error('name')
                                                <label id="name-error" class="error"
                                                    for="name">{{ $message }}</label>
                                            @enderror
                                        </div>



                                    @endif

                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane " id="customers">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Users Data</div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-nowrap" id="attribute_table2" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="100px">No.</th>
                                                <th>Name</th>
                                                <th>username</th>
                                                <th>email</th>
                                                <th>avatar</th>
                                                <th>phone</th>
                                                <th>address</th>
                                                <th>website</th>
                                                <th data-priority="1"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        var table = $('#attribute_table2').DataTable({
           processing: true,
           serverSide: true,
           lengthChange: false,
           // dom: 'lBftrip',
           // buttons: ['copy', 'excel', 'pdf', 'colvis'],
           responsive: true,
           language: {
               searchPlaceholder: 'Search...',
               sSearch: '',
               lengthMenu: '_MENU_ ',
           },
           ajax: "{{ route('groups.view2', $group->id) }}",

           columns: [{
                   data: 'DT_RowIndex',
                   name: 'DT_RowIndex',
                   orderable: false,
                   searchable: false
               },
               {
                   data: 'name',
                   name: 'name'
               },
               {
                   data: 'username',
                   name: 'username'
               },

               {
                   data: 'email',
                   name: 'email'
               },
               {
                   data: 'avatar',
                   name: 'avatar',
               },
               {
                   data: 'phone',
                   name: 'phone'
               },
               {
                   data: 'address',
                   name: 'address'
               },
               {
                   data: 'website',
                   name: 'website'
               },

               {
                   data: 'action',
                   name: 'action',
                   orderable: false,
                   searchable: false
               },

           ],
           order: [
               [1, 'asc']
           ]
       });

</script>
