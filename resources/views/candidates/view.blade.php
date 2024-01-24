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
    <x-status-message />
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
                        <span>{{ $candidate->candidateRole->candidate_role ?? null }}</span>
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

                    <h5 class="card-title">
                        <span>Schedule interview :</span>
                        <span>
                            <a href="javascript:void(0)" class="btn btn-info btn-sm"
                                onclick="scheduleCandidateInterview(<?= $candidate->id ?>)">Schedule</a>
                        </span>
                    </h5>
                    <hr>

                </div>
            </div>
        </div>

    </div>


    {{-- Schedule Interview Form --}}

    <div class="modal fade" id="scheduleInterviewModel" tabindex="-1" aria-labelledby="scheduleInterviewModelLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5 text-dark" id="scheduleInterviewLabel">{{ 'Schedule Interview' }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('candidate.scheduleInterview') }}" method="post">
                    @csrf
                    <input type="hidden" name="candidate_id" value="<?= $candidate->id ?>">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-lg-4">
                                <label for="">Interview Date</label>
                                <input type="date" name="interview_date" class="form-control"
                                    value="{{ old('interview_date', $candidate->scheduleInterview ? $candidate->scheduleInterview->interview_date : '') }}"  required>

                            </div>

                            <div class="col-lg-4">
                                <label for="">Interview Time</label>
                                <input type="time" name="interview_time" class="form-control"
                                    value="{{ old('interview_time', $candidate->scheduleInterview ? $candidate->scheduleInterview->interview_time : '') }}" required>
                            </div>

                            <div class="col-lg-4">
                                <label for="">Status</label>
                                <select name="interview_status" id="interview_status" class="form-control" required>
                                    <option value="">--Select Status--</option>
                                    <option value="selected"
                                        {{ $candidate->scheduleInterview ? ($candidate->scheduleInterview->interview_status == 'selected' ? 'selected' : '') : '' }}>
                                        Selected</option>

                                    <option value="pending"
                                        {{ $candidate->scheduleInterview ? ($candidate->scheduleInterview->interview_status == 'pending' ? 'selected' : '') : '' }}>
                                        Pending</option>

                                    <option value="rejected"
                                        {{ $candidate->scheduleInterview ? ($candidate->scheduleInterview->interview_status == 'rejected' ? 'selected' : '') : '' }}>
                                        Rejected</option>
                                </select>
                            </div>

                            <div class="col-lg-12" id="reasonContainer">
                                <label for="message-text" class="col-form-label">Message:</label>
                                <textarea class="form-control" name="reason" id="reason" required>{{ old('reason', $candidate->scheduleInterview ? $candidate->scheduleInterview->reason : '') }}</textarea>
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>

                    </div>

                </form>

            </div>
        </div>
    </div>

@endsection

@push('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).on("change","#interview_status",function(){
            let option = $(this).val();
            if(option == "pending"){
                $('#reason').html(`Coming on later date.`)
            }else{
                $('#reason').html(``)
            }
        })
        function scheduleCandidateInterview() {
            $('#scheduleInterviewModel').modal('show');
        }
    </script>

@endpush
