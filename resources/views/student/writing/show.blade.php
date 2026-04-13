<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Writing Mock Test - {{ $test->name }}</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --ielts-header: #f4f4f4;
            --ielts-border: #d1d1d1;
            --ielts-active: #ececec;
            --ielts-red: #e31837;
            --ielts-text: #222;
        }

        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Inter', sans-serif;
            color: var(--ielts-text);
            overflow: hidden;
            background-color: #fff;
        }

        /* --- Header --- */
        header {
            height: 50px;
            background-color: var(--ielts-header);
            border-bottom: 1px solid var(--ielts-border);
            display: flex;
            align-items: center;
            padding: 0 20px;
            justify-content: space-between;
        }

        .test-info {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .ielts-logo {
            font-weight: 800;
            color: var(--ielts-red);
            font-size: 1.5rem;
            letter-spacing: -1px;
        }

        .header-icons {
            display: flex;
            gap: 20px;
            color: #555;
        }

        /* --- Main Layout --- */
        .main-container {
            display: flex;
            height: calc(100vh - 100px); /* Subtract header and footer */
            width: 100%;
        }

        /* --- Panels --- */
        .panel {
            padding: 30px;
            overflow-y: auto;
            position: relative;
        }

        .left-panel {
            flex: 1;
            border-right: 8px solid #bbb; /* Draggable handle style */
            background-color: #fff;
        }

        .right-panel {
            flex: 1;
            background-color: #fff;
        }

        /* --- Instructions Area --- */
        .part-header {
            background: #f8f8f8;
            border: 1px solid #ddd;
            padding: 10px 15px;
            margin-bottom: 25px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .prompt-text {
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .chart-image {
            max-width: 100%;
            height: auto;
            border: 1px solid #eee;
            margin: 20px 0;
        }

        /* --- Input Area --- */
        .writing-area {
            width: 100%;
            height: 80%;
            border: 1px solid #4a90e2; /* IELTS blue outline style */
            border-radius: 4px;
            padding: 15px;
            font-size: 1rem;
            line-height: 1.6;
            resize: none;
            box-sizing: border-box;
            outline: none;
        }

        .word-counter {
            text-align: right;
            margin-top: 10px;
            font-size: 0.9rem;
            color: #444;
            font-weight: 500;
        }

        /* --- Footer Tabs --- */
        footer {
            height: 50px;
            background: #fdfdfd;
            border-top: 1px solid var(--ielts-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 10px;
        }

        .tabs-container {
            display: flex;
            height: 100%;
        }

        .tab-btn {
            padding: 0 25px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            border-right: 1px solid #ddd;
            transition: background 0.2s;
        }

        .tab-btn.active {
            background-color: #fff;
            border-bottom: 3px solid var(--ielts-border);
        }

        .tab-btn:hover {
            background-color: #f1f1f1;
        }

        .submit-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-btn {
            background-color: #e5e5e5;
            border: none;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            cursor: pointer;
        }

        .nav-btn.black {
            background-color: #000;
            color: #fff;
        }

        .btn-check {
            background-color: #f4f4f4;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
        }

        /* --- Hidden Task Logic --- */
        .task-content {
            display: none;
        }
        .task-content.active {
            display: block;
        }

        hr {
            border: 0;
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }

        /* --- Success Popup --- */
        #submission-popup {
            display: none; 
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background: rgba(0,0,0,0.85); 
            z-index: 10000; 
            align-items: center; 
            justify-content: center;
            backdrop-filter: blur(5px);
        }
        .popup-content {
            background: white; 
            padding: 40px; 
            border-radius: 16px; 
            text-align: center; 
            max-width: 450px; 
            width: 90%; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            animation: slideUp 0.4s ease-out;
        }
        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .popup-icon {
            color: #10b981; 
            font-size: 60px; 
            margin-bottom: 20px;
        }
        .btn-group {
            display: flex; 
            gap: 15px; 
            margin-top: 30px;
        }
        .popup-btn {
            flex: 1; 
            padding: 14px; 
            border-radius: 8px; 
            font-weight: 700; 
            text-decoration: none;
            transition: transform 0.2s;
        }
        .popup-btn:hover {
            transform: translateY(-2px);
        }
        .btn-home { background: #0d1624; color: white; }
        .btn-admin { background: #e31837; color: white; }
    </style>
</head>
<body>

    <header>
        <div class="ielts-logo">IELTS</div>
        <div class="test-info">
            Candidate Name: {{ auth('student')->user()->name ?? 'Guest User' }}
        </div>
        <div class="header-icons">
            <i class="fas fa-wifi"></i>
            <i class="fas fa-bell"></i>
            <i class="fas fa-bars"></i>
        </div>
    </header>

    <div class="main-container">
        <!-- Left Panel: The Question -->
        <div class="panel left-panel">
            @foreach($test->tasks as $index => $task)
                <div id="task-info-{{ $index + 1 }}" class="task-content @if($index == 0) active @endif">
                    <div class="part-header">
                        Part {{ $task->task_number }}<br>
                        {{ $task->instruction ?: 'You should spend about ' . ($task->task_number == 1 ? '20' : '40') . ' minutes on this task. Write at least ' . ($task->task_number == 1 ? '150' : '250') . ' words.' }}
                    </div>

                    <div class="prompt-text">
                        {!! nl2br(e($task->question_text)) !!}
                    </div>

                    @if($task->image)
                        <img src="{{ asset('storage/' . $task->image) }}" class="chart-image" alt="Task Image">
                    @endif
                    
                    <p class="mt-4 small text-muted">Part {{ $task->task_number }}</p>
                </div>
            @endforeach
        </div>

        <!-- Right Panel: The Input -->
        <div class="panel right-panel">
            @foreach($test->tasks as $index => $task)
                <div id="task-input-{{ $index + 1 }}" class="task-content @if($index == 0) active @endif" style="height: 100%;">
                    <textarea 
                        id="writing-textarea-{{ $index + 1 }}" 
                        class="writing-area" 
                        placeholder="Type your answer here..."
                        oninput="updateWordCount({{ $index + 1 }})"
                    ></textarea>
                    
                    <div class="word-counter">
                        Words: <span id="word-count-{{ $index + 1 }}">0</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <footer>
        <div class="tabs-container">
            @foreach($test->tasks as $index => $task)
                <button class="tab-btn @if($index == 0) active @endif" id="tab-btn-{{ $index + 1 }}" onclick="switchTask({{ $index + 1 }})">
                    Part {{ $task->task_number }}
                </button>
            @endforeach
        </div>

        <div class="submit-container">
            <span class="small text-muted" id="progress-text">1 of {{ $test->tasks->count() }}</span>
            <button class="nav-btn"><i class="fas fa-chevron-left"></i></button>
            <button class="nav-btn black" onclick="nextTask()"><i class="fas fa-chevron-right"></i></button>
            <button class="btn-check" onclick="submitTest()"><i class="fas fa-check"></i></button>
        </div>
    </footer>

    <!-- Submission Success Popup -->
    <div id="submission-popup">
        <div class="popup-content">
            <div class="popup-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 style="margin-bottom: 12px; font-weight: 800; color: #111;">Test Submitted!</h2>
            <p style="color: #666; font-size: 1.1rem; line-height: 1.6;">Your writing test has been submitted successfully. What would you like to do next?</p>
            <div class="btn-group">
                <a href="/" class="popup-btn btn-home">Home Page</a>
                <a href="{{ route('login') }}" class="popup-btn btn-admin">Admin Panel</a>
            </div>
        </div>
    </div>

    <script>
        let currentTask = 1;
        const totalTasks = {{ $test->tasks->count() }};

        function switchTask(taskNum) {
            currentTask = taskNum;

            // Update UI elements
            document.querySelectorAll('.task-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));

            document.getElementById(`task-info-${taskNum}`).classList.add('active');
            document.getElementById(`task-input-${taskNum}`).classList.add('active');
            document.getElementById(`tab-btn-${taskNum}`).classList.add('active');

            document.getElementById('progress-text').innerText = `${taskNum} of ${totalTasks}`;
        }

        function nextTask() {
            if (currentTask < totalTasks) {
                switchTask(currentTask + 1);
            } else {
                if(confirm("Submit your writing test?")) {
                    submitTest();
                }
            }
        }

        function updateWordCount(taskNum) {
            const text = document.getElementById(`writing-textarea-${taskNum}`).value;
            const count = text.trim() ? text.trim().split(/\s+/).length : 0;
            document.getElementById(`word-count-${taskNum}`).innerText = count;
        }

        function submitTest() {
            if (!confirm("Are you sure you want to submit your writing test?")) return;

            // Collecting answers
            const answers = {};
            for(let i=1; i<=totalTasks; i++) {
                answers[i] = document.getElementById(`writing-textarea-${i}`).value;
            }

            // CSRF Token
            const csrfToken = '{{ csrf_token() }}';

            // Show loading state
            document.querySelector('.btn-check').innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            document.querySelector('.btn-check').disabled = true;

            fetch("{{ route('student.writing-tests.submit', $test->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ answers: answers })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success popup
                    document.getElementById('submission-popup').style.display = 'flex';
                } else {
                    alert("Submission failed. Please try again.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("An error occurred during submission.");
            })
            .finally(() => {
                document.querySelector('.btn-check').innerHTML = '<i class="fas fa-check"></i>';
                document.querySelector('.btn-check').disabled = false;
            });
        }
    </script>
</body>
</html>
