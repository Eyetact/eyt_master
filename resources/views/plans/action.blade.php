@if (Auth::user()->hasAnyPermission(['edit.plan', 'delete.plan']))
    <div class="dropdown">
        <a class=" dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>

        </a>


        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            @can('edit.plan')
                @if (\App\Helpers\Helper::canWithCount('edit.plan', $model->created_at))
                    <li class="dropdown-item">
                        <a href="#" id="edit_item" data-path="{{ route('plans.view', $model->id) }}">View or Edit</a>
                    </li>
                @endif
            @endcan
            @can('delete.plan')
            @if (\App\Helpers\Helper::canWithCount('delete.plan', $model->created_at))
                <li class="dropdown-item">
                    <a href="#" data-id="{{ $model->id }}" class="plan-delete">Delete</a>
                </li>
                @endif
            @endcan
        </ul>
    </div>
@endif
