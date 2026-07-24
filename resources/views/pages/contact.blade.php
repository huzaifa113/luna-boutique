<x-app-layout>
    <section class="space-y-8 py-10">
        <div class="grid gap-10 lg:grid-cols-[1fr_0.9fr] lg:items-center">
            <div class="space-y-4">
                <span class="section-title">Contact</span>
                <h1 class="text-4xl font-semibold tracking-tight text-slate-900">Get in touch with our support team.</h1>
                <p class="max-w-2xl text-base text-slate-600">Have a question about your order, our products, or custom
                    requests? Send us a message and we'll respond quickly.</p>
            </div>
            <div class="rounded-[2rem] bg-slate-900 p-10 text-white shadow-[0_24px_80px_-40px_rgba(15,23,42,0.35)]">
                <p class="text-sm uppercase tracking-[0.3em] text-sky-200">Need help?</p>
                <p class="mt-4 text-lg font-semibold">Our support team is available to help with every step of your
                    shopping journey.</p>
                <ul class="mt-6 space-y-3 text-slate-200">
                    <li><strong>Email:</strong> support@lunaboutique.com</li>
                    <li><strong>Hours:</strong> Mon–Fri, 9am–6pm</li>
                </ul>
            </div>
        </div>

        @if (session('success'))
            <div
                class="rounded-[1.75rem] border border-emerald-200/70 bg-emerald-50 px-6 py-4 text-emerald-900 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
            <form action="{{ route('contact.send') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <label for="name" class="label">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="input-field w-full" />
                        @error('name')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="label">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="input-field w-full" />
                        @error('email')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div>
                    <label for="subject" class="label">Subject</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}"
                        class="input-field w-full" />
                    @error('subject')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="message" class="label">Message</label>
                    <textarea name="message" id="message" rows="6" class="input-field w-full min-h-[180px]">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="button-primary">Send message</button>
            </form>
        </div>
    </section>
</x-app-layout>
