<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['name', 'path'];
    
    public function chunks()
    {
        return $this->hasMany(Chunk::class);
    }
}
