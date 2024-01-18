@extends('layouts.main')

@push('page-title')
    <title>All Candidates</title>
@endpush

@push('heading')
    {{ 'Candidates' }}
@endpush

@section('content')
    @push('style')
    @endpush

    <x-status-message />
    <div id="export-result"></div>
    <div class="row">
        <div class="col-12">
            <div class="card">

                {{-- Date Filter --}}
                <form action="{{ route('candidates') }}" method="get">
                    <div class="row m-2">
                        <div class="col-lg-2">
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
                            <label for="from_date">{{ 'Date From' }}</label>
                            <input type="date" name="from_date" id="from_date" class="form-control"
                            value="{{ isset($_REQUEST['from_date']) ? $_REQUEST['from_date'] : '' }}" />
                        </div>

                        <div class="col-lg-2">
                            <label for="to_date">{{ 'Date To' }}</label>
                            <input type="date" name="to_date" id="to_date" class="form-control"
                            value="{{ isset($_REQUEST['to_date']) ? $_REQUEST['to_date'] : '' }}"  />
                        </div>

                        <div class="col-4">
                            <label for="search">{{ 'Search' }}</label>
                            <input type="text" name="search" id="search" class="form-control"
                                placeholder="Search....." value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" />
                        </div>

                        <div class="col-2">
                            <button type="submit" class="btn btn-primary mt-lg-4">{{ 'Filter' }}</button>
                            <button type="reset" class="btn btn-secondary mt-lg-4">{{ 'Reset' }}</button>
                        </div>
                    </div>
                </form>


                <div class="card-body">

                    @role('admin')
                    <form action="{{ route('candidate.selectedCandidateExport') }}" method="post" id="exportForm">
                        @csrf
                        <input type="hidden" name="selectedCandidates" id="selected-candidates" value="">
                        <button type="button" id="export-selected-button" class="btn btn-info btn-sm">Export Selected
                            Candidates</button>
                    </form>
                    @endrole

                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    @role('admin')<th><input class="form-check-input master-checkbox" type="checkbox" value=""
                                            id="" name=""></th>
                                    <th>{{ 'Candidate Name' }}</th> @endrole
                                    <th>{{ 'Email' }}</th>
                                    <th>{{ 'Phone' }}</th>
                                    <th>{{ 'Role' }}</th>
                                    <th>{{ 'Experience' }}</th>
                                    <th>{{ 'Date' }}</th>
                                    <th>{{ 'Actions' }}</th>
                                    <th>{{ 'Download Resume' }}</th>
                                </tr>
                            </thead>

                            <tbody id="candidatesData">
                                @foreach ($candidates as $info)
                                    <tr>
                                        @role('admin')  <td> <input type="checkbox"
                                                class="candidate-checkbox form-check-input child-checkbox"
                                                data-candidate-id="{{ $info->id }}"></td> @endrole
                                        <td>{{ $info->candidate_name }}</td>
                                        <td>{{ $info->email }}</td>
                                        <td>{{ $info->contact }}</td>
                                        <td>{{ $info->candidateRole->candidate_role ?? 'Null' }}</td>
                                        <td>{{ $info->experience }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($info->date)->format('d-M-Y') }}
                                        </td>
                                        <td>
                                            <div class="action-btns text-center" role="group">
                                                <a href="{{ route('candidate.view', ['candidate' => $info->id]) }}"
                                                    class="btn btn-primary waves-effect waves-light view">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                @can('candidate-edit')
                                                    <a href="{{ route('candidate.edit', [$info->id]) }}"
                                                        class="btn btn-info waves-effect waves-light edit">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                @endcan
                                                @can('candidate-delete')
                                                    <a href="{{ route('candidate.delete', [$info->id]) }}"
                                                        class="btn btn-danger waves-effect waves-light del"
                                                        onclick="return confirm('Are you sure delete this record!')">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </a>
                                                @endcan
                                            </div>
                                        </td>
                                        @if (!empty($info->upload_resume))
                                            @php
                                                $resumePath = $info->upload_resume;
                                            @endphp
                                            @if (Storage::disk('local')->exists($resumePath))
                                                <td class="text-center">
                                                    <a href="{{ route('download.resume', ['resume' => $info->id]) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="ri-download-cloud-fill"></i>
                                                    </a>
                                                </td>
                                            @endif
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $candidates->appends(request()->query())->links() }}

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@push('script')
    {{-- File export script --}}
    <script>
        $(document).ready(function() {
            $('.master-checkbox').click(function() {
                $('.child-checkbox').prop('checked', this.checked);
            });
            $('.child-checkbox').click(function() {
                if ($('.child-checkbox:checked').length === $('.child-checkbox').length) {
                    $('.master-checkbox').prop('checked', true);
                } else {
                    $('.master-checkbox').prop('checked', false);
                }
            });

            $('#export-selected-button').click(function() {
                var selectedCandidates = [];
                $('.candidate-checkbox:checked').each(function() {
                    selectedCandidates.push($(this).data('candidate-id'));
                });

                if (selectedCandidates.length === 0) {
                    alert("Please select at least one candidate.");
                } else {
                    $('#selected-candidates').val(selectedCandidates);
                    $('#exportForm').submit();
                }
            });

        });
    </script>
@endpush
