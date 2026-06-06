<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" integrity="sha384-FckWOBo7yuyMS7In0aXZ0aoVvnInlnFMwCv77x9sZpFgOonQgnBj1uLwenWVtsEj" crossorigin="anonymous" />
    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous" />
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" integrity="sha384-1UXhfqyOyO+W+XsGhiIFwwD3hsaHRz2XDGMle3b8bXPH5+cMsXVShDoHA3AH/y/p" crossorigin="anonymous" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" integrity="sha384-EkHEUZ6lErauT712zSr0DZ2uuCmi3DoQj6ecNdHQXpMpFNGAQ48WjfXCE5n20W+R" crossorigin="anonymous" />
    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" integrity="sha384-QDuPH5mj+mpSlxOMdoDh7QVS2FWsvwk7xLb8FlISOJG5V75v8rmHEjn65broLs2a" crossorigin="anonymous" />
    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" integrity="sha384-qsM4aCmm42FZFZQFtYkx8HFjJE58AaiaN9OeflAG4ygRC+SI9PKU5ztSXXeLxNF+" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" integrity="sha384-HIipfSYbpCkh5/1V87AWAeR5SUrNiewznrUrtNz1ux4uneLhsAKzv/0FnMbj3m6g" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" integrity="sha384-O1hPCzrpXngZMErIjr+H0eOvTf1GKeN4n8CAAgeGgNT8SmQot8u0Zf7V9ipByhSi" crossorigin="anonymous" />
    <link href="https://unpkg.com/@coreui/coreui@3.2.2/dist/css/coreui.min.css" rel="stylesheet" integrity="sha384-9Yx29JfMLjxoCde8abib+J/o2DFPtVLqzaj7gwY3TR04xfx8MEibw9bTYjor6bUi" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" integrity="sha384-AivDrv68J7248ZXpmMCs62ZQTUW01XQSEfpkiQokZq0MMGgfSd+QpUTdVSnxrxSz" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/css/perfect-scrollbar.min.css" rel="stylesheet" integrity="sha384-vnaUvdj57CTH1nPzyAeV1v5L4VVV/E52+CE3IBe5r8u1aw8N9nDDdup9dSZcSvAX" crossorigin="anonymous" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    @yield('styles')
</head>

<body class="c-app">
    @include('partials.menu')
    <div class="c-wrapper">
        <header class="c-header c-header-fixed px-3">
            <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
                <i class="fas fa-fw fa-bars"></i>
            </button>

            <a class="c-header-brand d-lg-none" href="#">{{ trans('panel.site_title') }}</a>

            <button class="c-header-toggler mfs-3 d-md-down-none" type="button" responsive="true">
                <i class="fas fa-fw fa-bars"></i>
            </button>

            <ul class="c-header-nav ml-auto">
                @if(count(config('panel.available_languages', [])) > 1)
                    <li class="c-header-nav-item dropdown d-md-down-none">
                        <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            {{ strtoupper(app()->getLocale()) }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @foreach(config('panel.available_languages') as $langLocale => $langName)
                                <a class="dropdown-item" href="{{ url()->current() }}?change_language={{ $langLocale }}">{{ strtoupper($langLocale) }} ({{ $langName }})</a>
                            @endforeach
                        </div>
                    </li>
                @endif


            </ul>
        </header>

        <div class="c-body">
            <main class="c-main">


                <div class="container-fluid">
                    @if(session('message'))
                        <div class="row mb-2">
                            <div class="col-lg-12">
                                <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                            </div>
                        </div>
                    @endif
                    @if($errors->count() > 0)
                        <div class="alert alert-danger">
                            <ul class="list-unstyled">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @yield('content')

                </div>


            </main>
            <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/perfect-scrollbar.min.js" integrity="sha384-iGe6Q9zj6wtoa9jUwML+Tqy2ns4E2uwDeEG5RnrteZzAOXYyFsaAw7FPlIE2YD/6" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/@coreui/coreui@3.2.2/dist/js/coreui.min.js" integrity="sha384-iq2gV0Ei5IUUBkHbCNeV5q5AtUlER+0GmDERvNTujUdIoo5yCZG8MXfn+aH0zvH7" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" integrity="sha384-rgWRqC0OFPisxlUvl332tiM/qmaNxnlY46eksSZD84t+s2vZlqGeHrncwIRX7CGp" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js" integrity="sha384-uiSTMvD1kcI19sAHJDVf68medP9HA2E2PzGis9Efmfsdb8p9+mvbQNgFhzii1MEX" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js" integrity="sha384-1sxPUKx7avI1vugE85HDjDu6Zfu/xZqlRkENaT6dch9W+Nky/E5c03ZAN4teq4LL" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js" integrity="sha384-Mo3vrai7wVnxYbQ9W0IKN6ynf7BYiVBVauZaT6eOMtESLNrDO5UEDliUVtJXcLf5" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js" integrity="sha384-jHHfbY7mQ4gga2XaTYVABzld/N4nFxzptAdg3DovdBJvjP1IEuBFcQC2G2L06m2G" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js" integrity="sha384-hCBj3pHlUNXEWXbbi9zzvd9JvntzPRup1Jb3gdfFGuyfdil28X8RB1DOSx7iPLNh" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js" integrity="sha384-cL8BKX3Gk1q5L71YJAGVdP7L0noPN90puPBBOhSbDaeWLhucDxRXYx2tfThCcpbY" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js" integrity="sha384-htFkmzBKFrwO7EbvHZPvJXWg0sJIkPPUTBDe6LXOU2ghApFVGQx9++EDSrKMZtHE" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js" integrity="sha384-qNRjPXKOnea7MmtbXG6HLwVyoGNFsu7ntdDOFOt3FS4vmj4MWUKyyKMjRKu0Hr4h" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js" integrity="sha384-yib/J8n+cev8VyEYY+4A3nSvnqCRhEkxUQhVFp8X+YuIXd0qhT3rTYXqel0zWzni" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js" integrity="sha384-wKgjgP3C7/ki5AAXb3CMNp6oPwMnO6caXzLLZ1AHUSVzS1G+1cmnB44UXTV7Xzue" crossorigin="anonymous"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js" integrity="sha384-+I4oPzaI7M3BCILdQVSUpksWyZVOvRqN6lYmWH/tT1FoHPbUKUfoJKV0yRcaNXwv" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js" integrity="sha384-sIzeKWIAHvT0Vm8QbfLCqZwBG0WMCkWVAOYd/330YSNeeQ1Y57N3T9lQz5Ry/EHH" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" integrity="sha384-eRwUWQDbnWMRrNpCKFsqmkfL7PMM8a4uUw5AvjTuLRoYFfozRz7g9BS696LvdNrE" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js" integrity="sha384-zINbQLO66rPXZxY90sH8+rj07wZ4OUI/mXpU/veA2zjKWcHlY2VBCjeAqBBd8xUK" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js" integrity="sha384-5qyOiYJgKPoDVonZt/IkdfcqPm0VQBCywCEYHcyNTLuS9AYWk1yN8L69o07+T864" crossorigin="anonymous"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        $(function() {
  let copyButtonTrans = '{{ trans('global.datatables.copy') }}'
  let csvButtonTrans = '{{ trans('global.datatables.csv') }}'
  let excelButtonTrans = '{{ trans('global.datatables.excel') }}'
  let pdfButtonTrans = '{{ trans('global.datatables.pdf') }}'
  let printButtonTrans = '{{ trans('global.datatables.print') }}'
  let colvisButtonTrans = '{{ trans('global.datatables.colvis') }}'
  let selectAllButtonTrans = '{{ trans('global.select_all') }}'
  let selectNoneButtonTrans = '{{ trans('global.deselect_all') }}'

  let languages = {
    'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json',
        'id': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Indonesian.json'
  };

  $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, { className: 'btn' })
  $.extend(true, $.fn.dataTable.defaults, {
    language: {
      url: languages['{{ app()->getLocale() }}']
    },
    columnDefs: [{
        orderable: false,
        className: 'select-checkbox',
        targets: 0
    }, {
        orderable: false,
        searchable: false,
        targets: -1
    }],
    select: {
      style:    'multi+shift',
      selector: 'td:first-child'
    },
    order: [],
    scrollX: true,
    pageLength: 100,
    dom: 'lBfrtip<"actions">',
    buttons: [
      {
        extend: 'selectAll',
        className: 'btn-primary',
        text: selectAllButtonTrans,
        exportOptions: {
          columns: ':visible'
        },
        action: function(e, dt) {
          e.preventDefault()
          dt.rows().deselect();
          dt.rows({ search: 'applied' }).select();
        }
      },
      {
        extend: 'selectNone',
        className: 'btn-primary',
        text: selectNoneButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'copy',
        className: 'btn-default',
        text: copyButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'csv',
        className: 'btn-default',
        text: csvButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'excel',
        className: 'btn-default',
        text: excelButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'pdf',
        className: 'btn-default',
        text: pdfButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'print',
        className: 'btn-default',
        text: printButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'colvis',
        className: 'btn-default',
        text: colvisButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      }
    ]
  });

  $.fn.dataTable.ext.classes.sPageButton = '';
});

    </script>
    @yield('scripts')
</body>

</html>