<?php

namespace App\Imports;

use App\Enums\JenisRegistrasiEnum;
use App\Models\Fasyankes;
use App\Models\Kota;
use App\Models\Sampel;
use App\Models\TesMasif;
use App\Rules\UniqueSampel;
use App\Traits\ImportExcelTrait;
use App\Traits\RegisterTrait;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RegisterRujukanTesMasifImport implements ToCollection, WithHeadingRow
{
    use RegisterTrait;
    use Importable;
    use ImportExcelTrait;

    public function collection(Collection $rows)
    {
        $this->checkValidLimit($rows);
        foreach ($rows as $key => $row) {
            $tesMasif = TesMasif::where('nomor_sampel', $row->get('nomor_sampel_fasyankes'))
                                ->where('available', true)
                                ->where('jenis_registrasi', JenisRegistrasiEnum::rujukan())
                                ->first();
            if (!$row->get('no') || !$tesMasif) {
                continue;
            }
            $row->nomor_sampel_labkes = trim($row->get('nomor_sampel_labkes'));
            $this->validated($row->toArray(), $key);
            $this->setData($this->mappingData($tesMasif, $row));
        }
    }

    private function mappingData($row, $rowDataExcel)
    {
        return [
            'no' => $rowDataExcel->get('no'),
            'sumber_pasien' => $row->kategori,
            'kunjungan_ke' => $row->kunjungan,
            'tanggal_kunjungan' => $row->tanggal_kunjungan,
            'rs_kunjungan' => $row->rs_kunjungan,
            'fasyankes_id' => $row->fasyankes_id,
            'fasyankes_nama' => optional(Fasyankes::find($row->fasyankes_id))->nama,
            'nama_dokter' => $row->dokter,
            'no_telp' => $row->telp_fasyankes,
            'nik' => $row->nik,
            'nama_lengkap' => $row->nama_pasien,
            'kewarganegaraan' => $row->kewarganeraan,
            'jenis_kelamin' => $row->jenis_kelamin,
            'tanggal_lahir' => $row->tanggal_lahir,
            'tempat_lahir' => $row->tempat_lahir,
            'provinsi_id' => $row->provinsi_id,
            'kota_id' => $row->kota_id,
            'kota_nama' => optional(Kota::find($row->kota_id))->nama,
            'kecamatan_id' => $row->kecamatan_id,
            'kelurahan_id' => $row->kelurahan_id,
            'no_hp' => $row->no_hp,
            'no_rw' => $row->rw,
            'no_rt' => $row->rt,
            'alamat_lengkap' => $row->alamat,
            'keterangan_lain' => $row->keterangan,
            'suhu' => $row->suhu,
            'usia_tahun' => $row->usia_tahun,
            'usia_bulan' => $row->usia_bulan,
            'status' => $row->kriteria,
            'nomor_sampel' => $rowDataExcel->get('nomor_sampel_labkes'),
            'nomor_sampel_fasyankes' => $row->nomor_sampel,
            'hasil_rdt' => $row->hasil_rdt,
            'jenis_registrasi' => JenisRegistrasiEnum::rujukan(),
            'registration_code' => $row->registration_code,
            'id' => $row->id
        ];
    }

    public function rules(): array
    {
        return [
            'nomor_sampel_fasyankes' => 'required|exists:tes_masif,nomor_sampel',
            'nomor_sampel_labkes' => [
                'required',
                'regex:/^' . Sampel::NUMBER_FORMAT_RUJUKAN . '$/',
                new UniqueSampel()
            ],
        ];
    }
}
