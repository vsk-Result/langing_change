@extends('layouts.app')

@section('content')
    @include('projects.versions.gallery_modal')

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Версии проекта</span>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        @foreach($project->versions as $ver)
                            <li class="nav-item">
                                <a class="nav-link {{ $version->id == $ver->id ? 'active' : '' }}" href="{{ route('projects.versions.edit', [$project, $ver]) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                    @if($project->actualVersion->id === $ver->id)
                                        {{ $ver->title }} - актуальная
                                    @else
                                        {{ $ver->title }}
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 border-bottom">
                    <h1 class="h2">
                        {{ $project->title }}
                        <p class="text-muted fs-6">Версия: {{ $version->title }}</p>
                    </h1>
                </div>

                <div class="d-flex justify-content-end flex-wrap flex-md-nowrap align-items-center pt-2 pb-2 mb-3 border-bottom">
                    <div class="btn-toolbar mb-2 mb-md-0">
                        @if($project->actualVersion->id !== $version->id)
                            <a href="{{ route('projects.versions.actual', [$project, $version]) }}" class="btn btn-sm btn-outline-dark m-1">Сделать актуальной</a>
                        @endif
                        <button id="update-btn" type="button" class="btn btn-sm btn-primary m-1">Сохранить текущую версию</button>
                        <button id="store-btn" type="button" class="btn btn-sm btn-warning m-1">Сохранить как новую версию</button>
                        <a href="{{ route('projects.versions.download', [$project, $version]) }}" class="btn btn-sm btn-success m-1">Скачать</a>
                        <button id="destroy-btn" type="button" class="btn btn-sm btn-danger m-1">Удалить</button>
                    </div>
                </div>

                <div class="hidden-info">
                    <form id="update-form" action="{{ route('projects.versions.update', [$project, $version]) }}" method="POST">
                        @csrf
                        <textarea name="html" class="html-raw"></textarea>
                        <textarea name="images" class="images-raw"></textarea>
                    </form>

                    <form id="store-form" action="{{ route('projects.versions.store', [$project, $version]) }}" method="POST">
                        @csrf
                        <textarea name="html" class="html-raw"></textarea>
                        <textarea name="images" class="images-raw"></textarea>
                    </form>

                    <form id="destroy-form" action="{{ route('projects.versions.destroy', [$project, $version]) }}" method="POST">
                        @csrf
                    </form>
                </div>

                <iframe name="{{ random_int(5, 10) }}" id="landing-iframe" data-src="{{ $version->getHTMLPath() . '?' . random_int(5, 10) }}" src="" frameborder="0"></iframe>
            </main>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentImage = null;
        let galleryModal = new bootstrap.Modal(document.getElementById('galleryModal'));
        let storeBtn = document.querySelector('#store-btn');
        let storeForm = document.querySelector('#store-form');
        let updateBtn = document.querySelector('#update-btn');
        let updateForm = document.querySelector('#update-form');
        let destroyBtn = document.querySelector('#destroy-btn');
        let destroyForm = document.querySelector('#destroy-form');
        let htmlRaws = document.querySelectorAll('.html-raw');
        let imagesRaws = document.querySelectorAll('.images-raw');
        let iframe = document.getElementById("landing-iframe");
        let iframeDoc = null;

        iframe.src = iframe.dataset.src;
        console.log('init complete');

        iframe.onload = function() {

            iframeDoc = iframe.contentWindow.document;

            console.log('iframe onload start');

            iframeDoc.querySelectorAll('*').forEach((el) => {
                el.contentEditable = true;
            })

            iframeDoc.querySelectorAll('img').forEach((el) => {
                el.addEventListener('click', (e) => {
                    currentImage = el;
                    galleryModal.show();
                })
            })
        }

        storeBtn.addEventListener('click', () => {
            updateHTMLRaw();
            updateImagesRaw();
            storeForm.submit();
        })
        updateBtn.addEventListener('click', () => {
            updateHTMLRaw();
            updateImagesRaw();
            updateForm.submit();
        })
        destroyBtn.addEventListener('click', () => {
            if (confirm('Вы действительно хотите удалить версию проекта?')) {
                destroyForm.submit();
            }
        })

        document.querySelectorAll('#galleryModal .modal-body img').forEach((el) => {
            el.addEventListener('click', (e) => {
                currentImage.src = el.src;
                updateImagesRaw();
                galleryModal.hide();
            })
        })

        function updateHTMLRaw() {
            htmlRaws.forEach((raw) => {
                raw.value = '<!DOCTYPE html><html>' + iframeDoc.querySelector('html').innerHTML + '</html>';
            })
        }

        function updateImagesRaw() {
            let images = '';
            iframeDoc.querySelectorAll('img').forEach((el) => {
                const src = el.src.replace(/^.*[\\\/]/, '');
                images += src + ';';
            })

            imagesRaws.forEach((raw) => {
                raw.value = images;
            })
        }
    </script>
@endpush
