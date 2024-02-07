@extends('layouts.main')

@push('page-title')
    <title>{{ __('Edit Candidate') }}</title>
@endpush

@push('heading')
    {{ 'Edit Candidate' }} : {{ $candidates->candidate_name }}
@endpush

@section('content')
    <x-status-message />
    <a href="{{route('candidates')}}" class="btn btn-primary btn-sm">{{'Candidate'}}</a>
    <a href="{{ url()->previous() }}" class="btn btn-warning btn-sm">{{'Back'}}</a>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <form method="post" action="{{ route('candidate.update') }}" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="" value="{{ $candidates->id }}">
                        @csrf
                        <h4 class="card-title mb-3">{{ __('Personal Details') }}</h4>

                        @role('admin')
                        <div class="row">
                            <div class="col-lg-12">
                                <x-form.textarea name="superadmin_instruction" label="Superadmin Instruction" value="{{ $candidates->superadmin_instruction }}" />
                            </div>
                        </div>
                        @endrole

                        <div class="row">
                            <div class="col-lg-6">
                                <x-form.input name="candidate_name" label="Candidate Name" :value="$candidates->candidate_name" />
                            </div>

                            <div class="col-lg-6">
                                <x-form.input name="contact" label="Contact" :value="$candidates->contact" />
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-6">
                                <x-form.input name="email" label="Email Address" :value="$candidates->email" />
                            </div>

                            <div class="col-lg-6">
                                <x-form.input name="contact_by" label="Contact By" :value="$candidates->contact_by" />
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <x-form.select label="Role Filter" chooseFileComment="--Select Role--"
                                        name="candidate_role_id" :options="$candidateRole" :selected="$candidates->candidate_role_id" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <x-form.input name="date" label="Date" type="date" :value="$candidates->date" />
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-6">
                                <x-form.input name="source" label="Source" :value="$candidates->source" />
                            </div>

                            <div class="col-lg-6">
                                <x-form.input name="experience" label="Experience" :value="$candidates->experience" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <x-form.input name="salary" label="Salary" :value="$candidates->salary" />
                            </div>

                            <div class="col-lg-6">
                                <x-form.input name="expectation" label="Expectation" :value="$candidates->expectation" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <label for="">Status</label>
                                <input type="text" name="status" value="{{ $candidates->status }}" class="form-control">
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-lg-4">
                                <x-form.input name="upload_resume" label="Upload Resume" type="file" />
                            </div>
                            <div class="col-lg-2 mt-lg-4">
                                @if (!@empty($candidates->upload_resume))
                                    <strong class="text-primary">{{ basename($candidates->upload_resume) }}</strong>
                                @else
                                    <strong class="text-primary">No File</strong>
                                @endif
                            </div>
                        </div>


                        <div class="col-lg-12">
                            <x-form.select name="interview_status_tag" label="Tag" chooseFileComment="--Select Tage--"
                                :options="[
                                    'interview scheduled' => 'Interview Scheduled',
                                    'interviewed' => 'Interviewed',
                                    'selected' => 'Selected',
                                    'rejected' => 'Rejected',
                                ]" :selected="$candidates->interview_status_tag" />
                        </div>

                        <div class="col-lg-12">
                            <x-form.textarea name="comment" label="Comment" value="{{ $candidates->comment }}" />
                        </div>

                        <div>
                            <button class="btn btn-primary" type="submit">{{ __('Update Candidate') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
