@extends('layouts.app')

@section('content')
<style>
    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 60vh;
    }
    .login-card {
        padding: 3rem;
        width: 100%;
        max-width: 400px;
        text-align: center;
    }
    @media (max-width: 640px) {
        .login-card {
            padding: 2rem 1.5rem;
        }
    }
</style>

<div class="container login-container">
    <div class="glass login-card">
        <h2 style="margin-bottom: 2rem; color: var(--primary);">Login Admin</h2>
        
        @if ($errors->any())
            <div style="color: red; margin-bottom: 1rem; text-align: left; font-size: 0.9rem;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <input type="email" name="email" placeholder="Email Address" required value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Masuk Dashboard</button>
        </form>
    </div>
</div>
@endsection
