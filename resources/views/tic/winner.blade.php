<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
    <style>
        body{
            text-align:center;
        }
    </style>
<body>
<h1>Congratulations! Player '{{$player}}' won!</h1>
<div><a href="{{route('tic.startGame')}}">Play Again</a></div>

</body>
</html>