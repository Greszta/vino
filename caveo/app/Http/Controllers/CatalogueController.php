<?php

namespace App\Http\Controllers;

use App\Models\Bouteille;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Base de la requête
        $query = Bouteille::query();

        // Si un terme de recherche est présent
        if ($search = request('recherche')) {
            // Filtre les noms qui commencent par la recherche
            $query->where('nom', 'like', $search . '%');
        }

        // Pagination + conservation du paramètre de recherche
        $bouteilles = $query->paginate(25)->withQueryString();

        // Retourne la vue avec les données
        return view('catalogue.index', compact('bouteilles'));
    }
}
