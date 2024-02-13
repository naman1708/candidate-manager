@extends('layouts.main')
@push('page-title')
    <title>{{ 'Managers - ' }} {{ $user->name }}</title>
@endpush

@push('heading')
    {{ 'Managers Detail' }}
@endpush

@push('heading-right')
@endpush

@section('content')

    {{-- Managers details --}}
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <h5 class="card-header">{{ 'Managers Details' }}</h5>
                <div class="card-body">

                    <h5 class="card-title">
                        <span>Name :</span>
                        <span>
                            {{ $user->name }}
                        </span>
                    </h5>
                    <hr>

                    <h5 class="card-title">
                        <span>Email :</span>
                        <span>
                            {{ $user->email }}
                        </span>
                    </h5>
                    <hr>


                    <h5 class="card-title">
                        <span>Role :</span>
                            @if (!empty($user->getRoleNames()))
                                @foreach ($user->getRoleNames() as $v)
                                    <label class="badge bg-success">{{ $v }}</label>
                                @endforeach
                            @endif

                    </h5>
                    <hr>

                    <h5 class="card-title">
                        <span>Status :</span>
                        @if ($user->status == 'active')
                        <b class="btn btn-primary btn-sm">{{'Active'}}</b>
                        @else
                        <b class="btn btn-danger btn-sm">{{'Block'}}</b>
                        @endif
                    </h5>
                    <hr>


                </div>
            </div>
        </div>


    </div>

@endsection
