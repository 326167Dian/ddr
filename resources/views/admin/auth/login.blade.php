@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-body">
            <h3 class="mb-2">Login Admin</h3>
            <p class="text-secondary">Masuk untuk mengatur konten front-end secara dinamis.</p>
            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                <div class="form-group basic">
                    <label class="label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="form-group basic">
                    <label class="label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-check mt-1 mb-2">
                    <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Masuk</button>
            </form>
        </div>
    </div>
@endsection
