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
                <form action="{{ route('candidate.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row justify-content container mt-4">
                        {{-- Filter role div start --}}
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="">Role Filter</label>
                                <select name="candidate_role_id" id="candidate_role_id" class="form-control">
                                    <option value="">All</option>
                                    @foreach ($candidateRole as $role)
                                        <option value="{{ $role->candidate_role }}"
                                            {{ request('candidate_role') == $role->candidate_role ? 'selected' : '' }}>
                                            {{ $role->candidate_role }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div> {{-- Filter role div end --}}

                        {{-- IMPORT EXPORT CANDIDATE --}}
                        <div class="col-lg-4">
                            <x-form.input name="file" label="Upload Excel File" type="file" />
                        </div>

                        <div class="col-lg-2 mt-lg-4">
                            <button class="btn btn-info mb-lg-4" type="submit">{{ __('File Import') }}</button>
                        </div>
                        <div class="col-lg-2 mt-lg-4">
                            <a href="{{ route('candidate.export') }}" class="btn btn-success">{{ __('File Export') }}</a>
                        </div>
                        {{-- IMPORT EXPORT CANDIDATE end div --}}

                    </div>
                </form>

                {{-- Search Query Start --}}
                <div class="float-right container">
                    <x-search.table-search action="{{ route('candidates') }}" method="get" name="search"
                        value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" btnClass="search_btn"
                        catVal="{{ request('candidate_role') }}" roleName="candidate_role" />
                </div>
                {{-- Search Query End --}}
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
                                    <td class="text-center">
                                        <a href="{{ route('download.resume', ['resume' => $info->id]) }}"
                                            class="btn btn-primary btn-sm">Download</a>

                                    </td>

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
    <script>
        $(document).ready(function() {
            $('#candidate_role_id').on("change", function() {
                let category = $(this).val()
                let url = "{{ route('candidates') }}"
                location.href = `${url}?candidate_role=${category}`

            })
        });
    </script>
@endpush
