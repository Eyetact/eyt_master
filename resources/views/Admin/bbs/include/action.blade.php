


<td>


        <div class="dropdown">
            <a class=" dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>

            </a>

            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                <li class="dropdown-item">
                    <a href="{{route('bbs.show', $model->id)}}"  >View </a>
                    </li>
    @can('edit.bb')
     @if (\App\Helpers\Helper::canWithCount('edit.bb', $model->created_at))
                    <li class="dropdown-item">
                        <a href="#" id="edit_item"  data-path="{{route('bbs.edit', $model->id)}}">Edit</a>
                        </li>
                        @endif
    @endcan
 @can('delete.bb')
  @if (\App\Helpers\Helper::canWithCount('delete.bb', $model->created_at))

                <li class="dropdown-item">
                <a  href="#" data-id="{{$model->id}}" class="model-delete">Delete</a>
                </li>
                  @endif
                @endcan
            </ul>
        </div>

</td>
