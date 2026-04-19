<style>
    /* ===== TRIGGER BUTTON ===== */
    .bot-header-btn {
        padding: 8px;
        background: transparent;
        border-radius: 50%;
        transition: all 0.2s ease;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
    }
    .bot-header-btn:hover {
        background: #f0ebff;
        transform: scale(1.05);
    }
    .bot-trigger-img {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        object-fit: cover;
        object-position: center top;
        border: 2px solid #d4bbff;
        box-shadow: 0 2px 10px rgba(139, 82, 255, 0.25);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .bot-header-btn:hover .bot-trigger-img {
        box-shadow: 0 4px 14px rgba(139, 82, 255, 0.4);
    }
    .bot-status-dot {
        position: absolute;
        bottom: 7px;
        right: 7px;
        width: 9px;
        height: 9px;
        background-color: #22c55e;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.3);
        animation: pulse-dot 2s infinite;
    }
    @keyframes pulse-dot {
        0%, 100% { box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.3); }
        50% { box-shadow: 0 0 0 5px rgba(34, 197, 94, 0); }
    }

    /* ===== WINDOW — bottom-RIGHT, desplazado arriba del botón WhatsApp ===== */
    .bot-window {
        position: fixed;
        bottom: 100px;
        right: 24px;
        width: 390px;
        height: 610px;
        max-height: calc(100vh - 120px);
        max-width: calc(100vw - 32px);
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 24px 48px rgba(22, 15, 35, 0.18), 0 0 0 1px rgba(241, 245, 249, 0.9);
        pointer-events: auto;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transform-origin: bottom right;
        transition: opacity 0.3s ease, transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.3s;
        opacity: 0;
        visibility: hidden;
        transform: scale(0.85) translateY(24px);
        z-index: 9998;
    }
    .bot-window.open {
        opacity: 1;
        visibility: visible;
        transform: scale(1) translateY(0);
    }

    /* ===== HEADER ===== */
    .bot-hdr {
        background: linear-gradient(160deg, #8b52ff 0%, #5e2ecc 100%);
        padding: 0 20px 18px;
        flex-shrink: 0;
        border-bottom: 1px solid rgba(255,255,255,0.07);
        position: relative;
    }
    .bot-hdr-top {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding-top: 14px;
        margin-bottom: 14px;
    }
    .bot-close-btn {
        color: rgba(255,255,255,0.55);
        background: rgba(255, 255, 255, 0.08);
        border: none;
        cursor: pointer;
        width: 28px; height: 28px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.2s;
    }
    .bot-close-btn:hover {
        background: rgba(255,255,255,0.18);
        color: white;
    }
    .bot-hdr-profile {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .bot-hdr-avatar {
        width: 56px; height: 56px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid rgba(255,255,255,0.3);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        background: white;
        flex-shrink: 0;
        position: relative;
    }
    .bot-hdr-avatar img {
        width: 100%; height: 100%;
        object-fit: cover;
        object-position: center top;
        transform: scale(1.7);
    }
    .bot-hdr-info h4 {
        color: white;
        font-size: 17px;
        font-weight: 800;
        margin: 0 0 3px;
        letter-spacing: -0.2px;
    }
    .bot-hdr-info p {
        color: rgba(220, 200, 255, 0.9);
        font-size: 12px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .bot-online-dot {
        width: 7px; height: 7px;
        background: #86efac;
        border-radius: 50%;
        display: inline-block;
        animation: pulse-dot 2s infinite;
    }

    /* ===== QUICK REPLIES SCROLLBAR ===== */
    .bot-chips-bar {
        padding: 11px 16px;
        background: #faf8ff;
        border-bottom: 1px solid #ece6ff;
        display: flex;
        gap: 8px;
        overflow-x: auto;
        white-space: nowrap;
        scrollbar-width: none;
        flex-shrink: 0;
        align-items: center;
    }
    .bot-chips-bar::-webkit-scrollbar { display: none; }
    .bot-chip {
        background: white;
        border: 1px solid #d4bbff;
        color: #8b52ff;
        padding: 6px 12px 6px 10px;
        border-radius: 20px;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 1px 3px rgba(139, 82, 255, 0.08);
        white-space: nowrap;
    }
    .bot-chip .material-symbols-outlined {
        font-size: 15px; color: #8b52ff;
    }
    .bot-chip:hover {
        background: #8b52ff;
        color: white;
        border-color: #8b52ff;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(139, 82, 255, 0.28);
    }
    .bot-chip:hover .material-symbols-outlined { color: white; }

    /* ===== MESSAGES ===== */
    .bot-messages {
        flex: 1;
        padding: 18px 16px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 14px;
        background: #faf8ff;
        scrollbar-width: thin;
        scrollbar-color: #d4bbff transparent;
    }
    .bot-messages::-webkit-scrollbar { width: 4px; }
    .bot-messages::-webkit-scrollbar-track { background: transparent; }
    .bot-messages::-webkit-scrollbar-thumb { background: #d4bbff; border-radius: 4px; }

    /* Message Row (avatar + bubble) */
    .bot-msg-row {
        display: flex;
        align-items: flex-end;
        gap: 9px;
        animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .bot-msg-row.user-row { flex-direction: row-reverse; }

    .bot-msg-avatar {
        width: 30px; height: 30px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        border: 2px solid #e8d8ff;
        background: white;
    }
    .bot-msg-avatar img {
        width: 100%; height: 100%;
        object-fit: cover;
        object-position: center top;
        transform: scale(1.7);
    }

    .bot-bubble {
        max-width: 78%;
        padding: 12px 16px;
        font-size: 14px;
        line-height: 1.55;
        border-radius: 18px;
        word-wrap: break-word;
    }
    .bot-bubble.bot {
        background: white;
        color: #1e293b;
        border-bottom-left-radius: 5px;
        border: 1px solid #ece6ff;
        box-shadow: 0 2px 6px rgba(139, 82, 255, 0.06);
    }
    .bot-bubble.user {
        background: linear-gradient(135deg, #8b52ff, #6d3de0);
        color: white;
        border-bottom-right-radius: 5px;
        box-shadow: 0 4px 12px rgba(139, 82, 255, 0.25);
    }

    /* Typing */
    .typing-indicator {
        display: none;
        padding: 13px 17px;
        background: white;
        border-radius: 18px;
        border-bottom-left-radius: 5px;
        border: 1px solid #ece6ff;
        align-self: flex-start;
        width: fit-content;
        gap: 5px;
        align-items: center;
        box-shadow: 0 2px 6px rgba(139, 82, 255, 0.06);
    }
    .typing-dot {
        width: 7px; height: 7px;
        background: #d4bbff;
        border-radius: 50%;
        animation: bounce-dot 1.4s infinite ease-in-out both;
    }
    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }
    @keyframes bounce-dot {
        0%, 80%, 100% { transform: scale(0.6); background: #d4bbff; }
        40% { transform: scale(1); background: #8b52ff; }
    }

    /* ===== INPUT AREA ===== */
    .bot-input-area {
        background: white;
        padding: 12px 16px;
        border-top: 1px solid #ece6ff;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }
    .bot-input-pill {
        flex: 1;
        background: #f5f0ff;
        border-radius: 24px;
        display: flex;
        align-items: center;
        padding: 4px 5px 4px 16px;
        border: 1.5px solid transparent;
        transition: all 0.2s;
    }
    .bot-input-pill:focus-within {
        border-color: #c4a0ff;
        background: white;
        box-shadow: 0 0 0 4px rgba(139, 82, 255, 0.10);
    }
    .bot-input {
        flex: 1;
        background: transparent;
        border: none;
        font-size: 14px;
        outline: none;
        color: #1e293b;
        padding: 9px 0;
        font-family: inherit;
    }
    .bot-input::placeholder { color: #c4a0ff; }
    .bot-send-btn {
        background: linear-gradient(135deg, #8b52ff, #6d3de0);
        color: white;
        width: 38px; height: 38px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(139, 82, 255, 0.35);
    }
    .bot-send-btn:hover {
        transform: scale(1.08);
        box-shadow: 0 6px 14px rgba(139, 82, 255, 0.45);
    }
    .bot-send-btn .material-symbols-outlined { font-size: 19px; margin-left: 2px; }

    .bot-footer-note {
        text-align: center;
        font-size: 10.5px;
        color: #c4a0ff;
        padding: 0 0 10px;
        letter-spacing: 0.2px;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

{{-- ===== HEADER TRIGGER BUTTON ===== --}}
<button type="button" class="bot-header-btn" onclick="toggleBot()" aria-label="Abrir Costurín" title="Costurín — Asistente Virtual">
    <img src="{{ asset('img/logo/costurin.png') }}" alt="Costurín" class="bot-trigger-img">
    <span class="bot-status-dot"></span>
</button>

{{-- ===== CHAT WINDOW ===== --}}
<div class="bot-window" id="bot-window">

    {{-- Header --}}
    <div class="bot-hdr">
        <div class="bot-hdr-top">
            <button onclick="toggleBot()" class="bot-close-btn" aria-label="Cerrar">
                <span class="material-symbols-outlined" style="font-size:18px">close</span>
            </button>
        </div>
        <div class="bot-hdr-profile">
            <div class="bot-hdr-avatar">
                <img src="{{ asset('img/logo/costurin.png') }}" alt="Costurín">
            </div>
            <div class="bot-hdr-info">
                <h4>Costurín</h4>
                <p>
                    <span class="bot-online-dot"></span>
                    Asistente Virtual · Stitch & Co
                </p>
            </div>
        </div>
    </div>

    {{-- Quick-Reply Chips (Hidden for menu-driven mode) --}}
    <div class="bot-chips-bar" style="display: none;">
    </div>

    {{-- Messages --}}
    <div class="bot-messages" id="bot-messages">
        {{-- Bienvenida --}}
        <div class="bot-msg-row">
            <div class="bot-msg-avatar">
                <img src="{{ asset('img/logo/costurin.png') }}" alt="Costurín">
            </div>
            <div class="bot-bubble bot">
                ¡Hola! 👋 Soy <strong>Costurín</strong>, tu asistente en <strong>Stitch & Co</strong>.<br><br>
                Por favor selecciona una de las siguientes opciones para poder ayudarte:<br>
                <div style="display:flex; flex-direction:column; gap:8px; margin-top:14px;">
                    <button class="bot-chip" style="width:100%; justify-content:flex-start;" onclick="sendAction('pagos')">💸 Formas de Pago</button>
                    <button class="bot-chip" style="width:100%; justify-content:flex-start;" onclick="sendAction('abonos')">💳 Abonos (Sistema de Apartado)</button>
                    <button class="bot-chip" style="width:100%; justify-content:flex-start;" onclick="sendAction('envio')">🚚 Envíos y Delivery</button>
                    <button class="bot-chip" style="width:100%; justify-content:flex-start;" onclick="sendAction('horario')">⏰ Horarios de Atención</button>
                    <button class="bot-chip" style="width:100%; justify-content:flex-start;" onclick="sendAction('devolucion')">🔄 Devoluciones y Cambios</button>
                    <button class="bot-chip" style="width:100%; justify-content:flex-start;" onclick="sendAction('contacto')">📞 Contacto con Asesor</button>
                </div>
            </div>
        </div>

        <div class="typing-indicator" id="bot-typing">
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
        </div>
    </div>

    {{-- Input (Hidden for menu-driven mode) --}}
    <div class="bot-input-area" style="display: none;">
        <div class="bot-input-pill">
            <input type="text" id="bot-input-field" class="bot-input" placeholder="Pregúntame algo…" onkeypress="handleEnter(event)" autocomplete="off">
            <button class="bot-send-btn" onclick="sendUserMessage()">
                <span class="material-symbols-outlined">send</span>
            </button>
        </div>
    </div>
    <p class="bot-footer-note">Costurín es un asistente automático · No somos IA generativa</p>

</div>

<script>
(function () {
    let isBotOpen = false;
    const botWindow  = document.getElementById('bot-window');
    const msgBox     = document.getElementById('bot-messages');
    const inputField = document.getElementById('bot-input-field');
    const typingEl   = document.getElementById('bot-typing');
    const AVATAR     = "{{ asset('img/logo/costurin.png') }}";

    // ─── Knowledge Base ───────────────────────────────────────────────
    const kb = [
        {
            keys: ['horario','hora','abierto','abren','cierran','atiend'],
            title: 'Horario de Atención',
            response: `Atendemos de <strong>Lunes a Sábado de 8:00 AM a 6:00 PM</strong> en nuestra tienda física.<br>
                       Los pedidos en línea se pueden hacer <strong>las 24 horas</strong> del día.`
        },
        {
            keys: ['ubicacion','ubicación','donde','direccion','dirección','local','guanare'],
            title: 'Nuestra Ubicación',
            response: `Puedes encontrarnos en <strong>Guanare, Estado Portuguesa</strong>.<br>
                       Contamos con parking disponible y atendemos pedidos con <strong>delivery dentro de la ciudad</strong>.`
        },
        {
            keys: ['envio','envío','delivery','despacho','entrega','llega'],
            title: 'Envíos y Delivery',
            response: `Ofrecemos <strong>Delivery</strong> en casi toda la Parroquia Guanare con tarifas entre <strong>$1 y $2</strong>.<br>
                       También puedes retirar en tienda de forma gratuita.<br>
                       Los pedidos se procesan en <strong>24–48 horas hábiles</strong>.`
        },
        {
            keys: ['pago','pagos','pagar','metodos','métodos','zelle','transferencia','efectivo','movil','móvil'],
            title: 'Formas de Pago',
            response: `Aceptamos:<br>
                       • 📲 <strong>Pago Móvil</strong> (Bancos nacionales)<br>
                       • 🏦 <strong>Transferencia Bancaria</strong><br>
                       • 💵 <strong>Efectivo</strong> (en tienda)<br>
                       • ✂️ <strong>Pago a Crédito (Apartado)</strong> — Paga el 30% ahora y el resto en 15 días.`
        },
        {
            keys: ['credito','crédito','abono','abonar','apartado','apartar','cuota','plazo','15 dia'],
            title: 'Sistema de Abonos (Crédito)',
            response: `Nuestro <strong>Sistema de Apartado</strong> te permite:<br>
                       ✅ Pagar solo el <strong>30% inicial</strong><br>
                       ✅ <strong>7 días</strong> para cancelar el resto<br>
                       ✅ Mínimo de compra: <strong>$30 USD</strong><br>
                       ⚠️ El depósito no es reembolsable en efectivo — solo en crédito en tienda.<br>
                       Solo disponible para <strong>retiro en tienda</strong>.`
        },
        {
            keys: ['menu_principal', 'menu', 'opciones'],
            title: 'Menú Principal',
            response: `¿En qué más te puedo ayudar?<br>
                       <div style="display:flex; flex-direction:column; gap:8px; margin-top:14px;">
                           <button class="bot-chip" style="width:100%; justify-content:flex-start;" onclick="sendAction('pagos')">💸 Formas de Pago</button>
                           <button class="bot-chip" style="width:100%; justify-content:flex-start;" onclick="sendAction('abonos')">💳 Abonos (Sistema de Apartado)</button>
                           <button class="bot-chip" style="width:100%; justify-content:flex-start;" onclick="sendAction('envio')">🚚 Envíos y Delivery</button>
                           <button class="bot-chip" style="width:100%; justify-content:flex-start;" onclick="sendAction('horario')">⏰ Horarios de Atención</button>
                           <button class="bot-chip" style="width:100%; justify-content:flex-start;" onclick="sendAction('devolucion')">🔄 Devoluciones y Cambios</button>
                           <button class="bot-chip" style="width:100%; justify-content:flex-start;" onclick="sendAction('contacto')">📞 Contacto con Asesor</button>
                       </div>`
        },
        {
            keys: ['producto','productos','que venden','tela','lana','tejido','boton','accesorio','hilo','aguja'],
            title: 'Nuestros Productos',
            response: `En Stitch & Co encuentras:<br>
                       🧶 <strong>Lanas</strong> — para tejido a mano y crochet<br>
                       🪡 <strong>Telas</strong> — algodón, denim, encaje, poliéster y más<br>
                       🧵 <strong>Accesorios de Costura</strong> — agujas, tijeras, cierres, hilos<br>
                       🔘 <strong>Botones</strong> — decorativos y funcionales<br>
                       🎨 <strong>Manualidades</strong> — todo para tus proyectos creativos<br><br>
                       Puedes explorar el catálogo completo en la sección de <a href="/stitch-and-co/public/productos" class="text-indigo-600 underline font-bold">Productos</a>.`
        },
        {
            keys: ['medida','medidas','talla','tallas','metro','tamaño','largo','ancho'],
            title: 'Medidas y Cantidades',
            response: `Las <strong>telas y lanas</strong> se venden por <strong>metro lineal</strong>. Puedes pedir la cantidad exacta que necesitas.<br><br>
                       📏 1 metro de tela = aprox. 140–150 cm de ancho (según el tipo)<br>
                       🧶 Las lanas vienen en <strong>ovillos o madejas</strong> — consulta el gramaje disponible en cada producto.<br><br>
                       En el detalle de cada producto verás la unidad de medida correspondiente.`
        },
        {
            keys: ['devolucion','devolución','devolver','cambio','cambiar','garantia','garantía','reembolso'],
            title: 'Devoluciones y Cambios',
            response: `Nuestro proceso de cambios:<br>
                       ✅ <strong>Cambio en 7 días</strong> si el producto está en perfectas condiciones<br>
                       ✅ Presentar <strong>factura o comprobante de pago</strong><br>
                       ⚠️ <strong>No se realizan reembolsos</strong> en efectivo sin justificación<br>
                       ⚠️ Los depósitos del sistema de crédito son en <strong>crédito de tienda</strong><br><br>
                       Contáctanos por <a href="https://wa.me/58424565914" class="text-indigo-600 underline font-bold" target="_blank">WhatsApp</a> para iniciar el proceso.`
        },
        {
            keys: ['telefono','teléfono','whatsapp','contacto','llamar','comunicar'],
            title: 'Contacto',
            response: `Puedes contactarnos por:<br>
                       📱 <strong>WhatsApp</strong>: <a href="https://wa.me/58424565914" class="text-indigo-600 underline font-bold" target="_blank">+58 424-565 9154</a><br>
                       📧 <strong>Email</strong>: info@stitchandco.com.ve<br>
                       🕐 Respondemos en horario de tienda: Lun–Sáb 8AM–6PM`
        },
        {
            keys: ['descuento','oferta','promocion','promoción','rebaja','sale'],
            title: 'Ofertas',
            response: `¡Tenemos una sección especial de <strong>Ofertas</strong>! 🎉<br>
                       Visita la pestaña <a href="/stitch-and-co/public/ofertas" class="text-indigo-600 underline font-bold">Ofertas</a> en el menú principal para ver los descuentos activos.<br><br>
                       También puedes suscribirte a nuestro boletín de noticias para recibir promos exclusivas.`
        },
        {
            keys: ['hola','buenas','buenos','saludos','hey','hi'],
            response: `¡Hola de nuevo! 😊 ¿En qué puedo ayudarte hoy?`
        },
        {
            keys: ['gracias','perfecto','excelente','chevere','chévere','genial','ok','listo','bien'],
            response: `¡De nada! Ha sido un placer ayudarte 🎀<br>Si tienes más preguntas, aquí estaré.`
        }
    ];

    // ─── Toggle ────────────────────────────────────────────────────────
    window.toggleBot = function () {
        isBotOpen = !isBotOpen;
        botWindow.classList.toggle('open', isBotOpen);
        if (isBotOpen) {
            setTimeout(() => inputField.focus(), 350);
            scrollBottom();
        }
    };

    // ─── Keyboard ──────────────────────────────────────────────────────
    window.handleEnter = function (e) {
        if (e.key === 'Enter') sendUserMessage();
    };

    // ─── Quick-chip ────────────────────────────────────────────────────
    window.sendAction = function (tag) {
        addUserRow(tag);
        respond(tag.toLowerCase());
    };

    // ─── User Message ──────────────────────────────────────────────────
    window.sendUserMessage = function () {
        const text = inputField.value.trim();
        if (!text) return;
        inputField.value = '';
        addUserRow(text);
        respond(text.toLowerCase());
    };

    // ─── Rows ──────────────────────────────────────────────────────────
    function addUserRow (text) {
        const row = document.createElement('div');
        row.className = 'bot-msg-row user-row';
        row.innerHTML = `<div class="bot-bubble user">${text}</div>`;
        msgBox.insertBefore(row, typingEl);
        scrollBottom();
    }

    function addBotRow (html) {
        const row = document.createElement('div');
        row.className = 'bot-msg-row';
        row.innerHTML = `
            <div class="bot-msg-avatar"><img src="${AVATAR}" alt="Costurín"></div>
            <div class="bot-bubble bot">${html}</div>`;
        msgBox.insertBefore(row, typingEl);
        scrollBottom();
    }

    // ─── palabras que indican pregunta sobre UN producto específico ────
    const PRODUCT_TRIGGERS = [
        'hay','tienen','tienes','cuantos','cuántos','stock','disponible',
        'agotado','agotada','precio','costo','vale','tela','lana','hilo',
        'boton','botón','cierres','cierre','aguja','agujas','color','colores',
        'metro','metros','ovillo','madejas','medida','grosor','marca'
    ];

    // Palabras del KB estático que NO deben pasar al buscador de productos
    const KB_ONLY = [
        'horario','hora','abierto','abren','cierran','ubicacion','donde',
        'direccion','envio','delivery','pago','pagos','credito','abono',
        'apartado','devolucion','cambio','oferta','descuento','whatsapp',
        'telefono','contacto','hola','buenas','gracias'
    ];

    // ─── Respond ───────────────────────────────────────────────────────
    function respond (query) {
        typingEl.style.display = 'flex';
        inputField.disabled = true;
        scrollBottom();

        const norm = q => q.normalize('NFD').replace(/[\u0300-\u036f]/g,'').toLowerCase();
        const nq   = norm(query);

        // 1) ¿Coincide con KB estático?
        const kbMatch = kb.find(rule => rule.keys.some(k => nq.includes(norm(k))));

        // Si es KB estático Y no contiene un trigger de producto, responder del KB
        const isKbOnly = kbMatch && KB_ONLY.some(k => nq.includes(norm(k)));

        // 2) ¿Parece consulta de producto?
        const isProductQuery = !isKbOnly && (
            PRODUCT_TRIGGERS.some(t => nq.includes(norm(t))) || query.length > 3
        );

        if (isKbOnly) {
            // Respuesta rápida del KB
            setTimeout(() => {
                typingEl.style.display = 'none';
                inputField.disabled = false;
                addBotRow(kbMatch.response);
                inputField.focus();
            }, 600 + Math.random() * 400);
            return;
        }

        if (isProductQuery) {
            // Llamar al API en tiempo real
            fetch(`{{ route('chatbot.producto') }}?q=${encodeURIComponent(query)}`)
                .then(r => r.json())
                .then(data => {
                    typingEl.style.display = 'none';
                    inputField.disabled = false;

                    if (data.found && data.resultados.length > 0) {
                        data.resultados.forEach(p => addBotRow(buildProductCard(p)));
                    } else {
                        // Si no hay producto, intentar KB
                        if (kbMatch) {
                            addBotRow(kbMatch.response);
                        } else {
                            addBotRow(`No encontré el producto "<strong>${query}</strong>" en nuestro catálogo. 🔍<br>
                                Intenta con el nombre completo o una palabra clave (ej: <em>tela algodón</em>, <em>lana gruesa</em>, <em>agujas</em>).`);
                        }
                    }
                    inputField.focus();
                })
                .catch(() => {
                    typingEl.style.display = 'none';
                    inputField.disabled = false;
                    addBotRow(`Mmm… tuve un problema consultando el catálogo 😕. Inténtalo de nuevo o contáctanos por <a href="https://wa.me/584245659154" class="font-bold underline" target="_blank">WhatsApp</a>.`);
                    inputField.focus();
                });
            return;
        }

        // Fallback KB o mensaje genérico
        setTimeout(() => {
            typingEl.style.display = 'none';
            inputField.disabled = false;
            const answer = kbMatch
                ? kbMatch.response
                : `Mmm… no entendí bien esa pregunta 😅. Prueba con los <strong>botones de acceso rápido</strong> de arriba o escríbeme el nombre de un producto para consultarlo.`;
            addBotRow(answer);
            inputField.focus();
        }, 700 + Math.random() * 500);
    }

    // ─── Product Card Builder ──────────────────────────────────────────
    function buildProductCard (p) {
        const stockIcon  = p.hay_stock ? '🟢' : '🔴';
        const stockLabel = p.hay_stock
            ? `<span style="color:#16a34a">Disponible</span>`
            : `<span style="color:#dc2626">Agotado</span>`;

        const stockDetail = p.hay_stock
            ? `<strong>${p.stock_total}</strong> ${p.unidades[0] ?? 'und.'} en inventario`
            : `Sin stock actualmente`;

        const coloresHtml = p.colores.length
            ? `<br>🎨 <strong>Colores:</strong> ${p.colores.join(', ')}`
            : '';

        const grosoresHtml = p.grosores.length
            ? `<br>📐 <strong>Grosor:</strong> ${p.grosores.join(', ')}`
            : '';

        const unidadHtml = p.unidades.length
            ? `<br>📏 <strong>Unidad:</strong> ${p.unidades.join(' / ')}`
            : '';

        let precioHtml = '';
        if (p.precio_min && p.precio_max) {
            if (p.precio_min === p.precio_max) {
                precioHtml = `<br>💲 <strong>Precio:</strong> $${parseFloat(p.precio_min).toFixed(2)}`;
            } else {
                precioHtml = `<br>💲 <strong>Precio:</strong> $${parseFloat(p.precio_min).toFixed(2)} – $${parseFloat(p.precio_max).toFixed(2)}`;
            }
        }

        const ofertaHtml = p.en_oferta
            ? `<br>🏷️ <strong style="color:#8b52ff">¡Tiene variantes en oferta!</strong>`
            : '';

        const agotadasHtml = p.variantes_agotadas > 0 && p.hay_stock
            ? `<br><small style="color:#94a3b8">${p.variantes_agotadas} variante(s) agotada(s)</small>`
            : '';

        return `
            <div style="border:1px solid #ece6ff;border-radius:14px;padding:14px 16px;background:#faf8ff;">
                <div style="font-weight:800;font-size:15px;color:#1e293b;margin-bottom:6px;">
                    📦 ${p.nombre}
                </div>
                <div style="font-size:13px;color:#64748b;margin-bottom:8px;">${p.categoria}</div>
                <div style="font-size:13.5px;line-height:1.7;color:#374151;">
                    ${stockIcon} <strong>Stock:</strong> ${stockLabel} — ${stockDetail}
                    ${unidadHtml}
                    ${coloresHtml}
                    ${grosoresHtml}
                    ${precioHtml}
                    ${ofertaHtml}
                    ${agotadasHtml}
                </div>
                <a href="{{ url('/producto') }}/${p.id}" target="_blank"
                   style="display:inline-block;margin-top:10px;background:#8b52ff;color:white;
                          padding:6px 14px;border-radius:20px;font-size:12px;font-weight:700;text-decoration:none;">
                   Ver producto →
                </a>
            </div>`;
    }

    // ─── Scroll ────────────────────────────────────────────────────────
    function scrollBottom () {
        setTimeout(() => msgBox.scrollTop = msgBox.scrollHeight, 20);
    }
})();
</script>
