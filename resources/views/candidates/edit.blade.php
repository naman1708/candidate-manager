@extends('layouts.main')

@push('page-title')
<title>{{ __('Edit Candidate')}}</title>
@endpush

@push('heading')
{{ 'Edit Candidate' }} : {{$candidates->candidate_name}}
@endpush

@section('content')

<x-status-message/>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <form method="post" action="{{ route('candidate.update') }}" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="" value="{{$candidates->id}}">
                    @csrf
                    <h4 class="card-title mb-3">{{__('Personal Details')}}</h4>      
                    
                    <div class="row">
                        <div class="col-lg-6">
                           <x-form.input name="candidate_name" label="Candidate Name" :value="$candidates->candidate_name"/>
                        </div>

                        <div class="col-lg-6">
                            <x-form.input name="contact" label="Contact" :value="$candidates->contact"/>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-6">
                            <x-form.input name="email" label="Email Address" :value="$candidates->email"/>
                        </div>

                        <div class="col-lg-6">
                            <x-form.input name="contact_by" label="Contact By" :value="$candidates->contact_by"/>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <x-form.select name="categories" label="Role" :options="[
                                'PHP' => 'PHP',
                                'Front end' => 'Front end',
                                'Freshers' => 'Freshers',
                                'HR' => 'HR',
                                'BDE' => 'BDE',
                            ]" :selected="$candidates->categories" />
                        </div>

                        <div class="col-lg-6">
                            <x-form.input name="date" label="Date" type="date" :value="$candidates->date"/>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-6">
                            <x-form.input name="source" label="Source" :value="$candidates->source" />
                        </div>

                        <div class="col-lg-6">
                            <x-form.input name="experience" label="Experience" :value="$candidates->experience"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <x-form.input name="salary" label="Salary" :value="$candidates->salary"/>
                        </div>

                        <div class="col-lg-6">
                            <x-form.input name="expectation" label="Expectation" :value="$candidates->expectation"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            {{-- <x-form.input name="status" label="Status" type="text"/> --}}
                            <label for="">Status</label>
                            <input type="text" name="status" value="{{$candidates->status}}" class="form-control">
                            @error('status')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-lg-4">
                            <x-form.input name="upload_resume" label="Upload Resume" type="file"/>
                        </div>
                        <div class="col-lg-2 mt-lg-4">
                            <a href="{{ asset('storage')}}/{{$candidates->upload_resume}}" target="_blank">View Resume</a>

                        </div>
                    </div>


                    <div>
                        <button class="btn btn-primary" type="submit">{{__('update Candidate')}}</button>
                    </div>
                </form>
           </div>
        </div>
    </div>
</div>

@endsection