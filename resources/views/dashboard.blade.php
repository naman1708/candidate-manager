@extends('layouts.main')

@push('page-title')
    <title>{{'Dashboard'}}</title>
@endpush

@push('heading')
    {{ 'Dashboard'}}
@endpush

@section('content')


{{-- quick info --}}
<div class="row">
    <x-design.card heading="Total Candidates" value="{{$total['count']}}" desc="User"/>
    <x-design.card heading="Total Candidates Role" value="{{$total['candidateRole']}}" icon="mdi-account-convert" desc="User"/>
    {{-- <x-design.card heading="Total"  value="99" desc="Returned to Investors"/> --}}
    {{-- <x-design.card heading="Total Pending"  value="99" color="primary" desc="Remaining amount to pay"/>
    <x-design.card heading="{{date('F')}} Payout"  value="99" color="danger" desc="Remaining Payout"/> --}}
</div>

@endsection
