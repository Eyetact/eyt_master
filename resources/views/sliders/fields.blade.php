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
        {!! Form::label('store_view', __('models/sliders.fields.store_view'), ['class' => 'required']) !!}
        <div class="select2-blue">
            {!! Form::select('store_view', ['' => 'Select Store View'] + $StoreView->toArray(), null, [
                'class' => 'form-control',
                'multiple' => false,
            ]) !!}
        </div>
    </div>

    <!-- Category Name Field -->
    <div class="form-group">
        {!! Form::label('name', __('models/sliders.fields.name'), ['class' => 'required']) !!}
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Category Name Field -->
    <div class="form-group">
        {!! Form::label('description', __('models/sliders.fields.description'), ['class' => 'required']) !!}
        {!! Form::text('description', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Image Field -->
    <div class="form-group">
        {!! Form::hidden('old_web_image', $pageType == 'Edit' ? $category->web_image : null, [
            'class' => 'form-control',
        ]) !!}
        {!! Form::label('web_image', __('models/sliders.fields.web_image'), [
            'class' => $pageType == 'Edit' ? '' : 'required',
        ]) !!}
        <div class="input-group">
            <div class="custom-file">
                {!! Form::file('web_image', ['class' => 'custom-file-input', 'id' => 'web_image']) !!}
                {!! Form::label('web_image', 'Choose file', ['class' => 'custom-file-label']) !!}
            </div>
        </div>
        {{-- <div class="alert alert-primary mt-2">
            <b>NOTE : </b> Add info for recommended size for Icon - 30*30.
        </div> --}}
    </div>
    <div class="clearfix"></div>
    <div class="" id="image-error">
    </div>
    @if ($pageType == 'Edit' && $category->web_image)
        <br />
        <p>
            <img src={{ asset($category->web_image) }} height="150" width="200">
        </p>
    @endif

    <!-- Image Field -->
    <div class="form-group">
        {!! Form::hidden('old_mobile_image', $pageType == 'Edit' ? $category->mobile_image : null, [
            'class' => 'form-control',
        ]) !!}
        {!! Form::label('mobile_image', __('models/sliders.fields.mobile_image'), [
            'class' => $pageType == 'Edit' ? '' : 'required',
        ]) !!}
        <div class="input-group">
            <div class="custom-file">
                {!! Form::file('mobile_image', ['class' => 'custom-file-input', 'id' => 'mobile_image']) !!}
                {!! Form::label('mobile_image', 'Choose file', ['class' => 'custom-file-label']) !!}
            </div>
        </div>
        {{-- <div class="alert alert-primary mt-2">
            <b>NOTE : </b> Add info for recommended size for Icon - 30*30.
        </div> --}}
    </div>
    <div class="clearfix"></div>
    <div class="" id="image-error">
    </div>
    @if ($pageType == 'Edit' && $category->mobile_image)
        <br />
        <p>
            <img src={{ asset($category->mobile_image) }} height="150" width="200">
        </p>
    @endif

    <!-- Category Name Field -->
    <div class="form-group">
        {!! Form::label('primary_button_title', __('models/sliders.fields.primary_button_title'), [
            'class' => 'required',
        ]) !!}
        {!! Form::text('primary_button_title', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Category Name Field -->
    <div class="form-group">
        {!! Form::label('primary_button_url', __('models/sliders.fields.primary_button_url'), ['class' => 'required']) !!}
        {!! Form::url('primary_button_url', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Category Name Field -->
    <div class="form-group">
        {!! Form::label('secondary_button_title', __('models/sliders.fields.secondary_button_title'), [
            'class' => 'required',
        ]) !!}
        {!! Form::text('secondary_button_title', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Category Name Field -->
    <div class="form-group">
        {!! Form::label('secondary_button_url', __('models/sliders.fields.secondary_button_url'), [
            'class' => 'required',
        ]) !!}
        {!! Form::url('secondary_button_url', null, ['class' => 'form-control']) !!}
    </div>


</div>
