<x-guest-layout>
    <div class="space-y-6">
        <div class="space-y-2 text-center">
            <h2 class="text-3xl font-semibold tracking-tight text-slate-900">Welcome back</h2>
            <p class="text-slate-500">Sign in to your account to continue.</p>
        </div>

        @if(session('status'))
            <div class="rounded-[1.75rem] border border-emerald-200/70 bg-emerald-50 px-6 py-4 text-emerald-900 shadow-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="input-field w-full" required autofocus autocomplete="username">
                @error('email')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="label">Password</label>
                <input id="password" type="password" name="password" class="input-field w-full" required autocomplete="current-password">
                @error('password')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-3 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded-xl border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span>Remember me</span>
                </label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-semibold text-indigo-600 transition hover:text-indigo-500">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="button-primary w-full">
                Sign in
            </button>

            @if(Route::has('register'))
                <p class="text-center text-sm text-slate-500">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-semibold text-indigo-600 transition hover:text-indigo-500">Create one</a>
                </p>
            @endif
        </form>
    </div>
</x-guest-layout>