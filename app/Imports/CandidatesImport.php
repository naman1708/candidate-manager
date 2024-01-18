<?php

namespace App\Imports;

use App\Models\Candidate;
use App\Models\CandidateRoles;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use SebastianBergmann\Type\NullType;
use Maatwebsite\Excel\Concerns\Importable;


class CandidatesImport implements ToModel, WithHeadingRow
{

    use Importable;

    private $newCandidatesCount = 0;
    private $updatedCandidatesCount = 0;

    public function model(array $row)
    {
        // dd($row);
        // Log::info('Debug information: ' . json_encode($row));

        $candidateRoleName = isset($row['candidate_role']) ? trim($row['candidate_role']) : NULL;
        $candidateRole = CandidateRoles::where('candidate_role', $candidateRoleName)->first();
        $excelDate = isset($row['date']) ? trim($row['date']) : NULL;
        // $dateIsValid = $this->parseDate($excelDate);

        // dd($dateIsValid);

        if (is_numeric($excelDate)) {
            $excelDate = (int)$excelDate;
            $dateIsValid = Carbon::createFromDate(1900, 1, 1)->addDays($excelDate - 2)->format('Y-m-d');
        } elseif (!is_null($excelDate)) {
            $parsedDate = Carbon::parse($excelDate);
            $dateIsValid = $parsedDate->format('Y-m-d');
        } else {
            // $excelDate is NULL, set $dateIsValid to NULL.
            $dateIsValid = NULL;
        }

        $resume = isset($row['resume']) ? trim($row['resume']) : NULL;
        $candidateData = [
            'candidate_role_id' => $candidateRole ? trim($candidateRole->id) : NULL,
            'candidate_name' => trim($row['candidate_name']),
            'email' => isset($row['email']) ? trim($row['email']) : NULL,
            'date' => $dateIsValid,
            'source' => isset($row['source']) ? trim($row['source']) : NULL,
            'experience' => isset($row['experience']) ? trim($row['experience']) : NULL,
            'contact' =>  isset($row['contact']) ?  (string)trim($row['contact']) : NULL,
            'contact_by' => isset($row['contact_by']) ? trim($row['contact_by']) : NULL,
            'status' => isset($row['status']) ? trim($row['status']) : NULL,
            'salary' => isset($row['salary']) ? trim($row['salary']) : NULL,
            'expectation' => isset($row['expectation']) ? trim($row['expectation']) : NULL,
            'upload_resume' => !empty($resume) ? 'resumes/' . $resume : NULL,
        ];

        $existingCandidateByEmail = Candidate::where('email', trim($row['email']))->first();
        $existingCandidateByContact = Candidate::where('contact', (string)trim($row['contact']))->first();

        // Check if a candidate was updated
        if ($existingCandidateByEmail || $existingCandidateByContact) {
            $existingCandidate = $existingCandidateByEmail ?? $existingCandidateByContact;
            $existingCandidate->update($candidateData);
            $this->updatedCandidatesCount++;
        } else {
            Candidate::create($candidateData);
            $this->newCandidatesCount++;
        }
    }

    public function getNewCandidatesCount()
    {
        return $this->newCandidatesCount;
    }

    public function getUpdatedCandidatesCount()
    {
        return $this->updatedCandidatesCount;
    }



    // private function parseDate($excelDate)
    // {
    //     $dateIsValid=null;

    //     if (is_numeric($excelDate)) {
    //         $excelDate = (int)$excelDate;
    //         $dateIsValid = Carbon::createFromDate(1900, 1, 1)->addDays($excelDate - 2)->format('Y-m-d');
    //     } else {
    //         // different date format parsing
    //         $parsedDate = Carbon::createFromFormat('d-m-y', $excelDate);
    //         if (!$parsedDate->isValid()) {
    //             $parsedDate = Carbon::parse($excelDate);
    //         }
    //         $dateIsValid = $parsedDate->format('Y-m-d');
    //     }
    //     return $dateIsValid;
    // }


    // private function parseDate($excelDate)
    // {
    //     $dateIsValid = null;

    //     // Try to parse the date as a numeric value.
    //     if (is_numeric($excelDate)) {
    //         $excelDate = (int)$excelDate;
    //         $dateIsValid = Carbon::createFromDate(1900, 1, 1)->addDays($excelDate - 2)->format('Y-m-d');
    //     } else {
    //         // Try to parse the date using different date formats.
    //         $dateFormats = [
    //             'd-m-y',
    //             'm-d-y',
    //             'Y-m-d',
    //             'd/m/y',
    //             'm/d/y',
    //             'Y/m/d',
    //         ];

    //         foreach ($dateFormats as $dateFormat) {
    //             $parsedDate = Carbon::createFromFormat($dateFormat, $excelDate);
    //             if ($parsedDate->isValid()) {
    //                 $dateIsValid = $parsedDate->format('Y-m-d');
    //                 break;
    //             }
    //         }
    //     }

    //     return $dateIsValid;
    // }



}
