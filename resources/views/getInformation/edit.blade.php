@extends('layouts.main')

@push('page-title')
    <title>{{ __('Get Informations') }}</title>
@endpush

@push('heading')
    {{ __('Get Informations -') }} {{$information->portal_name ?? ''}}
@endpush

@section('content')
    <x-status-message />

    <a href="{{ url()->previous() }}" class="btn btn-warning"><i class="fa fa-backward"></i> {{ 'Back' }}</a>
    <div class="row">

        <div class="col-lg-12">
            <div class="justify-content-end mt-4">

                <h4>{{ __('Details') }}</h4>
            </div>

            <div class="card shadow mt-4">
                <div class="card-body">

                    <form id="detailsForm" method="post" action="{{ route('information.update',['information'=>$information->id]) }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="details-container">

                            <div class="details-set">
                                <div class="row">

                                    <div class="col-lg-6">
                                        <x-form.input name="portal_name" label="Portal Name" value="{{$information->portal_name}}" />
                                    </div>

                                    <div class="col-lg-6">
                                        <x-form.input name="phone_number" label="Phone Number" value="{{$information->phone_number}}" />
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <x-form.input name="username" label="Username" type="email" value="{{$information->username}}" />
                                    </div>

                                    <div class="col-lg-6">
                                        <x-form.input name="password" label="Password" value="{{$information->password}}" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <x-form.textarea name="security_question" label="Security Question" value="{{$information->security_question}}" />
                                    </div>

                                    <div class="col-lg-12">
                                        <x-form.textarea name="security_answer" label="Security Answer" value="{{$information->security_answer}}" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-primary" type="submit">{{ __('Update Details') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
