<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">

        <link href="/docs/4.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    </head>
    <body>
        <h2>Verify Your Email Address</h2>

        <div>
            Thanks signing up with the LearnLanka.
            Please follow the link below to verify your email address.

            <a href="{{ URL::to('register/verify/' . $user->confirmation_code) }}" class="btn">
                <button> Click to verify </button>
            </a><br/>

        </div>

    </body>
</html>