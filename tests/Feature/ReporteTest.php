<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Orden;
use App\Models\Producto;
use App\Models\ProductoVariante;
use App\Models\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReporteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Creamos la configuración por defecto de tasa BCV en la caché
        \Illuminate\Support\Facades\Cache::put('bcv_rate', 36.50);
        \Illuminate\Support\Facades\Cache::put('config_usar_tasa_manual', true);
        \Illuminate\Support\Facades\Cache::put('config_tasa_bcv_manual', 36.50);
    }

    public function test_admin_can_access_reports_dashboard()
    {
        $admin = User::factory()->create(['rol' => 'admin']);

        // Test index (ventas)
        $response = $this->actingAs($admin)->get(route('admin.reportes.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.reportes.index');
        $response->assertViewHas('tipo', 'ventas');

        // Test index (inventario)
        $response = $this->actingAs($admin)->get(route('admin.reportes.index', ['tipo' => 'inventario']));
        $response->assertStatus(200);
        $response->assertViewHas('tipo', 'inventario');

        // Test index (clientes)
        $response = $this->actingAs($admin)->get(route('admin.reportes.index', ['tipo' => 'clientes']));
        $response->assertStatus(200);
        $response->assertViewHas('tipo', 'clientes');
    }

    public function test_admin_can_download_pdf_reports()
    {
        $admin = User::factory()->create(['rol' => 'admin']);

        // Crear una categoría, un producto y una variante de prueba
        $categoria = Categoria::create(['nombre' => 'Hilados']);
        $producto = Producto::create([
            'nombre' => 'Hilo Acrílico',
            'categoria_id' => $categoria->id
        ]);
        
        ProductoVariante::create([
            'producto_id' => $producto->id,
            'precio' => 5.0,
            'stock_base' => 10,
            'color' => 'Azul',
            'unidad_medida' => 'unidad'
        ]);

        $response = $this->actingAs($admin)->get(route('admin.reportes.pdf', ['tipo' => 'inventario']));
        $response->assertStatus(200);
        
        // Assert header is PDF format
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_regular_client_cannot_access_reports()
    {
        $client = User::factory()->create(['rol' => 'cliente']);

        $response = $this->actingAs($client)->get(route('admin.reportes.index'));
        $response->assertRedirect(route('home'));
    }
}
