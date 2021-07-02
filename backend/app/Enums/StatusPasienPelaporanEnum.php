<?php

namespace App\Enums;

use Spatie\Enum\Enum;

class StatusPasienPelaporanEnum extends Enum
{
    public static function CLOSEDCONTACT(): StatusPasienPelaporanEnum
    {
        return new class () extends StatusPasienPelaporanEnum
        {
            public function getValue(): string
            {
                return 'kontak erat';
            }

            public function getIndex(): int
            {
                return 1;
            }
        };
    }

    public static function SUSPECT(): StatusPasienPelaporanEnum
    {
        return new class () extends StatusPasienPelaporanEnum
        {
            public function getValue(): string
            {
                return 'suspek';
            }

            public function getIndex(): int
            {
                return 2;
            }
        };
    }

    public static function PROBABLE(): StatusPasienPelaporanEnum
    {
        return new class () extends StatusPasienPelaporanEnum
        {
            public function getValue(): string
            {
                return 'probable';
            }

            public function getIndex(): int
            {
                return 3;
            }
        };
    }

    public static function CONFIRMATION(): StatusPasienPelaporanEnum
    {
        return new class () extends StatusPasienPelaporanEnum
        {
            public function getValue(): string
            {
                return 'konfirmasi';
            }

            public function getIndex(): int
            {
                return 4;
            }
        };
    }
}
