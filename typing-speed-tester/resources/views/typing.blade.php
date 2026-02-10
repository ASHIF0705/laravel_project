<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Typing Speed Test Game</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background: linear-gradient(rgba(0, 0, 20, 0.6), rgba(0, 0, 50, 0.75)),
                        url('/images/typing-bg2.jpg') center/cover no-repeat fixed;
            color: #e0e0e0;
            transition: all 0.5s ease;
        }
        .dark {
            background: linear-gradient(rgba(0, 0, 0, 0.85), rgba(0, 0, 30, 0.95)),
                        url('/images/typing-bg2.jpg') center/cover no-repeat fixed;
        }
        .container {
            width: 1100px;
            max-width: 95%;
            margin: 60px auto;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
        }
        .dark .container {
            background: rgba(20, 20, 40, 0.75);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .stats {
            display: flex;
            gap: 40px;
            font-size: 1.4rem;
        }
        .stats span {
            color: #00d4ff;
            font-weight: bold;
        }
        .theme-toggle {
            font-size: 30px;
            color: #00d4ff;
            background: none;
            border: none;
            cursor: pointer;
            text-shadow: 0 0 10px #00d4ff;
        }
        .theme-toggle:hover { transform: scale(1.2); }
        .typing-area {
            position: relative;
            margin: 30px 0;
            border: 1px solid rgba(0, 212, 255, 0.35);
            border-radius: 12px;
            overflow: hidden;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(6px);
        }
        .display {
            position: absolute;
            inset: 0;
            padding: 24px;
            font-size: 22px;
            line-height: 1.65;
            white-space: pre-wrap;
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: normal;
            hyphens: none;
            pointer-events: none;
            color: #ddd;
        }
        #typing {
            width: 100%;
            min-height: 220px;
            height: auto;
            padding: 24px;
            font-size: 22px;
            line-height: 1.65;
            border: none;
            outline: none;
            resize: none;
            background: transparent;
            color: transparent;
            caret-color: #00d4ff;
            caret-color: transparent !important;
            overflow-y: hidden;
            white-space: pre-wrap;
            word-wrap: break-word;
            word-break: normal;
            hyphens: none;
        }
        .correct-char { color: #00ff9d; text-shadow: 0 0 8px #00ff9d; }
        .incorrect-char { color: #ff4d4d; background: rgba(255, 77, 77, 0.25); text-shadow: 0 0 5px #ff4d4d; }
        .current-char { background: #00d4ff; color: #000 !important; border-radius: 4px; box-shadow: 0 0 15px #00d4ff; text-shadow: 0 0 5px #00d4ff; }
    </style>
</head>
<body>
<!-- user name and timer-->
<div class="container">
    <div class="header">
        <div>
            <h3>Welcome, {{ $username }}</h3>
            <div class="stats">
                <div>⏱️: <span id="timer">{{ $time }}</span>s</div>
                <div>WPM: <span id="liveWpm">0</span></div>
                <div>Accuracy: <span id="liveAcc">100</span>%</div>
            </div>
        </div>
        <button class="theme-toggle" onclick="toggleDark()" id="themeBtn">
            <i class="fas fa-moon"></i>
        </button>
    </div>
<!--for typing area-->
    <div class="typing-area">
        <div id="display-text" class="display"></div>
        <textarea id="typing"  autofocus spellcheck="false"></textarea>
    </div>

    <form method="POST" action="/save" id="resultForm" style="display:none;">
        @csrf
        <input type="hidden" name="username" value="{{ $username }}">
        <input type="hidden" name="typed_text" id="typedText">
        <input type="hidden" name="sentence" value="{{ addslashes($sentence->sentence) }}">
        <input type="hidden" name="wpm" id="wpm">
        <input type="hidden" name="accuracy" id="accuracy">
        <input type="hidden" name="time" value="{{ $time }}">
    </form>
</div>

<script>
    let currentText = `{{ addslashes($sentence->sentence) }}`;
    const display = document.getElementById("display-text");
    const textarea = document.getElementById("typing");
    const themeBtn = document.getElementById("themeBtn");
    const timerEl = document.getElementById("timer");
    const liveWpmEl = document.getElementById("liveWpm");
    const liveAccEl = document.getElementById("liveAcc");

    function renderText(text) {
        display.innerHTML = text
            .split("")
            .map(char => `<span>${char === " " ? "&nbsp;" : char}</span>`)
            .join("");
        display.querySelector("span")?.classList.add("current-char");
    }

    renderText(currentText);

    let timeLeft = {{ $time }};
    let timerStarted = false;
    let timerId = null;
    let correctChars = 0;
    let totalTyped = 0;
    let startTime = null; 

    function toggleDark() {
        document.body.classList.toggle("dark");
        themeBtn.innerHTML = document.body.classList.contains("dark") 
            ? '<i class="fas fa-sun"></i>' 
            : '<i class="fas fa-moon"></i>';
    }

    document.addEventListener("keydown", e => {
        if (e.altKey && e.key === "Enter") {
            e.preventDefault();
            loadNewParagraph();
        }
    });

    async function loadNewParagraph() {
        clearInterval(timerId);
        timerStarted = false;
        startTime = null;
        timeLeft = {{ $time }};
        timerEl.innerText = timeLeft;
        textarea.value = "";
        textarea.disabled = false;
        correctChars = 0;
        totalTyped = 0;
        liveWpmEl.innerText = "0";
        liveAccEl.innerText = "100";
        renderText(currentText); 

        try {
            const response = await fetch('/get-new-sentence', {
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const data = await response.json();

            if (data.sentence) {
                currentText = data.sentence;
                renderText(currentText);
            }
        } catch (err) {
            console.error("Error loading new sentence:", err);
        }
    }

    textarea.addEventListener("input", handleInput);
    textarea.addEventListener("keydown", e => {
        if (textarea.value.length === 0 && e.key === "Backspace") e.preventDefault();
    });

    function handleInput() {
        if (!timerStarted) {
            startTimer();
            timerStarted = true;
        }

        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';

        const typed = textarea.value;
        const allSpans = display.querySelectorAll("span");

        totalTyped = typed.length;
        correctChars = 0;

        allSpans.forEach((s, i) => {
            s.className = "";
            if (i < typed.length) {
                if (typed[i] === currentText[i]) {
                    s.classList.add("correct-char");
                    correctChars++;
                } else {
                    s.classList.add("incorrect-char");
                }
            }
        });

        if (typed.length < allSpans.length) {
            allSpans[typed.length]?.classList.add("current-char");
        }

        if (typed.length > 0 && typed[typed.length - 1] === ' ' && currentText[typed.length - 1] === ' ') {
            correctChars--;
        }

        const accuracy = totalTyped === 0 ? 100 : Math.round((correctChars / totalTyped) * 100);
        liveAccEl.innerText = accuracy;

        const timeElapsedMs = startTime ? Date.now() - startTime : 0;
        const timeElapsedSec = timeElapsedMs / 1000;
        const minutes = timeElapsedSec / 60;
        const wpm = minutes > 0 ? Math.round((correctChars / 5) / minutes) : 0;
        liveWpmEl.innerText = wpm;

        if (typed.length >= currentText.length) {
            finishTest();
        }
    }

    function startTimer() {
        if (startTime === null) {
            startTime = Date.now(); 
        }
        timerId = setInterval(() => {
            timeLeft--;
            timerEl.innerText = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(timerId);
                finishTest();
            }
        }, 1000);
    }

    function finishTest() {
        clearInterval(timerId);
        textarea.disabled = true;

        document.getElementById("typedText").value = textarea.value;
        const timeElapsedMs = startTime ? Date.now() - startTime : 0;
        const timeElapsedSec = timeElapsedMs / 1000;
        const minutes = timeElapsedSec / 60 || 0.001; 

        const finalWpm = Math.round((correctChars / 5) / minutes);
        const finalAcc = totalTyped > 0 ? Math.round((correctChars / totalTyped) * 100) : 0;

        document.getElementById("wpm").value = finalWpm;
        document.getElementById("accuracy").value = finalAcc;

        console.log(`Test Complete → Time: ${timeElapsedSec.toFixed(1)}s | WPM: ${finalWpm} | Acc: ${finalAcc}% | Correct chars: ${correctChars}`);

        setTimeout(() => {
            document.getElementById("resultForm").submit();
        }, 2500);
    }

    display.querySelector("span")?.classList.add("current-char");
</script>

</body>
</html>