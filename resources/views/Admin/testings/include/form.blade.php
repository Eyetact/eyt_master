<div class="row mb-2">
@php
$model = \App\Models\Module::where('code', App\Generators\GeneratorUtils::singularSnakeCase('Testing'))
->orWhere('code', App\Generators\GeneratorUtils::pluralSnakeCase('Testing'))
->first();
$constrain_name = App\Generators\GeneratorUtils::singularSnakeCase('Testing');
if ($model) {
$for_attr = json_encode($model->fields()->select('code','attribute')->where('type', 'foreignId')->orWhere('primary', 'lookup')
        ->orWhere('type', 'fk')->get());
$for_attr = str_replace('"', "'", $for_attr);
}
@endphp
@if($model->is_system && auth()->user()->hasAnyRole(['vendor', 'admin'])  && !isset($testing) )
<div class="form-group col-sm-8">
<label class="custom-switch form-label">
<input type="hidden" name="global" value="0"> <!-- Hidden input as default value -->
<input type="checkbox" name="global" value="1" class="custom-switch-input" id="global-1"
{{ isset($testing) && $testing->global == '1' ? 'checked' : '' }}>
<span class="custom-switch-indicator"></span>
<span class="custom-switch-description">Add to global data</span>
</label>
</div>
@endif
@if($model->is_system && auth()->user()->hasRole('super') && isset($testing) )
<div class="col-md-12">
<div class="input-box">
<label for="status">{{ __('Status') }}</label>
<select data-constrain="{{ $constrain_name }}" data-source="Disable" data-attrs={!! isset($for_attr) ? $for_attr : '' !!} class="google-input @error('status') is-invalid @enderror" name="status" id="status" class="form-control">
<option value="" selected disabled>-- {{ __('Select status') }} --</option>
<option value="active" {{ isset($testing) && $testing->status == 'active' ? 'selected' : ('inactive' == 'active' ? 'selected' : '') }}>active</option>
<option value="inactive" {{ isset($testing) && $testing->status == 'inactive' ? 'selected' : ('inactive' == 'inactive' ? 'selected' : '') }}>inactive</option>
<option value="pending" {{ isset($testing) && $testing->status == 'pending' ? 'selected' : ('inactive' == 'pending' ? 'selected' : '') }}>pending</option>
</select>
@error('status')
<span class="text-danger">
{{ $message }}
</span>
@enderror
</div>
</div>
@endif
</div>