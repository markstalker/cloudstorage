<?php

namespace App\Models;

use App\Services\StorageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

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

    protected $appends = ['full_name', 'size'];

    /**
     * Build string from file name and extension.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        $filename = $this->name;
        if ($this->extension) {
            $filename .= '.'.$this->extension;
        }
        return $filename;
    }

    /**
     * Get a file size in bytes.
     *
     * @return int
     */
    public function getSizeAttribute(): int
    {
        $filesystemName = StorageService::getFilesystemName($this);
        return Storage::size(StorageService::FOLDER_NAME.'/'.$filesystemName);
    }
}
