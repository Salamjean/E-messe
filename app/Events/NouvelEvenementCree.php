<?php

namespace App\Events;

use App\Models\Evenement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NouvelEvenementCree
{
    use Dispatchable, SerializesModels;

    public $evenement;

    /**
     * Crée une nouvelle instance d'événement.
     */
    public function __construct(Evenement $evenement)
    {
        $this->evenement = $evenement;
    }
}
