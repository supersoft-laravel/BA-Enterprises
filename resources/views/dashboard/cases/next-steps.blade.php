@extends('layouts.master')
@section('title', 'Next Steps - Case #' . $case->case_no)

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header bg-label-primary">
                <h5 class="mb-0">
                    <i class="ti ti-list-check me-2"></i>
                    Next Steps for Case #{{ $case->case_no }}
                </h5>
                <p class="mb-0 text-muted">Vehicle: {{ $case->vehicle_reg_no }} • {{ $case->submitted_by }}</p>
            </div>

            <div class="card-body">
                <h6 class="mb-3 text-primary">Already Completed:</h6>
                <ul class="list-group mb-4">
                    @foreach ($doneWorks as $work)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><strong>{{ ucfirst($work) }}</strong></span>
                            <span class="badge bg-success">Done</span>
                        </li>
                    @endforeach
                    @if (empty($doneWorks))
                        <li class="list-group-item text-muted">No work completed yet.</li>
                    @endif
                </ul>

                <h6 class="mb-3 text-primary">Remaining Works (in recommended order):</h6>

                @if (empty($remainingWorks))
                    <div class="alert alert-success">
                        <i class="ti ti-check-circle me-2"></i>
                        All possible works have been completed!
                    </div>
                    <a href="{{ route('dashboard.cases.index') }}" class="btn btn-primary">
                        <i class="ti ti-arrow-left me-2"></i>Back to Cases List
                    </a>
                @else
                    <div class="row g-3">
                        @foreach ($remainingWorks as $work)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 border">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ ucfirst($work) }}</h5>
                                        <p class="card-text text-muted small">Click below to add details or skip.</p>
                                    </div>
                                    <div class="card-footer bg-transparent d-flex gap-2">
                                        <a href="{{ route('dashboard.cases.add-work-form', ['case' => $case->id, 'workType' => $work]) }}"
                                            class="btn btn-sm btn-primary flex-fill">
                                            <i class="ti ti-plus me-1"></i> Do This Work
                                        </a>
                                        <form
                                            action="{{ route('dashboard.cases.skip-work', ['case' => $case->id, 'workType' => $work]) }}"
                                            method="POST" class="flex-fill">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-label-secondary w-100">
                                                Skip
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 text-center">
                        <form action="{{ route('dashboard.cases.finish', $case->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="ti ti-check me-2"></i> Finish All Steps & Return to List
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
