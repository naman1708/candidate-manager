@extends('layouts.main')
@push('page-title')
<title>{{ "Candidates - ". $candidates['candidate_name'] }}</title>
@endpush

@push('heading')
{{ 'Candidates Detail' }}
@endpush

@push('heading-right')

@endpush

@section('content')

{{-- investor details --}}
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <h5 class="card-header">{{ 'Candidates Details' }}</h5>
            <div class="card-body">
                
                <h5 class="card-title">
                    <span>Candidate Name :</span> 
                    <span>
                        {{ $candidates['candidate_name'] }} 
                    </span>
                </h5>
                <hr>
                <h5 class="card-title">
                    <span>Role : </span>
                    <span>{{ $candidates->categories }}</span>
                </h5>
                <hr/>
                <h5 class="card-title">
                    <span>Date : </span>
                    <span>{{ \Carbon\Carbon::parse($candidates->date)->format('d-M-Y') }}</span>

                </h5>
                <hr/>
                <h5 class="card-title">
                    <span>Source : </span>
                    <span>{{ $candidates->source }}</span>
                </h5>
                <hr/>
                <h5 class="card-title">
                    <span>Experience: </span>
                    <span>{{ $candidates->experience }}</span>
                </h5>
                <hr/>
                <h5 class="card-title">
                    <span>Salary : </span>
                    <span>{{ $candidates->salary }}</span>
                </h5>
                <hr/>
                <h5 class="card-title">
                    <span>Expectation : </span>
                    <span>{{ $candidates->expectation }}</span>
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
                    <span>{{ $candidates->contact }}</span>
                </h5>
                <hr>
               
                <h5 class="card-title">
                    <span>Email :</span>
                    <span>{{ $candidates['email'] }}</span>
                </h5>
                <hr>

                <h5 class="card-title">
                    <span>Contact By :</span>
                    <span>{{ $candidates['contact_by'] }}</span>
                </h5>
                <hr>

                <h5 class="card-title">
                    <span>Status :</span>
                    <span>{{ $candidates['status'] }}</span>
                </h5>
                <hr>

                <h5 class="card-title">
                    <span>Download Resume :</span>
                    <a href="{{ route('download.resume', $candidates->id) }}" target="_blank" class="btn btn-primary">Download</a>
                </h5>
                <hr>
                
            </div>
        </div>
    </div>

</div>

@endsection