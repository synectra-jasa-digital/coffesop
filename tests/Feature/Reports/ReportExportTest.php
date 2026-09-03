<?php

namespace Tests\Feature\Reports;

use App\Reports\ReportExportService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Ingredient;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReportExportTest extends TestCase
{
    use RefreshDatabase;

    protected User $manager;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Owner/Admin']);
        Role::firstOrCreate(['name' => 'Manager/Supervisor']);

        $this->manager = User::factory()->create();
        $this->manager->assignRole('Manager/Supervisor');

        $category = Category::create(['name' => 'Kopi', 'description' => 'Minuman kopi']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Cafe Latte',
            'description' => 'Espresso dengan steamed milk',
            'price' => 28000,
            'is_active' => true,
        ]);

        $ingredient = Ingredient::create([
            'name' => 'Biji Kopi',
            'unit' => 'gram',
            'minimum_stock' => 100,
            'current_stock' => 10000,
        ]);

        $recipe = Recipe::create([
            'product_id' => $product->id,
            'name' => 'Resep '.$product->name,
        ]);

        RecipeIngredient::create([
            'recipe_id' => $recipe->id,
            'ingredient_id' => $ingredient->id,
            'quantity' => 18,
        ]);

        $this->table = Table::create(['number' => 'Meja 01', 'status' => 'available']);
        $this->product = $product;
    }

    protected function createOrder(): Order
    {
        $order = Order::create([
            'order_number' => 'ORD-EXP'.Str::random(4),
            'user_id' => $this->manager->id,
            'table_id' => $this->table->id,
            'type' => 'dine-in',
            'status' => 'completed',
            'payment_status' => 'paid',
            'subtotal' => 28000,
            'tax_amount' => 3080,
            'service_charge_amount' => 0,
            'discount_amount' => 0,
            'total' => 31080,
            'created_at' => Carbon::today(),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
            'price' => 28000,
            'subtotal' => 28000,
            'notes' => '',
            'status' => 'served',
        ]);

        Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'cash',
            'amount' => 31080,
            'status' => 'success',
        ]);

        return $order;
    }

    public function test_export_excel_downloads_file_with_sales_rows(): void
    {
        $this->actingAs($this->manager);
        $this->createOrder();

        $response = app(ReportExportService::class)->salesDailyExcel(Carbon::today());

        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\BinaryFileResponse::class, $response);
        $this->assertFileExists($response->getFile()->getPathname());
    }

    public function test_export_pdf_generates_for_sales(): void
    {
        $this->actingAs($this->manager);
        $this->createOrder();

        $response = app(ReportExportService::class)->salesDailyPdf(Carbon::today());

        $this->assertInstanceOf(\Illuminate\Http\Response::class, $response);
        $this->assertNotEmpty($response->getContent());
    }

    public function test_export_stock_pdf_generates(): void
    {
        $this->actingAs($this->manager);

        $response = app(ReportExportService::class)->stockPdf();

        $this->assertInstanceOf(\Illuminate\Http\Response::class, $response);
        $this->assertNotEmpty($response->getContent());
    }
}