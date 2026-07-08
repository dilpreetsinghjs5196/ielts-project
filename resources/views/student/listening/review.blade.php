@extends('layouts.admin')

@section('content')
<div class="test-container">
    <!-- Header -->
    <header class="test-header shadow-sm px-4 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('images/opera-dark-logo.webp') }}" alt="Logo" height="40">
            <div class="border-start ps-3">
                <h5 class="mb-0 fw-bold">{{ $test->name }}</h5>
                <small class="text-muted">Review Mode &bull; Score: {{ $attempt->score }} / 40</small>
            </div>
        </div>

        <div class="test-parts d-flex gap-2 mx-auto">
            @foreach($test->parts as $p_index => $part)
                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 nav-part-btn {{ $p_index === 0 ? 'active' : '' }}" 
                        id="header-nav-part-{{ $part->id }}"
                        onclick="activatePart('{{ $part->id }}')">
                    Part {{ $part->part_number ?? ($p_index + 1) }}
                </button>
            @endforeach
        </div>

        <div class="d-flex align-items-center gap-3">
            <a href="{{ auth('web')->check() ? route('admin.results.index') : route('student.dashboard') }}" class="btn btn-outline-dark rounded-pill px-4">
                <i class="fas fa-sign-out-alt me-2"></i> Exit Review
            </a>
        </div>
    </header>

    <main class="test-main">
        <!-- Full Width Review Content -->
        <section class="test-questions p-4 w-100 overflow-auto" style="background: #f8fafc;">
            <div class="container" style="max-width: 900px;">
                @php 
                    $allQuestions = $test->parts->flatMap->questions;
                    $embeddedQIds = []; 
                @endphp
                
                @foreach ($test->parts as $p_index => $part)
                    <div class="question-group {{ $p_index === 0 ? '' : 'd-none' }}" id="part-group-{{ $part->id }}" data-part-id="{{ $part->id }}">
                        <div class="part-header mb-4 p-4 bg-white rounded-3 shadow-sm border-start border-primary border-4">
                            <h4 class="fw-bold mb-2">Part {{ $part->part_number ?? ($p_index + 1) }}</h4>
                            <p class="text-muted mb-0">{!! nl2br($part->instruction) !!}</p>
                        </div>

                        <div class="questions-list">
                            @php $lastTitle = null; @endphp
                            @foreach ($part->questions as $question)
                                @php
                                    if (in_array($question->id, $embeddedQIds)) continue;
                                    $studentAnswer = $attempt->answers[$question->id] ?? null;
                                    
                                    // Grading Logic
                                    $correctAnswer = trim(strtolower($question->correct_answer));
                                    $isCorrect = false;
                                    if ($question->question_type === 'mcq_multi') {
                                        $correctArray = preg_split('/[,]| and /', $correctAnswer);
                                        $correctArray = array_map('trim', array_map('strtolower', $correctArray));
                                        $studentArray = is_array($studentAnswer) ? array_map('trim', array_map('strtolower', $studentAnswer)) : [];
                                        sort($correctArray);
                                        sort($studentArray);
                                        $hasAnswered = !empty($studentAnswer) && is_array($studentAnswer) && count($studentAnswer) > 0;
                                        $isCorrect = ($correctArray == $studentArray && $hasAnswered);
                                    } else {
                                        $hasAnswered = !empty($studentAnswer) && trim((string)$studentAnswer) !== '';
                                        if ($hasAnswered) {
                                            $alternatives = preg_split('/\s*(?:\/|\||\bor\b)\s*/i', $correctAnswer);
                                            $studentNormalized = trim(strtolower((string)$studentAnswer));
                                            
                                            $isCorrect = false;
                                            foreach ($alternatives as $alt) {
                                                $alt = trim($alt);
                                                $singular = preg_replace('/\s*\(.*?\)\s*/', '', $alt);
                                                $plural = str_replace(['(', ')'], '', $alt);
                                                if ($studentNormalized === $singular || $studentNormalized === $plural) {
                                                    $isCorrect = true;
                                                    break;
                                                }
                                            }
                                        } else {
                                            $isCorrect = false;
                                        }
                                    }
                                @endphp

                                @if(!empty($question->content) && !empty($question->title) && $question->title !== $lastTitle)
                                    <div class="question-set-header mt-4 mb-3 p-3 rounded" style="background: rgba(59, 130, 246, 0.05); border-left: 5px solid #3b82f6;">
                                        <div class="d-flex flex-column gap-2">
                                            @php 
                                                $title = $question->title;
                                                $badgeText = '';
                                                if (preg_match('/^\s*(Questions?\s*\d+\s*(?:[-–—_−‒―]|to|and|,)+\s*\d+)/iu', $title, $matches)) {
                                                    $badgeText = trim($matches[1]);
                                                    $title = trim(substr($title, strlen($matches[0])));
                                                } elseif (preg_match('/^\s*(Questions?\s*\d+)/iu', $title, $matches)) {
                                                    $badgeText = trim($matches[1]);
                                                    $title = trim(substr($title, strlen($matches[0])));
                                                }
                                            @endphp
                                            
                                            @if($badgeText)
                                                <div><span class="badge bg-primary px-3 py-2 rounded-2" style="font-size: 0.9rem;">{{ $badgeText }}</span></div>
                                            @endif
                                            
                                            @if($title)
                                                <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.1rem; line-height: 1.5;">{{ $title }}</h5>
                                            @endif

                                            @if($question->common_heading)
                                                <div class="mt-3 text-dark" style="line-height: 1.6; font-size: 0.95rem;">
                                                    {!! nl2br(e($question->common_heading)) !!}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @php $lastTitle = $question->title; @endphp
                                @endif

                                <div class="question-item card border-0 shadow-sm mb-4" 
                                     id="q-{{ $question->id }}"
                                     style="border-left: 5px solid {{ $isCorrect ? '#10b981' : ($hasAnswered ? '#ef4444' : '#e2e8f0') }} !important;">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-start gap-3 mb-3">
                                            <div class="q-number badge rounded-circle d-flex align-items-center justify-content-center text-white p-0" 
                                                 style="width: 30px; height: 30px; flex-shrink: 0; background-color: {{ $isCorrect ? '#10b981' : ($hasAnswered ? '#ef4444' : '#334155') }};">
                                                {{ $question->question_number }}
                                            </div>
                                            <div class="q-content flex-grow-1">
                                                @if(!$hasAnswered)
                                                    <div class="mb-2">
                                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1" style="font-size: 0.7rem; border: 1px solid #cbd5e1;">
                                                            <i class="fas fa-exclamation-circle me-1"></i> Not Answered
                                                        </span>
                                                    </div>
                                                @endif

                                                <div class="q-text fs-5 fw-bold text-dark mb-3">
                                                    @php
                                                        $qBody = $question->content ?: $question->title;
                                                        $pattern = '/(?:\[q(\d+)\]|(\d+)_{2,})/';
                                                        $renderedBody = preg_replace_callback($pattern, function($matches) use ($allQuestions, &$embeddedQIds, $attempt) {
                                                            $num = $matches[1] ?: $matches[2];
                                                            $targetQ = $allQuestions->firstWhere('question_number', $num);
                                                            if ($targetQ) {
                                                                $embeddedQIds[] = $targetQ->id;
                                                                $ans = $attempt->answers[$targetQ->id] ?? '';
                                                                $correct = trim(strtolower($targetQ->correct_answer));
                                                                
                                                                $hasAns = !empty($ans) && trim((string)$ans) !== '';
                                                                if ($hasAns) {
                                                                    $alternatives = preg_split('/\s*(?:\/|\||\bor\b)\s*/i', $correct);
                                                                    $studentNormalized = trim(strtolower((string)$ans));
                                                                    
                                                                    $isCorrect = false;
                                                                    foreach ($alternatives as $alt) {
                                                                        $alt = trim($alt);
                                                                        $singular = preg_replace('/\s*\(.*?\)\s*/', '', $alt);
                                                                        $plural = str_replace(['(', ')'], '', $alt);
                                                                        if ($studentNormalized === $singular || $studentNormalized === $plural) {
                                                                            $isCorrect = true;
                                                                            break;
                                                                        }
                                                                    }
                                                                } else {
                                                                    $isCorrect = false;
                                                                }
                                                                
                                                                $color = $isCorrect ? '#10b981' : (!empty($ans) ? '#ef4444' : '#cbd5e1');
                                                                $icon = $isCorrect ? '<i class="fas fa-check-circle ms-1"></i>' : (!empty($ans) ? '<i class="fas fa-times-circle ms-1"></i>' : '');
                                                                $displayVal = !empty($ans) ? $ans : '...';
                                                                
                                                                $correctHtml = '';
                                                                if (!$isCorrect && request()->is('admin/*')) {
                                                                    $correctHtml = '<span class="ms-2 badge bg-success-subtle text-success border border-success-subtle">'.$targetQ->correct_answer.'</span>';
                                                                }
                                                                
                                                                return '<span class="px-2 py-1 rounded border" style="border-color: '.$color.' !important; background: #f8fafc;">'.$displayVal.'</span>' . $icon . $correctHtml;
                                                            }
                                                            return $matches[0];
                                                        }, $qBody);
                                                    @endphp
                                                    {!! nl2br($renderedBody) !!}
                                                </div>

                                                @if ($question->image)
                                                    <div class="question-image mb-3">
                                                        <img src="{{ asset('storage/' . $question->image) }}" class="img-fluid rounded-3 border shadow-sm" style="max-height: 350px;">
                                                    </div>
                                                @endif

                                                @if ($question->question_type === 'mcq' || $question->question_type === 'mcq_multi')
                                                    <div class="options-grid d-grid gap-2">
                                                        @foreach ($question->options as $opt_key => $opt_val)
                                                            @php
                                                                $isSelected = is_array($studentAnswer) ? in_array($opt_key, $studentAnswer) : ($studentAnswer == $opt_key);
                                                                $correctArray = preg_split('/[,]| and /', trim(strtolower($question->correct_answer)));
                                                                $isOptionCorrect = in_array(strtolower((string)$opt_key), $correctArray);
                                                                
                                                                $bgClass = '';
                                                                if ($isSelected) {
                                                                    $bgClass = $isOptionCorrect ? 'bg-success-subtle border-success' : 'bg-danger-subtle border-danger';
                                                                } elseif ($isOptionCorrect && request()->is('admin/*')) {
                                                                    $bgClass = 'bg-success-subtle border-success opacity-75';
                                                                }
                                                            @endphp
                                                            <div class="p-3 border rounded-3 d-flex align-items-center gap-3 {{ $bgClass }}">
                                                                <span class="fw-bold">{{ $opt_key }}.</span>
                                                                <span>{{ $opt_val }}</span>
                                                                @if($isSelected)
                                                                    <i class="fas fa-{{ $isOptionCorrect ? 'check' : 'times' }}-circle ms-auto text-{{ $isOptionCorrect ? 'success' : 'danger' }}"></i>
                                                                @elseif($isOptionCorrect && request()->is('admin/*'))
                                                                    <i class="fas fa-check-circle ms-auto text-success"></i>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif($question->question_type === 'fill_blanks' || $question->question_type === 'short_answer')
                                                    @php $checkText = $question->content ?: $question->title; @endphp
                                                    @if(strpos($checkText, '[q') === false && strpos($checkText, '___') === false)
                                                        <div class="mt-2 p-3 bg-light rounded-3 border">
                                                            <div class="small text-muted mb-1">Your Answer:</div>
                                                            <span class="fw-bold {{ $isCorrect ? 'text-success' : ($hasAnswered ? 'text-danger' : 'text-muted italic') }}">
                                                                {{ $hasAnswered ? $studentAnswer : 'Not answered' }}
                                                            </span>
                                                            @if(!$isCorrect && request()->is('admin/*'))
                                                                <div class="mt-2 pt-2 border-top">
                                                                    <small class="text-success fw-bold d-block">Correct Answer:</small>
                                                                    <span class="text-dark">{{ $question->correct_answer }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </main>
</div>

<style>
    :root { --primary-gold: #ce9d3c; }
    body { background: #f8fafc; font-family: 'Inter', sans-serif; margin: 0; }
    .test-container { height: 100vh; display: flex; flex-direction: column; }
    .test-header { height: 70px; background: white; z-index: 100; flex-shrink: 0; border-bottom: 3px solid var(--primary-gold); }
    .test-main { flex: 1; display: flex; overflow: hidden; }
    .nav-part-btn.active { background: var(--primary-gold) !important; color: white !important; border-color: var(--primary-gold) !important; }
    .question-item { border-radius: 12px; transition: 0.3s; }
</style>

<script>
    function activatePart(partId) {
        document.querySelectorAll('.nav-part-btn').forEach(b => b.classList.toggle('active', b.id === `header-nav-part-${partId}`));
        document.querySelectorAll('.question-group').forEach(el => {
            el.classList.toggle('d-none', el.id !== `part-group-${partId}`);
        });
    }
</script>
@endsection
