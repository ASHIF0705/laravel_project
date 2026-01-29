<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Typing Speed Test Game</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            min-height: 100vh;
            background: /*linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.75)),*/
                        url('/images/typing-bg.jpg') center/cover no-repeat fixed;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .box {
            width: 420px;
            padding: 40px 30px;
            margin-top: 120px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.15);
            text-align: center;
        }
       
        h2 {
            font-size: 1.3rem;
            margin-bottom: 35px;
            opacity: 0.9;
            letter-spacing: 1px;
        }
        input, select, button {
            width: 100%;
            padding: 14px;
            margin: 14px 0;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
        }
        input, select {
            background: rgba(255, 255, 255, 0.12);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }
        input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        select option {
            background: #2d3436;
            color: white;
        }
        button {
            background: linear-gradient(90deg, #00d4ff, #0077b6);
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        button:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(0, 212, 255, 0.45);
        }
    </style>
</head>
<body>

<div class="box">
    
    <h2>Test Your Skills!</h2>

    <form method="POST" action="/start-test">
        @csrf
        <input type="text" name="username" placeholder="Enter your name" required autofocus>
        
        <select name="time" required>
            <option value="" disabled selected>Select Duration</option>
            <option value="15">⏱️:15 Seconds</option>
            <option value="30">⏱️:30 Seconds</option>
            <option value="60">⏱️:60 Seconds</option>
        </select>

        <button type="submit">Start Test</button>
    </form>
</div>

</body>
</html>