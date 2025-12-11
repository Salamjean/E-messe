<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Paroisse;
use Illuminate\Http\Request;

class ParoisseController extends Controller
{
    public function index(Request $request)
    {
        $query = Paroisse::query();

        // dd($query);
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhereHas('commune', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        $paroisses = $query->with('commune')->get();

        // dd($paroisses);
        // Get user favorites IDs
        $favorites = auth()->check()
            ? auth()->user()->favoris()->pluck('paroisse_id')->toArray()
            : [];

        return view('user.paroisse.index', compact('paroisses', 'favorites'));
    }

    public function show($id)
    {
        $paroisse = Paroisse::with(['commune', 'messes' => function ($q) {
            $q->where('statut', 'celebre')->latest()->take(5);
        }])->findOrFail($id);

        $isFavorite = auth()->check()
            ? auth()->user()->favoris()->where('paroisse_id', $id)->exists()
            : false;

        return view('user.paroisse.show', compact('paroisse', 'isFavorite'));
    }

    public function toggleFavorite($id)
    {
        $user = auth()->user();

        // Check if already favorite
        $favori = $user->favoris()->where('paroisse_id', $id)->first();

        if ($favori) {
            $favori->delete();
            $status = 'removed';
            $message = 'Paroisse retirée des favoris';
        } else {
            $user->favoris()->create(['paroisse_id' => $id]);
            $status = 'added';
            $message = 'Paroisse ajoutée aux favoris';
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }
}
