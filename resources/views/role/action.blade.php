@if (Auth::user()->hasAnyPermission(['edit.role', 'delete.role']))
<div class="dropdown">
    <a class=" dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>

    </a>

    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        @can('edit.role')
        @if (\App\Helpers\Helper::canWithCount('edit.role', $model->created_at))
    <li class="dropdown-item">
        <a class="edit-role edit_form" data-path="{{ route('role.edit', $model->id) }}" data-name="{{$model->name}}" data-id="{{$model->id}}"  href="#">View or Edit</a>
        </li>
        @endif
        @endcan


        @can('delete.role')
        @if (\App\Helpers\Helper::canWithCount('delete.role', $model->created_at))
        <li class="dropdown-item">
        <a  href="#" data-id="{{$model->id}}" class="delete-role">Delete</a>
        </li>
        @endif
        @endcan
    </ul>
</div>
@endif
