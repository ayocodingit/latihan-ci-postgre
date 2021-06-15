<?php

namespace App\Http\Requests;

use App\Enums\LabPCREnum;
use App\Enums\MetodeEkstraksiEnum;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Rules\EnumRule;

class EditEkstraksiRequest extends FormRequest
{
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
        $rules = [
            'tanggal_penerimaan_sampel' => 'required|date',
            'jam_penerimaan_sampel' => 'required|date_format:H:i',
            'petugas_penerima_sampel' => 'required',
            'catatan_penerimaan' => 'nullable',
            'operator_ekstraksi' => 'required',
            'tanggal_mulai_ekstraksi' => 'required|date',
            'jam_mulai_ekstraksi' => 'required|date_format:H:i',
            'metode_ekstraksi' => ['required', new EnumRule(MetodeEkstraksiEnum::class)],
            'nama_kit_ekstraksi' => 'required_if:metode_ekstraksi,' . MetodeEkstraksiEnum::Manual(),
            'alat_ekstraksi' => 'required_if:metode_ekstraksi,' . MetodeEkstraksiEnum::Otomatis(),
        ];

        if ($this->sampel->sampel_status != 'extraction_sample_extracted') {
            $rules['tanggal_pengiriman_rna'] = 'required|date';
            $rules['jam_pengiriman_rna'] = 'required|date_format:H:i';
            $rules['nama_pengirim_rna'] = 'required';
            $rules['lab_pcr_nama'] = 'required_if:lab_pcr_id,' . LabPCREnum::lainnya()->getIndex();
            $rules['lab_pcr_id'] = 'required|integer|exists:lab_pcr,id';
            $rules['catatan_pengiriman'] = 'nullable';
        }

        return $rules;
    }
}
