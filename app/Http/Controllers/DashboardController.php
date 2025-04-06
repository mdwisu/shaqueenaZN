<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        return view('dashboard.admin');
    }

    public function sellerDashboard()
    {
        return view('dashboard.seller');
    }

    public function customerDashboard()
    {
        return view('dashboard.customer');
    }
}
