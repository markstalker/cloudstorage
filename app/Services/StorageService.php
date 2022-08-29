<?php

namespace App\Services;

use App\Models\DownloadLink;
use App\Models\File;
use App\Models\Folder;
use Auth;
use Illuminate\Http\UploadedFile;
use Storage;
use Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StorageService
{
    /**
     * Folder name in 'storage/app'. Used for users files storing.
     * @var string
     */
    public const FOLDER_NAME = 'files';

    /**
     * Storage quota for each user (bytes). 100 MB
     * @var int
     */
    public const QUOTA = 100*1000*1000;

    /**
     * Create file in user storage.
     *
     * @param UploadedFile $uploadedFile
     * @param string|null $expiresAt
     * @param int|null $folderId
     * @return File
     */
    public static function createFile(\Illuminate\Http\UploadedFile $uploadedFile, ?string $expiresAt, ?int $folderId): File
    {
        $params = [
            'user_id' => Auth::user()->id,
            'name' => pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME),
            'extension' => $uploadedFile->getClientOriginalExtension(),
        ];

        if ($folderId) {
            $params['folder_id'] = $folderId;
        }
        if ($expiresAt) {
            $params['expires_at'] = $expiresAt;
        }

        $file = File::create($params);
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
        if ($file->downloadLink) {
            $file->downloadLink->delete();
        }

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
        if ($file->extension && $file->extension != '') {
            $filename .= '.'.$file->extension;
        }
        return $filename;
    }

    /**
     * Check if user has quota to new file upload.
     *
     * @param int $fileSize new file size (bytes).
     * @return bool
     */
    public static function hasQuota(int $fileSize): bool
    {
        return (self::getSize() + $fileSize) <= self::QUOTA;
    }

    /**
     * Get total size of user files (bytes).
     *
     * @return int
     */
    public static function getSize(): int
    {
        return Auth::user()->files->sum('size');
    }

    /**
     * Send file to user.
     *
     * @param File $file
     * @return StreamedResponse
     */
    public static function sendFile(File $file): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $path = self::FOLDER_NAME.'/'.self::getFilesystemName($file);
        return Storage::download($path, $file->full_name);
    }

    /**
     * Create public link for a user file.
     *
     * @param File $file
     * @return string
     */
    public static function publishFile(File $file): string
    {
        $uuid = '';

        if ($file->downloadLink) {
            return $file->downloadLink->url;
        }

        $isUnique = false;

        while (!$isUnique) {
            $uuid = Str::uuid();
            $isUnique = !(DownloadLink::firstWhere('uuid', $uuid));
        }

        $downloadLink = DownloadLink::create([
            'uuid' => $uuid,
            'file_id' => $file->id,
        ]);

        return $downloadLink->url;
    }
}
