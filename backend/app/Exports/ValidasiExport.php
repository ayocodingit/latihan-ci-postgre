<?php

namespace App\Exports;

use App\Traits\ConvertEnumTrait;
use App\Traits\ExportExcelTrait;
use App\Traits\ExportMappingTrait;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ValidasiExport implements
    FromCollection,
    WithEvents,
    WithMapping,
    WithHeadings,
    WithColumnFormatting,
    ShouldAutoSize,
    WithTitle
{
    use ExportExcelTrait;
    use ConvertEnumTrait;
    use ExportMappingTrait;

    public $header = [
        'No',
        'No Registrasi',
        'Kode Sampel',
        'Kategori',
        'Status',
        'Nama Pasien',
        'NIK',
        'Usia',
        'Satuan',
        'Tempat Lahir',
        'Tanggal Lahir',
        'Jenis Kelamin',
        'Domisili',
        'Alamat',
        'RT',
        'RW',
        'No Telp',
        'Instansi Pengirim',
        'Nama Fasyankes/Dinkes',
        'Tipe Sampel',
        'Parameter Lab',
        'CT Hasil',
        'Hasil',
        'Tanggal Registrasi',
        'Tanggal Terima Sampel',
        'Tanggal Pemeriksaan',
        'Tanggal Validasi',
    ];

    const TITLE = "Validasi";
    const CELLRANGE = "AA";

    public function __construct($models, $number)
    {
        $this->setUp($models, $number, self::TITLE, self::CELLRANGE);
    }

    public function map($model): array
    {
        return [
            $this->number++,
            $model->nomor_register,
            $model->nomor_sampel,
            $model->sumber_pasien,
            $this->convertEnumStatusPasien($model->status),
            $model->nama_lengkap,
            $model->nik . ' ',
            usiaPasien($model->tanggal_lahir, $model->usia_tahun),
            'Tahun',
            $model->tempat_lahir,
            $model->tanggal_lahir,
            $model->jenis_kelamin,
            $model->nama_kota,
            $this->alamatLengkap($model),
            $model->no_rt,
            $model->no_rw,
            $model->no_hp,
            $this->convertEnumFasyankesPengirim($model->fasyankes_pengirim),
            $model->fasyankes_nama,
            $model->jenis_sampel_nama,
            $this->hasil_deteksi($model->hasil_deteksi),
            $this->getHasilDeteksiTerkecil($model->hasil_deteksi, $model->kesimpulan_pemeriksaan),
            $this->convertEnumKesimpulanPemeriksaan($model->kesimpulan_pemeriksaan),
            Carbon::parse($model->created_at)->format('Y-m-d'),
            optional($model->waktu_sample_taken)->format('Y-m-d'),
            $model->tanggal_input_hasil,
            optional($model->waktu_sample_valid)->format('Y-m-d'),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER,
            'Q' => NumberFormat::FORMAT_NUMBER,
        ];
    }
}
