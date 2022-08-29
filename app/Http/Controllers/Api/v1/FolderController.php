<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFolderRequest;
use App\Http\Resources\FolderResource;
use App\Models\Folder;
use App\Services\StorageService;
use Auth;

class FolderController extends Controller
{
    /**
     * Checks if requested folder belongs to current user.
     *
     * @param Folder $folder
     * @return void
     */
    private function isAllowed(Folder $folder)
    {
        if (Auth::user()->id != $folder->user_id) {
            abort(404);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return FolderResource::collection(Auth::user()->folders()->with('files')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFolderRequest  $request
     * @return FolderResource
     */
    public function store(StoreFolderRequest $request)
    {
        return new FolderResource(StorageService::createFolder($request->validated('name')));
    }

    /**
     * Display the specified resource.
     *
     * @param Folder $folder
     * @return FolderResource
     */
    public function show(Folder $folder)
    {
        $this->isAllowed($folder);
        return new FolderResource($folder);
    }
}
