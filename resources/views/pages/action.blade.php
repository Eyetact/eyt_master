<div class="dropdown">
    <a class=" dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
    </a>

    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <li class="dropdown-item">
            <a class="edit-role edit_form" href="{{ route('pages.edit', $model->id) }}">
                Edit
            </a>
        </li>

        <li class="dropdown-item">
            <a class="delete-role" href="{{ route('pages.destroy', $model->id) }}"
                onclick="event.preventDefault(); if(confirm('Are you sure?')){ document.getElementById('delete-form-{{ $model->id }}').submit(); }">
                Delete
            </a>

            <form id="delete-form-{{ $model->id }}" action="{{ route('pages.destroy', $model->id) }}" method="POST"
                style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </li>
    </ul>
</div>
