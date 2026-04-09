const Cart = {
    getItems: function() {
        try {
            const cartStr = localStorage.getItem('stitch_cart');
            const parsed = cartStr ? JSON.parse(cartStr) : [];
            return Array.isArray(parsed) ? parsed : [];
        } catch (e) {
            return [];
        }
    },
    save: function(items) {
        localStorage.setItem('stitch_cart', JSON.stringify(items));
        this.updateBadge();
    },
    add: function(variantId, rawQty) {
        const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
        return fetch(`${baseUrl}/api/producto/${variantId}`)
            .then(res => res.json())
            .then(product => {
                const qty = parseFloat(rawQty);
                if (product.stock < qty) {
                    alert('Stock insuficiente.');
                    return false;
                }
                const items = this.getItems();
                const existing = items.find(i => parseInt(i.id) === parseInt(variantId));
                if (existing) {
                    if (existing.cantidad + qty > product.stock) {
                         alert('No hay suficiente stock.');
                         return false;
                    }
                    existing.cantidad += qty;
                } else {
                    items.push({
                        ...product,
                        cantidad: qty
                    });
                }
                this.save(items);
                // Custom event to trigger UI updates
                window.dispatchEvent(new Event('cartUpdated'));

                // Mostrar el modal de confirmación
                if (typeof this.showAddedModal === 'function') {
                    this.showAddedModal(product, qty);
                }
                return true;
            });
    },
    showAddedModal: function(product, addedQty) {
        if (typeof Swal === 'undefined') return;
        
        const items = this.getItems();
        const totalItems = items.reduce((acc, current) => acc + current.cantidad, 0);
        const subtotal = this.getTotal();
        const price = product.en_oferta ? product.precio_con_descuento : product.precio;
        
        const bcvRate = window.bcvRate || 1;
        
        // Formateadores de moneda
        const formatMoneyBs = (amountUsd) => {
            return 'Bs. ' + Number(amountUsd * bcvRate).toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        };
        const formatMoneyRef = (amountUsd) => {
            return 'Ref: $' + Number(amountUsd).toFixed(2);
        };

        const imgHtml = product.imagen 
            ? `<img src="${product.imagen}" alt="${product.nombre}" style="width: 100%; height: 100%; object-fit: cover;">`
            : `<div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: #f1f5f9; color: #6366f1;"><span class="material-symbols-outlined text-4xl">inventory_2</span></div>`;

        const details = [];
        if (product.color) details.push(`${product.color}`);
        if (product.talla) details.push(`Talla: ${product.talla}`);
        if (product.grosor) details.push(`Grosor: ${product.grosor}`);
        if (product.cm) details.push(`Medida: ${product.cm}`);
        if (product.unidad_medida) details.push(`Venta por: ${product.unidad_medida}`);
        const detailsStr = details.length > 0 ? details.join(' &bull; ') + '<br>' : '';

        const html = `
            <div class="flex flex-col sm:flex-row gap-5 text-left w-full mt-2">
                <a href="/producto/${product.id}" class="block" style="width: 80px; height: 80px; flex-shrink: 0; border-radius: 0.5rem; overflow: hidden; border: 1px solid #f1f5f9; background-color: #f8fafc;" title="Ver producto">
                    ${imgHtml}
                </a>
                <div class="flex-1 flex flex-col justify-center">
                    <a href="/producto/${product.id}" class="font-bold text-slate-900 text-sm leading-tight hover:text-primary transition-colors" title="Ver producto">${product.nombre}</a>
                    <p class="text-xs text-slate-500 mt-1.5">${detailsStr}Cantidad añadida: ${addedQty}</p>
                    <div class="mt-1.5 leading-tight">
                        <span class="font-black text-primary text-base">${formatMoneyBs(price * addedQty)}</span>
                        <span class="text-xs font-bold text-slate-400 pl-1">${formatMoneyRef(price * addedQty)}</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                <p class="text-sm font-semibold text-slate-900">Subtotal <span class="text-slate-500 font-normal">(${totalItems})</span></p>
                <div class="text-right leading-none">
                    <p class="font-black text-lg text-slate-900 inline-block">${formatMoneyBs(subtotal)}</p>
                    <span class="text-xs font-bold text-slate-400 block mt-1">${formatMoneyRef(subtotal)}</span>
                </div>
            </div>
            
            <div class="mt-6 flex flex-col gap-2.5">
                <a href="/checkout/init" onclick="Swal.close()" class="w-full text-center bg-primary hover:bg-primary-dark text-white font-bold py-3 rounded-xl shadow-lg shadow-primary/20 transition-all text-sm">
                    Proceder al Pago
                </a>
                <a href="/carrito" onclick="Swal.close()" class="w-full text-center bg-white border border-slate-300 hover:border-slate-400 text-slate-700 font-bold py-3 rounded-xl transition-all text-sm">
                    Ver Carrito Completo
                </a>
            </div>
        `;

        Swal.fire({
            title: '¡Añadido al Carrito!',
            html: html,
            showConfirmButton: false,
            showCloseButton: true,
            width: 440,
            padding: '1.5rem',
            customClass: {
                title: 'text-lg font-extrabold text-slate-900 border-b border-slate-100 pb-3 text-left m-0',
                popup: 'rounded-2xl',
                closeButton: 'text-slate-400 hover:text-slate-900 focus:outline-none mt-2 mr-2',
                htmlContainer: 'm-0 text-left overflow-hidden'
            }
        });
    },
    updateQty: function(variantId, delta) {
        let items = this.getItems();
        let item = items.find(i => parseInt(i.id) === parseInt(variantId));
        if (item) {
            let newVal = parseFloat(item.cantidad) + parseFloat(delta);
            // Avoid extreme precision bugs:
            newVal = Math.round(newVal * 100) / 100;
            
            if (newVal > item.stock) {
                alert('No hay suficiente stock.');
                return;
            }
            if (newVal < 1) newVal = 0;
            
            if (newVal === 0) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: '¿Deseas eliminar este producto de tu carrito?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7c3aed',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        customClass: {
                            title: 'text-xl font-bold text-slate-800',
                            popup: 'rounded-2xl',
                            confirmButton: 'rounded-lg font-bold px-6 outline-none',
                            cancelButton: 'rounded-lg font-bold px-6 outline-none'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            items = items.filter(i => i.id !== item.id);
                            this.save(items);
                            window.dispatchEvent(new Event('cartUpdated'));
                        }
                    });
                } else {
                    if (confirm('¿Deseas eliminar este producto de tu carrito?')) {
                        items = items.filter(i => i.id !== item.id);
                        this.save(items);
                        window.dispatchEvent(new Event('cartUpdated'));
                    }
                }
            } else {
                item.cantidad = newVal;
                this.save(items);
                window.dispatchEvent(new Event('cartUpdated'));
            }
        }
    },
    remove: function(variantId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Estás seguro?',
                text: '¿Deseas eliminar este producto de tu carrito?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#7c3aed',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    title: 'text-xl font-bold text-slate-800',
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg font-bold px-6 outline-none',
                    cancelButton: 'rounded-lg font-bold px-6 outline-none'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let items = this.getItems();
                    items = items.filter(i => parseInt(i.id) !== parseInt(variantId));
                    this.save(items);
                    window.dispatchEvent(new Event('cartUpdated'));
                }
            });
        } else {
            if (confirm('¿Deseas eliminar este producto de tu carrito?')) {
                let items = this.getItems();
                items = items.filter(i => parseInt(i.id) !== parseInt(variantId));
                this.save(items);
                window.dispatchEvent(new Event('cartUpdated'));
            }
        }
    },
    clear: function() {
        localStorage.removeItem('stitch_cart');
        this.updateBadge();
        window.dispatchEvent(new Event('cartUpdated'));
    },
    updateBadge: function() {
        const items = this.getItems();
        // Count unique rows (artículos únicos) NOT the sum of quantities
        const totalItems = items.length;
        
        const badges = document.querySelectorAll('.cart-badge');
        badges.forEach(badge => {
            if (totalItems > 0) {
                // Formatting for 99+
                const displayCount = totalItems > 99 ? '99+' : totalItems;
                badge.textContent = displayCount;
                
                // Apply pill classes dynamically
                if (totalItems > 9) {
                    badge.classList.remove('size-4', 'text-[10px]');
                    badge.classList.add('min-w-[20px]', 'px-1', 'h-4', 'text-[9px]');
                } else {
                    badge.classList.remove('min-w-[20px]', 'px-1', 'h-4', 'text-[9px]');
                    badge.classList.add('size-4', 'text-[10px]');
                }
                
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        });
    },
    getTotal: function() {
        const items = this.getItems();
        return items.reduce((acc, current) => {
            const price = current.en_oferta ? current.precio_con_descuento : current.precio;
            return acc + (price * current.cantidad);
        }, 0);
    }
};

document.addEventListener('DOMContentLoaded', () => {
    Cart.updateBadge();

    // Hijack ALL form submissions going to /carrito/agregar
    document.querySelectorAll('form[action*="/carrito/agregar"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-xl">sync</span>';
            btn.disabled = true;

            const variantId = form.querySelector('input[name="variante_id"]').value;
            const cantidad = form.querySelector('input[name="cantidad"]').value;

            Cart.add(variantId, cantidad).then(success => {
                btn.disabled = false;
                if (success) {
                    btn.innerHTML = '<span class="material-symbols-outlined text-xl">check</span>';
                    setTimeout(() => { btn.innerHTML = originalText; }, 2000);
                } else {
                    btn.innerHTML = originalText;
                }
            }).catch(err => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                alert('Error al agregar al carrito');
            });
        });
    });
});
