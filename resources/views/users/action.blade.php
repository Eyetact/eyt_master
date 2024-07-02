@if (Auth::user()->hasAnyPermission(['edit.user', 'delete.user']))
<div class="dropdown">
    <a class=" dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>

    </a>

    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        @can('edit.user')
        @if (\App\Helpers\Helper::canWithCount('edit.user', $model->created_at))
    <li class="dropdown-item">
        <a  href="{{ route('profile.index', $model->id) }}">View or Edit</a>
        </li>
        @endif
        @endcan


    @can('delete.user')
            @if (\App\Helpers\Helper::canWithCount('delete.user', $model->created_at))
        <li class="dropdown-item">
        <a  href="#" data-id="{{ $model->id }}" class="user-delete">Delete</a>
        </li>

        @endif
        @endcan
    </ul>
</div>
@endif
