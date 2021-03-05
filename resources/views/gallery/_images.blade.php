@foreach($images as $image)
    <div class="col-md-3 mb-4">
        <img src="{{ asset('images/' . $image->getFileName()) }}" class="img-thumbnail" alt="{{ $image->getFileName() }}">
    </div>
@endforeach
