<?php

namespace App\Http\Controllers;

use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DesignController extends Controller
{
    public function download($id)
    {
        $design = Design::findOrFail($id);

        if (Auth::id() !== $design->user_id && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized to download this file.');
        }

        $filePath = $design->file;
        $storagePath = $filePath;
        if (!Storage::exists($storagePath)) {
            $storagePath = 'public/' . $filePath;
        }

        if (!Storage::exists($storagePath)) {
            abort(404, 'File not found.');
        }

        return Storage::download($storagePath, 'codama-' . basename($filePath));
    }
}
