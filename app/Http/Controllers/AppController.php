<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\SystemConfig;
use App\QrCode;
use Carbon\Carbon;
use App\RecycleOrder;

class AppController extends Controller
{
    public function login(Request $request)
    {
        $login = $request->input("login");
        $password = $request->input("password");
        
        if ($login && $password) {
            $user = User::where("uid", $login)->first();
            if ($user) {
                if (password_verify($password, $user->{'password'})) {
                    $list = SystemConfig::getUsableStatus();
                    return response()->json(['status' => 'OK', 'list' => $list]);
                }
            }
        }

        return response()->json(['status' => 'FAILED']);
    }

    public function updateStatus(Request $request)
    {
        $qrCode = $request->input("qr_code");
        $status = $request->input("status");
        $updateBy = $request->input("update_by");

        if ($qrCode && $status && $updateBy) {
            $code = QrCode::where("value", $qrCode)->first();
            if ($code) {
                if (SystemConfig::isValidStatus($status)) {
                    $code->{'status'} = $status;
                    $code->{'update_by'} = $updateBy;
                    $code->{'update_time'} = Carbon::now();    
                    $code->save();
                    return response()->json(['status' => 'OK']);
                }
            }
            $order = RecycleOrder::where("qr_code", $qrCode)->first();
            if ($order) {
                if (SystemConfig::isValidStatus($status)) {
                    $codes = QrCode::where("recycle_order_id", $order->{'id'})
                                ->get();
                    if ($codes && count($codes) > 0) {
                        foreach ($codes as $code) {
                            $code->{'status'} = $status;
                            $code->{'update_by'} = $updateBy;
                            $code->{'update_time'} = Carbon::now();    
                            $code->save();
                        }
                        return response()->json(['status' => 'OK']);
                    }
                }
            }
        }

        return response()->json(['status' => 'FAILED']);
    }
}
