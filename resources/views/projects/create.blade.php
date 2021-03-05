@extends('layouts.app')

@section('content')
    <style>
        .img-thumbnail.active {
            border: 5px solid #0d6efd;
        }
        .img-thumbnail:hover {
            cursor: pointer;
        }
    </style>
    <div class="bg-light p-5 rounded">
        <h1 class="mb-3">Новый проект</h1>
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Название</label>
                <input name="title" type="text" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Описание</label>
                <input name="description" type="text" class="form-control">
            </div>
            <div class="mb-3">
                <label class="mb-2">Выберите шаблон</label>
                <input id="landingId" name="landing_id" type="hidden" class="form-control">
                <div class="row">
                    @foreach($landings as $landing)
                        <div class="col-2">
                            <img src="{{ $landing->getPreviewUrl() }}"
                                 class="img-thumbnail"
                                 alt="{{ $landing->title }}"
                                 data-id="{{ $landing->id }}"
                            >
                        </div>
                    @endforeach
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Создать</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        window.onload = () => {
            const imgClass = '.img-thumbnail'
            const landingInput = 'input[name="landing_id"]'
            const titleInput = 'input[name="title"]'

            function setActiveLanding({target}) {
                document.querySelectorAll(imgClass).forEach((i) => {
                    i.classList.remove('active')
                })
                target.classList.add('active')
                document.querySelector(landingInput).value = target.dataset.id
            }

            document.querySelectorAll(imgClass).forEach((i) => {
                i.addEventListener('click', setActiveLanding)
            })

            document.querySelector(imgClass).click()
            document.querySelector(titleInput).focus()
        }
    </script>
@endpush
