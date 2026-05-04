@extends('layouts.admin')

@section('content')
<div class="row min-vh-75 justify-content-center align-items-center">
    <div class="col-md-8 col-lg-6 text-center">
        <div class="card border-0 shadow-lg p-5" style="border-radius: 25px; background: linear-gradient(145deg, #ffffff, #f3f4f6);">
            <div class="mb-4">
                <div class="success-checkmark-wrapper mb-4">
                    <div class="check-icon" style="font-size: 5rem; color: #10b981;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <h1 class="fw-bold text-dark mb-2">Test Completed!</h1>
                <p class="text-muted fs-5">Thank you for participating in this mock test. Your results have been recorded successfully.</p>
            </div>

            <div class="result-box py-4 px-3 mb-5 rounded-4" style="background: rgba(59, 130, 246, 0.05); border: 2px dashed #3b82f6;">
                <div class="row align-items-center">
                    <div class="col-6 border-end">
                        <small class="text-uppercase fw-bold text-muted d-block mb-1">Your Score</small>
                        <span class="fs-2 fw-bold text-primary">{{ $attempt->score ?? 0 }}</span>
                        <small class="text-muted">marks</small>
                    </div>
                    <div class="col-6">
                        <small class="text-uppercase fw-bold text-muted d-block mb-1">Total Time</small>
                        <span class="fs-2 fw-bold text-dark">
                            @php
                                $start = \Carbon\Carbon::parse($attempt->started_at);
                                $end = \Carbon\Carbon::parse($attempt->completed_at);
                                $diff = $start->diffInMinutes($end);
                            @endphp
                            {{ round($diff) }}
                        </span>
                        <small class="text-muted">mins</small>
                    </div>
                </div>
            </div>

            <div class="action-buttons d-grid gap-3">
                <div class="d-flex gap-2">
                    <a href="{{ url('/') }}" class="btn btn-primary btn-lg rounded-pill shadow-sm py-3 fw-bold transition-all hover-lift flex-grow-1">
                        <i class="fas fa-home me-2"></i> Homepage
                    </a>
                    @php
                        $isListening = isset($attempt->listening_test_id);
                        $reviewRoute = route('student.tests.review', [
                            'id' => $test->id, 
                            'category' => $isListening ? 'listening' : 'reading'
                        ]);
                    @endphp
                    <a href="{{ $reviewRoute }}" class="btn btn-success btn-lg rounded-pill shadow-sm py-3 fw-bold transition-all hover-lift flex-grow-1">
                        <i class="fas fa-search me-2"></i> Review Answers
                    </a>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary btn-lg rounded-pill flex-grow-1 py-3 fw-bold">
                        Student Portal
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-dark btn-lg rounded-pill flex-grow-1 py-3 fw-bold">
                        Admin Login
                    </a>
                </div>
            </div>

            <div class="mt-5 text-muted small">
                IFITS Test Management System &bull; Level Up Your IELTS
            </div>
        </div>
    </div>
</div>

<style>
    .transition-all { transition: all 0.3s ease; }
    .hover-lift:hover { transform: translateY(-5px); }
    .min-vh-75 { min-height: 75vh; }
    .rounded-4 { border-radius: 1.5rem !important; }
</style>
@endsection
