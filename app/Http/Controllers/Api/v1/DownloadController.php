<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\DownloadLink;
use App\Services\StorageService;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function download(string $uuid)
    {
        $downloadLink = DownloadLink::firstWhere('uuid', $uuid);

        if (!$downloadLink) {
            abort(404);
        }

        return StorageService::sendFile($downloadLink->file);
    }
}
