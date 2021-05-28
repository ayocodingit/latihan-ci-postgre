<?php

namespace App\Imports;

use App\Enums\JenisKelaminEnum;
use App\Enums\JenisRegistrasiEnum;
use App\Enums\KewarganegaraanEnum;
use App\Enums\StatusPasienEnum;
use App\Models\Pasien;
use App\Models\Register;
use App\Models\Sampel;
use App\Rules\UniqueSampel;
use App\Traits\ImportExcelTrait;
use App\Traits\RegisterTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Enum\Laravel\Rules\EnumRule;
use Spatie\Enum\Laravel\Rules\EnumValueRule;

class RegisterMandiriImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    use RegisterTrait;
    use Importable;
    use ImportExcelTrait;

    const RULES = [
        'tanggal_kunjungan' => 'nullable|date|date_format:Y-m-d',
        'nik' => 'nullable|digits:16',
        'nama_pasien' => 'required|min:3',
        'kategori' => 'nullable',
        'alamat' => 'required|max:255',
        'tanggal_lahir' => 'nullable|date|date_format:Y-m-d',
        'tempat_lahir' => 'nullable',
        'provinsi_id' => 'nullable|numeric|exists:provinsi,id',
        'kota_id' => 'required|numeric|exists:kota,id',
        'kecamatan_id' => 'nullable|numeric|exists:kecamatan,id',
        'kelurahan_id' => 'nullable|numeric|exists:kelurahan,id',
        'kategori' => 'nullable',
        'kewarganegaraan' => 'nullable',
        'suhu' => ['nullable', 'regex:/^[0-9]+(\.[0-9]->-?)?$/'],
        'hasil_rdt' => 'nullable',
        'kunjungan' => 'nullable|integer|min:1|max:10',
        'rs_kunjungan' => 'nullable',
        'rt' => 'nullable',
        'rw' => 'nullable',
        'no_hp' => 'nullable',
        'keterangan' => 'nullable',
        'usia_tahun' => 'nullable|integer',
        'usia_bulan' => 'nullable|integer',
    ];

    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $key => $row) {
                if (!$row->get('no')) {
                    break;
                }
                $this->result['number_row'][] = $key + 1;
                $row['kriteria'] = strtolower($row->get('kriteria'));
                $row['nomor_sampel'] = trim(strtoupper($row->get('nomor_sampel')));
                $this->validated($row->toArray(), $key);
                $this->saveData($this->getItemsValidated($row->toArray(), false));
            }
            if ($this->result['errors_count'] === 0) {
                $this->setMessage('Sukses import data.');
                DB::commit();
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function saveData($row)
    {
        $row['kunjungan_ke'] = $row->get('kunjungan');
        $row['register_uuid'] = Str::uuid();
        $row['creator_user_id'] = auth()->user()->id;
        $row['sumber_pasien'] = $row->get('kategori');
        $row['nomor_register'] = $this->generateNomorRegister();
        $row['jenis_registrasi'] = JenisRegistrasiEnum::mandiri();
        $register = Register::create($row->toArray());
        $row['nama_lengkap'] = $row->get('nama_pasien');
        $row['alamat_lengkap'] = $row->get('alamat');
        $row['keterangan_lain'] = $row->get('keterangan');
        $row['status'] = $row->get('kriteria');
        $row['no_rt'] = $row->get('rt');
        $row['no_rw'] = $row->get('rw');
        $pasien = Pasien::create($row->toArray());
        $register->pasiens()->attach($pasien);
        $row['sampel_status'] = 'waiting_sample';
        $row['waktu_waiting_sample'] = now();
        $row['register_id'] = $register->id;
        Sampel::create($row->toArray());
    }

    public function rules(): array
    {
        return array_merge(self::RULES, [
            'kriteria' => new EnumValueRule(StatusPasienEnum::class),
            'nomor_sampel' => [
                'required',
                'regex:/^' . Sampel::NUMBER_FORMAT_MANDIRI . '$/',
                new UniqueSampel(),
                'unique:tes_masif,nomor_sampel',
            ],
            'kewarganeraan' => ['nullable', new EnumRule(KewarganegaraanEnum::class)],
            'jenis_kelamin' => ['nullable', new EnumRule(JenisKelaminEnum::class)],
        ]);
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
