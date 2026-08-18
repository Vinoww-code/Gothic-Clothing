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

class CheckoutAndAuthTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_guest_cannot_access_checkout_and_is_redirected_to_login(): void
    {
        $category = Category::firstOrCreate(
            ['name' => 'Test Gothic Category'],
            ['slug' => 'test-gothic-category', 'type' => 'costume']
        );

        $product = Product::create([
            'name' => 'Victorian Vampire Coat',
            'slug' => 'victorian-vampire-coat-' . uniqid(),
            'category_id' => $category->id,
            'description' => 'A gothic coat for rent',
            'price_per_day' => 150000,
            'status' => 'available',
        ]);

        $response = $this->get(route('checkout', $product->id));

        $response->assertRedirect(route('login'));
    }

    public function test_login_redirects_to_intended_checkout_url(): void
    {
        $user = User::create([
            'name' => 'Gothic Member',
            'email' => 'member_' . uniqid() . '@example.com',
            'password' => Hash::make('password123'),
        ]);

        $category = Category::firstOrCreate(
            ['name' => 'Test Gothic Category'],
            ['slug' => 'test-gothic-category', 'type' => 'costume']
        );

        $product = Product::create([
            'name' => 'Corset Gothique',
            'slug' => 'corset-gothique-' . uniqid(),
            'category_id' => $category->id,
            'description' => 'Black velvet corset',
            'price_per_day' => 85000,
            'status' => 'available',
        ]);

        // 1. Guest visits checkout -> sets intended URL
        $this->get(route('checkout', $product->id));

        // 2. Guest submits login
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        // 3. User should be redirected back to the product checkout
        $response->assertRedirect(route('checkout', $product->id));
        $this->assertAuthenticatedAs($user);
    }

    public function test_authenticated_user_can_submit_checkout_and_order_is_persisted(): void
    {
        $user = User::create([
            'name' => 'Raven Dark',
            'email' => 'raven_' . uniqid() . '@example.com',
            'password' => Hash::make('password123'),
        ]);

        $category = Category::firstOrCreate(
            ['name' => 'Test Gothic Category'],
            ['slug' => 'test-gothic-category', 'type' => 'costume']
        );

        $product = Product::create([
            'name' => 'Dark Knight Cloak',
            'slug' => 'dark-knight-cloak-' . uniqid(),
            'category_id' => $category->id,
            'description' => 'Velvet hooded cloak',
            'price_per_day' => 120000,
            'status' => 'available',
        ]);

        $fakeKtp = UploadedFile::fake()->image('ktp.jpg', 600, 400);
        $fakeSelfie = UploadedFile::fake()->image('selfie.jpg', 600, 400);

        $payload = [
            'rental_days' => 3,
            'foto_ktp' => $fakeKtp,
            'foto_selfie' => $fakeSelfie,
            'nik' => '3171012345678901',
            'name' => 'Raven Dark',
            'ttl' => 'JAKARTA, 31-10-1995',
            'gender' => 'PEREMPUAN',
            'delivery_method' => 'delivery',
            'whatsapp' => '081298765432',
            'address' => 'Jl. Boulevard Gothic No. 666',
            'payment_method' => 'qris',
            'notes' => 'Tolong kirim sebelum jam 2 siang',
        ];

        $response = $this->actingAs($user)->post(route('checkout.process', $product->id), $payload);

        // Check order was created in DB
        $order = Order::where('user_id', $user->id)->where('product_id', $product->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals(3, $order->rental_days);
        $this->assertEquals(360000, $order->total_amount); // 120000 * 3
        $this->assertEquals('qris', $order->payment_method);
        $this->assertEquals('pending', $order->payment_status);

        // Storage checks
        Storage::disk('public')->assertExists($order->id_card_path);
        Storage::disk('public')->assertExists($order->selfie_path);

        // Response should redirect to success page
        $response->assertRedirect(route('checkout.success', $order->order_code));

        // View success page
        $successResponse = $this->actingAs($user)->get(route('checkout.success', $order->order_code));
        $successResponse->assertStatus(200);
        $successResponse->assertSee($order->order_code);
        $successResponse->assertSee('Dark Knight Cloak');
        $successResponse->assertSee('360.000');
    }
}
