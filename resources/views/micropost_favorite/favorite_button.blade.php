@if (Auth::user()->is_favorite($micropost->id))
    {{-- アンフェイバリット（お気に入り解除）ボタン --}}
    <form method="POST" action="{{ route('favorites.unfavorite', $micropost->id) }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-error btn-block normal-case"
            onclick="return confirm('id = {{ $micropost->id }} のお気に入りを解除します。よろしいですか？')">Unfavorite</button>
    </form>
@else
    {{-- フェイバリット（お気に入り追加）ボタン --}}
    <form method="POST" action="{{ route('favorites.favorite', $micropost->id) }}">
        @csrf
        <button type="submit" class="btn btn-primary btn-block normal-case">Favorite</button>
    </form>
@endif