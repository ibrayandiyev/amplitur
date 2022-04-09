<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promocode;
use PDF;

class GeneratePdfController extends Controller
{
    public function gerarpromocode (Request $request, Promocode $promocode)
    {
        $attributes = $request->all();
        $pdf = PDF::loadview('backend.pdf.promocode', ['promocode' => $promocode]);

        return $pdf->setPaper('a5', 'landscape')->stream('teste.pdf');

    }
}
