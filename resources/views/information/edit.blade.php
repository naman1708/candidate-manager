@extends('layouts.main')

@push('page-title')
<title>{{ __('Edit Informations')}}</title>
@endpush

@push('heading')
{{ 'Edit Informations' }} : {{$information->candidate_name}}
@endpush

@section('content')

<x-status-message/>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <form method="post" action="{{ route('informations.update') }}" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="" value="{{$information->id}}">
                    @csrf
                    <h4 class="card-title mb-3">{{__('Personal Details')}}</h4>      
                    
                    <div class="row">
                        <div class="col-lg-6">
                           <x-form.input name="candidate_name" label="Candidate Name" :value="$information->candidate_name"/>
                        </div>
                        <div class="col-lg-6">
                            <x-form.input name="email" label="Email Address" :value="$information->email"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <x-form.select name="categories" label="Categories" :options="[
                                'PHP' => 'PHP',
                                'Front end' => 'Front end',
                                'Freshers' => 'Freshers',
                                'HR' => 'HR',
                                'BDE' => 'BDE',
                            ]" :selected="$information->categories" />
                        </div>

                        <div class="col-lg-6">
                            <x-form.input name="date" label="Date" type="date" :value="$information->date"/>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-6">
                            <x-form.input name="source" label="Source" :value="$information->source" />
                        </div>

                        <div class="col-lg-6">
                            <x-form.input name="experience" label="Experience" :value="$information->experience"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <x-form.input name="salary" label="Salary" :value="$information->salary"/>
                        </div>

                        <div class="col-lg-6">
                            <x-form.input name="expectation" label="Expectation" :value="$information->expectation"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <x-form.input name="contact" label="Contact" :value="$information->contact"/>
                        </div>
                        <div class="col-lg-6">
                            {{-- <x-form.input name="status" label="Status" type="text"/> --}}
                            <label for="">Status</label>
                            <input type="text" name="status" value="{{$information->status}}" class="form-control">
                            @error('status')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <x-form.input name="upload_resume" label="Upload Resume" type="file"/>
                        </div>
                    </div>

                    <div>
                        <button class="btn btn-primary" type="submit">{{__('update Information')}}</button>
                    </div>
                </form>
           </div>
        </div>
    </div>
</div>

@endsection