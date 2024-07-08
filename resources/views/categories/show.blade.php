@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('models/categories.view')</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right ml-2"
                       href="javascript:void(0);" onclick="history.back();">
                        Back
                    </a>
                    @can('categories.edit')
                        <a class="btn btn-primary float-right"
                            href="{{ route('categories.edit',$category->id) }}">
                            Edit
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('categories.show_fields')
                </div>
            </div>
        </div>
    </div>
@endsection
