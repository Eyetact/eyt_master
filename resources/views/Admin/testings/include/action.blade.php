


<td>


        <div class="dropdown">
            <a class=" dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>

            </a>

            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                <li class="dropdown-item">
                    <a href="{{route('testings.show', $model->id)}}"  >View </a>
                    </li>
    @can('edit.testing')
     @if (\App\Helpers\Helper::canWithCount('edit.testing', $model->created_at))
                    <li class="dropdown-item">
                        <a href="#" id="edit_item"  data-path="{{route('testings.edit', $model->id)}}">Edit</a>
                        </li>
                        @endif
    @endcan
 @can('delete.testing')
  @if (\App\Helpers\Helper::canWithCount('delete.testing', $model->created_at))

                <li class="dropdown-item">
                <a  href="#" data-id="{{$model->id}}" class="model-delete">Delete</a>
                </li>
                  @endif
                @endcan
            </ul>
        </div>

</td>
