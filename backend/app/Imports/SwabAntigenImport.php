<?php

namespace App\Imports;

use App\Enums\HasilPeriksaEnum;
use App\Enums\JenisAntigenEnum;
use App\Enums\JenisGejalaEnum;
use App\Enums\JenisIdentitasEnum;
use App\Enums\JenisKelaminEnum;
use App\Enums\KewarganegaraanEnum;
use App\Enums\KondisiPasienEnum;
use App\Enums\TujuanPemeriksaanEnum;
use App\Models\SwabAntigen;
use App\Traits\ImportExcelTrait;
use App\Traits\SwabAntigenTrait;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;
use Spatie\Enum\Laravel\Rules\EnumIndexRule;
use Spatie\Enum\Laravel\Rules\EnumRule;

class SwabAntigenImport implements WithHeadingRow, WithChunkReading, WithValidation, OnEachRow
{
    use Importable;
    use SwabAntigenTrait;
    use ImportExcelTrait;

    const RULES = [
        'nama_pasien' => 'required',
        'tanggal_lahir' => 'nullable|date|date_format:Y-m-d',
        'usia_tahun' => 'nullable|integer',
        'usia_bulan' => 'nullable|integer',
        'no_identitas' => 'required|digits:16',
        'kategori' => 'nullable',
        'alamat' => 'required',
        'kode_provinsi' => 'required|integer|exists:provinsi,id',
        'kode_kota_kabupaten' => 'required|integer|exists:kota,id',
        'kode_kecamatan' => 'required|integer|exists:kecamatan,id',
        'kode_kelurahan' => 'required|integer|exists:kelurahan,id',
        'rw' => 'nullable|max:3',
        'rt' => 'nullable|max:3',
        'tanggal_periksa' => 'nullable|date|date_format:Y-m-d',
        'tanggal_gejala' => 'nullable|date|date_format:Y-m-d',
        'no_telp' => 'required|max:16',
    ];

    public function onRow(Row $row)
    {
        $items = $this->getItemsValidated($row->toArray());
        SwabAntigen::create($items + [
            'nomor_registrasi' => $this->generateNomorRegister()
        ]);
    }

    public function rules(): array
    {
        return array_merge(self::RULES, [
            'jenis_kelamin' =>  ['nullable', new EnumRule(JenisKelaminEnum::class)],
            'warganegara' => ['required', new EnumRule(KewarganegaraanEnum::class)],
            'negara_asal' => 'required_if:warganegara,' . KewarganegaraanEnum::WNA(),
            'jenis_identitas' => ['required', new EnumRule(JenisIdentitasEnum::class)],
            'kondisi_pasien' => ['required', new EnumRule(KondisiPasienEnum::class)],
            'jenis_gejala' => ['nullable', new EnumRule(JenisGejalaEnum::class)],
            'tujuan_pemeriksaan' => ['nullable', new EnumIndexRule(TujuanPemeriksaanEnum::class)],
            'tujuan_pemeriksaan_lainnya' => 'required_if:tujuan_pemeriksaan,' . TujuanPemeriksaanEnum::lainnya()->getIndex(),
            'jenis_antigen' => ['nullable', new EnumRule(JenisAntigenEnum::class)],
            'no_hasil' => [
                'required',
                'unique:swab_antigen,no_hasil,NULL,id,deleted_at,NULL',
                'regex:/^' . SwabAntigen::NO_HASIL . '$/',
                'distinct'
            ],
            'hasil_periksa' =>  ['required', new EnumRule(HasilPeriksaEnum::class)],
        ]);
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
