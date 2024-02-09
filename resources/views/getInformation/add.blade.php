@extends('layouts.main')

@push('page-title')
    <title>{{ __('Get Informations') }}</title>
@endpush

@push('heading')
    {{ __('Get Informations') }}
@endpush

@section('content')
    <x-status-message />

    <a href="{{ url()->previous() }}" class="btn btn-warning"><i class="fa fa-backward"></i> {{ 'Back' }}</a>
    <div class="row">

        <div class="col-lg-12">
            <div class="justify-content-end mt-4">
                {{-- <button class="btn btn-info add-more float-end" type="button"><i class="fa fa-plus"></i>
                    {{ __('Add More') }}</button> --}}
                <h4>{{ __('Details') }}</h4>
            </div>

            <div class="card shadow mt-4">
                <div class="card-body">

                    <form id="detailsForm" method="post" action="{{ route('information.store') }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="details-container">

                            <div class="details-set">
                                <div class="row">

                                    <div class="col-lg-6">
                                        <x-form.input name="portal_name[]" label="Portal Name"/>
                                    </div>

                                    <div class="col-lg-6">
                                        <x-form.input name="phone_number[]" label="Phone Number" />
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <x-form.input name="username[]" label="Username" type="email" />
                                    </div>

                                    <div class="col-lg-6">
                                        <x-form.input name="password[]" label="Password" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <x-form.textarea name="security_question[]" label="Security Question" />
                                    </div>

                                    <div class="col-lg-12">
                                        <x-form.textarea name="security_answer[]" label="Security Answer" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-info add-more" type="button"><i class="fa fa-plus"></i>
                            {{ __('Add More') }}</button>
                        <button class="btn btn-primary" type="submit">{{ __('Save Details') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Add more fields
            $(".add-more").click(function() {
                var newSet = `
                <div class="details-set mt-4">
                    <div class="m-1">
                        <button class="btn btn-danger remove" type="button">{{ __('Remove') }}</button>
                     </div>

                    <div class="row mt-4">
                        <div class="col-lg-6">
                            <x-form.input name="portal_name[]" label="Portal Name" />
                        </div>
                        <div class="col-lg-6">
                            <x-form.input name="phone_number[]" label="Phone Number" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <x-form.input name="username[]" label="Username" type="email" />
                        </div>
                        <div class="col-lg-6">
                            <x-form.input name="password[]" label="Password" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <x-form.textarea name="security_question[]" label="Security Question" />
                        </div>
                        <div class="col-lg-12">
                            <x-form.textarea name="security_answer[]" label="Security Answer" />
                        </div>
                    </div>
                </div>

            `;
                $(".details-container").append(newSet);
            });

            // Remove fields
            $("body").on("click", ".remove", function() {
                $(this).closest(".details-set").remove();
            });
        });
    </script>
@endpush
