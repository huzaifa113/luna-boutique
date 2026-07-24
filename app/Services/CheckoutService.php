<?php

namespace App\Services;

use App\Models\Address;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function createOrder(User $user, array $data): Order
    {
        $cartItems = CartItem::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            throw ValidationException::withMessages(['cart' => 'Your shopping cart is empty.']);
        }

        $this->validateStock($cartItems);
        $this->validatePaymentMethod($cartItems, $data['payment_method']);

        $subtotal = $this->calculateSubtotal($cartItems);
        $coupon = $this->resolveCoupon($data['coupon_code'] ?? null, $subtotal);
        $discount = $coupon ? $coupon->calculateDiscount($subtotal) : 0;
        $tax = 0;
        $shipping = $subtotal >= 100 ? 0 : 20;
        $total = max(0, $subtotal - $discount + $tax + $shipping);

        return DB::transaction(function () use ($user, $data, $cartItems, $coupon, $subtotal, $discount, $tax, $shipping, $total) {
            $shippingAddress = $this->createAddress($user, $data, 'shipping', false, true);

            if ($this->billingSeparate($data)) {
                $billingAddress = $this->createAddress($user, $data, 'billing', true, false);
            } else {
                $billingAddress = $shippingAddress;
            }

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => Str::upper('ORD-' . Str::random(8)),
                'shipping_address_id' => $shippingAddress->id,
                'billing_address_id' => $billingAddress->id,
                'coupon_id' => $coupon?->id,
                'status' => Order::STATUS_PENDING,
                'payment_status' => Order::PAYMENT_STATUS_PENDING,
                'payment_method' => $data['payment_method'],
                'transaction_id' => $data['transaction_id'] ?? null,
                'payment_channel' => $data['payment_channel'] ?? null,
                'payment_screenshot' => $data['payment_screenshot'] ?? null,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product->id,
                    'product_name' => $item->product->name,
                    'product_sku' => $item->product->sku,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->price,
                    'total_price' => $item->quantity * $item->product->price,
                ]);

                $item->product->decrement('stock_quantity', $item->quantity);
            }

            if ($coupon) {
                $coupon->increment('used');
            }

            CartItem::where('user_id', $user->id)->delete();

            return $order;
        });
    }

    protected function billingSeparate(array $data): bool
    {
        return empty($data['billing_same_as_shipping']) ? false : !filter_var($data['billing_same_as_shipping'], FILTER_VALIDATE_BOOLEAN);
    }

    protected function validateStock(Collection $cartItems): void
    {
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock_quantity) {
                throw ValidationException::withMessages([
                    'cart' => "Product {$item->product->name} does not have enough stock available.",
                ]);
            }
        }
    }

    protected function validatePaymentMethod(Collection $cartItems, string $paymentMethod): void
    {
        if ($paymentMethod !== Order::PAYMENT_METHOD_CASH) {
            return;
        }

        $advanceProducts = $cartItems->filter(fn ($item) => $item->product->adv_payment);

        if ($advanceProducts->isNotEmpty()) {
            $names = $advanceProducts->map(fn ($item) => $item->product->name)->implode(', ');

            throw ValidationException::withMessages([
                'payment_method' => "Cash on delivery is not available for this order. The following products require advance payment: {$names}.",
            ]);
        }
    }

    public function resolveCoupon(?string $couponCode, float $subtotal): ?Coupon
    {
        if (empty($couponCode)) {
            return null;
        }

        $coupon = Coupon::where('code', $couponCode)->first();

        if (! $coupon || ! $coupon->isAvailable()) {
            throw ValidationException::withMessages(['coupon_code' => 'The coupon code is invalid or expired.']);
        }

        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            throw ValidationException::withMessages([
                'coupon_code' => 'This coupon requires a minimum order amount of $' . number_format((float) $coupon->min_order_amount, 2) . '.',
            ]);
        }

        return $coupon;
    }

    protected function calculateSubtotal(Collection $cartItems): float
    {
        return (float) $cartItems->reduce(function ($carry, CartItem $item) {
            return $carry + ($item->product->price * $item->quantity);
        }, 0);
    }

    protected function createAddress(User $user, array $data, string $section, bool $defaultBilling, bool $defaultShipping): Address
    {
        $prefix = $section === 'billing' ? 'billing_' : 'shipping_';

        return Address::create([
            'user_id' => $user->id,
            'first_name' => $data[$prefix . 'first_name'],
            'last_name' => $data[$prefix . 'last_name'],
            'company' => $data[$prefix . 'company'] ?? null,
            'address_line1' => $data[$prefix . 'address_line1'],
            'address_line2' => $data[$prefix . 'address_line2'] ?? null,
            'city' => $data[$prefix . 'city'],
            'state' => $data[$prefix . 'state'] ?? null,
            'postal_code' => $data[$prefix . 'postal_code'],
            'country' => $data[$prefix . 'country'],
            'phone' => $data[$prefix . 'phone'] ?? null,
            'is_default_billing' => $defaultBilling,
            'is_default_shipping' => $defaultShipping,
        ]);
    }
}