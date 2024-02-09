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
                <div class="row container mt-4 mb-4" style="display:flex; justify-content: space-between;">
                    <div class="col-6">
                        <a href="{{ route('users.create') }}" class="btn btn-success btn-sm mt-4">Create New user</a>
                    </div>
                    <div class="col-4 d-flex">
                        <x-search.table-search action="#" method="get" name="search"
                            value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" btnClass="search_btn"
                            catVal="{{ request('category') }}" />
                    </div>
                </div>


                <div class="card-body text-center">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap mx-auto w-75"
                            style="border-collapse: collapse; border-spacing: 0;">
                            <thead>
                                <tr>
                                    <th>{{ 'Name' }}</th>
                                    <th>{{ 'Email' }}</th>
                                    <th>{{ 'Role' }}</th>
                                    <th>{{ 'Actions' }}</th>

                                </tr>
                            </thead>

                            <tbody id="candidatesData">
                                @foreach ($data as $key => $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if (!empty($user->getRoleNames()))
                                                @foreach ($user->getRoleNames() as $v)
                                                    <label class="badge bg-success">{{ $v }}</label>
                                                @endforeach
                                            @endif
                                        </td>

                                        </td>
                                        <td>
                                            <div class="action-btns text-center" role="group">

                                                <a href="{{ route('users.show',$user->id) }}"
                                                    class="btn btn-primary waves-effect waves-light view">
                                                    <i class="ri-eye-line"></i>
                                                </a>

                                                <a href="{{ route('users.edit',$user->id) }}"
                                                    class="btn btn-info waves-effect waves-light edit">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                <form method="POST" action="{{ route('users.destroy', $user->id) }}" style="display:inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger waves-effect waves-light del" onclick="return confirm('Are you sure you want to delete this record?')">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>

                                                <a href="{{route('user.info',['user_id'=>$user->id])}}" class="btn btn-warning">{{'Informations'}}</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $data->onEachSide(5)->links() }}
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@push('script')
@endpush
