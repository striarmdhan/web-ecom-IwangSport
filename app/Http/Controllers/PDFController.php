<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;


class PDFController extends Controller
{
    public function downloadpdf()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $orders = Order::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->get();

        $data = [
            'date' => date('m/d/Y'),
            'order' => $orders
        ];

        $pdf = PDF::loadView('OrderPDF', $data);

        return $pdf->download('Laporan Order.pdf');
    }
}
