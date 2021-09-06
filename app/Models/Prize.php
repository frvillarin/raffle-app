<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    use HasFactory;

    protected $fillable= [
        'title',
    ];

    public function winner()
    {
        return $this->hasOne(Winner::class);
    }
}
