<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class States extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'population', "country_id"];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Countries::class, "country_id");
    }
    use HasFactory;
}
