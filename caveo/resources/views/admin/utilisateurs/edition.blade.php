@extends('layouts.main')

@section('title', 'Modifier un utilisateur')

@section('fleche')

<script src="{{ asset('js/selection-role.js') }}"></script>
<script src="{{ asset('js/toggle-mot-de-passe.js') }}"></script>

<a href="{{ route('admin.utilisateurs.index') }}" class="text-white text-2xl leading-none" aria-label="Retour">
    <img src="/images/fleches/gauche-blanc.svg" alt="Flèche de retour" class="w-10 h-10">
</a>
@endsection

@section('content')

@php
    $initiales = strtoupper(mb_substr($utilisateur->prenom, 0, 1) . mb_substr($utilisateur->nom, 0, 1));
    $roleActuel = optional($utilisateur->role)->nom;
    $afficherMotDePasse = $errors->has('mot_de_passe');
@endphp

<div class="m-4">
    <h1 class="text-3xl text-[#7A1E2E]" style="font-family: 'Crimson Text', serif;">
        Modifier un utilisateur
    </h1>
    <p class="text-sm text-gray-600 mt-1">
        Mettez à jour les informations de l'utilisateur.
    </p>
</div>

<div class="m-4">
    <x-alerts />
</div>

<!-- Carte de prévisualisation de l'utilisateur -->
<div class="mx-4 mb-4 flex items-center gap-3 border border-gray-200 rounded-lg p-4 bg-gradient-to-r from-[#FAF3F4] to-white">
    <div class="w-14 h-14 rounded-full bg-[#7A1E2E] text-white flex items-center justify-center font-semibold text-lg shrink-0">
        {{ $initiales ?: '?' }}
    </div>
    <div class="flex-1 min-w-0">
        <p class="text-base font-bold text-[#1A1A1A] truncate">
            {{ $utilisateur->prenom }} {{ $utilisateur->nom }}
        </p>
        <p class="text-sm text-gray-600 truncate">
            {{ $utilisateur->email }}
        </p>
    </div>
    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium shrink-0
        {{ $roleActuel === 'admin' ? 'bg-red-100 text-red-800' : 'bg-gray-200 text-gray-600' }}">
        {{ $roleActuel ?? 'Non défini' }}
    </span>
</div>

<form method="POST" action="{{ route('admin.utilisateurs.update', $utilisateur) }}" novalidate>
    @csrf
    @method('PUT')

    <!-- Section : Informations personnelles -->
    <div class="mx-4 mb-4 border border-gray-200 rounded-lg bg-white overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
            <h2 class="font-semibold text-[#1A1A1A]">Informations personnelles</h2>
        </div>
        <div class="p-4 flex flex-col gap-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Prénom -->
                <div>
                    <label for="prenom" class="block mb-1 text-sm font-semibold text-[#1A1A1A]">
                        Prénom
                    </label>
                    <input
                        type="text"
                        id="prenom"
                        name="prenom"
                        value="{{ old('prenom', $utilisateur->prenom) }}"
                        placeholder="Ex. Marie"
                        class="w-full border-2 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#A83248] focus:border-[#A83248] @error('prenom') border-red-600 @else border-gray-200 @enderror">
                    @error('prenom')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nom -->
                <div>
                    <label for="nom" class="block mb-1 text-sm font-semibold text-[#1A1A1A]">
                        Nom
                    </label>
                    <input
                        type="text"
                        id="nom"
                        name="nom"
                        value="{{ old('nom', $utilisateur->nom) }}"
                        placeholder="Ex. Dupont"
                        class="w-full border-2 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#A83248] focus:border-[#A83248] @error('nom') border-red-600 @else border-gray-200 @enderror">
                    @error('nom')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Courriel -->
            <div>
                <label for="email" class="block mb-1 text-sm font-semibold text-[#1A1A1A]">
                    Adresse courriel
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $utilisateur->email) }}"
                    placeholder="exemple@courriel.com"
                    class="w-full border-2 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#A83248] focus:border-[#A83248] @error('email') border-red-600 @else border-gray-200 @enderror">
                @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Rôle -->
            <div>
                <label class="block mb-2 text-sm font-semibold text-[#1A1A1A]">
                    Rôle
                </label>
                <div class="flex flex-wrap gap-2">
                    @foreach($roles as $role)
                    <label class="cursor-pointer">
                        <input
                            type="checkbox"
                            name="id_role"
                            value="{{ $role->id }}"
                            class="role-checkbox hidden peer"
                            {{ old('id_role', $utilisateur->id_role) == $role->id ? 'checked' : '' }}>
                        <span class="inline-block px-4 py-2 rounded-full text-sm font-medium border
                            peer-checked:bg-[#A83248] peer-checked:text-white peer-checked:border-[#A83248]
                            bg-white text-[#1A1A1A] border-gray-300">
                            {{ $role->nom }}
                        </span>
                    </label>
                    @endforeach
                </div>
                @error('id_role')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Section : Accès & sécurité -->
    <div class="mx-4 mb-4 border border-gray-200 rounded-lg bg-white overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
            <h2 class="font-semibold text-[#1A1A1A]">Accès &amp; sécurité</h2>
            <span class="text-xs text-gray-500">Optionnel</span>
        </div>
        <div class="p-4">
            <button type="button" id="toggleMotDePasse"
                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg text-[#7A1E2E] hover:bg-[#FAF3F4] hover:border-[#A83248] transition {{ $afficherMotDePasse ? 'hidden' : '' }}"
                aria-label="Modifier le mot de passe">
                <img src="{{ asset('images/icons/crayon.svg') }}" alt="" aria-hidden="true" class="w-5 h-5">
                <span class="font-medium">Modifier le mot de passe</span>
            </button>

            <div id="motDePasseChamps" class="{{ $afficherMotDePasse ? '' : 'hidden' }} flex flex-col gap-4">
                <p class="text-xs text-gray-500">
                    Laissez vide pour conserver le mot de passe actuel.
                </p>
                <div>
                    <label for="mot_de_passe" class="block mb-1 text-sm font-semibold text-[#1A1A1A]">
                        Nouveau mot de passe
                    </label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe"
                        placeholder="••••••••••••••••"
                        autocomplete="new-password"
                        class="w-full border-2 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#A83248] focus:border-[#A83248] @error('mot_de_passe') border-red-600 @else border-gray-200 @enderror">
                    @error('mot_de_passe')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="mot_de_passe_confirmation" class="block mb-1 text-sm font-semibold text-[#1A1A1A]">
                        Confirmer le mot de passe
                    </label>
                    <input type="password" id="mot_de_passe_confirmation" name="mot_de_passe_confirmation"
                        placeholder="••••••••••••••••"
                        autocomplete="new-password"
                        class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#A83248] focus:border-[#A83248]">
                </div>
            </div>
        </div>
    </div>

    <!-- Boutons d'action -->
    <div class="mx-4 flex gap-3 mb-28">
        <a href="{{ route('admin.utilisateurs.index') }}"
            class="w-1/2 text-center border border-gray-300 py-3 rounded-lg font-medium hover:bg-gray-50">
            Annuler
        </a>
        <button type="submit"
            class="w-1/2 bg-[#A83248] text-white py-3 rounded-lg font-medium hover:bg-[#7A1E2E] transition">
            Sauvegarder
        </button>
    </div>
</form>
@endsection