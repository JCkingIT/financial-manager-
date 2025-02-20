<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>
    @vite(['resources/css/auth.css'])
</head>

<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <h2>Login</h2>
                    <div class="inputbox">
                        <label for="">Email</label>
                        <input type="email" name='email' value='{{ $email }}' required>
                        @error('email')
                            <div>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="inputbox">
                        <label for="">Name</label>
                        <input type="text" name='name' value='{{ old('name') }}' required>
                        @error('name')
                            <div>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="inputbox">
                        <label for="">Password</label>
                        <input type="password" name='password' value='{{ old('password') }}' required>
                        @error('password')
                            <div>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="inputbox">
                        <label for="">Password c</label>
                        <input type="password" name='password_confirmation' value='{{ old('password_confirmation') }}'
                            required>
                        @error('password_confirmation')
                            <div>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="inputbox">
                        <input type="code" name='code' value='{{ $code }}' hidden>
                        @error('code')
                            <div>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit">Log in</button>
                </form>
            </div>
        </div>
    </section>
</body>

</html>
