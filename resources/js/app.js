import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// ─── Toast notification (pure DOM — no Alpine dependency) ──────────
(function () {
    let toastEl = null;
    let timeoutId = null;

    // Create toast element and inject it into the DOM
    function createToast() {
        const div = document.createElement('div');
        div.id = 'app-toast';
        div.style.cssText =
            'position:fixed;bottom:24px;right:24px;z-index:9999;max-width:360px;' +
            'transform:translateY(20px);opacity:0;transition:all 0.3s ease;' +
            'pointer-events:none;';
        div.innerHTML = `
            <div style="
                display:flex;align-items:center;gap:16px;
                border-radius:28px;border:1px solid rgba(5,150,105,0.4);
                background:#ecfdf5;padding:16px 24px;
                box-shadow:0 24px 60px -30px rgba(15,23,42,0.2);
            ">
                <div style="
                    width:32px;height:32px;border-radius:50%;
                    background:#d1fae5;display:flex;align-items:center;justify-content:center;
                    flex-shrink:0;
                ">
                    <svg width="16" height="16" fill="none" stroke="#059669" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span id="toast-msg" style="font-size:14px;font-weight:500;color:#064e3b;flex:1;"></span>
                <button id="toast-close" style="
                    background:none;border:none;cursor:pointer;padding:0;
                    color:#6ee7b7;font-size:18px;line-height:1;flex-shrink:0;
                ">✕</button>
            </div>
        `;
        document.body.appendChild(div);

        div.querySelector('#toast-close').addEventListener('click', () => hideToast(div));
        return div;
    }

    function hideToast(el) {
        el.style.transform = 'translateY(20px)';
        el.style.opacity = '0';
        el.style.pointerEvents = 'none';
    }

    window.showToast = function (message) {
        if (!toastEl) toastEl = createToast();
        if (timeoutId) clearTimeout(timeoutId);

        toastEl.querySelector('#toast-msg').textContent = message;
        toastEl.style.transform = 'translateY(0)';
        toastEl.style.opacity = '1';
        toastEl.style.pointerEvents = 'auto';

        timeoutId = setTimeout(() => {
            hideToast(toastEl);
        }, 3500);
    };
})();

// ─── AJAX Add-to-Cart ──────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form[data-ajax-cart]').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(form);
            const button = form.querySelector('button[type="submit"]');
            const originalText = button?.textContent || 'Add to Cart';

            if (button) {
                button.disabled = true;
                button.textContent = 'Adding…';
            }

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const data = await response.json();

                if (typeof window.showToast === 'function') {
                    window.showToast(data.message || 'Product added to cart!');
                } else {
                    alert(data.message || 'Product added to cart!');
                }
            } catch (error) {
                if (typeof window.showToast === 'function') {
                    window.showToast('Something went wrong. Please try again.');
                } else {
                    alert('Something went wrong. Please try again.');
                }
            } finally {
                if (button) {
                    button.disabled = false;
                    button.textContent = originalText;
                }
            }
        });
    });
});