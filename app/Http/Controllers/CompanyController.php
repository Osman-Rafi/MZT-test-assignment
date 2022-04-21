<?php

namespace App\Http\Controllers;

use App\Models\Company;

class CompanyController extends Controller
{
    public function getAllCompanies()
    {
        $companies = Company::select('id', 'name')->get();
        return $companies;
    }
}
