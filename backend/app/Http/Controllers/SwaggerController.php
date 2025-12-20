<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SwaggerController extends Controller
{
    public function docs()
    {
        return view('swagger.index');
    }

    public function yaml()
    {
        $yaml = File::get(base_path('openapi.yaml'));
        return response($yaml, 200)
            ->header('Content-Type', 'text/yaml');
    }
}
