<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Campaign: {{ $campaignName }}</title>
    <style>
        /* Styles for improved presentation */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        h5 {
            color: #333;
        }
        p {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Good News. New Campaign Initiated</h3>
        <h5>We AAA Company would like you to invite to our Newly started campaign so that we can strengthen our relationships with you<br>Here are the details of campaign:</h5>
        <p>Campaign Name: {{ $campaignName }}</p>
        <p>Description: {{ $campaignDescription }}</p>
        <p>Start Date: {{ $startDate }}</p>
        <p>End Date: {{ $endDate }}</p>
        <p>We invite you to join our new campaign!</p>
    </div>
</body>
</html>
