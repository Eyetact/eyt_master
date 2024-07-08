<form action="{{ route('xyzs.store') }}" method="POST"  enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        @php
                    
                    $model = App\Models\Module::where('migration','Like', '%'.'xyzs'.'%')->first();

                    $parent = App\Models\Module::find($model?->parent_id);
                    if($parent){
                        $parent_folder = App\Generators\GeneratorUtils::pluralSnakeCase($parent?->code);
                    }
                    // dd($model_id);
                @endphp

                @if($model->addable)
                        @include("admin.$parent_folder.include.form")

                @endif

                        @include('admin.xyzs.include.form')

                        @include('admin.xyzs.include.dropdown')

                        <a href="{{ url()->previous() }}" class="btn btn-secondary">{{ __('Back') }}</a>

                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </form>