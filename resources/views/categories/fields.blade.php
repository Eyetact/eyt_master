<style>
    .alert-primary {
        color: #004085 !important;
        background-color: #cce5ff !important;
        border-color: #b8daff !important;
    }
</style>
<div class="column col-sm-6">
<!-- Category Name Field -->
<div class="form-group">
    {!! Form::label('category_name', __('models/categories.fields.category_name'),['class' => 'required']) !!}
    {!! Form::text('category_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Image Field -->
<div class="form-group">
    {!! Form::hidden('old_image', $pageType == "Edit" ? $category->category_image : null, ['class' => 'form-control']) !!}
    {!! Form::label('category_image', __('models/categories.fields.image'),['class' => ($pageType == "Edit" ? '' : 'required')]) !!}
    <div class="input-group">
        <div class="custom-file">
            {!! Form::file('category_image', ['class' => 'custom-file-input','id' => 'category_image']) !!}
            {!! Form::label('category_image', 'Choose file', ['class' => 'custom-file-label']) !!}
        </div>
    </div>
    <div class="alert alert-primary mt-2">
        <b>NOTE : </b>  Add info for recommended size for Icon - 30*30.
    </div>
</div>
<div class="clearfix"></div>
<div class="" id="image-error">
</div>
@if($pageType == "Edit" && $category->category_image)
<br/>
<p>
    <img src={{ env('AWS_URL').$category->category_image }} height="150" width="200">
</p>
@endif
</div>