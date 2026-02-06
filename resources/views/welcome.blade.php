@extends('layouts.app')

@section('scripts')
    <script>
        // Initialize user favorites from server data
        window.userFavorites = {{ Js::from($favoriteIds ?? []) }};
    </script>
@endsection