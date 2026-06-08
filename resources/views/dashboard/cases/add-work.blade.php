@extends('layouts.master')
@section('title', 'Add ' . ucfirst($workType) . ' - Case #' . $case->case_no)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header bg-label-primary">
            <h5 class="mb-0">
                Add {{ ucfirst($workType) }} Details
                <small class="text-muted">— Case #{{ $case->case_no }}</small>
            </h5>
        </div>

        <div class="card-body">
            <form action="{{ route('dashboard.cases.add-work', ['case' => $case->id, 'workType' => $workType]) }}"
                  method="POST">
                @csrf

                @if($workType === 'transfer')
                    @include('dashboard.cases.partials.transfer-form')
                @elseif($workType === 'alteration')
                    @include('dashboard.cases.partials.alteration-form')
                @elseif($workType === 'tax')
                    @include('dashboard.cases.partials.tax-form')
                @elseif($workType === 'insurance')
                    @include('dashboard.cases.partials.insurance-form')
                @elseif($workType === 'permit')
                    @include('dashboard.cases.partials.permit-form')
                @elseif($workType === 'fitness')
                    @include('dashboard.cases.partials.fitness-form')
                @endif

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">Save {{ ucfirst($workType) }}</button>
                    <a href="{{ route('dashboard.cases.next-steps', $case->id) }}"
                       class="btn btn-label-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
