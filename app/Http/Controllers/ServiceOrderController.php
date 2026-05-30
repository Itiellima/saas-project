<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceOrderController extends Controller
{
    public function index()
    {
        return view('service-order.index');
    }

    public function create()
    {
        return view('service-order.create');
    }
}
