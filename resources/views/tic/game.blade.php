<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body{
            text-align:center;
        }
        .tic {
            display: grid;
            grid-template-columns: repeat(4, min-content);
            grid-template-rows: repeat(4, min-content);
            gap: .1em;
            justify-content: center;
        }
        .tic > * {
            border: 1px solid black;
            font-size: 5em;
            line-height: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            justify-content: center;
            width: 1em;
            height: 1em;
            color: inherit;
            text-decoration: none;

        }
    </style>
</head>
<body>
    
<h1>Let's Play Tic Tac Toe!</h1>
    <div class="game tic">
        @foreach ($board as $i=>$case)
            @if($case === '-')
                <a href="{{route('tic.playMove', [$player, implode('', $board), $i])}}">&bull;</a>
            @else
                <div>{{$case}}</div>
            @endif
        @endforeach
    </div>
    <div><a href="{{route('tic.startGame','X')}}">Restart with X</a></div>
    <div><a href="{{route('tic.startGame','O')}}">Restart with O</a></div>
</body>
</html>