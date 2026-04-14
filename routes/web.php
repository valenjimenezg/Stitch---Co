<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

// --- Públicas ---
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/categoria/{slug}', [\App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');
Route::get('/producto/{id}', [\App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
Route::get('/api/producto/{id}', [\App\Http\Controllers\ProductController::class, 'apiShow'])->name('api.products.show');
Route::get('/ofertas', [\App\Http\Controllers\OfferController::class, 'index'])->name('offers.index');
Route::get('/contacto', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');
Route::get('/sobre-nosotros', function() { return view('pages.about'); })->name('pages.about');
Route::get('/preguntas-frecuentes', function() { return view('pages.faq'); })->name('pages.faq');
Route::post('/contacto', [\App\Http\Controllers\ContactController::class, 'send'])->name('contact.send');
Route::get('/api/search', [\App\Http\Controllers\SearchController::class, 'query'])->name('search.query');
Route::get('/api/chatbot/producto', [\App\Http\Controllers\ChatbotController::class, 'buscarProducto'])->name('chatbot.producto');
Route::get('/buscar', [\App\Http\Controllers\SearchController::class, 'index'])->name('search.index');
Route::get('/factura/{id}', [\App\Http\Controllers\InvoiceController::class, 'descargarFacturaPublica'])->name('invoice.public');
Route::get('/test-factura/{id}', [\App\Http\Controllers\InvoiceController::class, 'previewFacturaHTML'])->name('invoice.preview');
Route::post('/newsletter/subscribe', [\App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::post('/producto/notificacion-stock', [\App\Http\Controllers\StockNotificationController::class, 'store'])->name('stock-notification.store');
Route::post('/carrito/agregar', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::get('/carrito', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::patch('/carrito/{item}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/{item}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::post('/lista-deseos/toggle', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');

// --- Autenticación ---
Route::middleware('guest')->group(function () {
    Route::get('/acceso', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::post('/registro', [RegisterController::class, 'store'])->name('register')->middleware('throttle:3,15');
    Route::post('/api/auth/check-document', [RegisterController::class, 'checkDocument'])->name('api.check-document')->middleware('throttle:5,1');

    // Recuperación de Contraseña
    Route::get('/password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showRequestForm'])->name('password.request');
    Route::post('/password/identify', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'identify'])->name('password.identify');
    Route::get('/password/method', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showSelectionForm'])->name('password.selection');
    Route::post('/password/send-code', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendCode'])->name('password.send_code');
    Route::get('/password/verify', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showVerifyForm'])->name('password.verify_form');
    Route::post('/password/verify', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'verifyCode'])->name('password.verify');
    Route::get('/password/new', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/update', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'updatePassword'])->name('password.update');
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/checkout/invitado', function() {
    session()->put('url.intended', route('checkout.index'));
    return redirect()->route('login')
        ->with('info', 'Crea una cuenta rápida para finalizar tu compra')
        ->withInput(['_tab' => 'registro']);
})->name('checkout.guest');

Route::get('/checkout/init', function() {
    return auth()->check() ? redirect()->route('checkout.index') : redirect()->route('checkout.guest');
})->name('checkout.init');

// --- Protegidas (auth) ---
Route::middleware('auth')->group(function () {

    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');

    Route::get('/lista-deseos', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/lista-deseos/mover/{id}', [\App\Http\Controllers\WishlistController::class, 'moveToCart'])->name('wishlist.move');

    Route::get('/mi-perfil', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/mi-perfil', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    Route::get('/direcciones', [\App\Http\Controllers\ProfileController::class, 'addresses'])->name('profile.addresses');
    Route::post('/direcciones', [\App\Http\Controllers\ProfileController::class, 'storeAddress'])->name('profile.addresses.store');
    Route::delete('/direcciones/{id}', [\App\Http\Controllers\ProfileController::class, 'destroyAddress'])->name('profile.addresses.destroy');

    Route::get('/mis-pedidos', [\App\Http\Controllers\ProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/mis-pedidos/{id}/continuar', [\App\Http\Controllers\ProfileController::class, 'resumeOrder'])->name('profile.orders.resume');
    Route::post('/mis-pedidos/{id}/continuar', [\App\Http\Controllers\ProfileController::class, 'storeReference'])->name('profile.orders.store_reference');
    Route::post('/mis-pedidos/{id}/cancelar', [\App\Http\Controllers\ProfileController::class, 'cancelOrder'])->name('profile.orders.cancel');
    Route::get('/mis-pedidos/{id}/factura', [\App\Http\Controllers\InvoiceController::class, 'descargarFactura'])->name('profile.orders.invoice');
});

// --- Admin ---
Route::prefix('admin')->middleware(['auth', 'check.role'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/productos', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/productos/exportar', [\App\Http\Controllers\Admin\ProductController::class, 'export'])->name('admin.products.export');
    Route::get('/productos/reporte-reposicion', [\App\Http\Controllers\Admin\ProductController::class, 'restockReport'])->name('admin.products.restock');
    Route::get('/proveedores/{proveedor}/pdf-reposicion', [\App\Http\Controllers\Admin\ProductController::class, 'downloadProviderPdf'])->name('admin.proveedores.pdf');
    Route::get('/productos/crear', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/productos', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/productos/{id}/editar', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/productos/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/productos/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('admin.products.destroy');

    Route::get('/inventario-logs', [\App\Http\Controllers\Admin\InventarioLogController::class, 'index'])->name('admin.inventario-logs');

    Route::get('/ofertas', [\App\Http\Controllers\Admin\OfferController::class, 'index'])->name('admin.offers.index');
    Route::post('/ofertas', [\App\Http\Controllers\Admin\OfferController::class, 'apply'])->name('admin.offers.apply');

    Route::get('/pedidos', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/pedidos/exportar', [\App\Http\Controllers\Admin\OrderController::class, 'export'])->name('admin.orders.export');
    Route::get('/pedidos/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
    Route::get('/pedidos/{id}/factura', [\App\Http\Controllers\InvoiceController::class, 'descargarFactura'])->name('admin.orders.invoice');
    Route::patch('/pedidos/{id}/estado', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.status');
    Route::post('/pedidos/{id}/entregar', [\App\Http\Controllers\Admin\OrderController::class, 'marcarEntregado'])->name('admin.orders.deliver');
    Route::post('/pedidos/{id}/aprobar', [\App\Http\Controllers\Admin\OrderController::class, 'approvePayment'])->name('admin.orders.approve');
    Route::delete('/pedidos/limpiar-cancelados', [\App\Http\Controllers\Admin\OrderController::class, 'destroyCancelled'])->name('admin.orders.destroy_cancelled');
    Route::get('/pedidos/{order}/generar-factura', [\App\Http\Controllers\Admin\OrderController::class, 'generateInvoice'])->name('admin.orders.generate_invoice');
    Route::post('/pedidos/{order}/pickup', [\App\Http\Controllers\Admin\OrderController::class, 'markAsPickedUp'])->name('admin.orders.picked_up');
    Route::post('/pedidos/{order}/local-delivery', [\App\Http\Controllers\Admin\OrderController::class, 'markAsDeliveredLocally'])->name('admin.orders.delivered_locally');
    Route::delete('/pedidos/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('admin.orders.destroy');
    
    Route::get('/pagos', [\App\Http\Controllers\Admin\OrderController::class, 'payments'])->name('admin.payments');
    Route::get('/envios', [\App\Http\Controllers\Admin\OrderController::class, 'shipping'])->name('admin.shipping');
    
    Route::get('/proveedores', [\App\Http\Controllers\Admin\ProveedorController::class, 'index'])->name('admin.proveedores.index');

    Route::get('/clientes', [\App\Http\Controllers\Admin\DashboardController::class, 'clients'])->name('admin.clients');
    Route::get('/clientes/exportar', [\App\Http\Controllers\Admin\DashboardController::class, 'exportClients'])->name('admin.clients.export');
    Route::get('/comunidad', [\App\Http\Controllers\Admin\DashboardController::class, 'newsletters'])->name('admin.comunidad');
    Route::get('/comunidad/exportar', [\App\Http\Controllers\Admin\DashboardController::class, 'exportNewsletters'])->name('admin.comunidad.export');
    Route::get('/notificaciones-stock', [\App\Http\Controllers\Admin\DashboardController::class, 'stockNotifications'])->name('admin.stock-notifications');
    Route::patch('/notificaciones-stock/{id}', [\App\Http\Controllers\Admin\DashboardController::class, 'updateStockNotification'])->name('admin.stock-notifications.update');

    Route::get('/configuracion-bcv', [\App\Http\Controllers\Admin\SettingsController::class, 'bcvIndex'])->name('admin.settings.bcv');
    Route::post('/configuracion-bcv', [\App\Http\Controllers\Admin\SettingsController::class, 'bcvUpdate'])->name('admin.settings.bcv.update');

    // API Charts
    Route::get('/api/ventas-mensuales', [\App\Http\Controllers\Admin\DashboardController::class, 'ventasMensuales'])->name('admin.api.ventas-mensuales');
    Route::get('/api/ventas-categoria', [\App\Http\Controllers\Admin\DashboardController::class, 'ventasCategoria'])->name('admin.api.ventas-categoria');
});
