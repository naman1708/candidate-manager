@extends('layouts.main')

@push('page-title')
<title>{{ __('Edit Candidate')}}</title>
@endpush

@push('heading')
{{ 'Edit Candidate' }} : {{$candidatesRole->candidate_role}}
@endpush

@section('content')

<x-status-message/>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <form method="post" action="{{ route('candidatesRole.update',[$candidatesRole->id]) }}" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="" value="{{$candidatesRole->id}}">
                    @csrf
                    <h4 class="card-title mb-3">{{__('Candidates Role Details')}}</h4>

                    <div class="row">
                        <div class="col-lg-12">
                           <x-form.input name="candidate_role" label="Candidate Role" :value="$candidatesRole->candidate_role" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <x-form.select name="role_status" label="Status" :options="[
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ]" :selected="$candidatesRole->status" />
                        </div>
                    </div>

                    <div>
                        <button class="btn btn-primary mt-2" type="submit">{{__('Update Candidate Role')}}</button>
                    </div>
                </form>
           </div>
        </div>
    </div>
</div>

@endsection
