<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SystemConfig;
use App\Order;
use App\OrderCategory;
use App\QrCode;
use App\RecycleOrder;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public $catLabels = array();
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('wati');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $cats = SystemConfig::where('type', 'CAT')->get();
        return view('home', ['cats' => $cats]);
    }

    public function wati(Request $request) {
        Log::info('request', ['request' => $request->all()]);
        if (isset($request->id)) {
            $this->proc($request->all());
        }
        return "success";
    }
    private function proc($data) {
        error_log($data['waId']);
    }

    public function allQrCodes() {
        $qrCodes = array();
        $orders = Order::all();
        foreach ($orders as $order) {
            $cats = $order->order_categories;
            foreach ($cats as $cat) {
                $qrs = $cat->qr_codes;
                foreach ($qrs as $qr) {
                    $qr['catLabel'] = $this->catLabel($cat->category);
                    $qr['orderNo'] = $order->order_no;
                    array_push($qrCodes, $qr);
                }
            }
        }
        $rOrders = RecycleOrder::all();
        foreach ($rOrders as $rcy) {
            $qrs = QrCode::where('recycle_order_id', $rcy->id)->get();
            $cat = $this->getRcyCat($qrs[0]);
            $catLabel = $this->catLabel($cat);
            foreach ($qrs as $qr) {
                $qr['catLabel'] = $catLabel;
                $qr['orderNo'] = $rcy->title;
                array_push($qrCodes, $qr);
            }
        }
        $allStatus = SystemConfig::getAllStatus();
        return view('all_qr_codes', ['qrCodes' => $qrCodes, 'allStatus' => $allStatus]);
    }

    private function getRcyCat($qr) {
        return $qr->category;
    }

    private function catLabel($category) {
        if (sizeof($this->catLabels) == 0) {
            $this->fetchCatLabels();
        }
        return $this->catLabels[$category];
    }

    private function fetchCatLabels() {
        $configs = SystemConfig::where('type', 'CAT')->get();
        foreach ($configs as $config) {
            $this->catLabels[$config->key] = $config->value;
        }
    }
}
