@extends('constructor@template::editTemplate')
@section('js')
    @parent
    <script>
        $(function () {
            // CodeMirror
            let code = CodeMirror.fromTextArea(document.getElementById("code"), {
                lineNumbers: true,
                mode: 'htmlmixed',
                theme: "default",
                continueComments: "Enter",
            });
            code.setSize(null, 600);

            let codeElem = CodeMirror.fromTextArea(document.getElementById("code-element"), {
                lineNumbers: true,
                mode: 'htmlmixed',
                theme: "default",
                continueComments: "Enter",
            });
            codeElem.setSize(null, 600);

            $('button#localization-point-to-code').on('click', function () {
                setCodeMirrorTarget(code);
            });

            $('button#localization-point-to-code-elem').on('click', function () {
                setCodeMirrorTarget(codeElem);
            });

            $('a.nav-link').on('click', function () {
                setInterval(() => code.refresh(), 500);
                setInterval(() => codeElem.refresh(), 500);
            });

            $('button#insert-content').on('click', function () {
                let content = $(this).attr('data-value');
                code.replaceSelection(content);
            });

            $('button#insert-element').on('click', function () {
                let content = $(this).attr('data-value');
                codeElem.replaceSelection(content);
            });

            let Toast = Swal.mixin({
                toast: true,
                position: 'bottom',
                showConfirmButton: false,
                timer: 3000
            });

            document.onkeydown = (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                    e.preventDefault();
                    saveTemplate(Toast, code.getValue(), codeElem.getValue());
                }
            }

            $('button#save-button').on('click', () => saveTemplate(Toast, code.getValue(), codeElem.getValue()));

        });
    </script>
@stop
@if($template->getType() === \App\Ship\Parents\Models\TemplateInterface::WIDGET_TYPE)
    @section('page-title')
        @parent
        <div class="input-group" style="margin-top: 10px">
            <div class="input-group-prepend">
                <span class="input-group-text">Count of Show Elements</span>
            </div>
            <input type="number" class="form-control" placeholder="Enter Count of Showing Elements..." id="widget-count"
                   value="{{ $template->getWidget()?->getCountElements() ?? 1 }}"/>
            <div class="input-group-prepend">
                <span class="input-group-text">Show By</span>
            </div>
            <select class="form-control" id="widget-show-by">
                @foreach($listShowBy as $showBy)
                    <option value="{{ $showBy }}"{{ $showBy === $template->getWidget()?->getShowBy() ? ' selected' : '' }}>{{ ucfirst($showBy) }}</option>
                @endforeach
            </select>
        </div>
    @endsection
@endif
@section('template-form')
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill"
                       href="#custom-tabs-four-home" role="tab"
                       aria-controls="custom-tabs-four-home"
                       aria-selected="true">Common Style</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill"
                       href="#custom-tabs-four-profile" role="tab"
                       aria-controls="custom-tabs-four-profile"
                       aria-selected="false">One Item Style</a>
                </li>
            </ul>
        </div>
        <div class="card-body card-code">
            <div class="tab-content" id="custom-tabs-four-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel"
                     aria-labelledby="custom-tabs-four-home-tab">
                    <div class="btn-group margin-10">
                        <button type="button" class="btn btn-warning"
                                id="localization-point-to-code"
                                data-toggle="modal"
                                data-target="#modal-localization-point">
                            Localization
                        </button>
                        <button type="button" class="btn btn-info" id="insert-content"
                                data-value="{ITEMS}">
                            All Items
                        </button>
                        <button type="button" class="btn btn-default" id="insert-content"
                                data-value="{HOME_LINK}">
                            Home Link
                        </button>
                    </div>

                    @if($template->getType() === \App\Ship\Parents\Models\TemplateInterface::MENU_TYPE)
                        <div class="btn-group margin-10">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                        aria-expanded="false">
                                    Widget
                                </button>
                                <div class="dropdown-menu" style="">
                                    @foreach($includableItems->get(\App\Ship\Parents\Models\TemplateInterface::WIDGET_TYPE) ?? [] as $item)
                                        <button class="dropdown-item" href="#" id="insert-content"
                                                data-value="{WIDGET_{{ $item->getId() }}}">
                                            {{ $item->getName() }}
                                            <sup>ID: {{ $item->getId() }} /
                                                Language: {{ $item->getLanguage()?->getName() ?? 'General' }}</sup>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <textarea id="code" class="p-3">{{ $template->getCommonHtml() }}</textarea>
                </div>
                <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel"
                     aria-labelledby="custom-tabs-four-profile-tab">
                    <div class="btn-group margin-10">
                        <button type="button" class="btn btn-warning"
                                id="localization-point-to-code-elem"
                                data-toggle="modal"
                                data-target="#modal-localization-point">
                            Localization
                        </button>
                        <button type="button" class="btn btn-info" id="insert-element" data-value="{LINK}">
                            Link URL
                        </button>
                        @if($template->getType() === \App\Ship\Parents\Models\TemplateInterface::MENU_TYPE)
                            <button type="button" class="btn btn-info" id="insert-element" data-value="{NAME}">
                                Link Name
                            </button>
                        @endif
                    </div>

                    @if($template->getType() === \App\Ship\Parents\Models\TemplateInterface::WIDGET_TYPE)
                        <div class="btn-group margin-10">
                            <button type="button" class="btn btn-dark" id="insert-element" data-value="{INDEX}">
                                Index Number
                            </button>

                            @if($template->getPage() !== null)
                                @foreach($template->getPage()?->getFields() as $field)
                                    <button type="button" class="btn btn-default" id="insert-element"
                                            data-value="{FIELD_{{$field->getId()}}}">
                                        {{$field->getName()}}&nbsp;<sup>ID: {{ $field->getId() }}</sup>
                                    </button>
                                @endforeach
                            @endif
                        </div>
                    @endif
                    <textarea id="code-element" class="p-3">{{ $template->getElementHtml() }}</textarea>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
@stop