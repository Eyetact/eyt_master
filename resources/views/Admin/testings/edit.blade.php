

                    <form action="{{ route('testings.update', $testing->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @include('admin.testings.include.form')
                          @include('admin.testings.include.dropdown')

                        <a href="{{ url()->previous() }}" class="btn btn-secondary">{{ __('Back') }}</a>

                        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                    </form>
