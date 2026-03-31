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
        $bouteilles = Bouteille::paginate(25);
        return view('catalogue.index', compact('bouteilles'));
    }
}
