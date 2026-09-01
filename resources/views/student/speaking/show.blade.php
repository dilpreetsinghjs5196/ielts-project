<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Speaking Mock Test - {{ $test->name }}</title>
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
            justify-content: center;
            height: calc(100vh - 100px); 
            width: 100%;
            background-color: #fafafa;
        }

        /* --- Panels --- */
        .panel {
            padding: 40px;
            overflow-y: auto;
            position: relative;
            background-color: #fff;
            max-width: 800px;
            width: 100%;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }

        /* --- Instructions Area --- */
        .part-header {
            background: #f8f8f8;
            border: 1px solid #ddd;
            padding: 10px 15px;
            margin-bottom: 25px;
            font-size: 1rem;
            font-weight: 700;
            text-align: center;
        }

        .prompt-text {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 30px;
            font-weight: 500;
            padding: 15px;
            background-color: #f4fbff;
            border-left: 4px solid #4a90e2;
        }

        .question-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .question-list li {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 15px;
            padding: 15px 20px;
            background: #fff;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            transition: transform 0.2s;
        }

        .question-list li:hover {
            transform: translateY(-2px);
            border-color: #4a90e2;
        }

        .question-icon {
            color: #4a90e2;
            margin-right: 15px;
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

        /* --- Hidden Task Logic --- */
        .task-content {
            display: none;
        }
        .task-content.active {
            display: block;
            animation: fadeIn 0.4s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- Exit / Success Popup --- */
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
            cursor: pointer;
        }
        .popup-btn:hover {
            transform: translateY(-2px);
        }
        .btn-home { background: #0d1624; color: white; border: none; }
        .btn-admin { background: #e31837; color: white; border: none; }

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
        <!-- Single Center Panel for Speaking -->
        <div class="panel">
            @foreach($test->parts as $index => $part)
                <div id="part-info-{{ $index + 1 }}" class="task-content @if($index == 0) active @endif">
                    <div class="part-header">
                        IELTS Speaking Part {{ $part->part_number }}<br>
                        <span class="text-muted small" style="font-size: 0.8rem; font-weight: 400;">{{ $part->title }}</span>
                    </div>

                    @if($part->passage)
                    <div class="prompt-text">
                        {!! nl2br(e($part->passage)) !!}
                    </div>
                    @endif

                    <ul class="question-list">
                        @foreach($part->questions as $question)
                        <li><i class="fas fa-comment-dots question-icon"></i> {{ $question->question_text }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

    <footer>
        <div class="tabs-container">
            @foreach($test->parts as $index => $part)
                <button class="tab-btn @if($index == 0) active @endif" id="tab-btn-{{ $index + 1 }}" onclick="switchTask({{ $index + 1 }})">
                    Part {{ $part->part_number }}
                </button>
            @endforeach
        </div>

        <div class="submit-container">
            <span class="small text-muted" id="progress-text">1 of {{ $test->parts->count() }}</span>
            <button class="nav-btn"><i class="fas fa-chevron-left"></i></button>
            <button class="nav-btn black" onclick="nextTask()"><i class="fas fa-chevron-right"></i></button>
            <button onclick="showExitPopup()" class="nav-btn bg-danger text-white ms-3 px-4" style="width: auto; text-decoration: none; font-weight: bold;">Exit Test</button>
        </div>
    </footer>

    <!-- Exit Settings / Success Popup -->
    <div id="submission-popup">
        <div class="popup-content">
            <div class="popup-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 style="margin-bottom: 12px; font-weight: 800; color: #111;">Test Completed!</h2>
            <p style="color: #666; font-size: 1.1rem; line-height: 1.6;">You have successfully completed this interactive speaking practice. What would you like to do next?</p>
            <div class="btn-group">
                <a href="{{ route('student.dashboard') }}" class="popup-btn btn-home">Dashboard</a>
                <a href="{{ route('student.dashboard') }}" class="popup-btn btn-admin">Back to Dashboard</a>
            </div>
        </div>
    </div>

    <script>
        let currentTask = 1;
        const totalTasks = {{ $test->parts->count() }};

        function switchTask(taskNum) {
            currentTask = taskNum;

            // Update UI elements
            document.querySelectorAll('.task-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));

            document.getElementById(`part-info-${taskNum}`).classList.add('active');
            document.getElementById(`tab-btn-${taskNum}`).classList.add('active');

            document.getElementById('progress-text').innerText = `${taskNum} of ${totalTasks}`;
        }

        function nextTask() {
            if (currentTask < totalTasks) {
                switchTask(currentTask + 1);
            }
        }

        function showExitPopup() {
            if(confirm("Are you sure you want to finish this Speaking Exam?")) {
                document.getElementById('submission-popup').style.display = 'flex';
            }
        }
    </script>
</body>
</html>
