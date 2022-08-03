<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\SystemConfig;
use App\Order;
use App\OrderCategory;
use App\QrCode;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function createOrder()
    {
        $cats = SystemConfig::where('type', 'CAT')->get();
        return view('order', ['cats' => $cats]);
    }

    public function index()
    {
        $orders = Order::all();
        return view('orders', ['orders' => $orders]);
    }

    public function store(Request $request)
    {
        $order = Order::where('order_no', $request->input('order_no'))->first();

        if ($order) {
            return redirect()->back()->with('message', 'Order No 已經存在, 請另創新 Order No!');
        }

        $order = Order::create($request->all());

        for ($i = 1; $i <= intval($request->input("no_of_cat")); $i++) {
            $orderCat = new OrderCategory();
            $orderCat->{"category"} = $request->input("category".$i);
            $orderCat->{"no_of_qr_code"} = $request->input("no_of_qr_code".$i);
            $order->order_categories()->save($orderCat);

            for ($j = 0; $j < $orderCat->{"no_of_qr_code"}; $j++) {
                $qrCode = new QrCode();
                $qrCode->{"value"} = uniqid();
                $qrCode->{"status"} = SystemConfig::getInitStatus()->{'key'};
                $qrCode->{"update_by"} = "SYSTEM";
                $qrCode->{"update_time"} = Carbon::now();
                $orderCat->qr_codes()->save($qrCode);
            }
        }

        return redirect()->route('orders', ['id' => $order->{"id"}]);
    }

    public function show($id)
    {
        $cats = SystemConfig::where('type', 'CAT')->get();
        $status = SystemConfig::getAllStatus();
        $order = Order::where('id', $id)->first();
        $orderCats = $order->order_categories()->get();
        return view('order', ['cats' => $cats, 'status' => $status,
            'order' => $order, 'orderCats' => $orderCats]);
    }

    public function update(Request $request, $id)
    {
        $order = Order::where('id', $id)->first();

        if ($order) {
            $order->fill($request->all());
            $order->save();    

            for ($i = 1; $i <= intval($request->input("no_of_cat")); $i++) {
                $cat = $request->input("category".$i);
                $noOfQr = $request->input("no_of_qr_code".$i);

                $orderCat = OrderCategory::where('order_id', $id)
                            ->where('category', $cat)->first();

                if ($orderCat) {
                    if ($noOfQr > $orderCat->{"no_of_qr_code"}) {
                        $diff = $noOfQr - $orderCat->{"no_of_qr_code"};

                        $orderCat->{"no_of_qr_code"} = $noOfQr;
                        $orderCat->save();

                        for ($j = 0; $j < $diff; $j++) {
                            $qrCode = new QrCode();
                            $qrCode->{"value"} = uniqid();
                            $qrCode->{"status"} = SystemConfig::getInitStatus()->{'key'};
                            $qrCode->{"update_by"} = "SYSTEM";
                            $qrCode->{"update_time"} = Carbon::now();
                            $orderCat->qr_codes()->save($qrCode);
                        }
                    }
                } else {
                    $orderCat = new OrderCategory();
                    $orderCat->{"category"} = $cat;
                    $orderCat->{"no_of_qr_code"} = $noOfQr;
                    $order->order_categories()->save($orderCat);

                    for ($j = 0; $j < $noOfQr; $j++) {
                        $qrCode = new QrCode();
                        $qrCode->{"value"} = uniqid();
                        $qrCode->{"status"} = SystemConfig::getInitStatus()->{'key'};
                        $qrCode->{"update_by"} = "SYSTEM";
                        $qrCode->{"update_time"} = Carbon::now();
                        $orderCat->qr_codes()->save($qrCode);
                    }
                }
            }
        }

        return redirect()->route('orders', ['id' => $id]);
    }

    public function destroy($id) {
        $order = Order::where('id', $id)->first();
        if ($order) {
            $orderCats = $order->order_categories;
            foreach ($orderCats as $cat) {
                $cat->qr_codes()->delete();
            }
            $order->order_categories()->delete();
            $order->delete();
        }
        return "success";
    }

    public function completed()
    {
        $all = Order::all();
        $orders = array();
        foreach ($all as $order) {
            if ($this->isCompleted($order)) {
                array_push($orders, $order);
            }
        }
        return view('completed_orders', ['orders' => $orders]);
    }

    private function isCompleted($order) {
        $s = SystemConfig::getCompletedStatus();
        $orderCats = $order->order_categories;
        foreach ($orderCats as $cat) {
            $count = $cat->qr_codes()->where('status', '!=', $s)->count();
            if ($count > 0) {
                return false;
            }
        }
        return true;
    }
}
