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


class CandidatesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        Log::info('Raw Data:', $row);

        $candidateRoleName = $row['candidate_role'];
        $candidateRole = CandidateRoles::where('candidate_role', $candidateRoleName)->first();

        $excelDate = trim($row['date']);

        if (is_numeric($excelDate)) {
            $excelDate = (int)$excelDate;
            $dateIsValid = Carbon::createFromDate(1900, 1, 1)->addDays($excelDate - 2)->format('Y-m-d');
        } else {
            Log::warning('Invalid date encountered: ' . (int)$excelDate);
            $dateIsValid = $excelDate;
        }

        $candidateData = [
            'candidate_role_id' => $candidateRole ? $candidateRole->id : null,
            'candidate_name' => $row['candidate_name'],
            'email' => $row['email'],
            'date' => (string)$dateIsValid,
            'source' => $row['source'],
            'experience' => $row['experience'],
            'contact' => (string)$row['contact'],
            'contact_by' => $row['contact_by'],
            'status' => $row['status'],
            'salary' => $row['salary'],
            'expectation' => $row['expectation'],
            'upload_resume' => $row['resume'],
        ];

        Log::info('Processed Data (Before Validation):', $candidateData);

        $existingCandidate = Candidate::where('contact', (string)$row['contact'])->first();

        if ($existingCandidate) {
            $existingCandidate->update($candidateData);
            Log::info('Record Updated:', $candidateData);
        } else {
            Candidate::create($candidateData);
            Log::info('Record Inserted:', $candidateData);
        }
    }
    
}

