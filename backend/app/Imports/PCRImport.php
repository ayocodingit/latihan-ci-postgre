<?php

namespace App\Imports;

use App\Enums\JenisRegistrasiEnum;
use App\Enums\KesimpulanPemeriksaanEnum;
use App\Enums\StatusPasienEnum;
use App\Models\Fasyankes;
use App\Models\Kota;
use App\Models\Sampel;
use App\Rules\ExistsSampel;
use App\Rules\ExistsSampleReceivedRule;
use App\Rules\SampleReceivedRule;
use App\Traits\ImportExcelTrait;
use App\Traits\RegisterTrait;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Enum\Laravel\Rules\EnumValueRule;

class RegisterRujukanImport implements ToCollection, WithHeadingRow
{
    use RegisterTrait;
    use Importable;
    use ImportExcelTrait;

    protected $rulesCTValue = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            if (!$row->get('no')) {
                continue;
            }
            $row['kriteria'] = strtolower($row->get('kriteria'));
            $row['nomor_sampel'] = trim($row->get('nomor_sampel'));
            $this->validated($row->toArray(), $key);
            $this->setData($this->mappingData($row));
        }
    }

    private function mappingData($row)
    {
        return [
            'nomor_sampel' => $row->get('no_sample'),
            'sampel_id' => $row->get('sampel_id'),
            'kesimpulan_pemeriksaan' => $row->get('interpretasi'),
            'tanggal_input_hasil' => $row->get('tanggal_periksa'),
            'target_gen' => $row->get('target_gen'),
        ];
    }

    public function rules(): array
    {
        $rules = [
            'tanggal_periksa' => 'required|date|date_format:Y-m-d',
            'no_sample' => [
                'required',
                'regex:/^' . Sampel::NUMBER_FORMAT . '$/',
                new ExistsSampleReceivedRule()
            ],
            'interpretasi' => [
                'required',
                new EnumValueRule(KesimpulanPemeriksaanEnum::class)
            ],
        ];

        return $rules + $this->rulesCTValue;
    }
}
