<?php

namespace App\Http\Controllers;

use App\Mail\ContactCandidateMail;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\ContactLog;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        $attr = $request->validate([
            'candidate_id' => 'required|numeric',
            'candidate_name' => 'required|string',
            'company_id' => 'required|numeric',
            'candidate_email' => 'required|email',
        ]);

        $company_name = Company::find($attr['company_id'])->name;
        $candidate_name = $attr['candidate_name'];

        $coins_remaining = Wallet::select('coins')->where('company_id', $attr['company_id'])->first()->coins;

        if ($coins_remaining >= 5) {
            /* Send email to the selected candidate */
            Mail::to($attr['candidate_email'])->send(new ContactCandidateMail($candidate_name, $company_name));

            /* Cost 5 coins for each contact */
            Wallet::select('id', 'coins')
                ->where('company_id', $attr['company_id'])
                ->where('coins', '>=', 5)
                ->decrement('coins', 5);

            /* Make a log for this contact */
            $contactLog = new ContactLog;
            $contactLog->candidate_id = $attr['candidate_id'];
            $contactLog->company_id = $attr['company_id'];
            $contactLog->save();

            return response()->json([
                'success' => true,
                'message' => 'Contacted successfully',
            ], 200);
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
