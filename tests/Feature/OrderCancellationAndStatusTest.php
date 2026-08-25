<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrderCancellationAndStatusTest extends TestCase
{
    use DatabaseTransactions;

    protected User $customer;
    protected User $otherCustomer;
    protected User $admin;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::create([
            'name' => 'Raven User',
            'email' => 'raven_' . uniqid() . '@gothic.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $this->otherCustomer = User::create([
            'name' => 'Other User',
            'email' => 'other_' . uniqid() . '@gothic.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $this->admin = User::create([
            'name' => 'Admin Boss',
            'email' => 'admin_' . uniqid() . '@gothic.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $category = Category::firstOrCreate(
            ['name' => 'Gothic Victorian Gown'],
            ['slug' => 'gothic-victorian-gown', 'type' => 'costume']
        );

        $this->product = Product::create([
            'name' => 'Vampire Queen Dress',
            'slug' => 'vampire-queen-dress-' . uniqid(),
            'category_id' => $category->id,
            'description' => 'A gothic dress',
            'price_per_day' => 150000,
            'status' => 'rented',
        ]);
    }

    public function test_customer_can_cancel_pending_order_and_product_becomes_available(): void
    {
        $order = Order::create([
            'order_code' => 'GTC-PENDING-' . uniqid(),
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'id_card_path' => 'orders/ktp/fake.jpg',
            'selfie_path' => 'orders/selfie/fake.jpg',
            'delivery_method' => 'pickup',
            'payment_method' => 'qris',
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'price_per_day' => 150000,
            'rental_days' => 1,
            'total_amount' => 150000,
        ]);

        $response = $this->actingAs($this->customer)->post(route('my.orders.cancel', $order->id));

        $response->assertRedirect(route('my.orders'));
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals('cancelled', $order->order_status);

        $this->product->refresh();
        $this->assertEquals('available', $this->product->status);
    }

    public function test_customer_can_cancel_paid_order_when_not_yet_packed_with_reason(): void
    {
        $order = Order::create([
            'order_code' => 'GTC-PAID-' . uniqid(),
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'id_card_path' => 'orders/ktp/fake.jpg',
            'selfie_path' => 'orders/selfie/fake.jpg',
            'delivery_method' => 'delivery',
            'whatsapp' => '08123456789',
            'shipping_address' => 'Jl. Gothic',
            'payment_method' => 'qris',
            'payment_status' => 'paid',
            'order_status' => 'confirmed', // Pembayaran disetujui, belum dikemas
            'price_per_day' => 150000,
            'rental_days' => 2,
            'total_amount' => 300000,
        ]);

        $response = $this->actingAs($this->customer)->post(route('my.orders.cancel', $order->id), [
            'cancellation_reason' => 'Acara cosplay diundur oleh pihak panitia penyelenggara',
        ]);

        $response->assertRedirect(route('my.orders'));
        $order->refresh();
        $this->assertEquals('cancelled', $order->order_status);
        $this->assertEquals('Acara cosplay diundur oleh pihak panitia penyelenggara', $order->cancellation_reason);

        $this->product->refresh();
        $this->assertEquals('available', $this->product->status);
    }

    public function test_customer_cannot_cancel_paid_order_without_reason(): void
    {
        $order = Order::create([
            'order_code' => 'GTC-PAID2-' . uniqid(),
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'id_card_path' => 'orders/ktp/fake.jpg',
            'selfie_path' => 'orders/selfie/fake.jpg',
            'delivery_method' => 'pickup',
            'payment_method' => 'qris',
            'payment_status' => 'paid',
            'order_status' => 'confirmed',
            'price_per_day' => 150000,
            'rental_days' => 1,
            'total_amount' => 150000,
        ]);

        $response = $this->actingAs($this->customer)->post(route('my.orders.cancel', $order->id), [
            'cancellation_reason' => '',
        ]);

        $response->assertSessionHasErrors('cancellation_reason');
        $order->refresh();
        $this->assertEquals('confirmed', $order->order_status);
    }

    public function test_customer_cannot_cancel_order_when_already_packing_or_shipping(): void
    {
        $order = Order::create([
            'order_code' => 'GTC-PACKING-' . uniqid(),
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'id_card_path' => 'orders/ktp/fake.jpg',
            'selfie_path' => 'orders/selfie/fake.jpg',
            'delivery_method' => 'delivery',
            'whatsapp' => '08123456789',
            'shipping_address' => 'Jl. Gothic',
            'payment_method' => 'qris',
            'payment_status' => 'paid',
            'order_status' => 'packing', // Sudah mulai dikemas
            'price_per_day' => 150000,
            'rental_days' => 1,
            'total_amount' => 150000,
        ]);

        $response = $this->actingAs($this->customer)->post(route('my.orders.cancel', $order->id), [
            'cancellation_reason' => 'Mau cancel dong',
        ]);

        $response->assertSessionHas('error');
        $order->refresh();
        $this->assertEquals('packing', $order->order_status); // Tetap packing
    }

    public function test_other_user_cannot_cancel_someone_elses_order(): void
    {
        $order = Order::create([
            'order_code' => 'GTC-STRANGER-' . uniqid(),
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'id_card_path' => 'orders/ktp/fake.jpg',
            'selfie_path' => 'orders/selfie/fake.jpg',
            'delivery_method' => 'pickup',
            'payment_method' => 'qris',
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'price_per_day' => 150000,
            'rental_days' => 1,
            'total_amount' => 150000,
        ]);

        $response = $this->actingAs($this->otherCustomer)->post(route('my.orders.cancel', $order->id));
        $response->assertStatus(403);
    }
}
