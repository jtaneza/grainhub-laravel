<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>GrainHub Login</title>
    <link rel="stylesheet" href="{{ asset('libs/css/main.css') }}">
</head>

<body class="login-body">
    <div class="login-container">
        <div class="grainhub-container">
            <img class="grainhub-login" src="{{ asset('libs/images/grainhub-logo.png') }}" alt="GrainHub Logo">
        </div>

        <div class="login-box">
            <div class="login-page">
                <div class="text-center">
                    <h1>Login</h1>
                </div>

                {{-- Display Laravel validation errors or flash messages --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Laravel login form --}}
                <form method="POST" action="{{ url('/login') }}" class="clearfix">
                    @csrf
                    <div class="form-group">
                        <label for="username" class="control-label">Username:</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="" required
                            autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password" class="control-label">Password:</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder=""
                            required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="login-button">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>