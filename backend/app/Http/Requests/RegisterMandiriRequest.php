<?php

namespace App\Http\Requests;

use App\Enums\JenisKelaminEnum;
use App\Enums\KewarganegaraanEnum;
use App\Enums\StatusPasienEnum;
use App\Enums\SumberPasienEnum;
use App\Models\Sampel;
use App\Rules\UniqueSampel;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Rules\EnumIndexRule;
use Spatie\Enum\Laravel\Rules\EnumValueRule;

class RegisterMandiriRequest extends FormRequest
{

    const RULES = [
        'reg_sumberpasien' => 'nullable',
        'reg_nama_pasien' => 'required',
        'reg_nik' => 'max:16',
        'reg_nohp' => 'nullable',
        'reg_provinsi_id' => 'required|numeric|exists:provinsi,id',
        'reg_kota_id' => 'required|numeric|exists:kota,id',
        'reg_kecamatan_id' => 'nullable|numeric|exists:kecamatan,id',
        'reg_kelurahan_id' => 'nullable|numeric|exists:kelurahan,id',
        'reg_alamat' => 'required',
        'reg_tgllahir' => 'nullable|date',
        'reg_usia_bulan' => 'nullable|numeric',
        'reg_usia_tahun' => 'nullable|numeric',
        'reg_tempatlahir' => 'nullable',
        'reg_tanggalkunjungan' => 'nullable|date',
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = self::RULES;
        $rules += [
            'reg_kewarganegaraan' => ['nullable', new EnumValueRule(KewarganegaraanEnum::class)],
            'reg_jk' => ['nullable', new EnumValueRule(JenisKelaminEnum::class)],
            'status' => ['nullable', new EnumIndexRule(StatusPasienEnum::class)],
            'reg_sampel' => [
                'required',
                'regex:/^' . Sampel::NUMBER_FORMAT_MANDIRI . '$/',
                new UniqueSampel(),
                'unique:tes_masif,nomor_sampel',
            ],
            'reg_sumberpasien_isian' => 'required_unless:reg_sumberpasien,' . SumberPasienEnum::Umum(),
        ];
        if ($this->register) {
            unset($rules['reg_sampel']);
        }
        return $rules;
    }
}
