<?php

namespace App\Traits;

trait FilterPcrTrait
{
    public function scopeFilterChumberPcr($query, $key, $value, $filter_inconclusive)
    {
        return $query->filterSampel($key, $value)
                        ->filterPemeriksaanSampel($key, $value)
                        ->filterSampelStatusPcr($key, $value, $filter_inconclusive);
    }

    public function scopeFilterSampelStatusPcr($query, $key, $value, $filter_inconclusive)
    {
        $query->when($key == 'sampel_status', function ($query) use ($value, $filter_inconclusive) {
            $query->when($value == 'extraction_sample_sent', function ($query) {
                $query->where('sampel.sampel_status', 'extraction_sample_sent');
            });
            $query->when($value == 'pcr_sample_received', function ($query) {
                $query->where('sampel.sampel_status', 'pcr_sample_received');
            });
            $query->when($value == 'analyzed', function ($query) use ($filter_inconclusive) {
                $query->filterSampelStatusPcrAnalyze($filter_inconclusive);
            });
        });
        return $query;
    }

    public function scopeFilterSampelStatusPcrAnalyze($query, $filter_inconclusive)
    {
        $query->when(!$filter_inconclusive, function ($query) {
            $query->whereIn('sampel.sampel_status', ['pcr_sample_analyzed', 'sample_verified', 'sample_valid']);
            $query->whereNotIn('pemeriksaansampel.kesimpulan_pemeriksaan', ['invalid', 'swab_ulang']);
        }, function ($query) {
            $query->where(function ($query) {
                $query->where('sampel.sampel_status', 'sample_invalid')
                        ->orWhere(function ($query) {
                            $query->whereIn('sampel.sampel_status', ['pcr_sample_analyzed', 'sample_verified', 'sample_valid'])
                                    ->where('pemeriksaansampel.kesimpulan_pemeriksaan', 'invalid');
                        });
            });
        });
        return $query;
    }
}
