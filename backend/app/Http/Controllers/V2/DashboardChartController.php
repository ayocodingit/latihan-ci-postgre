<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\DashboardChart;
use Illuminate\Http\Request;

class DashboardChartController extends Controller
{
    public function registrasi(Request $request)
    {
        $tipe = $request->input('tipe', 'Daily');
        $jenisRegistrasi = $request->input('jenis', 'mandiri');
        return response()->json($this->getDashboardChart('registrasi', $tipe, $jenisRegistrasi));
    }

    public function sampel(Request $request)
    {
        $tipe = $request->input('tipe', 'Daily');
        return response()->json($this->getDashboardChart('sampel', $tipe));
    }

    public function ekstraksi(Request $request)
    {
        $tipe = $request->input('tipe', 'Daily');
        return response()->json($this->getDashboardChart('ekstraksi', $tipe));
    }

    public function pcr(Request $request)
    {
        $tipe = $request->input('tipe', 'Daily');
        return response()->json($this->getDashboardChart('pcr', $tipe));
    }

    public function positifNegatif(Request $request)
    {
        $tipe = $request->input('tipe', 'Daily');
        $hasilPemeriksaan = $request->input('jenis', 'positif');
        return response()->json($this->getDashboardChart('positif_negatif', $tipe, $hasilPemeriksaan));
    }

    public function getDashboardChart($nameChart, $type, $where = NULL)
    {
        $models = DashboardChart::where('nama', $nameChart)
                                ->where('tipe', $type)
                                ->where('where', $where)
                                ->first();
        return [
            'label' => json_decode($models->label),
            'value' => json_decode($models->data),
        ];
    }
}
