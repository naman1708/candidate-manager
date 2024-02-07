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

    {{-- <div class="m-4 mt-4">
        <a href="{{route('currentDateUploadedCandidates')}}" class="btn btn-info waves-effect waves-light edit float-end">{{ 'Today Uploaded Candidate List' }} <i class="fa fa-eye"></i> </a>
    </div> --}}

    <h5 class="m-4"><b>{{ 'Candidate Schedule Interview List' }}</b></h5>

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


    {{-- Today Uploaded Candidate list --}}
    <h5 class="m-4"><b>{{ 'Today Uploaded Candidate list' }}</b></h5>
    <div class="row">
        <div class="col-lg-12">
            <div class="card p-4">
                <form action="{{ route('dashboard') }}" method="get">
                    <div class="row m-3">
                        @role('admin')
                            <div class="col-lg-2">
                                <label for="">{{ 'Managers Filter' }}</label>
                                <select name="manager" id="manager" class="form-control">
                                    <option value="">All</option>
                                    @foreach ($managers as $mana)
                                        <option value="{{ $mana->id }}"
                                            {{ isset($_REQUEST['manager']) && $_REQUEST['manager'] == $mana->id ? 'selected' : '' }}>
                                            {{ $mana->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endrole

                        <div class="col-lg-3">
                            <label for="candidate_role">{{ 'Role Filter' }}</label>
                            <select name="role" id="candidate_role" class="form-control">
                                <option value="">All</option>
                                @foreach ($candidateRole as $role)
                                    <option value="{{ $role->id }} "
                                        {{ isset($_REQUEST['role']) && $_REQUEST['role'] == $role->id ? 'selected' : '' }}>

                                        {{ $role->candidate_role }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2">
                            <x-form.select name="tag" label="Tag Filter" chooseFileComment="All" :options="[
                                'interview scheduled' => 'Interview Scheduled',
                                'interviewed' => 'Interviewed',
                                'selected' => 'Selected',
                                'rejected' => 'Rejected',
                            ]"
                                :selected="isset($_REQUEST['tag']) ? $_REQUEST['tag'] : ''" />
                        </div>

                        <div class="col-3">
                            <label for="search">{{ 'Search' }}</label>
                            <input type="text" name="iteam" id="iteam" class="form-control"
                                placeholder="Search....."
                                value="{{ isset($_REQUEST['iteam']) ? $_REQUEST['iteam'] : '' }}" />
                        </div>

                        <div class="col-2">
                            <button type="submit" class="btn btn-primary mt-lg-4">{{ 'Filter' }}</button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-lg-4">{{ 'Reset' }}</a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>{{ '#' }}</th>
                                <th>{{ 'Candidate Name' }}</th>
                                <th>{{ 'Phone' }}</th>
                                <th>{{ 'Role' }}</th>
                                <th>{{ 'Tag' }}</th>
                                <th>{{ 'Experience' }}</th>
                                <th>{{ 'Date' }}</th>
                                @role('admin')
                                    <th>{{ 'Createby' }}</th>
                                @endrole
                                <th>{{ 'Actions' }}</th>

                            </tr>
                        </thead>

                        <tbody id="candidatesData">
                            @foreach ($uploadedCandidates as $candidate)
                                <tr>
                                    <td>{{ ($uploadedCandidates->currentPage() - 1) * $uploadedCandidates->perPage() + $loop->index + 1 }}
                                    </td>
                                    <td>{{ $candidate->candidate_name }}</td>
                                    <td>{{ $candidate->contact }}</td>
                                    <td>{{ $candidate->candidateRole->candidate_role ?? 'Null' }}</td>
                                    <td>{{ isset($candidate->interview_status_tag) ? Str::ucfirst($candidate->interview_status_tag) : 'No Status' }}</td>

                                    <td>{{ $candidate->experience }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($candidate->date)->format('d-M-Y') }}
                                    </td>

                                    @role('admin')
                                        <td> {{ isset($candidate->createby->name) ? Str::ucfirst($candidate->createby->name) : '' }}
                                        </td>
                                    @endrole

                                    <td>
                                        <div class="action-btns text-center" role="group">
                                            <a href="{{ route('candidate.view', ['candidate' => $candidate->id]) }}"
                                                class="btn btn-primary waves-effect waves-light view">
                                                <i class="ri-eye-line"></i>
                                            </a>


                                            @if (!empty($candidate->upload_resume))
                                            @php
                                                $resumePath = $candidate->upload_resume;
                                            @endphp
                                            @if (Storage::disk('local')->exists($resumePath))
                                                <a href="{{ route('download.resume', ['resume' => $candidate->id]) }}"
                                                    class="btn btn-success">
                                                    <i class="ri-download-cloud-fill"></i>
                                                </a>
                                            @endif
                                                @endif

                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $uploadedCandidates->appends(request()->query())->links() }}

            </div>
        </div> <!-- end col -->
    </div>

@endsection
