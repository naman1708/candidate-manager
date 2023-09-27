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

<div class="row">
    <div class="col-12">
        <div class="card">
            <x-search.table-search action="{{ route('informations') }}" method="get" name="search"  value="{{isset($_REQUEST['search'])?$_REQUEST['search']:''}}"
            btnClass="search_btn"/>
            <div class="card-body">
                <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>{{ 'Candidate Name' }}</th>
                            <th>{{ 'Email' }}</th>
                            <th>{{ 'Role' }}</th>
                            <th>{{ 'Experience' }}</th>
                            <th>{{ 'Actions' }}</th>
                            <th>{{ 'Download Resume' }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($informations as $info)
                        <tr>
                            <td>{{ $info->candidate_name }}</td>
                            <td>{{ $info->email }}</td>
                            <td>{{ $info->categories }}</td>
                            <td>{{ $info->experience }}</td>
                            <td>
                                <div class="action-btns text-center" role="group">

                                    <a href="{{ route('informations.view',['informations'=> $info->id ]) }}" class="btn btn-primary waves-effect waves-light view">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('informations.edit',['informations'=> $info->id ]) }}" class="btn btn-info waves-effect waves-light edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <a href="{{ route('informations.delete',['informations'=> $info->id ]) }}" class="btn btn-danger waves-effect waves-light del" onclick="return confirm('Are you sure delete this record!')">
                                        <i class="ri-delete-bin-line"></i>
                                    </a>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('download.resume', $info->id) }}" target="_blank" class="btn btn-primary">Download</a>
                            </td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $informations->onEachSide(5)->links() }}
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection

@push('script')

@endpush