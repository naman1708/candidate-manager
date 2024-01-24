@extends('layouts.main')

@push('page-title')
    <title>{{ 'Dashboard' }}</title>
@endpush

@push('heading')
    {{ 'Dashboard' }}
@endpush

@section('content')
    {{-- quick info --}}
    <div class="row">
        <x-design.card heading="Total Candidates" value="{{ $total['candidate'] }}" desc="User" />
        <x-design.card heading="Total Candidates Role" value="{{ $total['candidatesRole'] }}" icon="mdi-account-convert"
            desc="User" />
    </div>


    <h4 class="card-title mt-4 mb-4">{{ __(' Candidate Schedule Interview List ') }}</h4>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <form action="{{ route('dashboard') }}" method="get">
                    <div class="row m-2">

                        <div class="col-lg-3">
                            <x-form.input name="from_date" label="Interview Date From" type="date"
                                value="{{ isset($_REQUEST['from_date']) ? $_REQUEST['from_date'] : '' }}" />
                        </div>

                        <div class="col-lg-3">
                            <x-form.input name="to_date" label="Date To" type="date"
                                value="{{ isset($_REQUEST['to_date']) ? $_REQUEST['to_date'] : '' }}" />
                        </div>

                        <div class="col-lg-4 mt-lg-4 my-search">
                            <input type="search" name="search" id="search" placeholder="Search....."
                                class="form-control" value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}">
                        </div>

                        <div class="col-lg-2 mt-lg-4">
                            <input type="submit" class="btn btn-primary" value="Filter">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Reset</a>
                        </div>

                    </div>
                </form>

                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>{{ '#' }}</th>
                                <th>{{ 'Interview Date & Time' }}</th>
                                <th>{{ 'Candidate Name' }}</th>
                                <th>{{ 'Email' }}</th>
                                <th>{{ 'Phone' }}</th>
                                <th>{{ 'Role' }}</th>
                                <th>{{ 'Interview Status' }}</th>
                                <th>{{ 'Actions' }}</th>
                            </tr>
                        </thead>

                        <tbody id="candidatesData">
                            @foreach ($total['pendingInterviews'] as $status)
                                <tr>
                                    <td>{{ ($total['pendingInterviews']->currentPage() - 1) * $total['pendingInterviews']->perPage() + $loop->index + 1 }}
                                    </td>
                                    <td>{{ $status->getInterviewDateAtAttribute($status->interview_date) }},
                                        {{ \Carbon\Carbon::parse($status->interview_time)->format('h:i A') }}</td>
                                    <td>{{ $status->candidate->candidate_name }}
                                    </td>
                                    <td>{{ $status->candidate->email }}</td>
                                    <td>{{ $status->candidate->contact }}</td>
                                    <td>{{ isset($status->candidate->candidateRole->candidate_role) ? $status->candidate->candidateRole->candidate_role : '-' }}
                                    </td>
                                    <td>{{ $status->interview_status }}</td>
                                    <td>
                                        <a href="{{ route('candidate.view', ['candidate' => $status->candidate->id]) }}"
                                            class="btn btn-info">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $total['pendingInterviews']->appends(request()->query())->links() }}

            </div>
        </div> <!-- end col -->
    </div>
@endsection
