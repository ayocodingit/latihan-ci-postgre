<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SampelDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $sampel = parent::toArray($request);
        $register = optional($this)->register;
        $pasien = optional($register)->pasiens();
        $pasien = optional($pasien)->with(['kota', 'kecamatan', 'kelurahan', 'provinsi']);
        $pasien = optional($pasien)->first();
        $fasyankes = optional($this->register)->fasyankes;
        $lastPemeriksaanSampel = $this->pemeriksaanSampel()
                                    ->orderBy('tanggal_input_hasil', 'desc')
                                    ->first();
        return [
            'data' => $sampel + [
                'pemeriksaan_sampel' => $this->pemeriksaanSampel,
                'status' => $this->status,
                'register' => $this->register,
                'validator' => $this->validator,
                'ekstraksi' => $this->ekstraksi,
                'pasien' => $pasien,
                'fasyankes' => $fasyankes,
                'pengambilanSampel' => $this->pengambilanSampel,
                'last_pemeriksaan_sampel' => $lastPemeriksaanSampel,
            ],
        ];
    }
}
