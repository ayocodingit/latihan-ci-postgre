<?php

namespace App\Enums;

use Spatie\Enum\Enum;

class FormatSampelEnum extends Enum
{
    public static function RUJUKAN(): FormatSampelEnum
    {
        return new class () extends FormatSampelEnum
        {
            public function getValue(): string
            {
                return '[RABCV]{1}[0-9]{1,}';
            }
        };
    }

    public static function MANDIRI(): FormatSampelEnum
    {
        return new class () extends FormatSampelEnum
        {
            public function getValue(): string
            {
                return '[L]{1}[0-9]{1,}';
            }
        };
    }

    public static function FILTER(): FormatSampelEnum
    {
        return new class () extends FormatSampelEnum
        {
            public function getValue(): string
            {
                return '[LRABCV]{1}[0-9]{1,}';
            }
        };
    }


}
