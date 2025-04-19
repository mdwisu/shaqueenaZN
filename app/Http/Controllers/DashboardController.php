<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function ownerDashboard()
    {
        return view('dashboard.owner');
    }

    public function adminDashboard()
    {
        return view('dashboard.admin');
    }

    public function customerDashboard()
    {
        return view('dashboard.customer');
    }
}
