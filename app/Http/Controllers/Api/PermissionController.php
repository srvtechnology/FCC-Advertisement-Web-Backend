<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PermissionNew;

class PermissionController extends Controller
{
    public function index()
    {
        return PermissionNew::all();
    }
}
