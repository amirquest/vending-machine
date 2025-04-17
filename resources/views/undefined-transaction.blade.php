<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تراکنش نامعتبر</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;700&display=swap');

        :root {
            --primary-color: #e74c3c;
            --secondary-color: #34495e;
            --background-color: #ecf0f1;
            --card-background: #ffffff;
            --text-color: #2c3e50;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Vazirmatn', 'Tahoma', 'Arial', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background-color: var(--card-background);
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            color: var(--primary-color);
            margin-bottom: 20px;
            font-size: 2rem;
        }

        p {
            color: var(--secondary-color);
            line-height: 1.8;
            margin-bottom: 15px;
        }

        .icon {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .error-message {
            background-color: #fdeaea;
            border: 1px solid #fababa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            color: var(--primary-color);
        }

        .support-info {
            margin-top: 30px;
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }
            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="icon">&#9888;</div>
    <h1>تراکنش نامعتبر است</h1>
    <p>خطایی در فرایند پرداخت شما رخ داده است!</p>
    <p>در صورت کسر وجه از حساب شما مبلغ مذکور طی ۷۲ ساعت به حساب شما عودت خواهد شد.</p>
    @if(isset($errorMessage) && !empty($errorMessage))
        <div class="error-message">
            <p>پیام خطا: {{ $errorMessage }}</p>
        </div>
    @endif
</div>
</body>
</html>