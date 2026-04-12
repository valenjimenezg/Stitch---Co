<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop any partial tables from previous failed runs
        Schema::disableForeignKeyConstraints();
        if (Schema::hasTable('producto_variantes')) Schema::drop('producto_variantes');
        if (Schema::hasTable('proveedores')) Schema::drop('proveedores');
        if (Schema::hasTable('orden_detalles')) Schema::drop('orden_detalles');
        if (Schema::hasTable('ordenes')) Schema::drop('ordenes');
        if (Schema::hasTable('notificaciones_crm')) Schema::drop('notificaciones_crm');
        if (Schema::hasTable('inventario_logs')) Schema::drop('inventario_logs');
        Schema::enableForeignKeyConstraints();

        // 1. Proveedores
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('tipo_documento', ['V', 'E', 'J', 'G'])->default('J');
            $table->string('documento_identidad')->unique();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->text('direccion')->nullable();
            $table->timestamps();
        });

        // 2. Users Update
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'direcciones')) {
                $table->json('direcciones')->nullable();
            }
            if (!Schema::hasColumn('users', 'lista_deseos')) {
                $table->json('lista_deseos')->nullable();
            }
            if (!Schema::hasColumn('users', 'tipo_documento')) {
                $table->enum('tipo_documento', ['V', 'E', 'J', 'G'])->nullable();
            }
            if (!Schema::hasColumn('users', 'documento_identidad')) {
                $table->string('documento_identidad')->nullable();
            }
        });

        // Migrate users logic only if the old columns exist
        if (Schema::hasColumn('users', 'document_type') || Schema::hasColumn('users', 'cedula_identidad')) {
            $users = DB::table('users')->get();
            foreach ($users as $u) {
                $td = $u->document_type ?? 'V';
                if (in_array(strtoupper($td), ['V','E','J','G'])) {
                    $td = strtoupper($td);
                } else {
                    $td = 'V';
                }

                $doc = ($u->document_number ?? null) ?: (($u->cedula_identidad ?? null) ?: null);
                if ($doc) {
                    $exists = \DB::table('users')->where('documento_identidad', $doc)->where('id', '!=', $u->id)->exists();
                    if ($exists) {
                        $doc = $doc . '-' . $u->id;
                    }
                } else {
                    $doc = 'SIN-DOC-' . $u->id;
                }

                DB::table('users')->where('id', $u->id)->update([
                    'tipo_documento' => $td,
                    'documento_identidad' => $doc
                ]);
            }

            Schema::table('users', function (Blueprint $table) {
                // Drop old cols if they exist
                if (Schema::hasColumn('users', 'document_type')) $table->dropColumn('document_type');
                if (Schema::hasColumn('users', 'document_number')) $table->dropColumn('document_number');
                if (Schema::hasColumn('users', 'cedula_identidad')) $table->dropColumn('cedula_identidad');
            });
            
            // Wait for columns to drop before applying unique (sometimes needed in sqlite, ok in mysql)
            Schema::table('users', function (Blueprint $table) {
                $table->string('documento_identidad')->unique()->change();
            });
        }

        // 3. Categorias (Rename categories)
        if (Schema::hasTable('categories') && !Schema::hasTable('categorias')) {
            Schema::rename('categories', 'categorias');
        }
        
        if (Schema::hasTable('categorias')) {
            if (Schema::hasColumn('categorias', 'name') && !Schema::hasColumn('categorias', 'nombre')) {
                Schema::table('categorias', function (Blueprint $table) {
                    $table->renameColumn('name', 'nombre');
                });
            }
        } else {
            Schema::create('categorias', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->timestamps();
            });
        }

        // Migrate string categorias from old productos
        if (Schema::hasTable('productos') && Schema::hasColumn('productos', 'categoria')) {
            if (!Schema::hasColumn('productos', 'categoria_id')) {
                Schema::table('productos', function (Blueprint $table) {
                    $table->foreignId('categoria_id')->nullable();
                });
            }
            $productos = DB::table('productos')->get();
            foreach ($productos as $p) {
                if ($p->categoria) {
                    $cat = DB::table('categorias')->where('nombre', $p->categoria)->first();
                    if (!$cat) {
                        $catId = DB::table('categorias')->insertGetId(['nombre' => $p->categoria, 'created_at' => now()]);
                    } else {
                        $catId = $cat->id;
                    }
                    DB::table('productos')->where('id', $p->id)->update(['categoria_id' => $catId]);
                }
            }
            Schema::table('productos', function (Blueprint $table) {
                $table->dropColumn('categoria');
            });
        }

        // 4. Producto Variantes (Self-referencing)
        Schema::create('producto_variantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('producto_variantes')->onDelete('cascade');
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedores')->nullOnDelete();
            
            // Attributes
            $table->string('color')->nullable();
            $table->string('grosor')->nullable();
            $table->string('marca')->nullable();
            
            // Unidades y Conversion
            $table->string('unidad_medida')->default('Unidad');
            $table->decimal('factor_conversion', 10, 4)->default(1);
            $table->decimal('stock_base', 12, 2)->default(0)->comment('Solo usado si parent_id es nulo');
            
            $table->decimal('precio', 10, 2)->nullable();
            $table->decimal('precio_usd', 10, 2)->nullable();
            $table->string('imagen')->nullable();
            
            $table->boolean('en_oferta')->default(false);
            $table->decimal('descuento_porcentaje', 5, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Migrate data to producto_variantes
        if (Schema::hasTable('detalle_productos')) {
            $detalles = DB::table('detalle_productos')->get();
            foreach ($detalles as $det) {
                // Insert Base Variant
                $baseId = DB::table('producto_variantes')->insertGetId([
                    'producto_id' => $det->producto_id,
                    'parent_id' => null,
                    'color' => $det->color ?? null,
                    'grosor' => $det->grosor ?? null,
                    'marca' => $det->marca ?? null,
                    'unidad_medida' => ($det->unidad_medida ?? null) ?: 'Base',
                    'factor_conversion' => 1,
                    'stock_base' => ($det->stock ?? null) ?: 0,
                    'precio' => $det->precio ?? null,
                    'precio_usd' => $det->precio_usd ?? null,
                    'imagen' => $det->imagen ?? null,
                    'en_oferta' => ($det->en_oferta ?? null) ?: false,
                    'descuento_porcentaje' => $det->descuento_porcentaje ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Insert Empaques (Presentations)
                if (Schema::hasTable('empaques_producto')) {
                    $empaques = DB::table('empaques_producto')->where('detalle_producto_id', $det->id)->get();
                    foreach ($empaques as $emp) {
                        DB::table('producto_variantes')->insert([
                            'producto_id' => $det->producto_id,
                            'parent_id' => $baseId,
                            'color' => $det->color ?? null,
                            'grosor' => $det->grosor ?? null,
                            'marca' => $det->marca ?? null,
                            'unidad_medida' => ($emp->nombre ?? null) ?: 'Empaque',
                            'factor_conversion' => ($emp->factor_conversion ?? null) ?: 1,
                            'stock_base' => 0, // Stock ONLY in parent
                            'precio_usd' => $emp->precio_usd ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        // 5. Ordenes
        Schema::create('ordenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('estado')->default('carrito'); // carrito, pendiente_pago, pagado, enviado, completado, cancelado
            
            // Financials
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('iva_amount', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('monto_abonado', 10, 2)->default(0);
            $table->decimal('tasa_bcv_aplicada', 10, 4)->nullable();
            
            // Address & Logistics
            $table->json('direccion_envio')->nullable();
            $table->string('tipo_envio')->nullable();
            $table->string('agencia_envio')->nullable();
            
            // Payment tracking (consolidating pagos)
            $table->string('metodo_pago')->nullable();
            $table->string('referencia_pago')->nullable();
            $table->string('banco_pago')->nullable();
            $table->string('telefono_pago')->nullable();
            $table->string('invoice_number')->nullable();
            
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Migrate Ventas to Ordenes
        if (Schema::hasTable('ventas')) {
            $ventas = DB::table('ventas')->get();
            foreach ($ventas as $v) {
                // Map status
                $estado = strtolower($v->estado ?? 'pagado');
                if (in_array($estado, ['pending', 'procesando'])) $estado = 'pendiente_pago';
                if (in_array($estado, ['pagado', 'paid'])) $estado = 'pagado';
                
                $direccionJSON = json_encode([
                    'direccion' => $v->direccion ?? null,
                    'ciudad' => $v->ciudad ?? null,
                    'calle_envio' => $v->calle_envio ?? null,
                    'estado_envio' => $v->estado_envio ?? null,
                    'codigo_postal' => $v->codigo_postal_envio ?? null
                ]);

                $ordenId = DB::table('ordenes')->insertGetId([
                    'user_id' => $v->user_id ?? null,
                    'estado' => $estado,
                    'subtotal' => ($v->subtotal ?? null) ?: 0,
                    'iva_amount' => ($v->iva_amount ?? null) ?: 0,
                    'delivery_fee' => (($v->delivery_fee ?? null) ?: ($v->costo_envio ?? null)) ?: 0,
                    'total_amount' => ($v->total_amount ?? null) ?: 0,
                    'monto_abonado' => ($v->total_amount ?? null) ?: 0, // Assuming fully paid if it was in ventas
                    'tasa_bcv_aplicada' => $v->tasa_bcv_aplicada ?? null,
                    'direccion_envio' => $direccionJSON,
                    'tipo_envio' => $v->tipo_envio ?? null,
                    'agencia_envio' => $v->agencia_envio ?? null,
                    'metodo_pago' => $v->metodo_pago ?? null,
                    'referencia_pago' => $v->referencia_pago ?? null,
                    'banco_pago' => $v->banco_pago ?? null,
                    'telefono_pago' => $v->telefono_pago ?? null,
                    'invoice_number' => $v->invoice_number ?? null,
                    'completed_at' => $v->completed_at ?? null,
                    'created_at' => ($v->created_at ?? null) ?: now(),
                    'updated_at' => ($v->updated_at ?? null) ?: now(),
                ]);
            }
        }

        // 6. Orden Detalles
        Schema::create('orden_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_id')->constrained('ordenes')->onDelete('cascade');
            $table->foreignId('variante_id')->nullable()->constrained('producto_variantes')->nullOnDelete();
            
            $table->decimal('cantidad', 10, 2);
            $table->decimal('precio_unitario', 10, 2);
            $table->string('unidad_medida_snapshot')->default('Unidad');
            $table->decimal('factor_conversion_snapshot', 10, 4)->default(1);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });

        // 7. Notificaciones CRM
        Schema::create('notificaciones_crm', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('tipo')->default('newsletter'); // newsletter, stock_alert
            $table->foreignId('variante_id')->nullable()->constrained('producto_variantes')->onDelete('cascade');
            $table->boolean('procesado')->default(false);
            $table->timestamps();
        });

        if (Schema::hasTable('subscribers')) {
            $subs = DB::table('subscribers')->get();
            foreach($subs as $s) {
                DB::table('notificaciones_crm')->insert(['email' => $s->email, 'tipo'=>'newsletter', 'procesado' => !($s->is_active ?? true)]);
            }
        }
        if (Schema::hasTable('newsletter_subscribers')) {
            $nsubs = DB::table('newsletter_subscribers')->get();
            foreach($nsubs as $ns) {
                DB::table('notificaciones_crm')->insert(['email' => $ns->email, 'tipo'=>'newsletter']);
            }
        }

        // 8. Inventario Logs
        Schema::create('inventario_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variante_id')->constrained('producto_variantes')->onDelete('cascade');
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedores')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->decimal('cantidad_cambio', 12, 2);
            $table->string('motivo'); // 'venta', 'restock', 'damage'
            $table->foreignId('orden_id')->nullable()->constrained('ordenes')->nullOnDelete();
            $table->timestamps();
        });

        // Finally: DROP OLD TABLES
        $tablesToDrop = [
            'detalle_ventas', 'detalle_carritos', 'ventas', 'carritos',
            'product_presentations', 'empaques_producto', 'detalle_productos', 'products',
            'facturas', 'pagos', 'direcciones', 'lista_deseos', 'configuracions',
            'notificaciones_stock', 'subscribers', 'newsletter_subscribers', 'movimiento_inventarios'
        ];

        // Disable foreign keys checks before dropping
        Schema::disableForeignKeyConstraints();
        foreach ($tablesToDrop as $dt) {
            if (Schema::hasTable($dt)) {
                Schema::drop($dt);
            }
        }
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // One-way migration
    }
};
