<?php

namespace App\Traits;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Provinsi;
use App\Models\SwabAntigen;
use Carbon\Carbon;

trait SwabAntigenTrait
{
    use ConvertEnumTrait;

    private $bulan;
    private $tahun;

    public function generateNomor(): string
    {
        $this->bulan = Carbon::now()->format('m');
        $this->tahun = Carbon::now()->format('Y');
        $nomorRegistrasi = SwabAntigen::whereMonth('created_at', $this->bulan)
                                        ->whereYear('created_at', $this->tahun)
                                        ->withTrashed()
                                        ->count('nama_pasien');
        return str_pad(++$nomorRegistrasi, 4, "0", STR_PAD_LEFT);
    }

    public function generateNomorRegister()
    {
        $generateNomor = $this->generateNomor();
        $tahunBulan = $this->bulan . "/" . $this->tahun;
        return "{$generateNomor}/RAR/Lab.COVID/{$tahunBulan}";
    }

    protected function swabAntigenLogs($origin, $changes)
    {
        $swabAntigenLogs = array();
        foreach ($changes as $key => $value) {
            if ($key == "updated_at") {
                continue;
            }
            if ($key == "tujuan_pemeriksaan") {
                $swabAntigenLogs[$key]["from"] = $origin[$key];
                $swabAntigenLogs[$key]["to"] = optional($this->convertTujuanPemeriksaanEnum($value))->getValue();
            } elseif (in_array($key, ['kode_provinsi', 'kode_kota_kabupaten', 'kode_kecamatan', 'kode_kelurahan'])) {
                $swabAntigenLogs = $this->translateWilayah($swabAntigenLogs, $origin, $key, $value);
            } else {
                $swabAntigenLogs[$key]["from"] = $origin[$key];
                $swabAntigenLogs[$key]["to"] = $value;
            }
        }
        return $swabAntigenLogs;
    }

    protected function translateWilayah($swabAntigenLogs, $origin, $key, $value)
    {
        if ($key == "kode_provinsi") {
            $swabAntigenLogs['provinsi']["from"] = optional(Provinsi::find($origin[$key]))->nama;
            $swabAntigenLogs['provinsi']["to"] = optional(Provinsi::find($value))->nama;
        } elseif ($key == "kode_kota_kabupaten") {
            $swabAntigenLogs['kota']["from"] = optional(Kota::find($origin[$key]))->nama;
            $swabAntigenLogs['kota']["to"] = optional(Kota::find($value))->nama;
        } elseif ($key == "kode_kecamatan") {
            $swabAntigenLogs['kecamatan']["from"] = optional(Kecamatan::find($origin[$key]))->nama;
            $swabAntigenLogs['kecamatan']["to"] = optional(Kecamatan::find($value))->nama;
        } else {
            $swabAntigenLogs['kelurahan']["from"] = optional(Kelurahan::find($origin[$key]))->nama;
            $swabAntigenLogs['kelurahan']["to"] = optional(Kelurahan::find($value))->nama;
        }

        return $swabAntigenLogs;
    }
}
