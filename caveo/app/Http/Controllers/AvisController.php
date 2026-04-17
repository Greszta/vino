<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Avis;

class AvisController extends Controller
{
    // Fonction permettant d'ajouter un nouvel avis
    public function store(Request $request) {

        $request->validate(
            [
                'id_bouteille' => 'required|exists:bouteilles,id',
                'note' => 'required|numeric|min:0.5|max:5',
                'commentaire' => 'nullable|string',
            ]
        );

        $utilisateur = Auth::user();

        $avisExistant = Avis::where('id_utilisateur', $utilisateur->id)
            ->where('id_bouteille', $request->id_bouteille)
            ->first();

        if ($avisExistant) {
            return redirect()->back()->with('error', 'Vous avez déjà laissé un avis, veuillez le modifier.');
        }

        Avis::create(
            [
                'id_utilisateur' => $utilisateur->id,
                'id_bouteille' => $request->id_bouteille,
                'note' => $request->note,
                'commentaire' => $request->commentaire,
            ]
        );

        return redirect()->back()->with('status', 'Avis ajouté avec succès.');
    }

    // Fonction permettant de modifier un avis existant
    public function update(Request $request, Avis $avis) {
       
        $request->validate(
            [
                'note' => 'required|numeric|min:0.5|max:5',
                'commentaire' => 'nullable|string',
            ]
        );

        return redirect()->back()->with('status', 'Avis modifié avec succès.');
    }
}
