<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminOrderAndFinanceTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected User $customer;
    protected Product $product;
    protected Order $order;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Storage::fake('public');

        $this->admin = User::create([
            'name' => 'Admin Lord',
            'email' => 'admin_' . uniqid() . '@gothic.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->customer = User::create([
            'name' => 'Gothic Customer',
            'email' => 'customer_' . uniqid() . '@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $category = Category::firstOrCreate(
            ['name' => 'Gothic Victorian Gown'],
            ['slug' => 'gothic-victorian-gown', 'type' => 'costume']
        );

        $this->product = Product::create([
            'name' => 'Crimson Velvet Gown',
            'slug' => 'crimson-velvet-gown-' . uniqid(),
            'category_id' => $category->id,
            'description' => 'A royal vampire velvet gown',
            'price_per_day' => 200000,
            'status' => 'available',
        ]);

        $fakeKtp = UploadedFile::fake()->image('ktp.jpg');
        $fakeSelfie = UploadedFile::fake()->image('selfie.jpg');

        $ktpPath = $fakeKtp->store('orders/ktp', 'local');
        $selfiePath = $fakeSelfie->store('orders/selfie', 'local');

        $this->order = Order::create([
            'order_code' => 'GTC-TEST-' . strtoupper(uniqid()),
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'nik' => '3171999988887777',
            'id_card_name' => 'Gothic Customer',
            'birth_date_place' => 'JAKARTA, 01-01-2000',
            'gender' => 'PEREMPUAN',
            'id_card_path' => $ktpPath,
            'selfie_path' => $selfiePath,
            'delivery_method' => 'delivery',
            'whatsapp' => '081299998888',
            'shipping_address' => 'Jl. Kastil Gotik No. 99',
            'payment_method' => 'qris',
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'price_per_day' => 200000,
            'rental_days' => 2,
            'start_date' => '2026-08-26',
            'end_date' => '2026-08-27',
            'total_amount' => 400000,
        ]);
    }

    public function test_admin_can_access_orders_index_and_view_order(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.orders.index'));
        $response->assertStatus(200);
        $response->assertSee($this->order->order_code);
        $response->assertSee('Gothic Customer');
    }

    public function test_admin_can_view_order_details_and_update_status(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.orders.show', $this->order->id));
        $response->assertStatus(200);
        $response->assertSee('Crimson Velvet Gown');

        $updateResponse = $this->actingAs($this->admin)->patch(route('admin.orders.updateStatus', $this->order->id), [
            'payment_status' => 'paid',
            'order_status' => 'confirmed',
            'admin_notes' => 'KTP terverifikasi valid',
        ]);

        $updateResponse->assertRedirect(route('admin.orders.show', $this->order->id));
        
        $this->order->refresh();
        $this->assertEquals('paid', $this->order->payment_status);
        $this->assertEquals('confirmed', $this->order->order_status);
    }

    public function test_admin_can_access_finance_monitoring_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.finance.index'));
        $response->assertStatus(200);
        $response->assertSee('Pemantauan Keuangan');
        $response->assertSee('Distribusi Metode Pembayaran');
    }

    public function test_customer_can_view_my_orders_page(): void
    {
        $response = $this->actingAs($this->customer)->get(route('my.orders'));
        $response->assertStatus(200);
        $response->assertSee('Riwayat Pesanan Saya');
        $response->assertSee($this->order->order_code);
    }

    public function test_public_user_can_view_product_detail_page(): void
    {
        $response = $this->get(route('product.show', $this->product->slug));
        $response->assertStatus(200);
        $response->assertSee('Crimson Velvet Gown');
        $response->assertSee('Simulasi');
    }

    public function test_secure_document_access_authorization(): void
    {
        // 1. Admin can access KTP
        $adminResp = $this->actingAs($this->admin)->get(route('documents.order', ['order' => $this->order->id, 'type' => 'ktp']));
        $adminResp->assertStatus(200);

        // 2. Customer (owner) can access KTP
        $custResp = $this->actingAs($this->customer)->get(route('documents.order', ['order' => $this->order->id, 'type' => 'ktp']));
        $custResp->assertStatus(200);

        // 3. Another user cannot access KTP
        $stranger = User::create([
            'name' => 'Stranger',
            'email' => 'stranger_' . uniqid() . '@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $strangerResp = $this->actingAs($stranger)->get(route('documents.order', ['order' => $this->order->id, 'type' => 'ktp']));
        $strangerResp->assertStatus(403);
    }
}
