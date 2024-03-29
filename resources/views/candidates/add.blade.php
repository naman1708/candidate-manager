@extends('layouts.main')

@push('page-title')
    <title>{{ __('Add New Candidates') }}</title>
@endpush

@push('heading')
    {{ __('Add New Candidates') }}
@endpush

@section('content')
    <x-status-message />
    <a href="{{route('candidates')}}" class="btn btn-primary btn-sm">{{'Candidates'}}</a>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <form method="post" action="{{ route('candidate.save') }}" enctype="multipart/form-data">
                        @csrf
                        <h4 class="card-title mb-3">{{ __('Personal Details') }}</h4>

                        <div class="row">
                            <div class="col-lg-6">
                                <x-form.input name="candidate_name" label="Candidate Name" />
                            </div>
                            <div class="col-lg-6">
                                <x-form.input name="contact" label="Contact Number" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <x-form.input name="email" label="Email Address" />
                            </div>

                            <div class="col-lg-6">
                                <x-form.input name="contact_by" label="Contact By" />
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <x-form.select label="Role Filter" chooseFileComment="--Select Role--"
                                    name="candidate_role_id" :options="$candidateRole" />
                            </div>

                            <div class="col-lg-6">
                                <x-form.input name="date" label="Date" type="date" value="<?php echo date('Y-m-d'); ?>" />
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-6">
                                <x-form.input name="source" label="Source" />
                            </div>

                            <div class="col-lg-6">
                                <x-form.input name="experience" label="Experience" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <x-form.input name="salary" label="Salary" />
                            </div>

                            <div class="col-lg-6">
                                <x-form.input name="expectation" label="Expectation" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <label for="">Status</label>
                                <input type="text" name="status" value="{{ old('status') }}" class="form-control">
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <x-form.input name="upload_resume" label="Upload Resume" type="file" />
                            </div>

                            <div class="col-lg-12">
                                <x-form.select name="interview_status_tag" label="Tag"
                                    chooseFileComment="--Select Tage--" :options="[
                                        'interview scheduled' => 'Interview Scheduled',
                                        'interviewed' => 'Interviewed',
                                        'selected' => 'Selected',
                                        'rejected' => 'Rejected',
                                    ]" />
                            </div>

                            <div class="col-lg-12">
                                <x-form.textarea name="comment" label="Comment" />
                            </div>

                        </div>

                        <div>
                            <button class="btn btn-primary" type="submit">{{ __('Add Candidate') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
