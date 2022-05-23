@extends('constructor.base')

@section('title', 'Theme ' . $theme->getName() . ' | List')
@section('page-title')
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Theme:</span>
        </div>
        <input type="text" class="form-control" placeholder="Enter Theme Name..." id="theme-name"
               value="{{ $theme->getName() }}"/>
        <div class="input-group-append">
            <button type="button" class="btn btn-warning" id="save-name">
                <i class="fas fa-circle-notch fa-spin" style="display: none;"></i>&nbsp;Save Name
            </button>
        </div>
    </div>
@stop
@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <style>
        .bootstrap-switch-container {
            display: contents;
        }
    </style>
@stop

@section('js')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

    <script>
        $(function () {
            $("#basePageMenuTable").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            $("#cssTable").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            $("#jsTable").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            var Toast = Swal.mixin({
                toast: true,
                position: 'bottom',
                showConfirmButton: false,
                timer: 3000
            });

            $('button#save-name').on('click', function () {
                $('button#save-name').prop("disabled", true);
                $('button#save-name i').show();

                let themeName = $('input#theme-name').val();

                $.ajax({
                    url: '{{ route('constructor_theme_name_update', ['id' => $theme->getId()]) }}',
                    type: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {'name': themeName},
                    success: function () {
                        $('button#save-name').prop("disabled", false);
                        $('button#save-name i').hide();
                        Toast.fire({
                            icon: 'success',
                            title: 'Saved Success!'
                        });
                    },
                    error: function (error) {
                        Toast.fire({
                            icon: 'error',
                            title: error.responseJSON.message
                        });
                        $('button#save-name').prop("disabled", false);
                        $('button#save-name i').hide();
                    }
                });
            });

            $('#create-page').on('click', function () {
                let templateType = $('.select-type').val();
                let pageId = $('.select-page').val();
                let languageId = $('.select-language').val();
                let name = $('input.name').val();
                name = name === '' ? null : name;

                if (templateType === '') {
                    return;
                }

                $('#create-page').prop("disabled", true);

                $.ajax({
                    url: '{{ route('constructor_template_store') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        'type': templateType,
                        'name': name,
                        'theme_id': {{ $theme->getId() }},
                        'page_id': pageId,
                        'language_id': languageId
                    },
                    success: function (response) {
                        if (response.id === undefined) {
                            $('#create-page').prop("disabled", false);
                            return;
                        }
                        location.href = '{{ route('constructor_template_edit', ':id') }}'.replace(':id', response.id);
                    },
                    error: function (error) {
                        Toast.fire({
                            icon: 'error',
                            title: error.responseJSON.message
                        });
                        $('#create-page').prop("disabled", false);
                    }
                });
            });

            let deletePageId = null;

            $('button[data-target="#modal-delete"]').on('click', function () {
                deletePageId = $(this).attr('data-id');
            });

            $('#delete-page').on('click', function () {
                if (deletePageId === null) {
                    return;
                }

                $('#delete-page').prop("disabled", true);

                $.ajax({
                    url: '{{ route('constructor_template_destroy', ':id') }}'.replace(':id', deletePageId),
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function () {
                        location.reload();
                    },
                    error: function (error) {
                        Toast.fire({
                            icon: 'error',
                            title: error.responseJSON.message
                        });
                        $('#delete-page').prop("disabled", false);
                    }
                });
            });

            $('button.create-base-template').on('click', function () {
                $("select.select-type option").prop("disabled", true);
                $('select.select-page').prop('disabled', false);
                $("select.select-type option[value='{{ \App\Ship\Parents\Models\TemplateInterface::BASE_TYPE }}']").prop('disabled', false);
                $("select.select-type option[value='{{ \App\Ship\Parents\Models\TemplateInterface::PAGE_TYPE }}']").prop('disabled', false).prop('selected', true);
                $("select.select-type option[value='{{ \App\Ship\Parents\Models\TemplateInterface::MENU_TYPE }}']").prop('disabled', false);
            });

            $('button.create-css-template').on('click', function () {
                $("select.select-type option").prop("disabled", true);
                $('select.select-page').prop('disabled', true);
                $("select.select-type option[value='{{ \App\Ship\Parents\Models\TemplateInterface::CSS_TYPE }}']").prop('disabled', false).prop('selected', true);
            });

            $('button.create-js-template').on('click', function () {
                $("select.select-type option").prop("disabled", true);
                $('select.select-page').prop('disabled', true);
                $("select.select-type option[value='{{ \App\Ship\Parents\Models\TemplateInterface::JS_TYPE }}']").prop('disabled', false).prop('selected', true);
            });

            $('select.select-type').on('change', function () {
                $('select.select-page').prop('disabled', true);
                if ($(this).val() === '{{\App\Ship\Parents\Models\TemplateInterface::PAGE_TYPE}}') {
                    $('select.select-page').prop('disabled', false);
                }
            });
        });
    </script>
@stop

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('constructor_template_index') }}">Themes</a></li>
    <li class="breadcrumb-item active">{{ $theme->getName() }}</li>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline card-outline-tabs">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="templates-base-page-tab" data-toggle="pill"
                                   href="#templates-base-page" role="tab" aria-controls="templates-base-page"
                                   aria-selected="false">Base & Pages & Menu</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="templates-css-tab" data-toggle="pill"
                                   href="#templates-css" role="tab" aria-controls="templates-css"
                                   aria-selected="false">CSS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="templates-js-tab" data-toggle="pill"
                                   href="#templates-js" role="tab" aria-controls="templates-js"
                                   aria-selected="false">JavaScript</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="templates-tabContent">
                            <div class="tab-pane fade active show" id="templates-base-page" role="tabpanel"
                                 aria-labelledby="templates-base-page-tab">
                                <button type="button" class="btn bg-gradient-primary create-base-template"
                                        data-toggle="modal"
                                        data-target="#modal-create">
                                    <i class="fas fa-plus-square"></i>&nbsp;
                                    Add Base/Page/Menus Template
                                </button>
                                <table id="basePageMenuTable" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Language</th>
                                        <th>Created</th>
                                        <th class="dt-right">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($theme->getTemplates()
                                                ?->filter(fn(\App\Ship\Parents\Dto\TemplateDto $templateDto) => in_array($templateDto->getType(), [
                                                    \App\Ship\Parents\Models\TemplateInterface::BASE_TYPE,
                                                    \App\Ship\Parents\Models\TemplateInterface::PAGE_TYPE,
                                                    \App\Ship\Parents\Models\TemplateInterface::MENU_TYPE
                                                ], true)) ?? [] as $template)
                                        <tr>
                                            <td>{{ $template->getId() }}</td>
                                            <td>{{ $template->getName() }}</td>
                                            <td>{{ $template->getType() }}{{ $template->getType() === \App\Ship\Parents\Models\TemplateInterface::PAGE_TYPE ? ' [' . $template?->getPage()->getName() . ']' : '' }}</td>
                                            <td>{{ $template->getLanguage()?->getName() ?? 'General' }}</td>
                                            <td>{{ $template->getCreateAt() }}</td>
                                            <td class="dt-right">
                                                <div class="btn-group">
                                                    <button type="button" class="btn bg-gradient-info btn-sm"
                                                            onclick="location.href='{{ route('constructor_template_edit', $template->getId()) }}'">
                                                        <i class="fas fa-code"></i></i>&nbsp;
                                                        Code Editor
                                                    </button>
                                                    <button type="button" class="btn bg-gradient-danger btn-sm"
                                                            data-id="{{ $template->getId() }}"
                                                            data-toggle="modal"
                                                            data-target="#modal-delete">
                                                        <i class="fas fa-trash-alt"></i>&nbsp;
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="templates-css" role="tabpanel"
                                 aria-labelledby="templates-css-tab">
                                <button type="button" class="btn bg-gradient-primary create-css-template"
                                        data-toggle="modal"
                                        data-target="#modal-create">
                                    <i class="fas fa-plus-square"></i>&nbsp;
                                    Add CSS
                                </button>
                                <table id="cssTable" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Language</th>
                                        <th>Created</th>
                                        <th class="dt-right">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($theme->getTemplates()
                                                ?->filter(fn(\App\Ship\Parents\Dto\TemplateDto $templateDto) => $templateDto->getType() === \App\Ship\Parents\Models\TemplateInterface::CSS_TYPE) ?? [] as $template)
                                        <tr>
                                            <td>{{ $template->getId() }}</td>
                                            <td>{{ $template->getName() }}</td>
                                            <td>{{ $template->getType() }}{{ $template->getType() === \App\Ship\Parents\Models\TemplateInterface::PAGE_TYPE ? ' [' . $template?->getPage()->getName() . ']' : '' }}</td>
                                            <td>{{ $template->getLanguage()?->getName() ?? 'General' }}</td>
                                            <td>{{ $template->getCreateAt() }}</td>
                                            <td class="dt-right">
                                                <div class="btn-group">
                                                    <button type="button" class="btn bg-gradient-info btn-sm"
                                                            onclick="location.href='{{ route('constructor_template_edit', $template->getId()) }}'">
                                                        <i class="fas fa-code"></i></i>&nbsp;
                                                        Code Editor
                                                    </button>
                                                    <button type="button" class="btn bg-gradient-danger btn-sm"
                                                            data-id="{{ $template->getId() }}"
                                                            data-toggle="modal"
                                                            data-target="#modal-delete">
                                                        <i class="fas fa-trash-alt"></i>&nbsp;
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="templates-js" role="tabpanel"
                                 aria-labelledby="templates-js-tab">
                                <button type="button" class="btn bg-gradient-primary create-js-template"
                                        data-toggle="modal"
                                        data-target="#modal-create">
                                    <i class="fas fa-plus-square"></i>&nbsp;
                                    Add JavaScript
                                </button>
                                <table id="jsTable" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Language</th>
                                        <th>Created</th>
                                        <th class="dt-right">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($theme->getTemplates()
                                                ?->filter(fn(\App\Ship\Parents\Dto\TemplateDto $templateDto) => $templateDto->getType() === \App\Ship\Parents\Models\TemplateInterface::JS_TYPE) ?? [] as $template)
                                        <tr>
                                            <td>{{ $template->getId() }}</td>
                                            <td>{{ $template->getName() }}</td>
                                            <td>{{ $template->getType() }}{{ $template->getType() === \App\Ship\Parents\Models\TemplateInterface::PAGE_TYPE ? ' [' . $template?->getPage()->getName() . ']' : '' }}</td>
                                            <td>{{ $template->getLanguage()?->getName() ?? 'General' }}</td>
                                            <td>{{ $template->getCreateAt() }}</td>
                                            <td class="dt-right">
                                                <div class="btn-group">
                                                    <button type="button" class="btn bg-gradient-info btn-sm"
                                                            onclick="location.href='{{ route('constructor_template_edit', $template->getId()) }}'">
                                                        <i class="fas fa-code"></i></i>&nbsp;
                                                        Code Editor
                                                    </button>
                                                    <button type="button" class="btn bg-gradient-danger btn-sm"
                                                            data-id="{{ $template->getId() }}"
                                                            data-toggle="modal"
                                                            data-target="#modal-delete">
                                                        <i class="fas fa-trash-alt"></i>&nbsp;
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>


            {{--<div class="col-12">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <button type="button" class="btn bg-gradient-primary create-language" data-toggle="modal"
                                data-target="#modal-create">
                            <i class="fas fa-plus-square"></i>&nbsp;
                            Add Template
                        </button>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Language</th>
                                <th>Created</th>
                                <th class="dt-right">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($theme->getTemplates()?->filter(fn(\App\Ship\Parents\Dto\TemplateDto $templateDto) => $templateDto->getType() === \App\Ship\Parents\Models\TemplateInterface::BASE_TYPE) ?? [] as $template)
                                <tr>
                                    <td>{{ $template->getId() }}</td>
                                    <td>{{ $template->getName() }}</td>
                                    <td>{{ $template->getType() }}{{ $template->getType() === \App\Ship\Parents\Models\TemplateInterface::PAGE_TYPE ? ' [' . $template?->getPage()->getName() . ']' : '' }}</td>
                                    <td>{{ $template->getLanguage()?->getName() ?? 'General' }}</td>
                                    <td>{{ $template->getCreateAt() }}</td>
                                    <td class="dt-right">
                                        <div class="btn-group">
                                            <button type="button" class="btn bg-gradient-info btn-sm"
                                                    onclick="location.href='{{ route('constructor_template_edit', $template->getId()) }}'">
                                                <i class="fas fa-code"></i></i>&nbsp;
                                                Code Editor
                                            </button>
                                            <button type="button" class="btn bg-gradient-danger btn-sm"
                                                    data-id="{{ $template->getId() }}"
                                                    data-toggle="modal"
                                                    data-target="#modal-delete">
                                                <i class="fas fa-trash-alt"></i>&nbsp;
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="modal fade" id="modal-delete">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Are you sure?</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure that you want remove one of the template?</p>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                        <button type="button" class="btn btn-danger" id="delete-page">Yes, remove
                                            this template!
                                        </button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>

                        <div class="modal fade" id="modal-create">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Create new Template</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Template Type</label>
                                            <select class="form-control select-type" style="width: 100%;">
                                                @foreach ($types as $type)
                                                    <option value="{{ $type }}">{{ $type }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>For Page</label>
                                            <select class="form-control select-page" style="width: 100%;">
                                                @foreach ($pages as $page)
                                                    <option value="{{ $page->getId() }}">{{ $page->getName() }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Language</label>
                                            <select class="form-control select-language" style="width: 100%;">
                                                <option value="">General</option>
                                                @foreach ($languages as $language)
                                                    <option value="{{ $language->getId() }}">{{ $language->getName() }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel
                                        </button>
                                        <button type="button" class="btn btn-success" id="create-page">Create</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>--}}
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>

    <div class="modal fade" id="modal-delete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Are you sure?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure that you want remove one of the template?</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="delete-page">Yes, remove
                        this template!
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modal-create">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create new Template</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Template Type</label>
                        <select class="form-control select-type" style="width: 100%;">
                            @foreach ($types as $type)
                                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control name"/>
                    </div>
                    <div class="form-group">
                        <label>For Page</label>
                        <select class="form-control select-page" style="width: 100%;">
                            @foreach ($pages as $page)
                                <option value="{{ $page->getId() }}">{{ $page->getName() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Language</label>
                        <select class="form-control select-language" style="width: 100%;">
                            <option value="">General</option>
                            @foreach ($languages as $language)
                                <option value="{{ $language->getId() }}">{{ $language->getName() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel
                    </button>
                    <button type="button" class="btn btn-success" id="create-page">Create</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
