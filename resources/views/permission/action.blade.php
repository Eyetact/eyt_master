@if (Auth::user()->hasAnyPermission(['delete.permission']))
<div class="dropdown">
    <a class=" dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>

    </a>

    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">

        @can('delete.permission')
        @if (\App\Helpers\Helper::canWithCount('delete.permission', $model->created_at))
        <li class="dropdown-item">
        <a  href="#" data-id="{{ $model->id }}" class="user-delete">Delete</a>
        </li>
        @endif
        @endcan
    </ul>
</div>
@endif
