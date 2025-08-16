@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/create.css') }}">
@endsection

@section('content')
<div class="create">
    <form class="create__form" action="{{ url('/products') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <h1 class="create__form-title">商品登録</h1>

        <div class="create__form-group">
            <label class="create__form-label">
                商品名
                <span class="create__form-required">必須</span>
            </label>
            <input class="create__form-input" type="text" name="name" placeholder="商品名を入力" value="{{ old('name') }}">
            <div class="create__form-error">
                @error('name')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="create__form-group">
            <label class="create__form-label">
                値段
                <span class="create__form-required">必須</span>
            </label>
            <input class="create__form-input" type="number" name="price" placeholder="値段を入力" value="{{ old('price') }}">
            <div class="create__form-error">
                @error('price')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="create__form-group">
            <label class="create__form-label">
                商品画像
                <span class="create__form-required">必須</span>
            </label>
            <img class="create__form-preview-img" id="imagePreview" src="#" alt="プレビュー" style="display: none;">
            <div class="create__form-file-preview">
                <label class="create__form-file-label">
                    ファイルを選択
                    <input class="create__form-input--file" id="imageInput" type="file" name="image" accept="image/*" value="{{ old('image') }}">
                </label>
                <span class="create__form-preview-name" id="fileName"></span>
            </div>
            <div class="create__form-error">
                @error('image')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="create__form-group">
            <label class="create__form-label">
                季節
                <span class="create__form-required">必須</span>
                <span class="create__form-note">複数選択可</span>
            </label>
            <div class="create__form-season-options">
                @foreach ($seasons as $season)
                    <label class="create__form-season-label">
                        <input type="checkbox" name="seasons[]" value="{{ $season->id }}" {{ is_array(old('seasons')) && in_array($season->id, old('seasons')) ? 'checked' : '' }}>
                        {{ $season->name }}
                    </label>
                @endforeach
            </div>
            <div class="create__form-error">
                @error('seasons')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="create__form-group">
            <label class="create__form-label">
                商品説明
                <span class="create__form-required">必須</span>
            </label>
            <textarea class="create__form-textarea" name="description" placeholder="商品の説明を入力" value="{{ old('description') }}"></textarea>
            <div class="create__form-error">
                @error('description')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="create__form-buttons">
            <a class="create__form-button create__form-button--buck" href="{{ url('/products') }}">戻る</a>
            <button class="create__form-button create__form-button--submit" type="submit">登録</button>
        </div>
    </form>
</div>
@endsection

@section('js')
<script>
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    const fileName = document.getElementById('fileName');

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };

        reader.readAsDataURL(file);
        fileName.textContent = file.name;
    } else {
        preview.src = '#';
        preview.style.display = 'none';
        fileName.textContent = '';
    }
});
</script>
@endsection