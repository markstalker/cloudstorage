<?php

namespace App\Services;

use App\Models\File;
use Auth;
use Illuminate\Http\UploadedFile;
use Storage;
use Str;

class StorageService
{
    private const FOLDER_NAME = 'files';

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
            'name' => $uploadedFile->getClientOriginalName(),
            'extension' => $uploadedFile->getClientOriginalExtension(),
        ]);

        $filename = self::buildFileName($file->id, $uploadedFile->getClientOriginalExtension());
        $uploadedFile->storeAs(self::FOLDER_NAME, $filename);
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
        Storage::delete(self::FOLDER_NAME.'/'.self::buildFileName($file->id, $file->extension));
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
     * Build string from file name and extension.
     *
     * @param string $name
     * @param string|null $extension
     * @return string
     */
    private static function buildFileName(string $name, ?string $extension): string
    {
        $filename = $name;
        if ($extension) {
            $filename .= '.'.$extension;
        }
        return $filename;
    }
}
