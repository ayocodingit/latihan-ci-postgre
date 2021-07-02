<?php

namespace App\Imports;

use App\Enums\FormatSampelEnum;
use App\Enums\KesimpulanPemeriksaanEnum;
use App\Models\Sampel;
use App\Rules\ExistsSampleReceivedRule;
use App\Traits\ImportExcelTrait;
use App\Traits\RegisterTrait;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Enum\Laravel\Rules\EnumValueRule;

class PCRImport implements ToCollection, WithHeadingRow
{
    use RegisterTrait;
    use Importable;
    use ImportExcelTrait;

    protected $rulesCTValue = [];

    protected $no = 1;

    /**
     * @return string
     */
    public function uniqueBy()
    {
        return 'no_sample';
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            if (!$row->get('no')) {
                continue;
            }
            $row['no_sample'] = trim($row->get('no_sample'));
            $row['interpretasi'] = strtolower($row->get('interpretasi'));
            $row['sampel_id'] = Sampel::where('nomor_sampel', $row['no_sample'])
                                    ->where('sampel_status', 'pcr_sample_received')
                                    ->value('id');
            $row = $this->getTargetGen($row);
            $this->validated($row->toArray(), $key);
            $this->setData($this->mappingData($row));
        }
    }

    private function getTargetGen($row)
    {
        $targetGen = [];
        $this->rulesCTValue = [];

        $ct_keys = array_keys($row->toArray());
        foreach ($ct_keys as $ct_key) {
            if (strpos($ct_key, 'ct_') === 0) {
                $this->setRuleTargetGen($ct_key);
                $targetGen[] = [
                    'target_gen' => strtoupper(substr($ct_key, 3)),
                    'ct_value' => $row[$ct_key],
                ];
            }
        }

        $row['target_gen'] = $targetGen;

        return $row;
    }

    private function setRuleTargetGen($ct_key)
    {
        $this->rulesCTValue[] = [
            $ct_key => 'required|numeric'
        ];
    }

    private function mappingData($row)
    {
        return [
            'no' => $this->no++,
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
                'regex:/^' . FormatSampelEnum::FILTER() . '$/',
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
