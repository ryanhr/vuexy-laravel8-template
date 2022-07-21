<div class="btn-group">
    @can($viewGate)
        <a class="btn btn-sm" href="{{ route($namespace.'.' . $crudRoutePart . '.show', $row->id) }}">
            <i data-feather="eye"></i>
        </a>
    @endcan
    <a class="btn btn-sm dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i data-feather="more-vertical"></i>
    </a>

    <div class="dropdown-menu dropdown-menu-end">
        @can($editGate)
            <a href="{{ route($namespace.'.' . $crudRoutePart . '.edit', $row->id) }}" class="dropdown-item">
                <i data-feather="edit-2"></i> {{ trans('global.edit') }}
            </a>
        @endcan
        @can($deleteGate)
            <a href="javascript:void(0);" class="dropdown-item delete-btn" data-id="{{$row->id}}">
                <i data-feather="trash-2"></i> {{ trans('global.delete') }}
            </a>

            <form action="{{ route($namespace.'.' . $crudRoutePart . '.destroy', $row->id) }}" method="POST"
                  class="delete-form">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
        @endcan
    </div>
</div>

<script>
    $('.delete-btn').on('click', function () {
        let current_form = $(this).parent().find('form');

        if (current_form) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    current_form.submit();
                }
            })
        }
    })
</script>

<script>
    feather.replace()
</script>

