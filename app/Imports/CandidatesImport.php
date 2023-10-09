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


        $candidateRoleName = $row['candidate_role'];
        $candidateRole = CandidateRoles::where('candidate_role', $candidateRoleName)->first();
        $excelDate = trim($row['date']);

        if (is_numeric($excelDate)) {
            $excelDate = (int)$excelDate;
            $dateIsValid = Carbon::createFromDate(1900, 1, 1)->addDays($excelDate - 2)->format('Y-m-d');
        } else {
            $dateIsValid = $excelDate;
        }

        $candidateData = [
            'candidate_role_id' => $candidateRole ? $candidateRole->id : Null,
            'candidate_name' => $row['candidate_name'],
            'email' => $row['email'],
            'date' => (string)$dateIsValid,
            'source' => $row['source'] ? $row['source'] : NULL,
            'experience' => $row['experience'],
            'contact' => (string)$row['contact'],
            'contact_by' => $row['contact_by'],
            'status' => $row['status'],
            'salary' => $row['salary'] ? $row['salary'] : NULL,
            'expectation' => $row['expectation'] ? $row['expectation'] : NULL,
            'upload_resume' => $row['resume'] ? 'resumes/' . $row['resume'] : NULL,
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
}
