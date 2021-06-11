<?php

namespace App\Rules;

use App\Models\Sampel;
use Illuminate\Contracts\Validation\Rule;

class ExistsSampleReceivedRule implements Rule
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
        return Sampel::where('nomor_sampel', $value)
                    ->where('sampel_status', 'pcr_sample_received')
                    ->doesntExist();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.exists', ['attribute' => 'nomor sampel']);
    }
}
