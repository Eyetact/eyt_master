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
        {!! Form::label('store_view', __('models/pages.fields.store_view'), ['class' => 'required']) !!}
        <div class="select2-blue">
            {!! Form::select('store_view', ['' => 'Select Store View'] + $StoreView->toArray(), null, [
                'class' => 'form-control',
                'multiple' => false,
            ]) !!}
        </div>
        {{-- {!! Form::select(
            'store_view',
            ['DRAFT' => 'DRAFT', 'PUBLISHED' => 'PUBLISHED', 'INACTIVE' => 'INACTIVE'],
            null,
            ['class' => 'form-control custom-select'],
        ) !!} --}}
    </div>

    <div class="form-group">
        {!! Form::label('name', __('models/pages.fields.name'), ['class' => 'required']) !!}
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('description', __('models/pages.fields.description'), ['class' => 'required']) !!}
        {!! Form::text('description', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('title', __('models/pages.fields.title'), ['class' => 'required']) !!}
        {!! Form::text('title', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('meta_date', __('models/pages.fields.meta_date'), ['class' => 'required']) !!}
        {!! Form::text('meta_date', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('meta_keywords', __('models/pages.fields.meta_keywords'), ['class' => 'required']) !!}
        {!! Form::text('meta_keywords', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('slug', __('models/pages.fields.slug'), ['class' => 'required']) !!}
        {!! Form::text('slug', null, ['class' => 'form-control']) !!}
    </div>
</div>
