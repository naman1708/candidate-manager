@extends('layouts.main')

@push('page-title')
<title>{{ __('Add New Informations')}}</title>
@endpush

@push('heading')
{{ __('Add New Informations') }}
@endpush

@section('content')

<x-status-message/>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                
                <form method="post" action="{{route('informations.save')}}" enctype="multipart/form-data">
                    @csrf
                    <h4 class="card-title mb-3">{{__('Personal Details')}}</h4>      
                    
                    <div class="row">
                        <div class="col-lg-6">
                           <x-form.input name="candidate_name" label="Candidate Name"/>
                        </div>
                        <div class="col-lg-6">
                            <x-form.input name="email" label="Email Address"/>
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
                            ]"/>
                        </div>

                        <div class="col-lg-6">
                            <x-form.input name="date" label="Date" type="date"/>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-6">
                            <x-form.input name="source" label="Source" />
                        </div>

                        <div class="col-lg-6">
                            <x-form.input name="experience" label="Experience"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <x-form.input name="salary" label="Salary"/>
                        </div>

                        <div class="col-lg-6">
                            <x-form.input name="expectation" label="Expectation"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <x-form.input name="contact" label="Contact"/>
                        </div>
                        <div class="col-lg-6">
                            {{-- <x-form.input name="status" label="Status" type="text"/> --}}
                            <label for="">Status</label>
                            <input type="text" name="status" value="{{old('status')}}" class="form-control">
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
                        <button class="btn btn-primary" type="submit">{{__('Add Information')}}</button>
                    </div>
                </form>
           </div>
        </div>
    </div>
</div>

@endsection