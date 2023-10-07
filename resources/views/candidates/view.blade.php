@extends('layouts.main')
@push('page-title')
    <title>{{ 'Candidates - ' }} {{ $candidate->candidate_name }}</title>
@endpush

@push('heading')
    {{ 'Candidates Detail' }}
@endpush

@push('heading-right')
@endpush

@section('content')

    {{-- Candidates details --}}
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <h5 class="card-header">{{ 'Candidates Details' }}</h5>
                <div class="card-body">

                    <h5 class="card-title">
                        <span>Candidate Name :</span>
                        <span>
                            {{ $candidate->candidate_name }}
                        </span>
                    </h5>
                    <hr>
                    <h5 class="card-title">
                        <span>Role : </span>
                        <span>{{ $candidate->candidateRole->candidate_role }}</span>
                    </h5>
                    <hr />
                    <h5 class="card-title">
                        <span>Date : </span>
                        <span>{{ \Carbon\Carbon::parse($candidate->date)->format('d-M-Y') }}</span>

                    </h5>
                    <hr />
                    <h5 class="card-title">
                        <span>Source : </span>
                        <span>{{ $candidate->source }}</span>
                    </h5>
                    <hr />
                    <h5 class="card-title">
                        <span>Experience: </span>
                        <span>{{ $candidate->experience }}</span>
                    </h5>
                    <hr />
                    <h5 class="card-title">
                        <span>Salary : </span>
                        <span>{{ $candidate->salary }}</span>
                    </h5>
                    <hr />
                    <h5 class="card-title">
                        <span>Expectation : </span>
                        <span>{{ $candidate->expectation }}</span>
                    </h5>
                    <hr>

                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <h5 class="card-header">Contact Details</h5>
                <div class="card-body">
                    <h5 class="card-title">
                        <span>Phone : </span>
                        <span>{{ $candidate->contact }}</span>
                    </h5>
                    <hr>

                    <h5 class="card-title">
                        <span>Email :</span>
                        <span>{{ $candidate['email'] }}</span>
                    </h5>
                    <hr>

                    <h5 class="card-title">
                        <span>Contact By :</span>
                        <span>{{ $candidate['contact_by'] }}</span>
                    </h5>
                    <hr>

                    <h5 class="card-title">
                        <span>Status :</span>
                        <span>{{ $candidate['status'] }}</span>
                    </h5>
                    <hr>

                    <h5 class="card-title">
                        <span>Download Resume :</span>
                        @if ($candidate->upload_resume)
                            @php
                                $resumePath = $candidate->upload_resume;
                            @endphp
                            @if (Storage::disk('local')->exists($resumePath) && Storage::disk('local')->size($resumePath) > 0)
                                <a href="{{ route('download.resume', $candidate->id) }}" class="btn btn-primary btn-sm">
                                    <i class="ri-download-cloud-fill"></i>
                                </a>
                            @else
                                <span class="text-primary">Resume not found.</span>
                            @endif
                        @else
                            <span class="text-danger">Resume not found.</span>
                        @endif

                    </h5>
                    <hr>

                </div>
            </div>
        </div>

    </div>

@endsection
