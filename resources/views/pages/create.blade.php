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
                    <h4 class="inline">@lang('models/pages.create')</h4>
                    <div class="heading-elements mt-0">

                    </div>
                </div>
            </div>

            {!! Form::open(['route' => 'pages.store', 'files' => true, 'id' => 'categoryForm']) !!}

            <div class="card-body">
                <div class="row">
                    @include('pages.fields', ['pageType' => 'Add'])
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

<script type="text/javascript">
    $(document).ready(function() {
        $('#categoryForm').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });

        console.log("ready !!!")

        // Custom rule for alphabets only
        $.validator.addMethod("lettersOnly", function(value, element) {
            // Check if the value contains only whitespace characters
            if (/^\s+$/.test(value)) {
                return false;
            }
            // Check if the value contains only alphabetic characters and spaces
            return /^[A-Za-z\s]+$/.test(value);
        }, "Please enter only alphabetic characters");

        $("#categoryForm").validate({
            rules: {
                category_name: {
                    required: true,
                    lettersOnly: true,
                    maxlength: 50
                }
            },
            messages: {
                category_name: {
                    required: "Category name is required.",
                    maxlength: "Category name is no more than 50 characters."
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") === "category_image") {
                    error.appendTo($(
                        "#image-error")); // Adjust the selector based on your specific structure
                } else {
                    error.insertAfter(element);
                }

            },
        });
    });
</script>
