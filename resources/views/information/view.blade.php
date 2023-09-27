@extends('layouts.main')
@push('page-title')
<title>{{ "Investor - ". $information['candidate_name'] }}</title>
@endpush

@push('heading')
{{ 'Information Detail' }}
@endpush

@push('heading-right')

@endpush

@section('content')

{{-- investor details --}}
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <h5 class="card-header">{{ 'Information Details' }}</h5>
            <div class="card-body">
                
                <h5 class="card-title">
                    <span>Candidate Name :</span> 
                    <span>
                        {{ $information['candidate_name'] }} 
                    </span>
                </h5>
                <hr>
                <h5 class="card-title">
                    <span>Role : </span>
                    <span>{{ $information->categories }}</span>
                </h5>
                <hr/>
                <h5 class="card-title">
                    <span>Date : </span>
                    <span>{{ \Carbon\Carbon::parse($information->date)->format('d-M-Y') }}</span>

                </h5>
                <hr/>
                <h5 class="card-title">
                    <span>Source : </span>
                    <span>{{ $information->source }}</span>
                </h5>
                <hr/>
                <h5 class="card-title">
                    <span>Experience: </span>
                    <span>{{ $information->experience }}</span>
                </h5>
                <hr/>
                <h5 class="card-title">
                    <span>Salary : </span>
                    <span>{{ $information->salary }}</span>
                </h5>
                <hr/>
                <h5 class="card-title">
                    <span>Expectation : </span>
                    <span>{{ $information->expectation }}</span>
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
                    <span>{{ $information->contact }}</span>
                </h5>
                <hr>
               
                <h5 class="card-title">
                    <span>Email :</span>
                    <span>{{ $information['email'] }}</span>
                </h5>
                <hr>

                <h5 class="card-title">
                    <span>Status :</span>
                    <span>{{ $information['status'] }}</span>
                </h5>
                <hr>

                <h5 class="card-title">
                    <span>Download Resume :</span>
                    <a href="{{ route('download.resume', $information->id) }}" target="_blank" class="btn btn-primary">Download</a>
                </h5>
                <hr>
                
            </div>
        </div>
    </div>

</div>

@endsection