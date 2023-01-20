<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Models\AffiliateCode;
use Illuminate\Support\Facades\Gate;

class AffiliateCodeController extends Controller
{
    public function index () {
        return AffiliateCode::where('user_id', auth()->user()->id)
            ->paginate(10);
    }

    public function store (Request $request) {
        if (Gate::denies('create-campaign-setup-campaign-setup')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ], 422);
        }

        $code = new AffiliateCode();
        $code->user_id = $request->user_id;
        $code->name = $request->name;
        $code->affiliate_code = $request->code;

        $code->save();
    }

    public function getAllAffiliates () {
        return User::where('role_id', 11)
            ->where('status', 'active')
            ->with('affiliateCodes')
            ->withCount('affiliateBuyers')
            ->paginate(10);
    }

    public function getAllAffiliateCampaigns () {
        return AffiliateCode::with('user:id,username,name,email,created_at')
            ->withCount('buyers')
            ->paginate(10);
    }

    public function getAffiliateUserCampaigns ($id) {
        return AffiliateCode::where('user_id', $id)
            ->with('user:id,username,name,email,created_at')
            ->withCount('buyers')
            ->paginate(10);
    }

    public function getAffiliateUserBuyers ($id) {
        return Registration::where('affiliate_id', $id)
            ->where('status', 'active')
            ->with('user:id,email')
            ->with('affiliateCode:id,name,affiliate_code')
            ->paginate(10);
    }

    public function getAffiliateCampaignBuyers ($id) {
        return Registration::where('affiliate_code_id', $id)
        ->where('status', 'active')
        ->with('user:id,email')
        ->with('affiliateCode:id,name,affiliate_code')
        ->paginate(10);
    }
}
