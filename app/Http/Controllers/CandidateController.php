<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Company;
use App\Models\Wallet;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::all();
        $coins = Company::find(1)->coins;
        return view('candidates.index', compact('candidates', 'coins'));
    }

    public function contactWithCandidate(Request $request)
    {
        $validatedData = $request->validate([
            'candidate_id' => 'required|numeric',
            'company_id' => 'required|numeric',
        ]);

        $coins = Wallet::select('id', 'coins')
            ->where('company_id', $request->company_id)
            ->where('coins', '>=', 5)
            ->decrement('coins', 5);

        if ($coins) {
            return response()->json($wallet);
        } else {
            return response()->json(['error' => 'You don\'t have enough coins'], 422);
        }
    }

    public function hire()
    {
        // @todo
        // Your code goes here...
    }
}
