<x-app-layout>
    <section class="space-y-8 py-10">
        <div class="space-y-3">
            <span class="section-title">Profile</span>
            <h1 class="text-4xl font-semibold tracking-tight text-slate-900">Manage your account settings.</h1>
            <p class="max-w-2xl text-base text-slate-600">Update your profile information, password, and manage your account.</p>
        </div>

        <div class="grid gap-8">
            <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                <h2 class="text-2xl font-semibold text-slate-900">Profile information</h2>
                <p class="mt-2 text-slate-600">Update your name and email address.</p>
                <div class="mt-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                <h2 class="text-2xl font-semibold text-slate-900">Update password</h2>
                <p class="mt-2 text-slate-600">Ensure your account is using a strong password.</p>
                <div class="mt-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                <h2 class="text-2xl font-semibold text-red-600">Delete account</h2>
                <p class="mt-2 text-slate-600">Permanently delete your account and all associated data.</p>
                <div class="mt-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </section>
</x-app-layout>