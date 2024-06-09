<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Order;
use Illuminate\Http\Request;


class PDFController extends Controller
{
    public function downloadpdf(){
        $orders = Order::all();
        
        $data = [
            'date' => date('m/d/Y'),
            'order' => $orders
        ];

        $pdf = PDF::loadView('OrderPDF', $data);

        return $pdf->download('Laporan Order.pdf');
    }
}
