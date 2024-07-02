
@if (Auth::user()->hasAnyPermission(['edit.attribute', 'delete.attribute']) || Auth::user()->hasRole('super'))
<div class="dropdown">
    <a class=" dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>

    </a>

    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">

        @if (\App\Helpers\Helper::canWithCount('edit.'.str($model->code)->singular()->lower(), $model->created_at))
    <li class="dropdown-item">
    <a href="#" id="edit_item"  data-path="{{ route('attribute.edit', $model->id) }}">View or Edit</a>
    </li>
    @endif



    @if (\App\Helpers\Helper::canWithCount('delete.'.str($model->code)->singular()->lower(), $model->created_at))

        <li class="dropdown-item">
        <a class="delete-attribute" href="#" data-id="{{ $model->id }}" class="attribute-delete">Delete</a>
        </li>
        @endif


    </ul>
</div>



@else
<div class="dropdown">
    @if (Auth::user()->hasAnyPermission(['edit.'.str($model->code)->singular()->lower(), 'delete.'.$model->code]))
            <a class=" dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>

            </a>
            @endif

            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                @can('edit.'.str($model->code)->singular()->lower())
                @if (\App\Helpers\Helper::canWithCount('edit.'.str($model->code)->singular()->lower(), $model->created_at))
            <li class="dropdown-item">
            <a href="#" id="edit_item"  data-path="{{ route('attribute.edit', $model->id) }}">View or Edit</a>
            </li>
            @endif
            @endcan

            @can('delete.'.str($model->code)->singular()->lower())
            @if (\App\Helpers\Helper::canWithCount('delete.'.str($model->code)->singular()->lower(), $model->created_at))

                <li class="dropdown-item">
                <a class="delete-attribute" href="#" data-id="{{ $model->id }}" class="attribute-delete">Delete</a>
                </li>
                @endif
                @endcan

            </ul>
        </div>

@endif
