<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Writing Test - {{ $test->name }}</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            padding-bottom: 50px;
        }
        .navbar {
            background-color: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .review-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            margin-bottom: 30px;
        }
        .card-header-task {
            background-color: #f1f5f9;
            padding: 15px 25px;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 700;
        }
        .task-body {
            padding: 30px;
        }
        .question-box {
            background-color: #fffaf0;
            border-left: 4px solid #ce9d3c;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 0 8px 8px 0;
        }
        .answer-box {
            background-color: #fff;
            border: 1px solid #cbd5e1;
            padding: 25px;
            border-radius: 8px;
            min-height: 200px;
            line-height: 1.8;
            white-space: pre-wrap;
        }
        .word-count-badge {
            display: inline-block;
            background-color: #e2e8f0;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-danger" href="#">IELTS REVIEW</a>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small d-none d-md-block">Test: {{ $test->name }}</span>
                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-dark btn-sm rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i> Exit Review
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="mb-5 text-center">
                    <h1 class="fw-bold mb-2">Review Your Writing Submission</h1>
                    <p class="text-muted">Completed on {{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y H:i') : 'N/A' }}</p>
                </div>

                @foreach($test->tasks as $index => $task)
                    <div class="review-card shadow-sm">
                        <div class="card-header-task d-flex justify-content-between align-items-center">
                            <span>Part {{ $task->task_number }}</span>
                            <span class="badge bg-primary rounded-pill">Writing</span>
                        </div>
                        <div class="task-body">
                            <div class="question-box">
                                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-question-circle me-2"></i> Question Text</h6>
                                <div class="question-content">
                                    {!! nl2br(e($task->question_text)) !!}
                                </div>
                                @if($task->image)
                                    <div class="mt-3">
                                        <img src="{{ asset('storage/' . $task->image) }}" class="img-fluid border rounded" style="max-height: 300px;" alt="Task Image">
                                    </div>
                                @endif
                            </div>

                            <div class="user-answer mt-4">
                                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-pen-nib me-2"></i> Your Response</h6>
                                <div class="answer-box shadow-sm">
                                    {{ $attempt->answers[$task->task_number] ?? 'No answer provided.' }}
                                </div>
                                <div class="word-count-badge">
                                    Word Count: {{ isset($attempt->answers[$task->task_number]) ? count(explode(' ', trim($attempt->answers[$task->task_number]))) : 0 }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="text-center mt-5">
                    <a href="{{ route('student.tests.restart', ['id' => $test->id, 'category' => 'writing']) }}" 
                       class="btn btn-warning px-5 py-3 fw-bold rounded-pill shadow"
                       onclick="return confirm('Are you sure you want to retry? This will delete your current submission.')">
                        <i class="fas fa-redo me-2"></i> Retry This Test Again
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
