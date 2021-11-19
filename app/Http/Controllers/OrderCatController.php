<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrderCategory;
use App\QrCode;
use App\SystemConfig;
use App\Order;
use App\RecycleOrder;

class OrderCatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function qrCodes($id)
    {
        $orderCat = OrderCategory::where('id', $id)
                    ->first();
        $qrCodes = QrCode::whereNull('recycle_order_id')
                    ->where('order_category_id', $id)
                    ->get();

        $catLabel = SystemConfig::where('type', 'CAT')
                    ->where('key', $orderCat->{'category'})
                    ->first()
                    ->{'value'};
        $orderCat->{'catLabel'} = $catLabel;

        $orderNo = Order::where('id', $orderCat->{'order_id'})
                    ->first()
                    ->{'order_no'};
        $orderCat->{'orderNo'} = $orderNo;

        $allStatus = SystemConfig::getAllStatus();

        $recycleStatus = SystemConfig::getRecycleStatus();

        $orders = RecycleOrder::all();

        return view('qr_codes', ['orderCat' => $orderCat,
                                'qrCodes' => $qrCodes,
                                'allStatus' => $allStatus,
                                'recycleStatus' => $recycleStatus,
                                'orders' => $orders]);
    }

    public function printQrCodes($id)
    {
        $orderCat = OrderCategory::where('id', $id)
                    ->first();
        $qrCodes = QrCode::whereNull('recycle_order_id')
                    ->where('order_category_id', $id)
                    ->get();

        $catLabel = SystemConfig::where('type', 'CAT')
                    ->where('key', $orderCat->{'category'})
                    ->first()
                    ->{'value'};

        $orderNo = Order::where('id', $orderCat->{'order_id'})
                    ->first()
                    ->{'order_no'};

        return view('print_qr_codes', ['catLabel' => $catLabel,
                                'qrCodes' => $qrCodes,
                                'orderNo' => $orderNo]);
    }
}
