<?php

namespace App\Models;

use App\Models\Presence;
use App\Models\ListModel;
use Illuminate\Database\Eloquent\Model;

class PramanSession extends Model
{
     protected $table = 'praman_sessions';

    protected $fillable = [
        'list_id',
        'session_date',
        'title',
        'status',
    ];

    // Relations

    public function list()
    {
        return $this->belongsTo(ListModel::class, 'list_id');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class, 'praman_session_id');
    }
    
}
