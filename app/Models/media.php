<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = ['content_id', 'file_path'];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
