<?php

namespace App\Enums;

use Spatie\Enum\Enum;

class RoleEnum extends Enum
{
    public static function ADMIN(): RoleEnum
    {
        return new class () extends RoleEnum
        {
            public function getIndex(): int
            {
                return 1;
            }
        };
    }

    public static function REGISTER(): RoleEnum
    {
        return new class () extends RoleEnum
        {
            public function getIndex(): int
            {
                return 2;
            }
        };
    }

    public static function SAMPEL(): RoleEnum
    {
        return new class () extends RoleEnum
        {
            public function getIndex(): int
            {
                return 3;
            }
        };
    }

    public static function EKSTRAKSI(): RoleEnum
    {
        return new class () extends RoleEnum
        {
            public function getIndex(): int
            {
                return 4;
            }
        };
    }

    public static function PCR(): RoleEnum
    {
        return new class () extends RoleEnum
        {
            public function getIndex(): int
            {
                return 5;
            }
        };
    }

    public static function VERIFIKATOR(): RoleEnum
    {
        return new class () extends RoleEnum
        {
            public function getIndex(): int
            {
                return 6;
            }
        };
    }

    public static function VALIDATOR(): RoleEnum
    {
        return new class () extends RoleEnum
        {
            public function getIndex(): int
            {
                return 7;
            }
        };
    }
}
