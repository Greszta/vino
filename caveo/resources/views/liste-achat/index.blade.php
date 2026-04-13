@extends('layouts.main')
@section('title', 'Liste Achat')

@section('fleche')
    <!-- Flèche de retour qui revient à la page précédente (Cellier ou Catalogue) -->
    <a href="{{ url()->previous() }}">
        <img src="{{ asset('images/fleches/gauche-blanc.svg') }}" alt="Flèche de retour" class="w-10 h-10">
    </a>
@endsection

@section('content')

<p>allo</p>

@endsection