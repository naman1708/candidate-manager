@extends('layouts.main')

@push('page-title')
<title>{{ __('Add New Candidates Role')}}</title>
@endpush

@push('heading')
{{ __('Add New Candidates Role') }}
@endpush

@section('content')

<x-status-message/>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <form method="post" action="{{route('candidatesRole.save')}}" enctype="multipart/form-data">
                    @csrf
                    <h4 class="card-title mb-3">{{__('Candidates Role Details')}}</h4>

                    <div class="row">
                        <div class="col-lg-12">
                           <x-form.input name="candidate_role" label="Candidate Role"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <x-form.select name="role_status" label="Status" chooseFileComment="--Select status--" :options="[
                              'active' => 'Active',
                                'inactive' => 'Inactive',
                            ]"/>
                        </div>
                    </div>

                    <div>
                        <button class="btn btn-primary mt-2" type="submit">{{__('Add Candidate Role')}}</button>
                    </div>
                </form>
           </div>
        </div>
    </div>
</div>

@endsection
