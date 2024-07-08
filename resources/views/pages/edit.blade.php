@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1></h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        <div class="card">
            <div class="card-head">
                <div class="card-header">
                    <h4 class="inline">@lang('models/pages.edit')</h4>
                    <div class="heading-elements mt-0">

                    </div>
                </div>
            </div>

            {!! Form::model($category, [
                'route' => ['pages.update', $category->id],
                'method' => 'patch',
                'id' => 'categoryEditForm',
                'files' => true,
            ]) !!}

            <div class="card-body">
                <div class="row">
                    @include('pages.fields', ['pageType' => 'Edit'])
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('pages.index') }}" class="btn btn-default">
                    @lang('crud.cancel')
                </a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection

@include('layouts.inject_script')

<script type="text/javascript">
    $(document).ready(function() {
        $('#categoryEditForm').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });

        $.validator.addMethod('validateImage', function(value, element) {
            if (element.files && element.files[0]) {
                var fileSize = element.files[0].size; // in bytes
                var fileType = element.files[0].type;

                // Check image size (e.g., maximum 2MB)
                // if (fileSize > 2 * 1024 * 1024) {
                // return false;
                // }

                // Check image type (e.g., allow only JPEG, PNG, or GIF)
                if (fileType !== 'image/jpeg' && fileType !== 'image/png' && fileType !== 'image/gif') {
                    return false;
                }
            }
            return true;
        }, 'Invalid image type.');

        // Custom rule for alphabets only
        $.validator.addMethod("lettersOnly", function(value, element) {
            // Check if the value contains only whitespace characters
            if (/^\s+$/.test(value)) {
                return false;
            }
            // Check if the value contains only alphabetic characters and spaces
            return /^[A-Za-z\s]+$/.test(value);
        }, "Please enter only alphabetic characters");

        $("#categoryEditForm").validate({
            rules: {
                category_name: {
                    required: true,
                    lettersOnly: true,
                    maxlength: 50
                },
                category_image: {
                    required: false,
                    validateImage: true
                }
            },
            messages: {
                category_name: {
                    required: "Category name is required."
                },
                category_image: {
                    required: "Category image is required."
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") === "category_image") {
                    error.appendTo($(
                        "#image-error")); // Adjust the selector based on your specific structure
                } else {
                    error.insertAfter(element);
                }

            }
        });
    });
</script>
