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



class CandidatesExport implements FromCollection, WithHeadings, WithStrictNullComparison, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        $candidates = Candidate::with('candidateRole')->get();

        $data = $candidates->map(function ($candidate) {
            return [
                'Candidate Name' => $candidate->candidate_name,
                'Contact' => $candidate->contact,
                'Email' => $candidate->email,
                'Contact By' => $candidate->contact_by,
                'Candidate Role' => $candidate->candidateRole->candidate_role,
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
            $styleArray =  AfterSheet::class => function (AfterSheet $event) {
                // Apply styling
                $event->sheet->getStyle('1')->applyFromArray([
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
}
