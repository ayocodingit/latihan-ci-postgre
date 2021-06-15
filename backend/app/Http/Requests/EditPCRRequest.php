<?php

namespace App\Http\Requests;

use App\Enums\LabPCREnum;
use App\Enums\MetodeEkstraksiEnum;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Rules\EnumRule;

class EditPCRRequest extends FormRequest
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
            'nama_kit_ekstraksi' => 'required',
            'metode_ekstraksi' => ['required', new EnumRule(MetodeEkstraksiEnum::class)],
            'jam_mulai_ekstraksi' => 'required|date_format:H:i',
            'operator_ekstraksi' => 'required',
            'tanggal_mulai_ekstraksi' => 'required|date',
            'catatan_penerimaan' => 'nullable',
        ];

        if ($this->sampel->sampel_status != 'pcr_sample_received') {
            $rules += [
                'tanggal_pengiriman_rna' => 'required|date',
                'jam_pengiriman_rna' => 'required|date_format:H:i',
                'nama_pengirim_rna' => 'required',
                'lab_pcr_id' => 'required|integer|exists:lab_pcr,id',
                'catatan_pengiriman' => 'nullable',
                'lab_pcr_nama' => 'required_if:lab_pcr_id,' . LabPCREnum::lainnya()->getIndex(),
            ];
        }

        return $rules;
    }
}
