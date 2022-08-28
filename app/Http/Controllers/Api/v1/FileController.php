<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShowFilesRequest;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use App\Services\StorageService;
use Auth;
use Storage;
use Str;

class FileController extends Controller
{
    /**
     * Checks if requested file belongs to current user.
     *
     * @param File $file
     * @return void
     */
    private function isAllowed(File $file)
    {
        if (Auth::user()->id != $file->user_id) {
            abort(404);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(ShowFilesRequest $request)
    {
        $folderId = $request->validated('folder_id');
        $files = Auth::user()->files()->whereFolderId($folderId)->get();

        return FileResource::collection($files);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreFileRequest $request
     * @return FileResource
     */
    public function store(StoreFileRequest $request)
    {
        $file = $request->file('file');
        $folderId = $request->validated('folder_id');

        return new FileResource(StorageService::createFile($file, $folderId));
    }

    /**
     * Display the specified resource.
     *
     * @param File $file
     * @return FileResource
     */
    public function show(File $file)
    {
        $this->isAllowed($file);
        return new FileResource($file);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateFileRequest $request
     * @param File $file
     * @return FileResource
     */
    public function update(UpdateFileRequest $request, File $file)
    {
        $this->isAllowed($file);
        StorageService::renameFile($file, $request->validated('name'));
        return new FileResource($file);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param File $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        $this->isAllowed($file);
        StorageService::removeFile($file);
        return response()->noContent();
    }

    public function download(int $id)
    {
        $file = File::findOrFail($id);
        $this->isAllowed($file);
        $path = StorageService::FOLDER_NAME.'/'.StorageService::getFilesystemName($file);
        return Storage::download($path, $file->full_name);
    }
}
