<?php

namespace App\Services;

use App\Models\File;
use App\Models\Folder;
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
     * @param int|null $folderId
     * @return File
     */
    public static function createFile(\Illuminate\Http\UploadedFile $uploadedFile, ?int $folderId): File
    {
        $params = [
            'uuid' => Str::uuid(),
            'user_id' => Auth::user()->id,
            'name' => pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME),
            'extension' => $uploadedFile->getClientOriginalExtension(),
        ];

        if ($folderId) {
            $params['folder_id'] = $folderId;
        }

        $file = File::create($params);

        $path = $file->id;
        if ($file->extension) {
            $path .= '.'.$file->extension;
        }

        $uploadedFile->storeAs(self::FOLDER_NAME, self::getFilesystemName($file));
        return $file;
    }

    /**
     * Create folder in user storage.
     *
     * @param string $name
     * @return Folder
     */
    public static function createFolder(string $name): Folder
    {
        $folder = Folder::create([
            'name' => $name,
            'user_id' => Auth::user()->id,
        ]);

        return $folder;
    }

    /**
     * Remove file from user storage.
     *
     * @param File $file
     * @return void
     */
    public static function removeFile(File $file): void
    {
        Storage::delete(self::FOLDER_NAME.'/'.self::getFilesystemName($file));
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
