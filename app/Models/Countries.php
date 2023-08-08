<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Countries extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'population'];
    public function states(): HasMany
    {
        return $this->hasMany(States::class, "country_id");
    }
    use HasFactory;
}
