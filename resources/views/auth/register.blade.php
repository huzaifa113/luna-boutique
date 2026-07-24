<x-guest-layout>
    <div class="space-y-6">
        <div class="space-y-2 text-center">
            <h2 class="text-3xl font-semibold tracking-tight text-slate-900">Create an account</h2>
            <p class="text-slate-500">Join Luna Boutique and start shopping.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="label">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" class="input-field w-full" required autofocus autocomplete="name">
                @error('name')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="input-field w-full" required autocomplete="username">
                @error('email')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="label">Password</label>
                <input id="password" type="password" name="password" class="input-field w-full" required autocomplete="new-password">
                @error('password')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="label">Confirm password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="input-field w-full" required autocomplete="new-password">
                @error('password_confirmation')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="button-primary w-full">
                Create account
            </button>

            @if(Route::has('login'))
                <p class="text-center text-sm text-slate-500">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-semibold text-indigo-600 transition hover:text-indigo-500">Sign in</a>
                </p>
            @endif
        </form>
    </div>
</x-guest-layout>