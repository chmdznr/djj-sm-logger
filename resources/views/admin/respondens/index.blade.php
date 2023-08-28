@extends('layouts.admin')
@section('content')
@can('responden_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.respondens.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.responden.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'Responden', 'route' => 'admin.respondens.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.responden.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Responden">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.responden.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.responden.fields.nama') }}
                    </th>
                    <th>
                        {{ trans('cruds.responden.fields.kode') }}
                    </th>
                    <th>
                        {{ trans('cruds.responden.fields.usia') }}
                    </th>
                    <th>
                        {{ trans('cruds.responden.fields.his_adekuat') }}
                    </th>
                    <th>
                        {{ trans('cruds.responden.fields.pergerakan') }}
                    </th>
                    <th>
                        {{ trans('cruds.responden.fields.paritas') }}
                    </th>
                    <th>
                        {{ trans('cruds.responden.fields.kardiotokografi') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\Responden::HIS_ADEKUAT_RADIO as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\Responden::PERGERAKAN_RADIO as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                    </td>
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('responden_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.respondens.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.respondens.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'nama', name: 'nama' },
{ data: 'kode', name: 'kode' },
{ data: 'usia', name: 'usia' },
{ data: 'his_adekuat', name: 'his_adekuat' },
{ data: 'pergerakan', name: 'pergerakan' },
{ data: 'paritas', name: 'paritas' },
{ data: 'kardiotokografi', name: 'kardiotokografi' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-Responden').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
    let strict = $(this).attr('strict') || false
    let value = strict && this.value ? "^" + this.value + "$" : this.value

    let index = $(this).parent().index()
    if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
    }

    if (value.toLowerCase() === 'all') {
        table
            .column(index)
            .search('')
            .draw();
    } else {
        table
            .column(index)
            .search(value, strict)
            .draw();
    }

  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })
});


</script>
@endsection