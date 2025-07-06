<?php

namespace App\Http\Controllers;

use App\Library\Signatures;
use App\Library\Helper as LibHelper;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use App\Models\Files as ModFiles;

class Signature extends Controller
{
    
    public function viewImage($filename) {
        
        $resultCheck = Signatures\Helper::checkFile($filename);
        if (! $resultCheck) abort(404, 'File not found');
        
        if (! Auth::check()) abort(403, '');
        
        if ($resultCheck->id_user !== Auth::user()->id_user) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }
        
        $storage = Storage::disk('signatures');
        $pathname = $resultCheck->file_path;
        
        $mime = 'image/' . $resultCheck->file_type;
        
        
        return response()->stream(function () use ($storage, $pathname) {
            $stream = $storage->readStream($pathname);
            fpassthru($stream);
            if (is_resource($stream)) fclose($stream);
        }, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="'.basename($filename).'"',
            // 'Cache-Control' => 'public, max-age=86400',
        ]);
    }
    
}
