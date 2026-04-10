@extends('layouts.main')

@section('title', $cellier->nom)

@section('fleche')
<a href="{{ route('celliers.index') }}" class="text-white text-2xl leading-none" aria-label="Retour aux celliers">
    ←
</a>
@endsection

@section('content')
<section class="px-4 py-5 pb-32 max-w-5xl mx-auto" style="font-family: 'Roboto', sans-serif;">
    <div class="bg-white rounded-2xl shadow border border-gray-100 p-5 mb-5">
        <h2 class="text-3xl text-[#7A1E2E] break-words" style="font-family: 'Crimson Text', serif;">
            {{ $cellier->nom }}
        </h2>

        <div class="mt-3 space-y-1 text-sm text-gray-700">
            <p>
                <span class="font-medium">Emplacement :</span>
                {{ $cellier->emplacement ?? 'Non précisé' }}
            </p>
            <p>
                <span class="font-medium">Description :</span>
                {{ $cellier->description ?? 'Aucune description' }}
            </p>
        </div>

        <div class="mt-4 flex flex-col gap-2 sm:flex-row">
            <a href="{{ route('celliers.edit', $cellier) }}"
                class="w-full sm:w-auto text-center px-4 py-3 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-100 transition text-sm">
                Modifier
            </a>

            <a href="{{ route('celliers.index') }}"
                class="w-full sm:w-auto text-center px-4 py-3 rounded-xl border border-[#7A1E2E] text-[#7A1E2E] hover:bg-[#7A1E2E] hover:text-white transition text-sm">
                Retour
            </a>
        </div>
    </div>

    <x-alerts />

    <div class="grid gap-5 lg:grid-cols-3">
        {{-- Formulaire ajout --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow border border-gray-100 p-5">
                <h3 class="text-2xl text-[#7A1E2E] mb-4" style="font-family: 'Crimson Text', serif;">
                    Ajouter une bouteille
                </h3>

                <form action="{{ route('inventaires.store', $cellier) }}" method="POST" class="space-y-4" novalidate>
                    @csrf

                    <div>
                        <label for="id_bouteille" class="block text-sm font-medium text-gray-700 mb-1">
                            Bouteille
                        </label>
                        <select
                            name="id_bouteille"
                            id="id_bouteille"
                            required
                            class="w-full rounded-xl border px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-[#7A1E2E] @error('id_bouteille') border-red-500 @else border-gray-300 @enderror">
                            <option value="">Choisir une bouteille</option>
                            @foreach($bouteilles as $bouteille)
                            <option value="{{ $bouteille->id }}" {{ old('id_bouteille') == $bouteille->id ? 'selected' : '' }}>
                                {{ $bouteille->nom }}
                                @if(!empty($bouteille->millesime))
                                ({{ $bouteille->millesime }})
                                @endif
                            </option>
                            @endforeach
                        </select>
                        @error('id_bouteille')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="quantite" class="block text-sm font-medium text-gray-700 mb-1">
                            Quantité
                        </label>
                        <input
                            type="number"
                            name="quantite"
                            id="quantite"
                            value="{{ old('quantite', 1) }}"
                            min="1"
                            class="w-full rounded-xl border px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-[#7A1E2E] @error('quantite') border-red-500 @else border-gray-300 @enderror">
                        @error('quantite')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-[#7A1E2E] text-white px-4 py-3 rounded-xl shadow hover:opacity-90 transition">
                        Ajouter au cellier
                    </button>
                </form>
            </div>
        </div>

        {{-- Inventaire --}}
        <div class="lg:col-span-2">
            <div class="space-y-4">
                @forelse($cellier->inventaires as $inventaire)
                <article class="bg-white rounded-2xl shadow border border-gray-100 p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 break-words">
                                {{ $inventaire->bouteille->nom ?? 'Bouteille introuvable' }}
                            </h4>

                            <div class="mt-2 space-y-1 text-sm text-gray-600">
                                <p><span class="font-medium">Type :</span> {{ $inventaire->bouteille->type ?? '-' }}</p>
                                <p><span class="font-medium">Pays :</span> {{ $inventaire->bouteille->pays ?? '-' }}</p>
                                <p><span class="font-medium">Millésime :</span> {{ $inventaire->bouteille->millesime ?? '-' }}</p>
                            </div>
                        </div>

                        @if($inventaire->quantite == 0)
                        <span class="shrink-0 inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                            Rupture
                        </span>
                        @endif
                    </div>

                    <div class="mt-4">
                        <p class="text-sm text-gray-700">
                            Quantité actuelle :
                            <span class="{{ $inventaire->quantite == 0 ? 'text-red-600 font-semibold' : 'font-semibold text-gray-900' }}">
                                {{ $inventaire->quantite }}
                            </span>
                        </p>

                        @if($inventaire->quantite == 0)
                        <p class="mt-1 text-xs text-red-500">
                            Cette bouteille est conservée dans le cellier, mais n’est plus en stock.
                        </p>
                        @endif
                    </div>

                    <form action="{{ route('inventaires.update', $inventaire) }}" method="POST" class="mt-4 space-y-2" novalidate>
                        @csrf
                        @method('PUT')

                        <label for="quantite_{{ $inventaire->id }}" class="block text-sm font-medium text-gray-700">
                            Modifier la quantité
                        </label>

                        <div class="flex gap-2">
                            <input
                                type="number"
                                name="quantite"
                                id="quantite_{{ $inventaire->id }}"
                                value="{{ $inventaire->quantite }}"
                                min="0"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-[#7A1E2E]">

                            <button type="submit"
                                class="px-4 py-3 rounded-xl border border-[#7A1E2E] text-[#7A1E2E] hover:bg-[#7A1E2E] hover:text-white transition">
                                OK
                            </button>
                        </div>

                        <p class="text-xs text-gray-500">
                            Mets 0 pour indiquer qu’il n’y a plus de stock.
                        </p>
                    </form>

                    <form action="{{ route('inventaires.destroy', $inventaire) }}" method="POST" class="mt-3">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            onclick="return confirm('Voulez-vous vraiment supprimer cette bouteille du cellier ?')"
                            class="w-full sm:w-auto px-4 py-2 rounded-xl border border-red-300 text-red-600 hover:bg-red-50 transition text-sm">
                            Supprimer la bouteille
                        </button>
                    </form>
                </article>
                @empty
                <div class="bg-white rounded-2xl shadow border border-gray-100 p-5">
                    <p class="text-gray-700">Aucune bouteille dans ce cellier pour le moment.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endsection