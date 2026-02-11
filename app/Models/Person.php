<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $fillable = ['list_id', 'name'];

    public function presences()
{
    return $this->hasMany(Presence::class);
}
}
