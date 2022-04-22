<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Company;

class CompanyController extends Controller
{
    public function getAllCompanies()
    {
        $companies = Company::select('id', 'name')->get();
        return response()->json($companies);
    }

    public function getAllCandidates(Company $company)
    {
        $coins = $company->wallet->coins;
        $candidates = Candidate::all();

        return response()->json([
            'coins' => $coins,
            'candidates' => $candidates,
        ]);
    }
}
