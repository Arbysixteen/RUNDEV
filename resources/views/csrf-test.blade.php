<!DOCTYPE html>
<html>
<head>
    <title>CSRF Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>CSRF Test Page</h1>
    
    <p><strong>CSRF Token:</strong> {{ csrf_token() }}</p>
    <p><strong>Session ID:</strong> {{ session()->getId() }}</p>
    
    <form action="/debug/csrf-test" method="POST">
        @csrf
        <button type="submit">Test CSRF</button>
    </form>
    
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
    
    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif
</body>
</html>
