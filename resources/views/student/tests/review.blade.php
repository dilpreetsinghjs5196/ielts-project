@extends('layouts.admin')

@section('content')
<div class="test-container">
    <!-- Header -->
    <header class="test-header shadow-sm px-4 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" height="40">
            <div class="border-start ps-3">
                <h5 class="mb-0 fw-bold">{{ $test->name }}</h5>
                <small class="text-muted">Review Mode &bull; Score: {{ $attempt->score }} marks</small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-dark rounded-pill px-4">
                <i class="fas fa-sign-out-alt me-2"></i> Exit Review
            </a>
        </div>
    </header>

    <main class="test-main d-flex flex-grow-1 overflow-hidden" style="height: calc(100vh - 130px);">
        <!-- Left Side: Passage -->
        <section class="test-passage p-4 flex-grow-1" id="passage-container">
            @foreach ($test->questionGroups as $g_index => $group)
                <div class="passage-group {{ $g_index === 0 ? '' : 'd-none' }}" id="passage-group-{{ $group->id }}">
                    <div class="passage-content mb-5">
                        <div class="reading-passage card border-0 shadow-sm p-4 overflow-auto" style="height: calc(100vh - 200px);">
                            <div class="passage-header mb-4 text-center border-bottom pb-4">
                                <h3 class="fw-bold mb-1 text-uppercase tracking-wider">READING PASSAGE {{ $g_index + 1 }}</h3>
                                <p class="text-muted mb-0">You should spend about 20 minutes on Questions related to this passage.</p>
                            </div>
                            <div class="passage-text">
                                {!! $group->passage !!}
                            </div>
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
                                    $isCorrect = ($correctArray == $studentArray && !empty($studentArray));
                                } else {
                                    $isCorrect = (!empty($studentAnswer) && $correctAnswer === trim(strtolower((string)$studentAnswer)));
                                }
                            @endphp

                            <div class="question-item card border-0 shadow-sm mb-4" 
                                 id="q-{{ $question->id }}"
                                 data-q-id="{{ $question->id }}" 
                                 data-q-type="{{ $question->question_type }}"
                                 style="border-left: 5px solid {{ $isCorrect ? '#10b981' : (!empty($studentAnswer) ? '#ef4444' : '#e2e8f0') }} !important;">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start gap-3 mb-3">
                                        <div class="q-number badge rounded-circle d-flex align-items-center justify-content-center text-white p-0" 
                                             style="width: 30px; height: 30px; flex-shrink: 0; background-color: {{ $isCorrect ? '#10b981' : (!empty($studentAnswer) ? '#ef4444' : '#334155') }};">
                                            {{ $question->question_number }}
                                        </div>
                                        <div class="q-content flex-grow-1">
                                            @if(empty($studentAnswer))
                                                <div class="mb-2">
                                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1" style="font-size: 0.7rem;">
                                                        <i class="fas fa-exclamation-circle me-1"></i> Answer not given
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="q-text fs-5 fw-bold text-dark mb-3">
                                                @php
                                                    $isEmbeddedInBody = false;
                                                    $body = $question->question_text;
                                                    
                                                    $renderedBody = preg_replace_callback('/\[q(\d+)\]|(\d+)_+/', function($matches) use ($allQuestions, &$embeddedQIds, $attempt, &$isEmbeddedInBody) {
                                                        $num = $matches[1] ?: $matches[2];
                                                        $targetQ = $allQuestions->firstWhere('question_number', $num);
                                                        
                                                        if ($targetQ) {
                                                            $embeddedQIds[] = $targetQ->id;
                                                            $isEmbeddedInBody = true;
                                                            $ans = $attempt->answers[$targetQ->id] ?? '';
                                                            $correct = trim(strtolower($targetQ->correct_answer));
                                                            $isCorrect = (!empty($ans) && $correct === trim(strtolower((string)$ans)));
                                                            
                                                            $color = $isCorrect ? '#10b981' : (!empty($ans) ? '#ef4444' : '#cbd5e1');
                                                            $icon = $isCorrect ? '<i class="fas fa-check-circle ms-1"></i>' : (!empty($ans) ? '<i class="fas fa-times-circle ms-1"></i>' : '');
                                                            $displayVal = !empty($ans) ? $ans : 'Answer not given';
                                                            $textColor = !empty($ans) ? 'inherit' : '#94a3b8';
                                                            
                                                            return '<input type="text" disabled 
                                                                    class="form-control d-inline-block mx-2 text-center" 
                                                                    style="width: 150px; border: 2px solid '.$color.'; border-radius: 8px; background: white; color: '.$textColor.'; font-style: '.(!empty($ans)? 'normal' : 'italic').';" 
                                                                    value="'.$displayVal.'"> ' . $icon;
                                                        }
                                                        return $matches[0];
                                                    }, $body);
                                                @endphp
                                                {!! $renderedBody !!}
                                            </div>

                                            @if ($question->question_type === 'mcq' || $question->question_type === 'tfng')
                                                <div class="options-grid d-grid gap-2">
                                                    @foreach ($question->options as $option)
                                                        @php
                                                            $isSelected = ($studentAnswer == $option['key']);
                                                            $correctKey = $question->correct_answer;
                                                            $isOptionCorrect = ($option['key'] == $correctKey);
                                                            
                                                            $bgClass = '';
                                                            $borderClass = '';
                                                            if ($isSelected) {
                                                                if ($isCorrect) {
                                                                    $bgClass = 'bg-success-subtle';
                                                                    $borderClass = 'border-success';
                                                                } else {
                                                                    $bgClass = 'bg-danger-subtle';
                                                                    $borderClass = 'border-danger';
                                                                }
                                                            }
                                                        @endphp
                                                        <label class="option-label p-3 border rounded-3 d-flex align-items-center gap-3 {{ $bgClass }} {{ $borderClass }}">
                                                            <input type="radio" class="form-check-input" disabled {{ $isSelected ? 'checked' : '' }}>
                                                            <span class="option-key fw-bold text-muted">{{ $option['key'] }}.</span>
                                                            <span class="option-text">{{ $option['value'] }}</span>
                                                            @if($isSelected)
                                                                <i class="fas fa-{{ $isCorrect ? 'check' : 'times' }}-circle ms-auto text-{{ $isCorrect ? 'success' : 'danger' }}"></i>
                                                            @endif
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @elseif ($question->question_type === 'mcq_multi')
                                                <div class="options-grid d-grid gap-2">
                                                    @foreach ($question->options as $option)
                                                        @php
                                                            $isSelected = is_array($studentAnswer) && in_array($option['key'], $studentAnswer);
                                                            $correctArray = preg_split('/[,]| and /', trim(strtolower($question->correct_answer)));
                                                            $isOptionCorrect = in_array(strtolower($option['key']), $correctArray);
                                                            
                                                            $bgClass = '';
                                                            if ($isSelected) {
                                                                $bgClass = $isOptionCorrect ? 'bg-success-subtle border-success' : 'bg-danger-subtle border-danger';
                                                            }
                                                        @endphp
                                                        <label class="option-label p-3 border rounded-3 d-flex align-items-center gap-3 {{ $bgClass }}">
                                                            <input type="checkbox" class="form-check-input" disabled {{ $isSelected ? 'checked' : '' }}>
                                                            <span class="option-key fw-bold text-muted">{{ $option['key'] }}.</span>
                                                            <span class="option-text">{{ $option['value'] }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @elseif ($question->question_type === 'fill_blanks')
                                                @if(!$isEmbeddedInBody)
                                                    <div class="mt-3">
                                                        <input type="text" disabled 
                                                               class="form-control {{ empty($studentAnswer) ? 'text-muted fst-italic' : 'fw-bold' }}" 
                                                               style="height: 50px; border-radius: 12px; border: 2px solid {{ $isCorrect ? '#10b981' : (!empty($studentAnswer) ? '#ef4444' : '#e2e8f0') }};" 
                                                               value="{{ !empty($studentAnswer) ? $studentAnswer : 'Answer not given' }}">
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
        </section>
    </main>

    <!-- Footer -->
    <footer class="test-footer bg-white border-top px-4 d-flex align-items-center justify-content-between">
        <div class="footer-left d-flex align-items-center gap-4 overflow-hidden">
            @foreach ($test->questionGroups as $g_index => $group)
                <div class="nav-part d-flex align-items-center gap-2 {{ $g_index === 0 ? 'active' : '' }}" onclick="activatePart('{{ $group->id }}')">
                    <span class="part-label fw-bold">Part {{ $g_index + 1 }}</span>
                    <div class="part-questions d-flex gap-2">
                        @foreach ($group->questions as $q)
                            <a href="javascript:void(0)" 
                               class="question-nav-link text-decoration-none {{ !empty($attempt->answers[$q->id]) ? 'text-success' : 'text-muted' }}" 
                               onclick="scrollToQuestion('q-{{ $q->id }}')">
                                {{ $q->question_number }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </footer>
</div>

<style>
    /* Premium Look */
    :root { --main-dark: #0d1624; }
    body { background: #f8fafc; font-family: 'Inter', sans-serif; height: 100vh; overflow: hidden; }
    .test-header { height: 70px; background: white; z-index: 100; }
    .test-resizer { width: 12px; background: #f1f5f9; cursor: col-resize; display: flex; align-items: center; justify-content: center; }
    .resizer-handle { background: #3b82f6; width: 4px; height: 30px; border-radius: 2px; }
    .test-passage, .test-questions { width: 50%; overflow-y: auto; scrollbar-width: thin; }
    .test-footer { height: 60px; }
    .nav-part { cursor: pointer; opacity: 0.6; transition: 0.3s; padding: 5px 15px; border-radius: 20px; }
    .nav-part.active { opacity: 1; background: #f1f5f9; }
    .option-label { transition: 0.2s; }
</style>

<script>
    function activatePart(groupId) {
        document.querySelectorAll('.nav-part').forEach(p => p.classList.toggle('active', p.id === `nav-part-${groupId}`));
        document.querySelectorAll('.passage-group, .question-group').forEach(el => {
            el.classList.toggle('d-none', el.dataset.groupId != groupId && el.id != `passage-group-${groupId}`);
        });
    }
    function scrollToQuestion(id) {
        const el = document.getElementById(id);
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
</script>
@endsection
