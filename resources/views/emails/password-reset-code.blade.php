
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>NOOR Password Reset</title>
</head>

<body
    style="
        margin: 0;
        padding: 0;
        background: #f5f1e8;
        font-family: Arial, sans-serif;
        color: #1c1410;
    "
>

    <div
        style="
            max-width: 520px;
            margin: 40px auto;
            padding: 35px 25px;
            background: #ffffff;
            border-radius: 16px;
        "
    >

        <div
            style="
                font-size: 28px;
                font-weight: bold;
                margin-bottom: 25px;
            "
        >
            NOOR
        </div>

        <h1
            style="
                margin: 0 0 12px;
                font-size: 26px;
            "
        >
            Reset your password
        </h1>

        <p
            style="
                margin: 0 0 25px;
                color: #756e65;
                line-height: 1.6;
                font-size: 14px;
            "
        >
            We received a request to reset your NOOR account password.
            Use the verification code below to continue.
        </p>

        <div
            style="
                padding: 18px;
                margin: 25px 0;
                background: #f5f1e8;
                border-radius: 12px;
                text-align: center;
                font-size: 32px;
                font-weight: bold;
                letter-spacing: 8px;
            "
        >
            {{ $code }}
        </div>

        <p
            style="
                margin: 0;
                color: #756e65;
                line-height: 1.6;
                font-size: 13px;
            "
        >
            This verification code will expire in
            <strong>10 minutes</strong>.
        </p>

        <p
            style="
                margin-top: 30px;
                color: #756e65;
                font-size: 12px;
                line-height: 1.6;
            "
        >
            If you did not request a password reset, you can safely ignore
            this email.
        </p>

        <div
            style="
                margin-top: 30px;
                padding-top: 18px;
                border-top: 1px solid #eee;
                color: #9a9289;
                font-size: 11px;
            "
        >
            © {{ date('Y') }} NOOR
        </div>

    </div>

</body>
</html>
