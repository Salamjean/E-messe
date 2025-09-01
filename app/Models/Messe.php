<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messe extends Model
{
    protected $fillable = [
        'user_id',
        'paroisse_id',
        'type_intention',
        'nom_defunt',
        'motif_action_graces',
        'motif_intention',
        'nom_prenom_concernes',
        'date_souhaitee',
        'heure_souhaitee',
        'celebration_choisie',
        'nom_demandeur',
        'email_demandeur',
        'telephone_demandeur',
        'montant_offrande',
        'statut',
        'dates_selectionnees',
        'download_count', 
        'last_downloaded_at', 
    ];

    public function paroisse()
    {
        return $this->belongsTo(Paroisse::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function dernierPaiement()
    {
        return $this->hasOne(Paiement::class)->latest();
    }

    public static function getStatuts()
    {
        return [
            'en_attente_paiement' => 'En attente de paiement',
            'payee' => 'Payée',
            'confirmee' => 'Confirmée',
            'annulee' => 'Annulée',
            'echec_paiement' => 'Échec de paiement'
        ];
    }

    protected function nomPrenomConcernes(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true) ?? [],
            set: fn ($value) => is_array($value) ? json_encode($value) : $value,
        );
    }

    public function hasValidDates(): bool
    {
        // Pour les messes sans dates sélectionnées (date simple)
        if (empty($this->dates_selectionnees) || $this->dates_selectionnees == '[]' || $this->dates_selectionnees == 'null') {
            return !Carbon::parse($this->date_souhaitee)->endOfDay()->isPast();
        }
        
        // Pour les messes avec dates multiples
        $dates = json_decode($this->dates_selectionnees, true);
        
        if (!is_array($dates) || empty($dates)) {
            return !Carbon::parse($this->date_souhaitee)->endOfDay()->isPast();
        }
        
        // Vérifier s'il y a au moins une date valide à partir de date_souhaitee
        foreach ($dates as $date) {
            if ($this->isDateValidFromStart($date)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Vérifie si une date/jour est valide à partir de date_souhaitee
     */
    private function isDateValidFromStart(string $date): bool
    {
        $startDate = Carbon::parse($this->date_souhaitee);
        
        // Si c'est un jour de la semaine (messe quotidienne)
        if (in_array($date, ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'])) {
            // Trouver la prochaine occurrence de ce jour à partir de date_souhaitee
            $nextDate = $this->findNextDayOccurrence($date, $startDate);
            
            // Vérifier si la date n'est pas passée
            return !$nextDate->endOfDay()->isPast();
        }
        
        // Si c'est une date spécifique (messe dominicale)
        try {
            $dateObj = Carbon::parse($date);
            // Vérifier si la date est égale ou après date_souhaitee et non passée
            return $dateObj->gte($startDate) && !$dateObj->endOfDay()->isPast();
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Trouve la prochaine occurrence d'un jour de la semaine à partir d'une date
     */
    private function findNextDayOccurrence(string $day, Carbon $startDate): Carbon
    {
        $frenchDays = ['Lundi' => 1, 'Mardi' => 2, 'Mercredi' => 3, 'Jeudi' => 4, 
                      'Vendredi' => 5, 'Samedi' => 6, 'Dimanche' => 7];
        
        $targetDay = $frenchDays[$day];
        $currentDay = $startDate->dayOfWeekIso;
        
        // Calculer le nombre de jours à ajouter
        $daysToAdd = $targetDay - $currentDay;
        if ($daysToAdd < 0) {
            $daysToAdd += 7;
        }
        
        return $startDate->copy()->addDays($daysToAdd);
    }
    
    /**
     * Récupère les dates valides à partir de date_souhaitee
     */
    public function getValidDates(): array
    {
        $validDates = [];
        $startDate = Carbon::parse($this->date_souhaitee);
        
        // Pour les messes sans dates sélectionnées
        if (empty($this->dates_selectionnees) || $this->dates_selectionnees == '[]' || $this->dates_selectionnees == 'null') {
            if (!$startDate->endOfDay()->isPast()) {
                $validDates[] = $startDate->format('d/m/Y');
            }
            return $validDates;
        }
        
        $dates = json_decode($this->dates_selectionnees, true);
        
        if (!is_array($dates) || empty($dates)) {
            if (!$startDate->endOfDay()->isPast()) {
                $validDates[] = $startDate->format('d/m/Y');
            }
            return $validDates;
        }
        
        // Mapper les jours français aux numéros
        $frenchDays = ['Lundi' => 1, 'Mardi' => 2, 'Mercredi' => 3, 'Jeudi' => 4, 
                      'Vendredi' => 5, 'Samedi' => 6, 'Dimanche' => 7];
        
        foreach ($dates as $date) {
            // Si c'est un jour de la semaine
            if (isset($frenchDays[$date])) {
                $nextDate = $this->findNextDayOccurrence($date, $startDate);
                
                if (!$nextDate->endOfDay()->isPast()) {
                    $validDates[] = $date . ' (' . $nextDate->format('d/m/Y') . ')';
                }
            } 
            // Si c'est une date spécifique
            else {
                try {
                    $dateObj = Carbon::parse($date);
                    if ($dateObj->gte($startDate) && !$dateObj->endOfDay()->isPast()) {
                        $validDates[] = $dateObj->format('d/m/Y');
                    }
                } catch (\Exception $e) {
                    // Ignorer les dates invalides
                }
            }
        }
        
        // Trier les dates chronologiquement
        usort($validDates, function($a, $b) {
            // Extraire la date pour la comparaison
            $dateA = $this->extractDateFromString($a);
            $dateB = $this->extractDateFromString($b);
            
            return strtotime($dateA) - strtotime($dateB);
        });
        
        return $validDates;
    }
    
    /**
     * Extrait la date d'une chaîne (pour le tri)
     */
    private function extractDateFromString(string $dateString): string
    {
        // Si la chaîne contient une date entre parenthèses (ex: "Lundi (15/01/2024)")
        if (preg_match('/\(([^)]+)\)/', $dateString, $matches)) {
            return $matches[1];
        }
        
        // Sinon, c'est déjà une date
        return $dateString;
    }
    
    /**
     * Accesseur pour les dates valides
     */
    public function getValidDatesAttribute(): array
    {
        return $this->getValidDates();
    }
    
    /**
     * Récupère la période de validité à partir de date_souhaitee
     */
    public function getValidityPeriod(): array
    {
        $startDate = Carbon::parse($this->date_souhaitee);
        $validDates = $this->getValidDates();
        
        if (empty($validDates)) {
            return [
                'start' => $startDate->format('d/m/Y'),
                'end' => 'Aucune date valide'
            ];
        }
        
        // Trouver la dernière date valide
        $lastDate = null;
        foreach ($validDates as $dateStr) {
            $date = $this->extractDateFromString($dateStr);
            $dateObj = Carbon::createFromFormat('d/m/Y', $date);
            
            if (!$lastDate || $dateObj->gt($lastDate)) {
                $lastDate = $dateObj;
            }
        }
        
        return [
            'start' => $startDate->format('d/m/Y'),
            'end' => $lastDate ? $lastDate->format('d/m/Y') : $startDate->format('d/m/Y')
        ];
    }

    public function getCelebrationsCount(): array
{
    $total = 0;
    $celebrated = 0;
    
    $startDate = Carbon::parse($this->date_souhaitee);
    $today = Carbon::today();
    
    // Pour les messes sans dates sélectionnées (date simple)
    if (empty($this->dates_selectionnees) || $this->dates_selectionnees == '[]' || $this->dates_selectionnees == 'null') {
        $total = 1;
        // Utilisez le download_count comme compteur de célébrations
        $celebrated = min($this->download_count, 1); // Maximum 1 même si téléchargé plusieurs fois
        return ['total' => $total, 'celebrated' => $celebrated];
    }
    
    // Pour les messes avec dates multiples
    $dates = json_decode($this->dates_selectionnees, true);
    
    if (!is_array($dates) || empty($dates)) {
        $total = 1;
        $celebrated = min($this->download_count, 1);
        return ['total' => $total, 'celebrated' => $celebrated];
    }
    
    // Pour les messes avec dates multiples, chaque téléchargement compte comme une célébration
    $total = count($dates);
    $celebrated = min($this->download_count, $total); // Ne pas dépasser le total
    
    return ['total' => $total, 'celebrated' => $celebrated];
}

    /**
     * Accesseur pour le compteur de célébrations
     */
    public function getCelebrationsCountAttribute(): array
    {
        return $this->getCelebrationsCount();
    }
}