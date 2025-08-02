@if (Auth::user()->is_favorite($micropost->id))
    {{-- お気に入り解除ボタン --}}
    <form method="POST" action="{{ route('favorites.unfavorite', $micropost->id) }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-warning btn-sm normal-case">Unfavorite</button>
    </form>
@else
    {{-- お気に入り追加ボタン --}}
    <form method="POST" action="{{ route('favorites.favorite', $micropost->id) }}">
        @csrf
        <button type="submit" class="btn btn-success btn-sm normal-case">Favorite</button>
    </form>
@endif
