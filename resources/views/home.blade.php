<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Twitter Matcher</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <style>
            .drag-item {
                border: 2px solid black;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 10px;
                margin: 0 5px 10px;
            }

            .handle-item {
                font-size: 1.5rem;
                background: #4099FF;
            }

            .drag-item p {
                margin: 0;
            }
        </style>
    </head>
    <body>
        {{-- <div class="container">
            <div class="jumbotron text-center">
                <h1>Twitter Match Game</h1>
                <p>The goal of the game is to match twitter handles to a tweet sent from the handle in the least # of tries. Think you have what it takes?</p>
                <br>
                <button class="btn btn-lg">Get Started</button>
            </div>
        </div> --}}

        {{-- <div class="container">
            <div class="row">
                <div class="col">
                    <h1 class="text-center" style="margin-top: 20px;">Twitter Match Game</h1>
                    <p class="text-right">4:30 Remaining</p>
                </div>
            </div>

            @foreach (range(1, 5) as $num)
                <div class="row">
                    <div class="col drag-item handle-item">
                        <p>@barackobama</p>
                    </div>
                    <div class="col drag-item handle-item">
                        <p>@barackobama</p>
                    </div>
                    <div class="col drag-item tweet-item">
                        <p>I read letters like these every single day. It was one of the best parts of the job – hearing from you.</p>
                    </div>
                    <div class="col drag-item tweet-item">
                        <p>I read letters like these every single day. It was one of the best parts of the job – hearing from you.</p>
                    </div>
                </div>
            @endforeach

            <div class="row">
                <div class="col-xs-12 col-md-8 offset-md-2" style="margin-top: 20px;">
                    <form>
                        <p>Submit your results to see where you stack up on the leaderboard!</p>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="name" class="form-control" id="name" placeholder="Name...">
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" placeholder="Email...">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn" value="Submit Results">
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}

        <div class="container">
            <table class="table table-striped">
                <thead class="thead-inverse">
                    <tr>
                        <th>Rank</th>
                        <th>Name</th>
                        <th># of Correct Matches</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (range(1, 20) as $rank)
                        <tr>
                            <th>{{ $rank }}</th>
                            <td>Tony Stark</td>
                            <td>9/10</td>
                            <td>2:22</td>
                        </tr>
                    @endforeach
                    <tr class="table-active">
                        <th>105</th>
                        <td>Tony Stark</td>
                        <td>8/10</td>
                        <td>5:37</td>
                    </tr>
                </tbody>
            </table>
            <button class="btn btn-lg">Play Again</button>
        </div>

    </body>
</html>
