<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = ['name', 'satuan', 'pilar', 'judul', 'deskripsi', 'status'];

    public function media()
    {
        return $this->hasMany(Media::class);
    }
}