<?php

namespace App\Rules;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Provinsi;
use Illuminate\Contracts\Validation\Rule;

class ExistsWilayah implements Rule
{

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = getConvertCodeDagri($value);

        if ($value) {
            return $this->isExistWilayah($attribute, $value);
        }

        return true;
    }

    protected function isExistWilayah($attribute, $value)
    {
        switch ($attribute) {
            case 'provinsi_id':
                $result = Provinsi::where('id', $value)->exists();
                break;
            case 'kota_id':
                $result = Kota::where('id', $value)->exists();
                break;
            case 'kecamatan_id':
                $result = Kecamatan::where('id', $value)->exists();
                break;
            case 'kelurahan_id':
                $result = Kelurahan::where('id', $value)->exists();
                break;
        }

        return $result;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute tidak ditemukan';
    }
}
