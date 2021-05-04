<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\DashboardChart;
use Illuminate\Http\Request;

class DashboardChartController extends Controller
{
    public function registrasi(Request $request)
    {
        $models = DashboardChart::where('nama', 'registrasi')
                                ->where('tipe', $request->input('tipe', 'Daily'))
                                ->where('where', $request->input('jenis', 'mandiri'))
                                ->first();
        return response()->json([
            'label' => json_decode($models->label),
            'value' => json_decode($models->data),
        ]);
    }

    public function sampel(Request $request)
    {
        $models = DashboardChart::where('nama', 'sampel')
                                ->where('tipe', $request->input('tipe', 'Daily'))
                                ->first();
        return response()->json([
            'label' => json_decode($models->label),
            'value' => json_decode($models->data),
        ]);
    }

    public function ekstraksi(Request $request)
    {
        $models = DashboardChart::where('nama', 'ekstraksi')
                                ->where('tipe', $request->input('tipe', 'Daily'))
                                ->first();
        return response()->json([
            'label' => json_decode($models->label),
            'value' => json_decode($models->data),
        ]);
    }

    public function pcr(Request $request)
    {
        $models = DashboardChart::where('nama', 'pcr')
                                ->where('tipe', $request->input('tipe', 'Daily'))
                                ->first();
        return response()->json([
            'label' => json_decode($models->label),
            'value' => json_decode($models->data),
        ]);
    }

    public function positifNegatif(Request $request)
    {
        $models = DashboardChart::where('nama', 'positif_negatif')
                                ->where('tipe', $request->input('tipe', 'Daily'))
                                ->where('where', $request->input('jenis', 'positif'))
                                ->first();
        return response()->json([
            'label' => json_decode($models->label),
            'value' => json_decode($models->data),
        ]);
    }
}
