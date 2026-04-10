@extends('layouts.main')

@section('title', 'Créer un cellier')

@section('fleche')
<a href="{{ route('celliers.index') }}" class="text-white text-2xl leading-none" aria-label="Retour">
    ←
</a>
@endsection

@section('content')
<section class="px-4 py-5 pb-32 max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow border border-gray-100 p-5">
        <h2 class="text-3xl text-[#7A1E2E] mb-5" style="font-family: 'Crimson Text', serif;">
            Créer un cellier
        </h2>

        <x-alerts />

        <form action="{{ route('celliers.store') }}" method="POST" class="space-y-5" novalidate>
            @csrf

            @include('celliers._form')

            <div class="flex flex-col gap-2 sm:flex-row">
                <button type="submit"
                    class="w-full sm:w-auto bg-[#7A1E2E] text-white px-5 py-3 rounded-xl shadow hover:opacity-90 transition">
                    Enregistrer
                </button>

                <a href="{{ route('celliers.index') }}"
                    class="w-full sm:w-auto text-center px-5 py-3 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</section>
@endsection