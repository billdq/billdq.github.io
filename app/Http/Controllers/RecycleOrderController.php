<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\RecycleOrder;
use App\QrCode;
use App\SystemConfig;
use App\OrderCategory;

class RecycleOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = RecycleOrder::all();
        return view('recycle_orders', ['orders' => $orders]);
    }

    public function store(Request $request)
    {
        $id = $request->input('id');
        $title = $request->input('title');
        $qrCodes = $request->input('qr_codes');

        if ($id == '-1') {
            $order = new RecycleOrder();
            $order->{'title'} = $title;
            $order->{'weight'} = 0;
            $order->{'amount'} = 0;
            $order->{'date'} = Carbon::now();
            $order->{'qr_code'} = uniqid();
            $order->save();
            $id = $order->{'id'};
        } else {
            $order = RecycleOrder::where('id', $id)->first();
        }

        $order->{'amount'} = $order->{'amount'} + count($qrCodes);
        $order->save();

        $updateQrCodes = QrCode::whereIn('value', $qrCodes)->get();
        foreach ($updateQrCodes as $code) {
            $orderCatId = $code->{'order_category_id'};
            $code->{'order_category_id'} = $code->{'order_category_id'} + 10000000;
            $code->{'recycle_order_id'} = $id;
            $code->save();
        }

        $orderCat = OrderCategory::where('id', $orderCatId)->first();
        if ($orderCat) {
            $orderCat->{'no_of_qr_code'} = $orderCat->qr_codes()->count();
            $orderCat->save();
        }

        return response()->json(['order_id' => $id]);
    }

    public function show($id)
    {
        $order = RecycleOrder::where('id', $id)->first();
        return view('recycle_order', ['order' => $order]);
    }

    public function update(Request $request, $id)
    {
        $order = RecycleOrder::where('id', $id)->first();

        if ($order) {
            $order->fill($request->all());
            $order->save();
        }

        return redirect()->route('recycle_orders', ['id' => $id]);
    }

    public function destroy($id)
    {
        $order = RecycleOrder::where('id', $id)->first();

        if ($order) {
            QrCode::where('recycle_order_id', $id)->delete();
            $order->delete();
        }

        return "success";
    }

    public function qrCodes($id)
    {
        $order = RecycleOrder::where('id', $id)->first();
        $qrCodes = QrCode::where('recycle_order_id', $id)
                    ->get();
        $allStatus = SystemConfig::getAllStatus();

        return view('recycle_qr_codes', ['order' => $order,
                                'qrCodes' => $qrCodes,
                                'allStatus' => $allStatus]);
    }

    public function printQrCode($id)
    {
        $order = RecycleOrder::where('id', $id)->first();
        return view('print_qr_code', ['order' => $order]);
    }
}
