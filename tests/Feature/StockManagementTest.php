<?php

namespace Tests\Feature;

use App\Models\Carrito;
use App\Models\DetalleCarrito;
use App\Models\DetalleProducto;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_decrements_after_purchase()
    {
        $user = User::factory()->create();
        $producto = Producto::create(['nombre' => 'Lana', 'categoria' => 'Lanas']);
        $variante = DetalleProducto::create([
            'producto_id' => $producto->id,
            'precio' => 10,
            'stock' => 5,
            'color' => 'Rojo',
        ]);

        $carrito = Carrito::create(['user_id' => $user->id, 'estado' => 'activo']);
        DetalleCarrito::create([
            'carrito_id' => $carrito->id,
            'variante_id' => $variante->id,
            'cantidad' => 2,
        ]);

        $response = $this->actingAs($user)->post(route('checkout.process'), [
            'calle' => 'Calle Falsa 123',
            'ciudad' => 'Cochabamba',
            'metodo' => 'efectivo',
            'tipo_envio' => 'retiro_tienda',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertEquals(3, $variante->fresh()->stock);
    }

    public function test_checkout_fails_if_insufficient_stock()
    {
        $user = User::factory()->create();
        $producto = Producto::create(['nombre' => 'Lana', 'categoria' => 'Lanas']);
        $variante = DetalleProducto::create([
            'producto_id' => $producto->id,
            'precio' => 10,
            'stock' => 1,
            'color' => 'Rojo',
        ]);

        $carrito = Carrito::create(['user_id' => $user->id, 'estado' => 'activo']);
        DetalleCarrito::create([
            'carrito_id' => $carrito->id,
            'variante_id' => $variante->id,
            'cantidad' => 2,
        ]);

        $response = $this->actingAs($user)->from(route('cart.index'))->post(route('checkout.process'), [
            'calle' => 'Calle Falsa 123',
            'ciudad' => 'Cochabamba',
            'metodo' => 'efectivo',
            'tipo_envio' => 'retiro_tienda',
        ]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHasErrors('stock');
        $this->assertEquals(1, $variante->fresh()->stock);
    }

    public function test_stock_restored_on_cancellation()
    {
        $user = User::factory()->create(['rol' => 'admin']);
        $producto = Producto::create(['nombre' => 'Lana', 'categoria' => 'Lanas']);
        $variante = DetalleProducto::create([
            'producto_id' => $producto->id,
            'precio' => 10,
            'stock' => 3,
            'color' => 'Rojo',
        ]);

        $venta = Venta::create([
            'user_id' => $user->id,
            'total_venta' => 20,
            'metodo_pago' => 'efectivo',
            'estado' => 'pendiente',
        ]);

        $venta->detalles()->create([
            'variante_id' => $variante->id,
            'cantidad' => 2,
            'precio_unitario' => 10,
            'subtotal' => 20,
        ]);

        // Simular cancelación por admin
        $response = $this->actingAs($user)->patch(route('admin.orders.status', $venta->id), [
            'estado' => 'cancelado',
        ]);

        $this->assertEquals(5, $variante->fresh()->stock);
    }
}
