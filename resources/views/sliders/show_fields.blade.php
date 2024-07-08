<div class="column col-sm-6">
    <!-- Category Name Field -->
    <div class="col-sm-12">
        {!! Form::label('category_name', __('models/sliders.fields.category_name') . ':') !!}
        <p>{{ $category->category_name }}</p>
    </div>

    <!-- Image Field -->
    <div class="col-sm-12">
        {!! Form::label('category_image', __('models/newsEvents.fields.image')) !!}
        <p>
            <img src={{ env('AWS_URL') . $category->category_image }} height="150" width="200">
        </p>
    </div>

    <!-- Created By Field -->
    <div class="col-sm-12">
        {!! Form::label('created_by', __('models/sliders.fields.created_by') . ':') !!}
        <p>{{ $category->createdUser ? $category->createdUser->name : '' }}</p>
    </div>

    <!-- Updated By Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('models/sliders.fields.updated_by') . ':') !!}
        <p>{{ $category->updatedUser ? $category->updatedUser->name : '' }}</p>
    </div>
</div>
<div class="column col-sm-6">
    <!-- Created At Field -->
    <div class="col-sm-12">
        {!! Form::label('created_at', __('models/sliders.fields.created_at') . ':') !!}
        <p>{{ $category->created_at }}</p>
    </div>

    <!-- Updated At Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('models/sliders.fields.updated_at') . ':') !!}
        <p>{{ $category->updated_at }}</p>
    </div>
</div>
