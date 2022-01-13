<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class TestController extends Controller
{
    //
    public function index(){
        return Inertia::render('Test', [
            'test_value' => 123456
        ]);
    }
}
