<?php

namespace App\Enums;

use Spatie\Enum\Enum;

class FasyankesMandiriEnum extends Enum
{
    public static function LABJABAR(): FasyankesMandiriEnum
    {
        return new class () extends FasyankesMandiriEnum
        {
            public function getValue(): string
            {
                return 'lab';
            }

            public function getIndex(): int
            {
                return 70;
            }
        };
    }
}
