@props(['category'])

<div class="category-card">
    <div class="space-y-4">
        <div>
            <a href="{{ route('categories.show', $category) }}"
                class="text-xl font-semibold text-slate-900 transition hover:text-indigo-600">{{ $category->name }}</a>
            <p class="mt-3 text-sm text-slate-500">{{ Str::limit($category->description, 100) }}</p>
        </div>
        <a href="{{ route('categories.show', $category) }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 transition hover:text-indigo-500">Shop
            {{ strtolower($category->name) }} <span aria-hidden="true">→</span></a>
    </div>
</div>
