@extends('layouts.main')

@section('title', 'Mes celliers')

@section('content')
<section class="px-4 py-5 pb-32 max-w-5xl mx-auto" style="font-family: 'Roboto', sans-serif;">
    <div class="flex items-start justify-between gap-3 mb-5">
        <div>
            <h2 class="text-3xl text-[#7A1E2E]" style="font-family: 'Crimson Text', serif;">
                Mes celliers
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                Gérez vos celliers en toute simplicité.
            </p>
        </div>

        <a href="{{ route('celliers.create') }}"
            class="shrink-0 bg-[#7A1E2E] text-white px-4 py-2 rounded-xl shadow hover:opacity-90 transition text-sm">
            Nouveau
        </a>
    </div>

    <x-alerts />

    @if($celliers->isEmpty())
    <div class="bg-white rounded-2xl shadow border border-gray-100 p-5">
        <p class="text-gray-700">Vous n’avez encore aucun cellier.</p>
    </div>
    @else
    <div class="space-y-4">
        @foreach($celliers as $cellier)
        <article class="bg-white rounded-2xl shadow border border-gray-100 p-4">
            <h3 class="text-2xl text-[#7A1E2E] break-words" style="font-family: 'Crimson Text', serif;">
                {{ $cellier->nom }}
            </h3>

            <div class="mt-3 space-y-1 text-sm text-gray-700">
                <p>
                    <span class="font-medium">Emplacement :</span>
                    {{ $cellier->emplacement ?? 'Non précisé' }}
                </p>
                <p>
                    <span class="font-medium">Description :</span>
                    {{ $cellier->description ?? 'Aucune description' }}
                </p>
                <p>
                    <span class="font-medium">Entrées d’inventaire :</span>
                    {{ $cellier->inventaires_count ?? 0 }}
                </p>
            </div>

            <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                <a href="{{ route('celliers.show', $cellier) }}"
                    class="w-full sm:w-auto text-center px-4 py-2 rounded-xl border border-[#7A1E2E] text-[#7A1E2E] hover:bg-[#7A1E2E] hover:text-white transition text-sm">
                    Voir le cellier
                </a>

                <a href="{{ route('celliers.edit', $cellier) }}"
                    class="w-full sm:w-auto text-center px-4 py-2 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-100 transition text-sm">
                    Modifier
                </a>

                <form action="{{ route('celliers.destroy', $cellier) }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        onclick="return confirm('Voulez-vous vraiment supprimer ce cellier ? Cette action est irréversible.')"
                        class="w-full sm:w-auto px-4 py-2 rounded-xl border border-red-300 text-red-600 hover:bg-red-50 transition text-sm">
                        Supprimer
                    </button>
                </form>
            </div>
        </article>
        @endforeach
    </div>
    @endif
</section>
@endsection