@extends('layouts.main')

@push('page-title')
    <title>{{ 'Dashboard' }}</title>
@endpush

@push('heading')
    {{ 'Today Uploade Candidate List' }}
@endpush

@section('content')
<div class="m-4 mt-4">
    <a href="{{route('dashboard')}}" class="btn btn-info waves-effect waves-light edit float-end">{{ 'Back' }} <i class="fa fa-backward"></i></a>
</div>
    <h4 class="card-title mt-4 mb-4">{{ __('Candidates List ') }}</h4>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('currentDateUploadedCandidates') }}" method="get">
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
                            <select name="candidate_role" id="candidate_role" class="form-control">
                                <option value="">All</option>
                                @foreach ($candidateRole as $role)
                                    <option value="{{ $role->id }} "
                                        {{ isset($_REQUEST['candidate_role']) && $_REQUEST['candidate_role'] == $role->id ? 'selected' : '' }}>

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
                            <input type="text" name="search" id="search" class="form-control"
                                placeholder="Search....."
                                value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" />
                        </div>

                        <div class="col-2">
                            <button type="submit" class="btn btn-primary mt-lg-4">{{ 'Filter' }}</button>
                            <a href="{{ route('currentDateUploadedCandidates') }}" class="btn btn-secondary mt-lg-4">{{ 'Reset' }}</a>
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
                                    <td>{{ $candidate->interview_status_tag ?? 'No Status' }}</td>
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
