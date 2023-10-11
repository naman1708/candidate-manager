<?php

namespace App\Exports;

use App\Models\Candidate;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CandidatesExport implements FromCollection, WithHeadings, WithStrictNullComparison, ShouldAutoSize, WithEvents, WithStyles, WithTitle
{
    use Exportable;

    private $candidates;

    public function __construct($candidates)
    {
        $this->candidates = $candidates;
    }

    public function collection()
    {
        // $this->candidates;
        $candidates = Candidate::with('candidateRole')->whereIn('id', $this->candidates)->get();

        $data = $candidates->map(function ($candidate) {
            return [
                'Candidate Name' => $candidate->candidate_name,
                'Contact' => $candidate->contact,
                'Email' => $candidate->email,
                'Contact By' => $candidate->contact_by,
                'Candidate Role' => $candidate->candidateRole->candidate_role ?? 'N/A',
                'Date' => $candidate->date,
                'Source' => $candidate->source,
                'Experience' => $candidate->experience,
                'Salary' => $candidate->salary,
                'Expectation' => $candidate->expectation,
                'Status' => $candidate->status,
                'Resume' => $candidate->upload_resume,
            ];
        });

        return $data;
    }

    public function headings(): array
    {
        return [
            'Candidate Name',
            'Contact',
            'Email',
            'Contact By',
            'Candidate Role',
            'Date',
            'Source',
            'Experience',
            'Salary',
            'Expectation',
            'Status',
            'Resume'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Apply styling to the headings
                $event->sheet->getStyle('A1:L1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            '1' => ['font' => ['bold' => true]],
        ];
    }
    public function title(): string
    {
        return 'Candidates';
    }
}
