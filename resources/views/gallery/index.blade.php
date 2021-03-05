@extends('layouts.app')

@section('content')
    <div class="bg-light p-5 rounded">
        <h1>Галерея</h1>

        <div class="row mt-4">
            <div class="col">
                <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Загрузить новые изображения</label>
                        <input name="images[]" type="file" accept=".png,.jpeg,.jpg,.gif" class="form-control" multiple="multiple" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Загрузить</button>
                </form>
            </div>
        </div>

        <div class="row mt-4">
            @include('gallery._images', $images)
        </div>
    </div>
@endsection
