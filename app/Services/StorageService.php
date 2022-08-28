<?php

namespace App\Services;

use App\Models\File;
use Auth;
use Illuminate\Http\UploadedFile;
use Storage;
use Str;

class StorageService
{
    public const FOLDER_NAME = 'files';

    /**
     * Create file in user storage.
     *
     * @param UploadedFile $uploadedFile
     * @return File
     */
    public static function createFile(\Illuminate\Http\UploadedFile $uploadedFile): File
    {
        $file = File::create([
            'uuid' => Str::uuid(),
            'user_id' => Auth::user()->id,
            'name' => pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME),
            'extension' => $uploadedFile->getClientOriginalExtension(),
        ]);

        $path = $file->id;
        if ($file->extension) {
            $path .= '.'.$file->extension;
        }

        $uploadedFile->storeAs(self::FOLDER_NAME, self::getFilesystemName($file));
        return $file;
    }

    /**
     * Remove file from user storage.
     *
     * @param File $file
     * @return void
     */
    public static function removeFile(File $file): void
    {
        Storage::delete(self::FOLDER_NAME.'/'.$file->full_name);
        $file->delete();
    }

    /**
     * Rename user file.
     *
     * @param File $file
     * @param string $newName
     * @return void
     */
    public static function renameFile(File $file, string $newName): void
    {
        $file->update(['name' => $newName]);
    }

    /**
     * Get file name in filesystem.
     *
     * @param File $file
     * @return string
     */
    public static function getFilesystemName(File $file): string
    {
        $filename = $file->id;
        if ($file->extension) {
            $filename .= '.'.$file->extension;
        }
        return $filename;
    }
}
