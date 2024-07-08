<form action="{{ route('aaas.store') }}" method="POST"  enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        @include('admin.aaas.include.form')

                        @include('admin.aaas.include.dropdown')

                        <a href="{{ url()->previous() }}" class="btn btn-secondary">{{ __('Back') }}</a>

                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </form>