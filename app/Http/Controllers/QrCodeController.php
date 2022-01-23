<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QrCode;

class QrCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function batchDelete(Request $request) {
        $qrCodes = $request->input('qr_codes');
        if ($qrCodes) {
            $orderCat = $this->findOrderCat($qrCodes);
            $rows = QrCode::whereIn('value', $qrCodes)->delete();
            $this->updateOrderCat($orderCat, $rows);
        }
        return "success";
    }

    private function updateOrderCat($orderCat, $count) {
        $noOfQr = $orderCat->{"no_of_qr_code"} - $count;
        $orderCat->{"no_of_qr_code"} = $noOfQr;
        $orderCat->save();
    }

    private function findOrderCat($qrCodes) {
        $qr = QrCode::whereIn('value', $qrCodes)->first();
        return $qr->order_category;
    }
}
