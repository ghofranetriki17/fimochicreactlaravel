<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'percentage',
        'start_date',
        'end_date',
    ];

    protected $dates = ['start_date', 'end_date'];

    /**
     * Vérifie si le code promo est encore valide en se basant sur la date de fin.
     *
     * @return bool
     */
    public function isValid()
    {
        return now()->lte($this->end_date);
    }
}
