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
                const qty = parseInt(rawQty);
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
        
        // Formateador de moneda
        const formatMoney = (amount) => {
            return 'Bs. ' + Number(amount).toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        };

        const imgHtml = product.imagen 
            ? `<img src="${product.imagen}" alt="${product.nombre}" class="w-full h-full object-cover">`
            : `<div class="w-full h-full flex items-center justify-center bg-primary/10 text-primary"><span class="material-symbols-outlined text-4xl">inventory_2</span></div>`;

        const details = [];
        if (product.color) details.push(`${product.color}`);
        if (product.talla) details.push(`Talla: ${product.talla}`);
        if (product.grosor) details.push(`Grosor: ${product.grosor}`);
        if (product.cm) details.push(`Medida: ${product.cm}`);
        const detailsStr = details.length > 0 ? details.join(' &bull; ') + '<br>' : '';

        const html = `
            <div class="flex flex-col sm:flex-row gap-5 text-left w-full mt-2">
                <div class="w-20 h-20 shrink-0 rounded-lg overflow-hidden border border-slate-100 bg-slate-50">
                    ${imgHtml}
                </div>
                <div class="flex-1 flex flex-col justify-center">
                    <p class="font-bold text-slate-900 text-sm leading-tight">${product.nombre}</p>
                    <p class="text-xs text-slate-500 mt-1.5">${detailsStr}Cantidad: ${addedQty}</p>
                    <p class="font-bold text-primary mt-1.5">${formatMoney(price)}</p>
                </div>
            </div>
            
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                <p class="text-sm font-semibold text-slate-900">Subtotal <span class="text-slate-500 font-normal">(${totalItems})</span></p>
                <p class="font-black text-lg text-slate-900">${formatMoney(subtotal)}</p>
            </div>
            
            <div class="mt-6 flex flex-col gap-2.5">
                <a href="/checkout" onclick="Swal.close()" class="w-full text-center bg-primary hover:bg-primary-dark text-white font-bold py-3 rounded-xl shadow-lg shadow-primary/20 transition-all text-sm">
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
            let newVal = item.cantidad + parseInt(delta);
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
        const totalItems = items.reduce((acc, current) => acc + current.cantidad, 0);
        const badges = document.querySelectorAll('.cart-badge');
        badges.forEach(badge => {
            if (totalItems > 0) {
                badge.textContent = totalItems;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
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
