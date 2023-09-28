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
                <div class="w-25" style="padding-left: 30px;">
                    <x-form.select name="categories" label="Select Role" :options="[
                        '' => 'All',
                        'PHP' => 'PHP',
                        'Front end' => 'Front end',
                        'Freshers' => 'Freshers',
                        'HR' => 'HR',
                        'BDE' => 'BDE',
                    ]"
                    :selected="request('category')"/>
                </div>

                <div class="float-right">
                    <x-search.table-search action="{{ route('candidates') }}" method="get" name="search"
                        value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" btnClass="search_btn" catVal="{{request('category')}}"/>
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
                                    <td>{{ $info->categories }}</td>
                                    <td>{{ $info->experience }}</td>
                                    <td>
                                        <div class="action-btns text-center" role="group">

                                            <a href="{{ route('candidate.view', [$info->id]) }}"
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
                                    <td>
                                        <a href="{{ route('download.resume', ['resume' => $info->id]) }}" target="_blank"
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
    $(document).ready(function () {
        $('#categories').on("change",function(){
            let category = $(this).val()
            let url = "{{route('candidates')}}"
            location.href = `${url}?category=${category}`
           
        })
    });
</script>
@endpush
