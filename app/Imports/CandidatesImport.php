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
        $candidateRoleName = isset($row['candidate_role'])? $row['candidate_role'] : NULL;
        $candidateRole = CandidateRoles::where('candidate_role', $candidateRoleName)->first();
        $excelDate = isset($row['date'])? trim($row['date']) : NULL;
        $dateIsValid = $this->parseDate($excelDate);

        // if (is_numeric($excelDate)) {
        //     $excelDate = (int)$excelDate;
        //     $dateIsValid = Carbon::createFromDate(1900, 1, 1)->addDays($excelDate - 2)->format('Y-m-d');
        // } else {
        //     $parsedDate = Carbon::parse($excelDate);
        //     $dateIsValid =$parsedDate->format('Y-m-d');
        // }

        $resume = isset($row['resume'])?$row['resume']:NULL;
        $candidateData = [
            'candidate_role_id' => $candidateRole ? $candidateRole->id : NULL,
            'candidate_name' => $row['candidate_name'],
            'email' => isset($row['email']) ? $row['email'] : NULL,
            'date' => (string)$dateIsValid,
            'source' => isset($row['source']) ? $row['source'] : NULL,
            'experience' => isset($row['experience']) ? $row['experience'] : NULL,
            'contact' =>  isset($row['contact']) ? (string)$row['contact'] : NULL,
            'contact_by' => isset($row['contact_by'] ) ? $row['contact_by']  : NULL,
            'status' => isset($row['status']) ? $row['status'] : NULL,
            'salary' => isset($row['salary']) ? $row['salary'] : NULL,
            'expectation' => isset($row['expectation'] )? $row['expectation'] : NULL,
            'upload_resume' => !empty($resume) ? 'resumes/' . $resume : NULL,
        ];

        $existingCandidateByEmail = Candidate::where('email', $row['email'])->first();
        $existingCandidateByContact = Candidate::where('contact', (string)$row['contact'])->first();

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


    private function parseDate($excelDate)
    {
        if (is_numeric($excelDate)) {
            $excelDate = (int)$excelDate;
            $dateIsValid = Carbon::createFromDate(1900, 1, 1)->addDays($excelDate - 2)->format('Y-m-d');
        } else {
            // Try different date format parsing here
            $parsedDate = Carbon::createFromFormat('d-m-y', $excelDate);
            if (!$parsedDate->isValid()) {
                $parsedDate = Carbon::parse($excelDate);
            }
            $dateIsValid = $parsedDate->format('Y-m-d');
        }
        return $dateIsValid;
    }

}
