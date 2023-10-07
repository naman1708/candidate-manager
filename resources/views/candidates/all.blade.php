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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="row container mt-4 mb-4" style="display:flex; justify-content: space-between;">

                    <div class="col-lg-5 mx-1">
                        <x-form.select label="Role Filter" chooseFileComment="All" name="candidate_role_id" :options="$candidateRole"
                            :selected="$selectedRole" />
                    </div>
                    <div class="col-lg-4 mt-1 mr-3">
                        <x-search.table-search action="{{ route('candidates') }}" method="get" name="search"
                            value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" btnClass="search_btn"
                            catVal="{{ request('candidate_role') }}" roleName="candidate_role" />
                    </div>
                </div>

                <div class="card-body">
                    <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>{{ 'Candidate Name' }}</th>
                                <th>{{ 'Email' }}</th>
                                <th>{{ 'Phone' }}</th>
                                <th>{{ 'Role' }}</th>
                                <th>{{ 'Experience' }}</th>
                                <th>{{ 'Actions' }}</th>
                                <th>{{ 'Download Resume' }}</th>
                            </tr>
                        </thead>

                        <tbody id="candidatesData">
                            @foreach ($candidates as $info)
                                <tr>
                                    <td>{{ $info->candidate_name }}</td>
                                    <td>{{ $info->email }}</td>
                                    <td>{{ $info->contact }}</td>
                                    <td>{{ $info->candidateRole->candidate_role }}</td>
                                    <td>{{ $info->experience }}</td>
                                    <td>
                                        <div class="action-btns text-center" role="group">
                                            <a href="{{ route('candidate.view', ['candidate' => $info->id]) }}"
                                                class="btn btn-primary waves-effect waves-light view">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('candidate.edit', [$info->id]) }}"
                                                class="btn btn-info waves-effect waves-light edit">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            <a href="{{ route('candidate.delete', [$info->id]) }}"
                                                class="btn btn-danger waves-effect waves-light del"
                                                onclick="return confirm('Are you sure delete this record!')">
                                                <i class="ri-delete-bin-line"></i>
                                            </a>
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
                    {{ $candidates->onEachSide(5)->links() }}
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@push('script')
    {{-- <script>
        $(document).ready(function() {
            $('#candidate_role_id').on("change", function() {
                let category = $(this).val()
                let url = "{{ route('candidates') }}"
                location.href = `${url}?candidate_role=${category}`

            })
        });
    </script> --}}


    <script>
        $(document).ready(function() {
            $('#candidate_role_id').on("change", function() {
                let category = $(this).val();
                let url = "{{ route('candidates') }}";

                // Append the role filter to the existing search query parameters
                let search = new URLSearchParams(window.location.search);
                search.set('candidate_role', category);

                // Redirect to the updated URL
                window.location.href = `${url}?${search.toString()}`;
            });
        });
    </script>
@endpush
