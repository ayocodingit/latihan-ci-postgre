<?php

namespace App\Http\Controllers\V2;

use App\Enums\StatusPasienEnum;
use App\Http\Controllers\Controller;
use App\Models\DashboardCounter;

class DashboardController extends Controller
{
    public function trackingProgress()
    {
        $data['registration'] = $this->getTotal('tracking_progress_registration');
        $data['sampel'] = $this->getTotal('tracking_progress_sampel');
        $data['ekstraksi'] = $this->getTotal('tracking_progress_ekstraksi');
        $data['rtpcr'] = $this->getTotal('tracking_progress_rtpcr');
        $data['verifikasi'] = $this->getTotal('tracking_progress_verifikasi');
        $data['validasi'] = $this->getTotal('tracking_progress_validasi');

        return response()->json([
            'result' => $data,
            'status' => 200,
        ]);
    }

    public function positifNegatif()
    {
        $data['positif'] = $this->getTotal('pasien_positif');
        $data['negatif'] = $this->getTotal('pasien_negatif');

        return response()->json([
            'result' => $data,
            'status' => 200,
        ]);
    }

    public function pasienDiperiksa()
    {
        $data = [];
        foreach (StatusPasienEnum::getAll() as $item) {
            $key = str_replace(' ', '_', strtolower($item->getValue()));
            $data[$key] = $this->getTotal('pasien_diperiksa_' . $item->getIndex());
        }

        return response()->json([
            'result' => $data,
            'status' => 200,
        ]);
    }

    public function registrasi()
    {
        $data['mandiri'] = $this->getTotal('registrasi_mandiri');
        $data['rujukan'] = $this->getTotal('registrasi_rujukan');
        $data['total'] = $this->getTotal('registrasi_total');
        $data['jumlah_perhari_mandiri'] = $this->getTotal('registrasi_jumlah_perhari_mandiri');
        $data['jumlah_perhari_rujukan'] = $this->getTotal('registrasi_jumlah_perhari_rujukan');
        $data['data_belum_lengkap_mandiri'] = $this->getTotal('registrasi_data_belum_lengkap_mandiri');
        $data['data_belum_lengkap_rujukan'] = $this->getTotal('registrasi_data_belum_lengkap_rujukan');
        $data['pemeriksaan_selesai_mandiri'] = $this->getTotal('registrasi_pemeriksaan_selesai_mandiri');
        $data['pemeriksaan_selesai_rujukan'] = $this->getTotal('registrasi_pemeriksaan_selesai_rujukan');
        $data['belum_input_rujukan'] = $this->getTotal('registrasi_belum_input_rujukan');
        return response()->json([
            'result' => $data,
            'status' => 200,
        ]);
    }

    public function adminSampel()
    {
        $data['jumlah_perhari_sampel'] = $this->getTotal('admin_sampel_jumlah_perhari_sampel');
        $data['sampel_register_mandiri'] = $this->getTotal('admin_sampel_sampel_register_mandiri');
        $data['total_sampel'] = $this->getTotal('admin_sampel_total_sampel');
        return response()->json([
            'result' => $data,
            'status' => 200,
        ]);
    }

    public function ekstraksi()
    {
        $data['jumlah_perhari_ektraksi'] = $this->getTotal('ekstraksi_jumlah_perhari_ektraksi');
        $data['sampel_baru'] = $this->getTotal('ekstraksi_sampel_baru');
        $data['ekstraksi'] = $this->getTotal('ekstraksi_ekstraksi');
        $data['kirim'] = $this->getTotal('ekstraksi_kirim');
        $data['sampel_invalid'] = $this->getTotal('ekstraksi_sampel_invalid');
        return response()->json([
            'result' => $data,
            'status' => 200,
        ]);
    }

    public function pcr()
    {
        $data['sampel_baru'] = $this->getTotal('pcr_sampel_baru');
        $data['jumlah_perhari_pcr'] = $this->getTotal('pcr_jumlah_perhari_pcr');
        $data['hasil_pemeriksaan'] = $this->getTotal('pcr_hasil_pemeriksaan');
        $data['re_pcr'] = $this->getTotal('pcr_re_pcr');
        return response()->json([
            'result' => $data,
            'status' => 200,
        ]);
    }

    public function verifikasi()
    {
        $data['belum_diverifikasi'] = $this->getTotal('verifikasi_belum_diverifikasi');
        $data['terverifikasi'] = $this->getTotal('verifikasi_terverifikasi');
        return response()->json([
            'result' => $data,
            'status' => 200,
        ]);
    }

    public function validasi()
    {
        $data['belum_divalidasi'] = $this->getTotal('validasi_belum_divalidasi');
        $data['tervalidasi'] = $this->getTotal('validasi_tervalidasi');
        return response()->json([
            'result' => $data,
            'status' => 200,
        ]);
    }

    public function getTotal($name)
    {
        return DashboardCounter::where('nama', $name)->value('total');
    }
}
