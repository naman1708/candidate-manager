@extends('layouts.main')

@push('page-title')
    <title>{{ __('View Informations') }}</title>
@endpush

@push('heading')
    {{ __('View Informations -') }}  {{$information->portal_name ?? ''}}
@endpush

@section('content')

    <x-status-message />

    <a href="{{ url()->previous() }}" class="btn btn-warning"><i class="fa fa-backward"></i> {{ 'Back' }}</a>

    <div class="row mt-2">
        <div class="col-lg-12">
            <div class="card border border-secondary rounded">
                <div class="card-header d-flex justify-content-between">
                    <h5>{{ 'View Details' }}</h5>

                    <a href="{{route('information.edit',['information'=>$information->id])}}" class="float-end btn btn-info">{{ 'Edit Information' }}</a>
                </div>

                <div class="card-body">
                    <div class="row mt-lg-12">

                        <div class="col-6">
                            <strong>Portal Name:</strong>
                            <span>
                                {{ isset($information->portal_name) ? $information->portal_name : 'No Portal Name' }}
                            </span>
                        </div>


                        <div class="col-6">
                            <strong>Created By :</strong>
                            <strong>
                                {{ isset($information->manager->name) ? $information->manager->name : 'No Manager' }}
                            </strong>
                        </div>

                        <hr>

                        <div class="col-4 mt-2">
                            <strong>Username :</strong>
                            <span>
                                {{ isset($information->username) ? $information->username : 'Username' }}
                            </span>
                        </div>

                        <div class="col-4">
                            <strong>Password :</strong>
                            <strong>
                                {{ isset($information->password) ? Str::ucfirst($information->password) : 'No Password' }}
                            </strong>
                        </div>

                        <div class="col-4">
                            <strong>Phone Number :</strong>
                            <strong>
                                {{ isset($information->phone_number) ? $information->phone_number : 'No Phone Number' }}
                            </strong>
                        </div>
                        <hr>

                        <div class="col-12 mt-2 p-2">
                            <strong>{{ 'Security Question :' }}</strong>
                            <strong>
                                {{ isset($information->security_question) ? Str::ucfirst($information->security_question) : 'No question' }}
                            </strong>
                        </div>
                        <hr>

                        <div class="col-12 mt-2 p-2">
                            <strong>{{ 'Security Answer :' }}</strong>
                            <strong>
                                {{ isset($information->security_answer) ? Str::ucfirst($information->security_answer) : 'No answer' }}
                            </strong>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script></script>
@endpush
