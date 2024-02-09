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

                <div class="card-body">
                    <form action="{{ route('informations') }}" method="get">

                        <div class="row">

                            <div class="col-lg-3 mt-lg-4">
                                <a href="{{ route('information.create') }}" class="btn btn-primary"> <i
                                        class="fa fa-plus"></i> {{ 'Add' }}</a>
                            </div>

                            <div class="col-lg-3">
                                @role('admin')
                                    <x-form.select label="Mangers Filter" chooseFileComment="All" name="manager"
                                        :options="$managers" :selected="isset($_REQUEST['manager']) ? $_REQUEST['manager'] : ''" />
                                @endrole
                            </div>

                            <div class="col-lg-4 mt-lg-4">
                                <input type="search" name="search" placeholder="Search....." class="form-control" value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" >
                            </div>

                            <div class="col-lg-2 mt-lg-4">
                                <input type="submit" class="btn btn-info" value="Filter">
                                <a href="{{ route('informations') }}" class="btn btn-success">{{ 'Reset' }}</a>
                            </div>

                        </div>
                    </form>


                    <div class="table-responsive mt-lg-2">
                        <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>{{ '#' }}</th>
                                    <th>{{ 'Portal Name' }}</th>
                                    <th>{{ 'Username' }}</th>
                                    <th>{{ 'Password' }}</th>
                                    <th>{{ 'Phone Number' }}</th>
                                    <th>{{ 'Created By' }}</th>
                                    <th>{{ 'Action' }}</th>

                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($informations as $detail)
                                    <tr>
                                        <td>{{ $informations->perPage() * ($informations->currentPage() - 1) + $loop->index + 1 }}
                                        </td>

                                        <td>{{ isset($detail->portal_name) ? $detail->portal_name : 'No Portal Name' }}
                                        </td>
                                        <td>{{ $detail->username ?? 'No Username' }}</td>
                                        <td>{{ $detail->password ?? 'No Password' }}</td>
                                        <td>{{ $detail->phone_number ?? 'No Phone Number' }}</td>
                                        <td>{{ $detail->manager->name ?? 'No Manager' }}</td>

                                        <td>
                                            <a href="{{ route('information.show', ['information' => $detail->id]) }}"
                                                class="btn btn-primary"><i class="fa fa-eye"></i></a>

                                            <a href="{{ route('information.delete', ['information' => $detail->id]) }}"
                                                class="btn btn-danger"
                                                onclick="return confirm('Are you sure delete this details!')"><i
                                                    class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        {{ $informations->appends(request()->query())->links() }}
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@push('script')
@endpush
