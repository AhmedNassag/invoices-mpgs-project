
<!-- resources/views/payment/error.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment Error</title>
<style>
    /* Reset styling */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    /* Center content */
    body, html {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f2f5f7;
    }

    /* Container styling */
    .error-container {
        text-align: center;
        background: #ffffff;
        padding: 40px;
        width: 400px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        color: #333;
    }

    /* Error icon styling */
    .error-icon {
        font-size: 60px;
        color: #f44336; /* Red color for error */
        margin-bottom: 20px;
    }

    /* Heading styling */
    h1 {
        font-size: 28px;
        margin-bottom: 10px;
        color: #333;
    }

    /* Subtitle styling */
    p {
        font-size: 16px;
        color: #555;
        margin-bottom: 20px;
    }

    /* Button styling */
    .button {
        display: inline-block;
        padding: 12px 20px;
        margin: 10px 5px;
        background-color: #0070ba;
        color: #ffffff;
        font-size: 14px;
        font-weight: bold;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    /* Button hover effect */
    .button:hover {
        background-color: #005fa3;
    }

    /* Secondary button for retry */
    .button-secondary {
        background-color: #f44336;
    }
</style>
</head>
<body>

<div class="error-container">
    <div class="error-icon">❌</div>
    <h1>Payment Error</h1>
    <p>Unfortunately, we couldn't process your payment. Please try again or contact support if the issue persists.</p>
    <a href="{{ url('/') }}" class="button button-secondary">Return to home</a>
</div>

</body>
</html>
