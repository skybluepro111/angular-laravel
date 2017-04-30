<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Storage;
use File;
use Log;

class SetupController extends Controller
{
    public function runSetup() {
        $thumbnailDirectory = public_path() . '/thumbs';
        if(!File::exists($thumbnailDirectory))
        {
            File::makeDirectory($thumbnailDirectory, $mode = 0755, true, true);
            Log::info('Created directory: "' . $thumbnailDirectory);
            echo 'Created directory: "' . $thumbnailDirectory;
        }
    }
}
