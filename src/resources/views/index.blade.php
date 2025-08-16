@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
    <div class="content">
        <div class="content-header">
            <h1 class="content-header__title">商品一覧</h1>
            <a href="{{ route('products.register') }}" class="content-header__button">+商品を追加</a>
        </div>

        <div class="content-body">
            <aside class="content__sidebar">
                <form class="search-form" action="{{ route('products.search') }}" method="GET">
                    <div class="search-form__group">
                        <input class="search-form__input" type="text" name="keyword" placeholder="商品名で検索" value="{{ request('keyword') }}">

                        <button class="search-form__button" type="submit">検索</button>

                        <label class="search-form__label" for="sort">価格順で表示</label>
                        <div class="search-form__select-wrapper">
                            <select class="search-form__select {{ request('sort') ? '' : 'is-placeholder' }}" name="sort" id="sort">
                                <option value="" disabled {{ request('sort') ? '' : 'selected' }} hidden>価格順で並び替え</option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>高い順に表示</option>
                                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>安い順に表示</option>
                            </select>
                        </div>
                        @php
                            $sort = request('sort');
                            $isSorted = in_array($sort, ['price_asc','price_desc'], true);
                            $qs = http_build_query(request()->except('sort'));
                            $clearUrl = url('/products') . ($qs ? ('?'.$qs) : '');
                        @endphp

                        @if ($isSorted)
                            <div class="sort-chip">
                                <span class="sort-chip__label">
                                    {{ $sort === 'price_desc' ? '高い順に表示' : '安い順に表示' }}
                                </span>
                                <a href="{{ $clearUrl }}" class="sort-chip__clear" aria-label="並び替えを解除">×</a>
                            </div>
                        @endif
                    </div>
                </form>
            </aside>

            <section class="product-list">
                @forelse ($products as $product)
                    <a class="product-card" href="{{ url('/products/' . $product->id . '/update') }}">
                        <div class="product-card__image-wrap">
                            <img
                                class="product-card__image"
                                src="{{ asset('storage/fruits-img/' . basename($product->image)) }}"
                                alt="{{ $product->name }}"
                                onerror="this.src='{{ asset('images/noimage.png') }}'">
                        </div>
                        <div class="product-card__info">
                            <h2 class="product-card__info-name">{{ $product->name }}</h2>
                            <p class="product-card__info-price">¥{{ number_format($product->price) }}</p>
                        </div>
                    </a>
                @empty
                    <p class="product-list__empty">該当する商品が見つかりませんでした。</p>
                @endforelse

                @if(method_exists($products, 'links'))
                    <div class="product-list__pagination">
                        {{ $products->links('vendor.pagination.custom') }}
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectWrapper = document.querySelector('.search-form__select-wrapper');
    const select = selectWrapper?.querySelector('select');

    select?.addEventListener('mousedown', () => {
        selectWrapper.classList.toggle('open');
    });
    document.addEventListener('click', (e) => {
        if (!selectWrapper.contains(e.target)) {
        selectWrapper.classList.remove('open');
        }
    });

    const sort = document.getElementById('sort');
    if (sort) {
        const syncPlaceholderClass = () => sort.classList.toggle('is-placeholder', !sort.value);
        syncPlaceholderClass();
        sort.addEventListener('change', syncPlaceholderClass);
    }
});
</script>
@endsection