@extends('layouts.main')

@push('page-title')
<title>{{ __('Add Managers')}}</title>
@endpush

@push('heading')
{{ __('Add Managers') }}
@endpush

@section('content')

<x-status-message/>

<a href="{{route('users.index')}}" class="btn btn-warning btn-sm m-2"> <i class="fa fa-backward"></i> {{'Back'}}</a>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <form method="post" action="{{route('users.store')}}" enctype="multipart/form-data">
                    @csrf
                    <h4 class="card-title mb-3">{{__('Managers Details')}}</h4>

                    <div class="row">
                        <div class="col-lg-6">
                           <x-form.input name="name" label="Name"/>
                        </div>
                        <div class="col-lg-6">
                            <x-form.input name="email" label="Email Address"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <x-form.input name="password" label="Passsword" type="password"/>
                        </div>

                        <div class="col-lg-6">
                            <x-form.input name="confirm-password" label="Confirm Password" type="password"/>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-6">
                            <x-form.select label="Role" chooseFileComment="--Select Role--"
                                name="roles" :options="$roles" />
                        </div>

                        <div class="col-lg-6">
                            <x-form.select name="customer_status" label="Status"
                                chooseFileComment="--Select Status--" :options="[
                                    'active' => 'Active',
                                    'block' => 'Block',
                                ]" />
                        </div>

                    </div>

                    <div>
                        <button class="btn btn-primary mt-2" type="submit">{{__('Add User')}}</button>
                    </div>
                </form>
           </div>
        </div>
    </div>
</div>

@endsection
