<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'folder_id',
        'user_id',
        'name',
        'extension',
        'expires_at',
    ];
}
