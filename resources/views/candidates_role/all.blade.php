@extends('layouts.main')

@push('page-title')
    <title>All Candidates</title>
@endpush

@push('heading')
    {{ 'Candidates Roles' }}
@endpush

@section('content')
    @push('style')
    @endpush

    <x-status-message />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="justify-content-end d-flex">
                    <x-search.table-search action="{{ route('candidatesRoles') }}" method="get" name="search"
                        value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" btnClass="search_btn"
                        catVal="{{ request('category') }}" />
                </div>

                <div class="card-body text-center">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap mx-auto w-75"
                            style="border-collapse: collapse; border-spacing: 0;">
                            <thead>
                                <tr>
                                    <th>{{ 'Candidate Role' }}</th>
                                    <th>{{ 'Status' }}</th>
                                    <th>{{ 'Actions' }}</th>

                                </tr>
                            </thead>

                            <tbody id="candidatesData">
                                @foreach ($candidatesRoles as $role)
                                    <tr>
                                        <td>{{ $role->candidate_role }}</td>
                                        <td>
                                            @if ($role->status == 'active')
                                                {{-- <span class="badge badge-info text-info">Active</span> --}}
                                                <strong class="text-info">Active</strong>
                                            @else
                                                <strong class="text-dark">Inactive</strong>
                                            @endif
                                        </td>

                                        </td>
                                        <td>
                                            <div class="action-btns text-center" role="group">

                                                <a href="{{ route('candidatesRole.edit', ['candidatesRole' => $role->id]) }}"
                                                    class="btn btn-info waves-effect waves-light edit">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                <a href="{{ route('candidatesRole.delete', ['candidatesRole' => $role->id]) }}"
                                                    class="btn btn-danger waves-effect waves-light del"
                                                    onclick="return confirm('Are you sure delete this record!')">
                                                    <i class="ri-delete-bin-line"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $candidatesRoles->onEachSide(5)->links() }}
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@push('script')
@endpush
