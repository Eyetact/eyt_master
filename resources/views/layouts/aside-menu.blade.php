<aside class="app-sidebar">
    <div class="app-sidebar__logo">
        <a class="header-brand" href="{{ url('/dashboard') }}">
            <img src="{{ Auth::user()?->settings?->logo ? URL::asset('uploads/logo/' . Auth::user()->settings->logo) : '' }}"
                class="header-brand-img desktop-lgo" alt="Admintro logo">
            <img src="{{ Auth::user()?->settings?->logo ? URL::asset('uploads/logo/' . Auth::user()->settings->dark_logo) : '' }}"
                class="header-brand-img dark-logo" alt="Admintro logo">
            <img src="{{ URL::asset('assets/images/brand/favicon.png') }}" class="header-brand-img mobile-logo"
                alt="Admintro logo">
            <img src="{{ URL::asset('assets/images/brand/favicon1.png') }}" class="header-brand-img darkmobile-logo"
                alt="Admintro logo">
        </a>
    </div>
    <div class="app-sidebar__user">
        <div class="dropdown user-pro-body text-center">
            <div class="user-pic">
                <img src="{{ Auth::user()->profile_url }}" alt="user-img" class="avatar-xl rounded-circle mb-1">
            </div>
            <div class="user-info">
                <h5 class=" mb-1">{{ Auth::user()->name }} <i class="ion-checkmark-circled  text-success fs-12"></i>
                </h5>
                <span class="text-muted app-sidebar__user-name text-sm">{{ Auth::user()->username }} </span>
            </div>
        </div>
    </div>
    <ul class="side-menu app-sidebar3">
        <li class="side-item side-item-category mt-4">Main</li>
        <li class="slide">
            <a class="side-menu__item" href="{{ url('/dashboard') }}">
                <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height  ="24" viewBox="0 0 24 24"
                    width="24">
                    <path d="M0 0h24v24H0V0z" fill="none" />
                    <path
                        d="M19 5v2h-4V5h4M9 5v6H5V5h4m10 8v6h-4v-6h4M9 17v2H5v-2h4M21 3h-8v6h8V3zM11 3H3v10h8V3zm10 8h-8v10h8V11zm-10 4H3v6h8v-6z" />
                </svg>
                <span class="side-menu__label">Dashboard</span></a>
        </li>
        <li class="slide">
            <a class="side-menu__item" href="{{ url('module_manager') }}">
                <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height  ="24" viewBox="0 0 24 24"
                    width="24">
                    <path d="M0 0h24v24H0V0z" fill="none" />
                    <path
                        d="M19 5v2h-4V5h4M9 5v6H5V5h4m10 8v6h-4v-6h4M9 17v2H5v-2h4M21 3h-8v6h8V3zM11 3H3v10h8V3zm10 8h-8v10h8V11zm-10 4H3v6h8v-6z" />
                </svg>
                <span class="side-menu__label">Modules Manager</span></a>
        </li>
        <li class="slide">
            <a class="side-menu__item" href="{{ url('attribute') }}">
                <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height  ="24" viewBox="0 0 24 24"
                    width="24">
                    <path d="M0 0h24v24H0V0z" fill="none" />
                    <path
                        d="M19 5v2h-4V5h4M9 5v6H5V5h4m10 8v6h-4v-6h4M9 17v2H5v-2h4M21 3h-8v6h8V3zM11 3H3v10h8V3zm10 8h-8v10h8V11zm-10 4H3v6h8v-6z" />
                </svg>
                <span class="side-menu__label">Attributes</span></a>
        </li>






        <li class="slide">
            <a class="side-menu__item" href="{{ url('data') }}">
                <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height  ="24" viewBox="0 0 24 24"
                    width="24">
                    <path d="M0 0h24v24H0V0z" fill="none" />
                    <path
                        d="M19 5v2h-4V5h4M9 5v6H5V5h4m10 8v6h-4v-6h4M9 17v2H5v-2h4M21 3h-8v6h8V3zM11 3H3v10h8V3zm10 8h-8v10h8V11zm-10 4H3v6h8v-6z" />
                </svg>
                <span class="side-menu__label">Data Manager</span></a>
        </li>

        @if (auth()->user()->can('mail.settings') || auth()->user()->hasRole('super'))
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                    <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                        width="24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z" />
                    </svg>
                    <span class="side-menu__label">Mail Settings</span><i class="angle fa fa-angle-right"></i></a>
                <ul class="slide-menu ">
                    <li class="sub-slide">
                        <a class="sub-side-menu__item" href="{{ route('main_mailbox.index') }}"><span
                                class="sub-side-menu__label">Mailbox Settings</span></a>
                    </li>
                    <li class="sub-slide">
                        <a class="sub-side-menu__item" href="{{ route('smtp.index') }}"><span
                                class="sub-side-menu__label">SMTP Settings</span></a>
                    </li>
                </ul>
            </li>
        @endif
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                    width="24">
                    <path d="M0 0h24v24H0V0z" fill="none" />
                    <path
                        d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z" />
                </svg>
                <span class="side-menu__label">Configuration</span><i class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu ">
                <li class="sub-slide">
                    <a class="sub-side-menu__item" href="{{ route('setting.index') }}"><span
                            class="sub-side-menu__label">Settings</span></a>
                </li>
            </ul>


        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                    width="24">
                    <path d="M0 0h24v24H0V0z" fill="none" />
                    <path
                        d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z" />
                </svg>
                <span class="side-menu__label">File Manager</span><i class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu ">
                <li class="sub-slide">
                    <a class="sub-side-menu__item" href="{{ route('files') }}"><span
                            class="sub-side-menu__label">Settings</span></a>
                </li>
            </ul>


        </li>
    </ul>
    <ul class="side-menu app-sidebar3">
        <li class="side-item side-item-category mt-4">Module Manger</li>

        @php
            if (auth()->user()->hasRole('super')) {
                $module_ids = \App\Models\Module::where('user_id', auth()->user()->id)->pluck('id');

                $side_menus = \App\Models\MenuManager::with('children.children.children')
                    ->where('parent', '0')
                    ->where('is_delete', 0)
                    ->where('include_in_menu', 1)
                    ->whereIn('module_id', $module_ids) // Filter by module_id
                    ->orderBy('sequence', 'asc')
                    ->get();
            }
        @endphp



        @foreach ($side_menus as $item)
            {{-- @php
            echo json_encode($item->childrens(),true)
        @endphp --}}
            @php

                $pers = [];
                array_push(
                    $pers,
                    'view.' .
                        str($item->module->code)
                            ->singular()
                            ->lower(),
                );

            @endphp
            @foreach ($item->childrens() as $item2)
                @php
                    array_push(
                        $pers,
                        'view.' .
                            str($item2->module->code)
                                ->singular()
                                ->lower(),
                    );
                @endphp
            @endforeach
            @if (Auth::user()->hasAnyPermission($pers) || Auth::user()->hasRole('super'))
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $item->path) }}">
                        <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24"
                            viewBox="0 0 24 24" width="24">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path
                                d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z" />
                        </svg>
                        <span class="side-menu__label">{{ $item->sidebar_name }}</span>
                        <i class="angle fe fe-chevron-right"></i>
                    </a>
                    <ul class="slide-menu ">
                        @can('view.' .
                            str($item->module->code)->singular()->lower())
                            <li class="sub-slide">
                                <a class="sub-side-menu__item" href="{{ url('/' . $item->path) }}"><span
                                        class="sub-side-menu__label">{{ $item->name }}</span></a>
                            </li>
                        @endcan

                        @foreach ($item->childrens() as $item)
                            @can('view.' .
                                str($item->module->code)->singular()->lower())
                                <li class="sub-slide">
                                    <a class="sub-side-menu__item"
                                        @if (count($item->childrens())) data-toggle="sub-slide" @endif
                                        href="{{ url('/' . $item->path) }}"><span
                                            class="sub-side-menu__label">{{ $item->name }}</span>

                                        @if (count($item->children) > 0)
                                            <i class="sub-angle fe fe-chevron-down"></i>
                                        @endif
                                    </a>

                                    @if (count($item->childrens()))
                                        <ul class="sub-slide-menu">
                                            <li><a class="sub-slide-item"
                                                    href="{{ url('/' . $item->path) }}">{{ $item->name }}</a></li>

                                            @foreach ($item->childrens() as $item)
                                                @can('view.' .
                                                    str($item->module->code)->singular()->lower())
                                                    <li><a class="sub-slide-item"
                                                            href="{{ url('/' . $item->path) }}">{{ $item->name }}</a></li>
                                                @endcan
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endcan
                        @endforeach
                        {{-- <li class="sub-slide is-expanded">
                        <a class="sub-side-menu__item" data-toggle="sub-slide" href="https://laravel.spruko.com/admitro/Vertical-IconSidedar-Light/#"><span class="sub-side-menu__label">File Manager</span><i class="sub-angle fe fe-chevron-down"></i></a>

                    </li> --}}
                    </ul>


                </li>
            @endif
        @endforeach
    </ul>

    <ul class="side-menu app-sidebar3">
        <li class="side-item side-item-category mt-4">Basic</li>
        {{-- <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                    width="24">
                    <path d="M0 0h24v24H0V0z" fill="none" />
                    <path
                        d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z" />
                </svg>
                <span class="side-menu__label">Settings</span><i class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu ">

                 <li class="sub-slide">
                    <a class="sub-side-menu__item" href="{{ url('module_manager') }}"><span
                            class="sub-side-menu__label">Modules Manager</span></a>
                </li>
            </ul>
        </li> --}}


        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                    width="24">
                    <path d="M0 0h24v24H0V0z" fill="none" />
                    <path
                        d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z" />
                </svg>
                <span class="side-menu__label">Customers</span><i class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu ">

                @role('super')
                    <li class="sub-slide">
                        {{-- {{ url('module')}} --}}
                        <a class="sub-side-menu__item" href="{{ route('users.admins') }}"><span
                                class="sub-side-menu__label">Admins</span></a>
                    </li>

                    <li class="sub-slide">
                        {{-- {{ url('module')}} --}}
                        <a class="sub-side-menu__item" href="{{ route('users.pvendors') }}"><span
                                class="sub-side-menu__label">Public Vendors</span></a>
                    </li>
                @endrole

                @hasanyrole('super|admin')
                    <li class="sub-slide">
                        {{-- {{ url('module')}} --}}
                        <a class="sub-side-menu__item" href="{{ route('users.vendors') }}"><span
                                class="sub-side-menu__label">Vendors</span></a>
                    </li>
                @endhasanyrole

                @hasanyrole('super|admin')
                    <li class="sub-slide">
                        {{-- {{ url('module')}} --}}
                        <a class="sub-side-menu__item" href="{{ route('groups.index') }}"><span
                                class="sub-side-menu__label">Groups</span></a>
                    </li>
                @endhasanyrole


            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                    width="24">
                    <path d="M0 0h24v24H0V0z" fill="none" />
                    <path
                        d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z" />
                </svg>
                <span class="side-menu__label">permissions</span><i class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu ">

                {{--
                @if (Auth::user()->hasAnyPermission(['view.role']))
                <li class="{{ request()->is('role') ? 'active' : '' }}"><a class="menu-item" href="{{ route('role.index') }}" data-i18n="Roles">Roles</a>
                </li>
                @endif --}}



                @if (Auth::user()->hasAnyPermission(['view.role']))
                    <li class="sub-slide {{ request()->is('role') ? 'active' : '' }}"><a class="sub-side-menu__item"
                            href="{{ route('role.index') }}"><span class="sub-side-menu__label">Roles</span></a>
                    </li>
                @endif


                @if (Auth::user()->hasAnyPermission(['view.permission']))
                    <li class="sub-slide"><a class="sub-side-menu__item"
                            href="{{ route('permission.index') }}"><span
                                class="sub-side-menu__label">Permissions</span></a>
                    </li>
                @endif
                @if (Auth::user()->hasAnyPermission(['view.user']))
                    <li class="sub-slide">
                        {{-- {{ url('module')}} --}}
                        <a class="sub-side-menu__item" href="{{ route('ugroups.index') }}"><span
                                class="sub-side-menu__label">Groups</span></a>
                    </li>
                    <li class="sub-slide">
                        {{-- {{ url('module')}} --}}
                        <a class="sub-side-menu__item" href="{{ route('users.users') }}"><span
                                class="sub-side-menu__label">Users</span></a>
                    </li>
                @endif


            </ul>
        </li>
        @if (Auth::user()->hasAnyPermission(['view.plan', 'view.subscription']))
            <li class="slide">

                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                    <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24"
                        viewBox="0 0 24 24" width="24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M12 2L2 7l10 5 9-4.5L12 2zm0 18l-9-4.5L2 17V7l10 5 10-5v10l-1.5-.75L12 20z" />
                    </svg>
                    <span class="side-menu__label">Subscriptions</span><i class="angle fa fa-angle-right"></i>
                </a>

                <ul class="slide-menu ">
                    @can('view.subscription')
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('subscriptions.index') }}"><span
                                    class="sub-side-menu__label">Subscriptions</span></a>
                        </li>
                    @endcan
                    @can('view.plan')
                        <li class="sub-slide">
                            {{-- {{ url('module')}} --}}
                            <a class="sub-side-menu__item" href="{{ route('plans.index') }}"><span
                                    class="sub-side-menu__label">Plans</span></a>
                        </li>
                    @endcan


                </ul>
            </li>
        @endif
    </ul>
</aside>
<!--aside closed-->
