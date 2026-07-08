@extends('layouts.admin')

@section('content')
<div class="test-container">
    <!-- Header -->
    <header class="test-header shadow-sm px-4 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('images/opera-dark-logo.webp') }}" alt="Logo" height="40">
            <div class="border-start ps-3">
                <h5 class="mb-0 fw-bold">{{ $test->name }}</h5>
                <small class="text-muted">Review Mode &bull; Score: {{ $attempt->score }} / {{ $test->questionGroups->flatMap->questions->count() }}</small>
            </div>
        </div>

        <div class="test-parts d-flex gap-2 mx-auto">
            @foreach($test->questionGroups as $g_index => $group)
                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 nav-part-btn {{ $g_index === 0 ? 'active' : '' }}" 
                        id="header-nav-part-{{ $group->id }}"
                        onclick="activatePart('{{ $group->id }}')">
                    Part {{ $g_index + 1 }}
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
        <!-- Left Side: Passage -->
        <section class="test-passage p-4 flex-grow-1" id="passage-container">
            @foreach ($test->questionGroups as $g_index => $group)
                <div class="passage-group {{ $g_index === 0 ? '' : 'd-none' }}" id="passage-group-{{ $group->id }}" data-group-id="{{ $group->id }}">
                    <div class="passage-content mb-5">
                        <div class="reading-passage card border-0 shadow-sm p-4 overflow-auto" style="height: calc(100vh - 200px);">
                            <div class="passage-header mb-4 text-center border-bottom pb-4">
                                <h3 class="fw-bold mb-1 text-uppercase tracking-wider">READING PASSAGE {{ $g_index + 1 }}</h3>
                                <p class="text-muted mb-0">You should spend about 20 minutes on Questions related to this passage.</p>
                            </div>
                            <div class="passage-text">
                                {!! $group->passage !!}
                            </div>
                            @if ($group->image)
                                <div class="segment-image mt-4 text-center">
                                    <img src="{{ asset('storage/' . $group->image) }}" class="img-fluid rounded-3 border shadow-sm" style="max-height: 500px;">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </section>

        <!-- Divider -->
        <div class="test-resizer" id="test-divider">
            <div class="resizer-handle">
                <i class="fas fa-ellipsis-v text-white"></i>
            </div>
        </div>

        <!-- Right Side: Questions -->
        <section class="test-questions p-4 flex-grow-1 shadow-sm" id="questions-container" style="background: #fdfdfd;">
            @php 
                $allQuestions = $test->questionGroups->flatMap->questions;
                $embeddedQIds = []; 
            @endphp
            
            @foreach ($test->questionGroups as $g_index => $group)
                <div class="question-group {{ $g_index === 0 ? '' : 'd-none' }}" data-group-id="{{ $group->id }}">
                    <div class="questions-list">
                        @php $lastTitle = null; @endphp
                        @foreach ($group->questions as $question)
                            @php
                                if (in_array($question->id, $embeddedQIds)) continue;
                                $studentAnswer = $attempt->answers[$question->id] ?? null;
                                
                                // Grading Logic (Simple for UI)
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

                            @if(!empty($question->title) && $question->title !== $lastTitle)
                                <div class="question-set-header mt-5 mb-4 p-4 rounded-4" style="background: rgba(59, 130, 246, 0.05); border-left: 5px solid #3b82f6;">
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
                                            <h5 class="fw-bold mb-0 text-dark" style="line-height: 1.5;">{{ $title }}</h5>
                                        @endif

                                        @if($question->common_heading)
                                            <div class="mt-3 text-dark" style="line-height: 1.6; font-size: 0.95rem;">
                                                {!! nl2br(e($question->common_heading)) !!}
                                            </div>
                                        @endif

                                        @if(!empty($question->settings['instruction']))
                                            <div class="mt-2 text-muted small" style="line-height: 1.8;">
                                                {!! nl2br(preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $question->settings['instruction'])) !!}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @php $lastTitle = $question->title; @endphp
                            @endif

                            <div class="question-item card border-0 shadow-sm mb-4" 
                                 id="q-{{ $question->id }}"
                                 data-q-id="{{ $question->id }}" 
                                 data-q-type="{{ $question->question_type }}"
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
                                                    $isEmbeddedInBody = false;
                                                    $body = $question->content;
                                                    
                                                    $renderedBody = preg_replace_callback('/\[q(\d+)\]|(\d+)_+/', function($matches) use ($allQuestions, &$embeddedQIds, $attempt, &$isEmbeddedInBody) {
                                                        $num = $matches[1] ?: $matches[2];
                                                        $targetQ = $allQuestions->firstWhere('question_number', $num);
                                                        
                                                        if ($targetQ) {
                                                            $embeddedQIds[] = $targetQ->id;
                                                            $isEmbeddedInBody = true;
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
                                                            $displayVal = !empty($ans) ? $ans : 'Answer not given';
                                                            $textColor = !empty($ans) ? 'inherit' : '#94a3b8';
                                                            
                                                            $correctHtml = '';
                                                            if (!$isCorrect && request()->is('admin/*')) {
                                                                $correctHtml = '<span class="ms-2 badge bg-success-subtle text-success border border-success-subtle" title="Correct Answer">'.$targetQ->correct_answer.'</span>';
                                                            }
                                                            
                                                            return '<input type="text" disabled 
                                                                    class="form-control d-inline-block mx-2 text-center" 
                                                                    style="width: 150px; border: 2px solid '.$color.'; border-radius: 8px; background: white; color: '.$textColor.'; font-style: '.(!empty($ans)? 'normal' : 'italic').';" 
                                                                    value="'.$displayVal.'"> ' . $icon . $correctHtml;
                                                        }
                                                        return $matches[0];
                                                    }, $body);
                                                @endphp
                                                {!! $renderedBody !!}
                                            </div>

                                            @if ($question->image)
                                                <div class="question-image mb-3">
                                                    <img src="{{ asset('storage/' . $question->image) }}" class="img-fluid rounded-3 border shadow-sm" style="max-height: 400px;">
                                                </div>
                                            @endif

                                            @if ($question->question_type === 'mcq' || $question->question_type === 'tfng')
                                                <div class="options-grid d-grid gap-2">
                                                    @foreach ($question->options as $index => $val)
                                                        @php
                                                            $key = is_numeric($index) ? chr(65 + (int)$index) : $index;
                                                            $isSelected = ($studentAnswer == $key);
                                                            $correctKey = $question->correct_answer;
                                                            $isOptionCorrect = (trim(strtolower((string)$key)) == trim(strtolower((string)$correctKey)));
                                                            
                                                            $bgClass = '';
                                                            $borderClass = '';
                                                            if ($isSelected) {
                                                                if ($isOptionCorrect) {
                                                                    $bgClass = 'bg-success-subtle';
                                                                    $borderClass = 'border-success';
                                                                } else {
                                                                    $bgClass = 'bg-danger-subtle';
                                                                    $borderClass = 'border-danger';
                                                                }
                                                            } elseif ($isOptionCorrect && request()->is('admin/*')) {
                                                                $bgClass = 'bg-success-subtle border-success opacity-75';
                                                            }
                                                        @endphp
                                                        <label class="option-label p-3 border rounded-3 d-flex align-items-center gap-3 {{ $bgClass }} {{ $borderClass }}">
                                                            <input type="radio" class="form-check-input" disabled {{ $isSelected ? 'checked' : '' }}>
                                                            <span class="option-key fw-bold text-muted">{{ $key }}.</span>
                                                            <span class="option-text">{{ $val }}</span>
                                                            @if($isSelected)
                                                                <i class="fas fa-{{ $isOptionCorrect ? 'check' : 'times' }}-circle ms-auto text-{{ $isOptionCorrect ? 'success' : 'danger' }}"></i>
                                                            @elseif($isOptionCorrect && request()->is('admin/*'))
                                                                <i class="fas fa-check-circle ms-auto text-success"></i>
                                                            @endif
                                                        </label>
                                                    @endforeach
                                                </div>
                                                @if(!$isCorrect && request()->is('admin/*'))
                                                    <div class="mt-2 p-2 bg-success-subtle rounded-3 border-start border-success border-4">
                                                        <small class="text-success fw-bold d-block mb-1"><i class="fas fa-check-circle me-1"></i> Correct Answer:</small>
                                                        <span class="text-dark fw-bold text-uppercase">{{ $question->correct_answer }}</span>
                                                    </div>
                                                @endif
                                            @elseif ($question->question_type === 'mcq_multi')
                                                <div class="options-grid d-grid gap-2">
                                                    @foreach ($question->options as $index => $val)
                                                        @php
                                                            $key = is_numeric($index) ? chr(65 + (int)$index) : $index;
                                                            $isSelected = is_array($studentAnswer) && in_array($key, $studentAnswer);
                                                            $correctArray = preg_split('/[,]| and /', trim(strtolower($question->correct_answer)));
                                                            $isOptionCorrect = in_array(strtolower((string)$key), $correctArray);
                                                            
                                                            $bgClass = '';
                                                            if ($isSelected) {
                                                                $bgClass = $isOptionCorrect ? 'bg-success-subtle border-success' : 'bg-danger-subtle border-danger';
                                                            } elseif ($isOptionCorrect && request()->is('admin/*')) {
                                                                $bgClass = 'bg-success-subtle border-success opacity-75';
                                                            }
                                                        @endphp
                                                        <label class="option-label p-3 border rounded-3 d-flex align-items-center gap-3 {{ $bgClass }}">
                                                            <input type="checkbox" class="form-check-input" disabled {{ $isSelected ? 'checked' : '' }}>
                                                            <span class="option-key fw-bold text-muted">{{ $key }}.</span>
                                                            <span class="option-text">{{ $val }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                                @if(!$isCorrect && request()->is('admin/*'))
                                                    <div class="mt-2 p-2 bg-success-subtle rounded-3 border-start border-success border-4">
                                                        <small class="text-success fw-bold d-block mb-1"><i class="fas fa-check-circle me-1"></i> Correct Answers:</small>
                                                        <span class="text-dark fw-bold text-uppercase">{{ $question->correct_answer }}</span>
                                                    </div>
                                                @endif
                                            @elseif ($question->question_type === 'fill_blanks' || $question->question_type === 'short_answer')
                                                @if(!$isEmbeddedInBody)
                                                    <div class="mt-3">
                                                        <div class="small text-muted mb-1">Your Answer:</div>
                                                        <input type="text" disabled 
                                                               class="form-control {{ !$hasAnswered ? 'text-muted fst-italic' : 'fw-bold' }}" 
                                                               style="height: 50px; border-radius: 12px; border: 2px solid {{ $isCorrect ? '#10b981' : ($hasAnswered ? '#ef4444' : '#cbd5e1') }};" 
                                                               value="{{ $hasAnswered ? $studentAnswer : 'Answer not given' }}">
                                                        
                                                        @if(!$isCorrect && request()->is('admin/*'))
                                                            <div class="mt-2 p-2 bg-success-subtle rounded-3 border-start border-success border-4">
                                                                <small class="text-success fw-bold d-block mb-1"><i class="fas fa-check-circle me-1"></i> Correct Answer:</small>
                                                                <span class="text-dark fw-bold">{{ $question->correct_answer }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            @else
                                                {{-- Fallback for any other type --}}
                                                <div class="mt-3 p-3 border rounded-3 bg-light" style="border: 1px solid #cbd5e1 !important;">
                                                    <div class="mb-2">
                                                        <small class="text-muted d-block mb-1">Your Answer:</small>
                                                        <span class="fw-bold {{ $hasAnswered ? ($isCorrect ? 'text-success' : 'text-danger') : 'text-muted fst-italic' }}">
                                                            {{ $hasAnswered ? (is_array($studentAnswer) ? implode(', ', $studentAnswer) : $studentAnswer) : 'Answer not given' }}
                                                        </span>
                                                    </div>
                                                    @if(!$isCorrect && request()->is('admin/*'))
                                                        <div class="pt-2 border-top">
                                                            <small class="text-success fw-bold d-block mb-1">Correct Answer:</small>
                                                            <span class="fw-bold text-success">{{ $question->correct_answer }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </section>
    </main>
</div>

<style>
    /* Premium Look */
    :root { --main-dark: #0d1624; }
    body { background: #f8fafc; font-family: 'Inter', sans-serif; height: 100vh; overflow: hidden; margin: 0; }
    .test-container { height: 100vh; display: flex; flex-direction: column; }
    .test-header { height: 70px; background: white; z-index: 100; flex-shrink: 0; }
    .test-main { flex: 1; display: flex; overflow: hidden; }
    .test-resizer { width: 12px; background: #f1f5f9; cursor: col-resize; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .resizer-handle { background: #3b82f6; width: 4px; height: 30px; border-radius: 2px; }
    .test-passage, .test-questions { width: 50%; overflow-y: auto; scrollbar-width: thin; }
    .nav-part { cursor: pointer; opacity: 0.6; transition: 0.3s; padding: 5px 15px; border-radius: 20px; }
    .nav-part.active { opacity: 1; background: #f1f5f9; }
    .nav-part-btn.active { background: #3b82f6 !important; color: white !important; border-color: #3b82f6 !important; }
    .option-label { transition: 0.2s; }
</style>

<script>
    function activatePart(groupId) {
        document.querySelectorAll('.nav-part-btn').forEach(b => b.classList.toggle('active', b.id === `header-nav-part-${groupId}`));
        document.querySelectorAll('.passage-group, .question-group').forEach(el => {
            el.classList.toggle('d-none', el.dataset.groupId != groupId && el.id != `passage-group-${groupId}`);
        });
    }
    function scrollToQuestion(id) {
        const el = document.getElementById(id);
        if (el) {
            // Find parent group and activate it
            const group = el.closest('.question-group');
            if (group) {
                activatePart(group.dataset.groupId);
            }
            setTimeout(() => {
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 100);
        }
    }
</script>
@endsection
