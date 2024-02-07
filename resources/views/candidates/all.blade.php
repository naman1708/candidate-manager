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
                    <div class="row m-3">

                        @role('admin')
                            <div class="col-lg-4">
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

                        <div class="col-lg-4">
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

                        <div class="col-lg-4">
                            <x-form.select name="tag" label="Tag Filter" chooseFileComment="All" :options="[
                                'interview scheduled' => 'Interview Scheduled',
                                'interviewed' => 'Interviewed',
                                'selected' => 'Selected',
                                'rejected' => 'Rejected',
                            ]"
                                :selected="isset($_REQUEST['tag']) ? $_REQUEST['tag'] : ''" />
                        </div>




                        <div class="col-lg-3">
                            <label for="from_date">{{ 'Date From' }}</label>
                            <input type="date" name="from_date" id="from_date" class="form-control"
                                value="{{ isset($_REQUEST['from_date']) ? $_REQUEST['from_date'] : '' }}" />
                        </div>

                        <div class="col-lg-3">
                            <label for="to_date">{{ 'Date To' }}</label>
                            <input type="date" name="to_date" id="to_date" class="form-control"
                                value="{{ isset($_REQUEST['to_date']) ? $_REQUEST['to_date'] : '' }}" />
                        </div>

                        <div class="col-3">
                            <label for="search">{{ 'Search' }}</label>
                            <input type="text" name="search" id="search" class="form-control"
                                placeholder="Search....."
                                value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" />
                        </div>

                        <div class="col-2">
                            <button type="submit" class="btn btn-primary mt-lg-4">{{ 'Filter' }}</button>
                            <a href="{{ route('candidates') }}" class="btn btn-secondary mt-lg-4">{{ 'Reset' }}</a>
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
                                    @role('admin')
                                        <th><input class="form-check-input master-checkbox" type="checkbox" value=""
                                                id="" name=""></th>
                                    @endrole
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
                                @foreach ($candidates as $info)
                                    <tr>
                                        @role('admin')
                                            <td> <input type="checkbox"
                                                    class="candidate-checkbox form-check-input child-checkbox"
                                                    data-candidate-id="{{ $info->id }}"></td>
                                        @endrole
                                        <td>{{ $info->candidate_name }}</td>
                                        <td>{{ $info->contact }}</td>
                                        <td>{{ $info->candidateRole->candidate_role ?? 'Null' }}</td>
                                        <td>{{ $info->interview_status_tag ?? 'No Status' }}</td>
                                        <td>{{ $info->experience }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($info->date)->format('d-M-Y') }}
                                        </td>

                                        @role('admin')
                                            <td> {{ isset($info->createby->name) ? Str::ucfirst($info->createby->name) : '' }}
                                            </td>
                                        @endrole

                                        <td>
                                            <a href="{{ route('candidate.view', ['candidate' => $info->id]) }}"
                                                class="btn btn-primary"> <i class="fa fa-eye"></i></a>
                                            @can('candidate-delete')
                                                <a href="{{ route('candidate.delete', [$info->id]) }}"
                                                    class="btn btn-danger del"
                                                    onclick="return confirm('Are you sure delete this record!')"><i
                                                        class="fa fa-trash"></i>
                                                </a>
                                            @endcan

                                            @role('admin')
                                                <a href="javascript:void(0)"
                                                    class="btn btn-warning m-4"onclick="addEditInstruction(<?= $info->id ?>)">{{ 'Instruction' }}</a>
                                            @endrole

                                            @if (!empty($info->upload_resume))
                                                @php
                                                    $resumePath = $info->upload_resume;
                                                @endphp
                                                @if (Storage::disk('local')->exists($resumePath))
                                                    <a href="{{ route('download.resume', ['resume' => $info->id]) }}"
                                                        class="btn btn-success">
                                                        <i class="ri-download-cloud-fill"></i>
                                                    </a>
                                                @endif
                                            @endif
                                        </td>

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


    {{-- Cooment Model Form --}}

    <!-- Modal for adding/editing instructions -->
    <div class="modal fade" id="superadminInstructionModel" tabindex="-1" aria-labelledby="superadminInstructionModelLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('candidateAddEditInstructions', ['candidate_id' => $info->id]) }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addCommentModelLabel">{{ 'Superadmin Instruction' }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="">Instructions <span class="text-danger">*</span></label>
                                <textarea name="superadmin_instruction" id="superadmin_instruction" cols="20" rows="10"
                                    class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Instruction</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@push('script')
    <script>
        function addEditInstruction(candidate_id) {
            // Show the modal
            $('#superadminInstructionModel').modal('show');

            let url = '{{ url('getSuperadminInstruction') }}/' + candidate_id;
            $.get(url, function(data) {

                $('#superadmin_instruction').val(data.superadmin_instruction);

                $('#superadminInstructionModel form').attr('action', '{{ url('candidate-edit-instructions') }}/' +
                    candidate_id);
            });
        }
    </script>

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
