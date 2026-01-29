<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - Typing Speed Test Game</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.5s ease;
        }
        .container {
            width: 800px;
            max-width: 90%;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }
        h2 {
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #00d4ff;
            text-shadow: 0 0 15px rgba(0, 212, 255, 0.7);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        th {
            background: linear-gradient(90deg, #00d4ff, #0077b6);
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        tr:hover {
            background: rgba(0, 212, 255, 0.1);
            transform: scale(1.01);
            transition: all 0.3s ease;
        }
        tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.05);
        }
        .wpm {
            color: #00ff9d;
            font-weight: bold;
            text-shadow: 0 0 5px #00ff9d;
        }
        .accuracy {
            color: #ffd700;
            font-weight: bold;
        }
        .back-btn {
            background: linear-gradient(90deg, #00d4ff, #0077b6);
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 1.1rem;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 212, 255, 0.4);
        }
        .empty {
            text-align: center;
            color: #aaa;
            font-style: italic;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2><i class="fas fa-trophy"></i> Leaderboard</h2>

    @if($leaders->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Name</th>
                    <th>WPM</th>
                    <th>Accuracy</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leaders as $index => $l)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $l->username }}</td>
                        <td class="wpm">{{ $l->wpm }}</td>
                        <td class="accuracy">{{ $l->accuracy }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="empty">No results yet! Be the first to test your speed.</p>
    @endif

    <a href="/" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Home
    </a>
</div>

</body>
</html>