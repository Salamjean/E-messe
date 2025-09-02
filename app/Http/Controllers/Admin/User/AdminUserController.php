<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::active()->get();
        $localisations = User::distinct()->pluck('email');
        
        // Gestion du tri
        $sort = request()->get('sort');
        if ($sort) {
            switch ($sort) {
                case 'name':
                    $users = $users->sortBy('name');
                    break;
                case 'name_desc':
                    $users = $users->sortByDesc('name');
                    break;
                case 'recent':
                    $users = $users->sortByDesc('created_at');
                    break;
                case 'oldest':
                    $users = $users->sortBy('created_at');
                    break;
            }
        }
        
        return view('admin.user.index', compact('users', 'localisations'));
    }

    // Archiver un utilisateur
    public function archive(User $user)
    {
        $user->archive(); // Utilise la méthode archive du modèle
        
        return redirect()->route('admin.user.index')
            ->with('success', 'Utilisateur archivé avec succès');
    }

    // Lister les utilisateurs archivés
    public function archived()
    {
        $archivedUsers = User::archived()->get(); // Utilise le scope archived
        return view('admin.user.archived', compact('archivedUsers'));
    }

    // Restaurer un utilisateur archivé
    public function restore($id)
    {
        $user = User::find($id);
        $user->unarchive(); // Utilise la méthode unarchive du modèle
        
        return redirect()->route('users.archived')
            ->with('success', 'Utilisateur restauré avec succès');
    }

    // Supprimer définitivement un utilisateur
    public function forceDelete($id)
    {
        $user = User::find($id);
        $user->delete(); // Suppression définitive de la base de données
        
        return redirect()->route('users.archived')
            ->with('success', 'Utilisateur supprimé définitivement');
    }
}
