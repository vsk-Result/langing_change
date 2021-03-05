@extends('layouts.app')

@section('content')
    <div class="bg-light p-5 rounded">
        <h1>Мои проекты</h1>
        <p class="lead">Создайте новый проект. Выберите один из пяти готовых шаблонов. Измените его содержимое. Скачайте готовый архив проекта.</p>
        <a href="{{ route('projects.create') }}" class="btn btn-success">Создать проект</a>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 mt-3">
            @foreach($projects as $project)
                <div class="col">
                    <div class="card shadow-sm">
                        <img class="bd-placeholder-img card-img-top" src="{{ $project->landing->getPreviewUrl() }}" alt="{{ $project->title }}">

                        <div class="card-body">
                            <p class="card-text"><strong>{{ $project->title }}</strong></p>
                            <p class="card-text">{{ $project->description }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-primary">Изменить</a>
                                <small class="text-muted">{{ $project->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
