@extends('layouts.main')

@push('page-title')
    <title>All Informations</title>
@endpush

@push('heading')
    {{ 'Informations' }}
@endpush

@section('content')
    @push('style')
    @endpush
    <x-status-message />

    <a href="{{route('users.index')}}" class="btn btn-warning">{{'Back'}}</a>

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    <form action="{{ route('user.info',['user_id'=>$user->id]) }}" method="get">

                        <div class="row">

                            <div class="col-lg-3 mt-lg-4">
                            </div>

                            <div class="col-lg-3">
                            </div>

                            <div class="col-lg-4 mt-lg-4">
                                <input type="search" name="search" placeholder="Search....." class="form-control" value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" >
                            </div>

                            <div class="col-lg-2 mt-lg-4">
                                <input type="submit" class="btn btn-info" value="Filter">
                                <a href="{{ route('user.info',['user_id'=>$user->id]) }}" class="btn btn-success">{{ 'Reset' }}</a>
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

                                @foreach ($informationData as $detail)
                                    <tr>
                                        <td>{{ $informationData->perPage() * ($informationData->currentPage() - 1) + $loop->index + 1 }}
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
                        {{ $informationData->appends(request()->query())->links() }}
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@push('script')
@endpush
