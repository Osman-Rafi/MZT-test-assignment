<?php

namespace App\Http\Controllers;

use App\Mail\ContactCandidateMail;
use App\Mail\HiringConfirmation;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\ContactLog;
use App\Models\Wallet;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CandidateController extends Controller
{
    use ApiResponser;

    public function index()
    {
        $candidates = Candidate::all();
        $coins = Company::find(1)->coins;
        return view('candidates.index', compact('candidates', 'coins'));
    }

    public function contactWithCandidate(Request $request)
    {
        /* Validate request data */
        $attr = $request->validate([
            'candidate_id' => 'required|numeric',
            'candidate_name' => 'required|string',
            'company_id' => 'required|numeric',
            'candidate_email' => 'required|email',
        ]);

        $company_name = Company::find($attr['company_id'])->name;
        $candidate_name = $attr['candidate_name'];

        if ($this->coinTransaction(5, $attr['company_id'], 'decrement')) {
            /* Send email to candidate */
            Mail::to($attr['candidate_email'])->send(new ContactCandidateMail($candidate_name, $company_name));

            /* Make a log for this contact */
            if (!ContactLog::where('company_id', $attr['company_id'])->where('candidate_id', $attr['candidate_id'])->exists()) {
                ContactLog::create([
                    'company_id' => $attr['company_id'],
                    'candidate_id' => $attr['candidate_id'],
                ]);
            }
            return $this->successStatus(true, 'Mail sent successfully.', 200);
        } else {
            return $this->successStatus(false, 'You dont\'t have enough coins to contact a candidate', 422);
        }
    }

    private function coinTransaction($coins, $company_id, $trasaction_type)
    {
        if ($trasaction_type == 'increment') {
            Wallet::where('company_id', $company_id)->increment('coins', $coins);
            return true;
        } elseif ($trasaction_type == 'decrement') {
            $coins_remaining = Wallet::where('company_id', $company_id)->first()->coins;

            if ($coins_remaining >= $coins) {
                Wallet::where('company_id', $company_id)->decrement('coins', $coins);
                return true;
            }
            return false;
        }
        return false;
    }
    public function hireCandidate(Request $request)
    {
        /* Validate request data */
        $attr = $request->validate([
            'candidate_id' => 'required|numeric',
            'company_id' => 'required|numeric',
        ]);

        /* find candidate & company details */
        $candidate = Candidate::find($attr['candidate_id'])->first();
        $company = Company::find($attr['company_id'])->first();

        /* check if the candidate is available for hire */
        if ($candidate->hired_by !== null) {
            return $this->successStatus(false, 'Candidate has already been hired by another company', 422);
        } else {
            /* checking if the candidate has contacted before by the company than hire him/her */
            if (ContactLog::where('candidate_id', $candidate->id)->where('company_id', $company->id)->exists()) {
                /* make a log for hiring */
                Candidate::find($candidate->id)->update(['hired_by' => $company->id]);

                /* send an confirmation email to the candidate */
                Mail::to($candidate->email)->send(new HiringConfirmation($candidate->name, $company->name));

                /* put back 5 coins to company wallet */
                $this->coinTransaction(5, $company->id, 'increment');

                return $this->successStatus(true, 'Candidate hired successfully.', 200, ['candidate_id' => $candidate->id, 'company_id' => $company->id]);
            } else {
                return $this->successStatus(false, 'You must contact with this candidate before hire.', 422);
            }
        }
    }
}
