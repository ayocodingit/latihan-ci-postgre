<?php

namespace App\Rules;

use App\Models\Sampel;
use Illuminate\Contracts\Validation\Rule;

class ExistsSampleReceivedRule implements Rule
{

    public $description;

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
        $sampel = Sampel::where('nomor_sampel', $value)->first();

        if (!$sampel) {
            $this->description = 'tidak ditemukan';
        } elseif ($sampel->sampel_status == 'extraction_sample_sent') {
            $this->description = 'belum diterima di Lab PCR. Mohon diterima terlebih dahulu.';
        } elseif ($sampel->sampel_status != 'pcr_sample_received') {
            $this->description = 'masih pada status ' . $sampel->status->deskripsi;
        }

        return !$this->description ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Nomor sampel ' . $this->description;
    }
}
