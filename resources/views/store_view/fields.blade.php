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
        {!! Form::label('region', __('models/store_view.fields.region'), ['class' => 'required']) !!}
        {{-- {!! Form::text('region', null, ['class' => 'form-control']) !!} --}}
        <div class="select2-blue">
            {!! Form::select('region', ['' => 'Select Region'] + $countryRegion->toArray(), null, [
                'class' => 'form-control',
                'multiple' => false,
            ]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('language', __('models/store_view.fields.language'), ['class' => 'required']) !!}
        {!! Form::text('language', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('slug', __('models/store_view.fields.slug'), ['class' => 'required']) !!}
        {!! Form::text('slug', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('description', __('models/store_view.fields.description'), ['class' => 'required']) !!}
        {!! Form::text('description', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('meta_description', __('models/store_view.fields.meta_description'), ['class' => 'required']) !!}
        {!! Form::text('meta_description', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('meta_keywords', __('models/store_view.fields.meta_keywords'), ['class' => 'required']) !!}
        {!! Form::text('meta_keywords', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('status', __('models/store_view.fields.status'), ['class' => 'required']) !!}
        <br>
        <label class="custom-switch form-label">
            <input type="checkbox" name="status" class="custom-switch-input" id="status"
                {{ old('status', $pageType== "Edit" && $category->status == 'active') ? 'checked' : '' }}>
            <span class="custom-switch-indicator"></span>
        </label>
    </div>
</div>
