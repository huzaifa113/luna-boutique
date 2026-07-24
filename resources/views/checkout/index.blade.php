<x-app-layout>
    <section class="py-10">
        <div class="space-y-3">
            <span class="section-title">Checkout</span>
            <h1 class="text-4xl font-semibold tracking-tight text-slate-900">Complete your order with confidence.</h1>
            <p class="max-w-2xl text-base text-slate-600">Enter shipping and payment details to finalize your purchase.
            </p>
        </div>

        @if ($items->isEmpty())
            <div class="mt-8 rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                <h2 class="text-2xl font-semibold text-slate-900">Your cart is empty</h2>
                <p class="mt-3 text-slate-600">Add items to your cart before you proceed to checkout.</p>
                <a href="{{ route('shop.index') }}" class="button-primary mt-6 inline-flex">Browse products</a>
            </div>
        @else
            <div class="mt-10 grid gap-8 lg:grid-cols-[1.8fr_1.1fr]">
                <div class="space-y-6">
                    <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                        <div class="space-y-4">
                            <h2 class="text-2xl font-semibold text-slate-900">Shipping information</h2>
                            <p class="text-slate-600">Provide your shipping address so we can deliver your order
                                promptly.</p>
                        </div>

                        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form"
                            enctype="multipart/form-data" x-data="{ billingSameAsShipping: {{ old('billing_same_as_shipping') ? 'true' : 'false' }}, paymentMethod: '{{ old('payment_method', 'online') }}' }" class="mt-8 space-y-8">
                            @csrf
                            <div class="grid gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="shipping_first_name" class="label">First name</label>
                                    <input id="shipping_first_name" name="shipping_first_name"
                                        value="{{ old('shipping_first_name') }}" class="input-field w-full" required>
                                    @error('shipping_first_name')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="shipping_last_name" class="label">Last name</label>
                                    <input id="shipping_last_name" name="shipping_last_name"
                                        value="{{ old('shipping_last_name') }}" class="input-field w-full" required>
                                    @error('shipping_last_name')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="shipping_address_line1" class="label">Address</label>
                                    <input id="shipping_address_line1" name="shipping_address_line1"
                                        value="{{ old('shipping_address_line1') }}" class="input-field w-full" required>
                                    @error('shipping_address_line1')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="shipping_address_line2" class="label">Address 2</label>
                                    <input id="shipping_address_line2" name="shipping_address_line2"
                                        value="{{ old('shipping_address_line2') }}" class="input-field w-full">
                                    @error('shipping_address_line2')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="shipping_city" class="label">City</label>
                                    <input id="shipping_city" name="shipping_city" value="{{ old('shipping_city') }}"
                                        class="input-field w-full" required>
                                    @error('shipping_city')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="shipping_postal_code" class="label">Postal code</label>
                                    <input id="shipping_postal_code" name="shipping_postal_code"
                                        value="{{ old('shipping_postal_code') }}" class="input-field w-full" required>
                                    @error('shipping_postal_code')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="shipping_country" class="label">Country</label>
                                    <input id="shipping_country" name="shipping_country"
                                        value="{{ old('shipping_country', 'United States') }}"
                                        class="input-field w-full" required>
                                    @error('shipping_country')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="shipping_phone" class="label">Phone</label>
                                    <input id="shipping_phone" name="shipping_phone"
                                        value="{{ old('shipping_phone') }}" class="input-field w-full">
                                    @error('shipping_phone')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div
                                class="flex items-center gap-3 rounded-[1.75rem] border border-slate-200 bg-slate-50 p-4">
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input type="checkbox" id="billing_same_as_shipping" name="billing_same_as_shipping"
                                        value="1" x-model="billingSameAsShipping" class="peer sr-only">
                                    <span
                                        class="inline-flex h-6 w-11 items-center rounded-full bg-slate-300 transition peer-checked:bg-indigo-600">
                                        <span
                                            class="inline-block h-4 w-4 translate-x-1 rounded-full bg-white transition peer-checked:translate-x-6"></span>
                                    </span>
                                    <span class="ml-3 text-sm font-medium text-slate-700">Use shipping address as
                                        billing address</span>
                                </label>
                            </div>

                            <div x-show="!billingSameAsShipping" x-cloak
                                class="space-y-6 rounded-[1.75rem] border border-slate-200 bg-slate-50 p-6">
                                <div class="space-y-3">
                                    <h2 class="text-2xl font-semibold text-slate-900">Billing information</h2>
                                    <p class="text-slate-600">Provide billing details if they differ from shipping.</p>
                                </div>
                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div>
                                        <label for="billing_first_name" class="label">First name</label>
                                        <input id="billing_first_name" name="billing_first_name"
                                            value="{{ old('billing_first_name') }}" class="input-field w-full">
                                        @error('billing_first_name')
                                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="billing_last_name" class="label">Last name</label>
                                        <input id="billing_last_name" name="billing_last_name"
                                            value="{{ old('billing_last_name') }}" class="input-field w-full">
                                        @error('billing_last_name')
                                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="billing_address_line1" class="label">Address</label>
                                        <input id="billing_address_line1" name="billing_address_line1"
                                            value="{{ old('billing_address_line1') }}" class="input-field w-full">
                                        @error('billing_address_line1')
                                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="billing_address_line2" class="label">Address 2</label>
                                        <input id="billing_address_line2" name="billing_address_line2"
                                            value="{{ old('billing_address_line2') }}" class="input-field w-full">
                                        @error('billing_address_line2')
                                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="billing_city" class="label">City</label>
                                        <input id="billing_city" name="billing_city"
                                            value="{{ old('billing_city') }}" class="input-field w-full">
                                        @error('billing_city')
                                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="billing_postal_code" class="label">Postal code</label>
                                        <input id="billing_postal_code" name="billing_postal_code"
                                            value="{{ old('billing_postal_code') }}" class="input-field w-full">
                                        @error('billing_postal_code')
                                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="billing_country" class="label">Country</label>
                                        <input id="billing_country" name="billing_country"
                                            value="{{ old('billing_country', 'United States') }}"
                                            class="input-field w-full">
                                        @error('billing_country')
                                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="billing_phone" class="label">Phone</label>
                                        <input id="billing_phone" name="billing_phone"
                                            value="{{ old('billing_phone') }}" class="input-field w-full">
                                        @error('billing_phone')
                                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div
                                class="space-y-6 rounded-[2rem] bg-white p-6 shadow-[0_18px_40px_-20px_rgba(15,23,42,0.08)]">
                                <div class="space-y-3">
                                    <h2 class="text-2xl font-semibold text-slate-900">Payment</h2>
                                    <p class="text-slate-600">Choose how you'd like to pay for this order.</p>
                                </div>

                                <div>
                                    <label for="payment_method" class="label">Payment method</label>
                                    <select id="payment_method" name="payment_method" class="input-field w-full"
                                        required x-model="paymentMethod">
                                        <option value="online"
                                            {{ old('payment_method', 'online') === 'online' ? 'selected' : '' }}>Online
                                            payment</option>
                                        @unless ($requiresAdvancePayment)
                                            <option value="cash_on_delivery"
                                                {{ old('payment_method') === 'cash_on_delivery' ? 'selected' : '' }}>Cash
                                                on delivery</option>
                                        @endunless
                                    </select>
                                    @if ($requiresAdvancePayment)
                                        <p class="mt-2 text-sm text-amber-600">One or more items in your order require
                                            advance payment, so cash on delivery is not available.</p>
                                    @endif
                                    @error('payment_method')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div x-show="paymentMethod === 'online'" x-cloak
                                    class="space-y-6 rounded-[1.75rem] border border-slate-200 bg-slate-50 p-6">
                                    <div class="space-y-2">
                                        <h3 class="text-lg font-semibold text-slate-900">Pay to one of the accounts
                                            below</h3>
                                        <p class="text-sm text-slate-600">Transfer the order total to any of these
                                            accounts, then enter your payment details below so we can verify it.</p>
                                    </div>

                                    <div class="grid gap-4 sm:grid-cols-3">
                                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                                Bank account</p>
                                            <p class="mt-2 text-sm font-medium text-slate-900">My Store (Pvt) Ltd</p>
                                            <p class="text-sm text-slate-600">Meezan Bank</p>
                                            <p class="text-sm text-slate-600">PK00MEZN0000000</p>
                                        </div>
                                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                                Easypaisa</p>
                                            <p class="mt-2 text-sm font-medium text-slate-900">My Store</p>
                                            <p class="text-sm text-slate-600">0300-0000000</p>
                                        </div>
                                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                                JazzCash</p>
                                            <p class="mt-2 text-sm font-medium text-slate-900">My Store</p>
                                            <p class="text-sm text-slate-600">0300-0000000</p>
                                        </div>
                                    </div>

                                    <div class="grid gap-6 sm:grid-cols-2">
                                        <div>
                                            <label for="payment_channel" class="label">Payment through</label>
                                            <select id="payment_channel" name="payment_channel"
                                                class="input-field w-full"
                                                x-bind:required="paymentMethod === 'online'">
                                                <option value="">Select method</option>
                                                <option value="easypaisa"
                                                    {{ old('payment_channel') === 'easypaisa' ? 'selected' : '' }}>
                                                    Easypaisa</option>
                                                <option value="jazzcash"
                                                    {{ old('payment_channel') === 'jazzcash' ? 'selected' : '' }}>
                                                    JazzCash</option>
                                                <option value="bank_account"
                                                    {{ old('payment_channel') === 'bank_account' ? 'selected' : '' }}>
                                                    Bank account</option>
                                            </select>
                                            @error('payment_channel')
                                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="transaction_id" class="label">Transaction ID</label>
                                            <input id="transaction_id" name="transaction_id"
                                                value="{{ old('transaction_id') }}" class="input-field w-full"
                                                x-bind:required="paymentMethod === 'online'">
                                            @error('transaction_id')
                                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label for="payment_screenshot" class="label">Transaction
                                                screenshot</label>
                                            <input type="file" id="payment_screenshot" name="payment_screenshot"
                                                accept="image/*"
                                                class="input-field w-full file:mr-4 file:rounded-full file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-indigo-700"
                                                x-bind:required="paymentMethod === 'online'">
                                            <p class="mt-2 text-xs text-slate-500">Upload a screenshot of your payment
                                                (max 4MB). Your order will be confirmed once we verify the payment.</p>
                                            @error('payment_screenshot')
                                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="coupon_code" class="label">Coupon code</label>
                                    <div class="flex gap-3">
                                        <input id="coupon_code" name="coupon_code" value="{{ old('coupon_code') }}"
                                            class="input-field w-full">
                                        <button type="button" id="apply-coupon-btn"
                                            class="button-primary shrink-0 px-6">Apply</button>
                                    </div>
                                    <p id="coupon-message" class="mt-2 text-sm" hidden></p>
                                    @error('coupon_code')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="notes" class="label">Order notes</label>
                                    <textarea id="notes" name="notes" rows="4" class="input-field w-full min-h-[140px]">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" id="place-order-btn" class="button-primary w-full">
                                    Place order - $<span id="place-order-total">{{ number_format($total, 2) }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="rounded-[2rem] bg-white p-6 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                        <h2 class="text-xl font-semibold text-slate-900">Order summary</h2>
                        <div class="mt-6 space-y-4 text-slate-600">
                            @forelse($items as $item)
                                <div class="flex items-center justify-between">
                                    <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                                    <span>${{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                </div>
                            @empty
                                <p class="text-slate-500">No items in your cart.</p>
                            @endforelse
                            @if ($items->isNotEmpty())
                                <div class="border-t border-slate-200 pt-4">
                                    <div class="flex items-center justify-between text-slate-600">
                                        <span>Subtotal</span>
                                        <span>${{ number_format($subtotal, 2) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-slate-600">
                                        <span>Shipping</span>
                                        <span id="summary-shipping">
                                            @if ($shipping > 0)
                                                ${{ number_format($shipping, 2) }}
                                                <span class="ml-2 text-xs text-slate-400">(Free on orders over
                                                    $100)</span>
                                            @else
                                                <span class="text-emerald-600">Free</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div id="summary-discount-row"
                                        class="flex items-center justify-between text-emerald-600" hidden>
                                        <span>Discount (<span id="summary-discount-code"></span>)</span>
                                        <span>-$<span id="summary-discount"></span></span>
                                    </div>
                                    <div
                                        class="mt-4 flex items-center justify-between text-lg font-semibold text-slate-900">
                                        <span>Total</span>
                                        <span>$<span id="summary-total">{{ number_format($total, 2) }}</span></span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </aside>
            </div>
        @endif
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const applyBtn = document.getElementById('apply-coupon-btn');

                if (!applyBtn) {
                    return;
                }

                const couponInput = document.getElementById('coupon_code');
                const couponMessage = document.getElementById('coupon-message');
                const discountRow = document.getElementById('summary-discount-row');
                const discountCode = document.getElementById('summary-discount-code');
                const discountAmount = document.getElementById('summary-discount');
                const summaryTotal = document.getElementById('summary-total');
                const placeOrderTotal = document.getElementById('place-order-total');

                const formatMoney = (value) => Number(value).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                });

                const showMessage = (text, isError) => {
                    couponMessage.textContent = text;
                    couponMessage.hidden = false;
                    couponMessage.classList.toggle('text-rose-600', isError);
                    couponMessage.classList.toggle('text-emerald-600', !isError);
                };

                applyBtn.addEventListener('click', async function() {
                    const code = couponInput.value.trim();

                    if (!code) {
                        showMessage('Please enter a coupon code.', true);
                        return;
                    }

                    applyBtn.disabled = true;
                    applyBtn.textContent = 'Applying...';

                    try {
                        const response = await fetch('{{ route('checkout.apply-coupon') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                coupon_code: code
                            }),
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            const message = data.errors?.coupon_code?.[0] ?? data.message ??
                                'The coupon code is invalid or expired.';
                            discountRow.hidden = true;
                            showMessage(message, true);
                            return;
                        }

                        discountCode.textContent = data.coupon_code;
                        discountAmount.textContent = formatMoney(data.discount);
                        discountRow.hidden = false;
                        summaryTotal.textContent = formatMoney(data.total);
                        placeOrderTotal.textContent = formatMoney(data.total);
                        showMessage('Coupon applied! Your discount has been added to the total.', false);
                    } catch (error) {
                        discountRow.hidden = true;
                        showMessage('Something went wrong while applying the coupon. Please try again.',
                            true);
                    } finally {
                        applyBtn.disabled = false;
                        applyBtn.textContent = 'Apply';
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
