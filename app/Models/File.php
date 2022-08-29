<?php

namespace App\Models;

use App\Services\StorageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Storage;

class File extends Model
{
    use HasFactory, Prunable;

    protected $fillable = [
        'folder_id',
        'user_id',
        'name',
        'extension',
        'expires_at',
    ];

    protected $appends = [
        'full_name',
        'size',
        'public_url',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function prunable()
    {
        return static::where('expires_at', '>=', now());
    }

    protected function pruning()
    {
        if ($this->downloadLink) {
            $this->downloadLink->delete();
        }
    }

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

    public function downloadLink()
    {
        return $this->hasOne(DownloadLink::class);
    }

    public function getPublicUrlAttribute()
    {
        $downloadLink =$this->downloadLink;
        return $downloadLink ? $downloadLink->url : null;
    }
}
