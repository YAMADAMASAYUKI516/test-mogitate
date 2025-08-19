@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}">
@endsection

@section('content')
<div class="edit">
    <form class="edit__form" action="{{ url("/products/{$product->id}/update") }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="edit__breadcrumbs">
            <a class="edit__breadcrumbs-link" href="{{ url('/products') }}">商品一覧</a> &gt; {{$product->name}}
        </div>

        <div class="edit__form-body">
            <div class="edit__form-left">
                <img class="edit__form-image" id="preview" src="{{ asset('storage/fruits-img/' . basename($product->image)) }}" alt="{{ $product->name }}">
                <input type="hidden" name="saved_image" value="{{ $product->image }}">

                <label class="edit__form-file-label" for="image">ファイルを選択</label>
                <input class="edit__form-file" id="image" type="file" name="image" onchange="updateFileInfo(this)">

                <span class="edit__form-filename" id="filename">{{ basename($product->image) }}</span>
                <div class="edit__form-error">
                @error('image')
                    {{ $message }}
                @enderror
                </div>
            </div>

            <div class="edit__form-right">
                <div class="edit__form-group">
                    <label class="edit__form-label">商品名</label>
                    <input class="edit__form-input" type="text" name="name" value="{{ old('name', $product->name) }}">
                </div>
                <div class="edit__form-error">
                @error('name')
                    {{ $message }}
                @enderror
                </div>

                <div class="edit__form-group">
                    <label class="edit__form-label">値段</label>
                    <input class="edit__form-input" type="number" name="price" value="{{ old('price', $product->price) }}">
                </div>
                <div class="edit__form-error">
                @error('price')
                    {{ $message }}
                @enderror
                </div>

                <div class="edit__form-group">
                    <label class="edit__form-label">季節</label>
                    <div class="edit__form-season-options">
                        @foreach ($seasons as $season)
                            <label class="edit__form-season-label">
                                <input type="checkbox" name="seasons[]" value="{{ $season->id }}" {{ in_array($season->id, old('seasons', $product->seasons->pluck('id')->toArray())) ? 'checked' : '' }}>
                                {{ $season->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="edit__form-error">
                @error('seasons')
                    {{ $message }}
                @enderror
                </div>
            </div>
        </div>

        <div class="edit__form-description">
            <div class="edit__form-group">
                <label class="edit__form-label">商品説明</label>
                <textarea class="edit__form-textarea" name="description">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>
            <div class="edit__form-error">
            @error('description')
                {{ $message }}
            @enderror
            </div>

        <div class="edit__form-buttons-wrapper">
            <div class="edit__form-buttons-center">
                <a class="edit__form-button edit__form-button--back" href="{{ url('/products') }}">戻る</a>
                <button class="edit__form-button edit__form-button--submit" type="submit">変更を保存</button>
            </div>
    </form>

            <form class="edit__form-delete" action="{{ url("/products/{$product->id}/delete") }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="edit__form-button--delete" type="submit">
                    <svg class="edit__form-icon-trash" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 6l3 18h12l3-18H3zm5 14l-1-10h2l1 10H8zm4 0l-1-10h2l1 10h-2zm4 0l-1-10h2l1 10h-2zM19 2h-5V0h-4v2H5v2h14V2z"/>
                    </svg>
                </button>
            </form>
        </div>
</div>
@endsection

@section('js')
<script>
    function updateFileName(input) {
        const fileName = input.files.length > 0 ? input.files[0].name : '';
        document.getElementById('filename').textContent = fileName;
    }

    function updateFileInfo(input) {
        const file = input.files[0];
        const filename = document.getElementById('filename');
        const preview = document.getElementById('preview');

        if (file) {
            filename.textContent = file.name;

            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection