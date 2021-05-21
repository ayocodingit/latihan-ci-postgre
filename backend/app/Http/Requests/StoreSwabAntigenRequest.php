<?php

namespace App\Http\Requests;

use App\Enums\HasilPeriksaEnum;
use App\Enums\JenisAntigenEnum;
use App\Enums\JenisGejalaEnum;
use App\Enums\JenisIdentitasEnum;
use App\Enums\JenisKelaminEnum;
use App\Enums\KewarganegaraanEnum;
use App\Enums\KondisiPasienEnum;
use App\Enums\TujuanPemeriksaanEnum;
use App\Models\SwabAntigen;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Rules\EnumIndexRule;
use Spatie\Enum\Laravel\Rules\EnumRule;

class StoreSwabAntigenRequest extends FormRequest
{
    const RULES = [
        'nama_pasien' => 'required',
        'tanggal_lahir' => 'nullable|date|date_format:Y-m-d',
        'usia_tahun' => 'nullable|integer',
        'usia_bulan' => 'nullable|integer',
        'no_telp' => 'required|max:16',
        'kategori' => 'nullable',
        'alamat' => 'required',
        'kode_provinsi' => 'required|integer|exists:provinsi,id',
        'kode_kota_kabupaten' => 'required|integer|exists:kota,id',
        'kode_kecamatan' => 'required|integer|exists:kecamatan,id',
        'kode_kelurahan' => 'required|integer|exists:kelurahan,id',
        'rw' => 'nullable|max:3',
        'rt' => 'nullable|max:3',
        'tanggal_gejala' => 'nullable|date|date_format:Y-m-d',
        'tanggal_periksa' => 'nullable|date|date_format:Y-m-d',
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
            'jenis_kelamin' => ['nullable', new EnumRule(JenisKelaminEnum::class)],
            'warganegara' => ['required', new EnumRule(KewarganegaraanEnum::class)],
            'negara_asal' => 'required_if:warganegara,' . KewarganegaraanEnum::WNA(),
            'jenis_identitas' => ['required', new EnumRule(JenisIdentitasEnum::class)],
            'no_identitas' => $this->getIdentityRules(),
            'kondisi_pasien' => ['required', new EnumRule(KondisiPasienEnum::class)],
            'jenis_gejala' => ['nullable', new EnumRule(JenisGejalaEnum::class)],
            'tujuan_pemeriksaan' => ['nullable', new EnumIndexRule(TujuanPemeriksaanEnum::class)],
            'tujuan_pemeriksaan_lainnya' => $this->getPurposeRule(),
            'jenis_antigen' => ['nullable', new EnumRule(JenisAntigenEnum::class)],
            'no_hasil' => $this->getNoResultRules(),
            'hasil_periksa' => ['required', new EnumRule(HasilPeriksaEnum::class)],
        ];
        if ($this->swab_antigen) {
            unset($rules['no_hasil']);
        }
        return $rules;
    }

    public function getIdentityRules()
    {
        $roleNoIdentitas = '|min:6|max:16';
        if ($this->input('jenis_identitas') == JenisIdentitasEnum::ktp()) {
            $roleNoIdentitas = '|digits:16';
        }
        return 'required' . $roleNoIdentitas;
    }

    public function getNoResultRules()
    {
        return [
            'required',
            'unique:swab_antigen,no_hasil,NULL,id,deleted_at,NULL',
            'regex:/^' . SwabAntigen::NO_HASIL . '$/'
        ];
    }

    public function getPurposeRule()
    {
        return 'required_if:tujuan_pemeriksaan,' . TujuanPemeriksaanEnum::lainnya()->getIndex();
    }

}
