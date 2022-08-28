<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'file_id',
    ];

    public function getUrlAttribute()
    {
        return url('api/v1/downloads/'.$this->uuid);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
