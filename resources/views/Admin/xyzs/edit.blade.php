

                    <form action="{{ route('xyzs.update', $xyz->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @include('admin.xyzs.include.form')
                          @include('admin.xyzs.include.dropdown')

                        <a href="{{ url()->previous() }}" class="btn btn-secondary">{{ __('Back') }}</a>

                        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                    </form>
