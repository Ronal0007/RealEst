<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>
<body>
<form action="/sendemail" method="post">
    {{csrf_field()}}
    <label for="email">Email:</label>
    <input type="text" name="email" id="email" placeholder="email address"><br><br>
    <label for="body">Body:</label>
    <textarea name="body" id="" cols="30" rows="10" placeholder="Message body"></textarea><br><br>
    <input type="submit" value="Send Email">
</form>
</body>
</html>
