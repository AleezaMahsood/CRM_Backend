<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Confirmation</title>
</head>
<body>
    <h1>Meeting Scheduled</h1>
    <p>Hello {{ $leadName }},</p>
    <p>We are pleased to inform you that your meeting has been scheduled.</p>
    <p>Details are as follows:</p>
    <ul>
        <li>Date: {{ $date }}</li>
        <li>Time: Specified in your calendar invitation</li>
        <li>Venue: Will be confirmed via email</li>
    </ul>
    <p>Please contact us if you have any questions.</p>
    <p>Best Regards,<br>FAAA Company</p>
</body>
</html>
