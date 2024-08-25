<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Happy Birthday</title>
    <style>
        body {
            font-family: 'Wendy One', sans-serif;
            background-image: radial-gradient(#374566, #010203);
        }
        span {
            text-transform: uppercase;
        }
        .container {
            width: 800px;
            height: 420px;
            padding: 10px;
            margin: 0 auto;
            position: relative;
        }
        .balloon {
            width: 738px;
            margin: 0 auto;
            padding-top: 30px;
            position: relative;
        }
        .balloon > div {
            width: 104px;
            height: 140px;
            background: rgba(182, 15, 97, 0.9);
            border-radius: 80% 80% 80% 80%;
            margin: 0 auto;
            position: absolute;
            padding: 10px;
            box-shadow: inset 17px 7px 10px rgba(182, 15, 97, 0.9);
            transform-origin: bottom center;
        }
        .balloon > div:nth-child(1) {
            background: rgba(182, 15, 97, 0.9);
            left: 0;
            box-shadow: inset 10px 10px 10px rgba(135, 11, 72, 0.9);
            animation: balloon1 6s ease-in-out infinite;
        }
        .balloon > div:nth-child(2) {
            background: rgba(242, 112, 45, 0.9);
            left: 120px;
            box-shadow: inset 10px 10px 10px rgba(222, 85, 14, 0.9);
            animation: balloon2 6s ease-in-out infinite;
        }
        .balloon > div:nth-child(3) {
            background: rgba(45, 181, 167, 0.9);
            left: 240px;
            box-shadow: inset 10px 10px 10px rgba(35, 140, 129, 0.9);
            animation: balloon4 6s ease-in-out infinite;
        }
        .balloon > div:nth-child(4) {
            background: rgba(190, 61, 244, 0.9);
            left: 360px;
            box-shadow: inset 10px 10px 10px rgba(173, 14, 240, 0.9);
            animation: balloon1 5s ease-in-out infinite;
        }
        .balloon > div:nth-child(5) {
            background: rgba(180, 224, 67, 0.9);
            left: 480px;
            box-shadow: inset 10px 10px 10px rgba(158, 206, 34, 0.9);
            animation: balloon3 5s ease-in-out infinite;
        }
        .balloon > div:nth-child(6) {
            background: rgba(242, 194, 58, 0.9);
            left: 600px;
            box-shadow: inset 10px 10px 10px rgba(234, 177, 15, 0.9);
            animation: balloon2 3s ease-in-out infinite;
        }
        .balloon > div:before {
            color: inherit;
            position: absolute;
            bottom: -11px;
            left: 52px;
            content: "▲";
            font-size: 1em;
        }
        span {
            font-size: 4.8em;
            color: white;
            position: relative;
            top: 30px;
            left: 50%;
            margin-left: -27px;
        }
        h1 {
            position: relative;
            top: 200px;
            text-align: center;
            color: white;
            font-size: 3.5em;
        }

        /* Keyframes */
        @keyframes balloon1 {
            0%, 100% {
                transform: translateY(0) rotate(-6deg);
            }
            50% {
                transform: translateY(-20px) rotate(8deg);
            }
        }
        @keyframes balloon2 {
            0%, 100% {
                transform: translateY(0) rotate(6deg);
            }
            50% {
                transform: translateY(-30px) rotate(-8deg);
            }
        }
        @keyframes balloon3 {
            0%, 100% {
                transform: translate(0, -10px) rotate(6deg);
            }
            50% {
                transform: translate(-20px, 30px) rotate(-8deg);
            }
        }
        @keyframes balloon4 {
            0%, 100% {
                transform: translate(10px, -10px) rotate(-8deg);
            }
            50% {
                transform: translate(-15px, 10px) rotate(10deg);
            }
        }
    </style>
</head>
<body>
<link href="http://fonts.googleapis.com/css?family=Wendy+One" rel="stylesheet" type="text/css">
<div class="container">
    <div class="balloon">
        <div><span>☺</span></div>
        <div><span>B</span></div>
        <div><span>D</span></div>
        <div><span>A</span></div>
        <div><span>Y</span></div>
        <div><span>!</span></div>
    </div>
    <h1>{{ $name }}</h1>
</div>
</body>
</html>
