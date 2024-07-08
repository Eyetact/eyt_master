

                    <form action="{{ route('testmodules.update', $testmodule->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @include('admin.testmodules.include.form')
                          @include('admin.testmodules.include.dropdown')

                        <a href="{{ url()->previous() }}" class="btn btn-secondary">{{ __('Back') }}</a>

                        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                    </form>
