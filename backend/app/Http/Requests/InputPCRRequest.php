<?php

namespace App\Http\Requests;

use App\Enums\KesimpulanPemeriksaanEnum;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Rules\EnumValueRule;

class InputPCRRequest extends FormRequest
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
        return [
            'hasil_deteksi' => 'array|min:1',
            'kesimpulan_pemeriksaan' => ['required', new EnumValueRule(KesimpulanPemeriksaanEnum::class)],
            'hasil_deteksi.*.target_gen' => 'required',
            'hasil_deteksi.*.ct_value' => 'required|numeric',
            'tanggal_input_hasil' => 'required|date',
            'jam_input_hasil' => 'required',
            'catatan_pemeriksaan' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'hasil_deteksi.min' => 'Minimal 1 hasil deteksi CT Value'
        ];
    }
}
