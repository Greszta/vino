<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ListeAchat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListeAchatController extends Controller
{
    public function index(){
        /**
         * Liste d'achat de l'utilisateur connecté.
         */
        $listes = collect();

        if (Auth::check()) {
            $listes = ListeAchat::where('id_utilisateur', Auth::id())
                ->orderBy('nom')
                ->get();
        }

        return view('liste-achat.index', ['listes' => $listes]);
    }

    public function create(){
        return view('liste-achat.create');
    }

    public function store(Request $request)
    {
        $utilisateur = Auth::user();

        $validated = $request->validate([
            'nom' => 'required|string|max:75|',
            'description' => 'nullable|string|max:2000',
        ]);

        ListeAchat::create([
            'nom' => $validated['nom'],
            'id_utilisateur' => $utilisateur->id,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('achat.index');
    }

    public function edit(ListeAchat $liste)
    {
        $this->verifierProprietaire($liste);

        return view('liste-achat.edit', compact('liste'));
    }

    public function update(Request $request, ListeAchat $liste)
    {
        $this->verifierProprietaire($liste);

        $validated = $request->validate([
            'nom' => 'required|string|max:75',
            'description' => 'nullable|string|max:2000',
        ]);

        $liste->update([
            'nom' => $validated['nom'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('achat.index')
            ->with('status', 'La liste d\'achat a été modifié avec succès.');
    }

    public function destroy(ListeAchat $liste)
    {
        $this->verifierProprietaire($liste);

        $liste->delete();

        return redirect()
            ->route('achat.index')
            ->with('status', 'La liste d\'achat a été supprimé avec succès.');
    }

    private function verifierProprietaire(ListeAchat $liste): void
    {
        $utilisateur = Auth::user();

        abort_if(
            $liste->id_utilisateur !== $utilisateur->id,
            403,
            'Accès non autorisé à cette liste d\'achat.'
        );
    }
}
