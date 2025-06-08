@extends('layouts.pos')

@section('title', 'Ventas')

@section('content')
    <!-- Contenido -->
    <!--Listado de productos-->
    <div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Ventas</h1>
    <!-- Listado de productos -->
    <div class="flex gap-4">
        <div class="w-2/3">
            <div id="productos" class="grid grid-cols-3 gap-4">
                <!-- Aquí se generarán los cards de productos dinámicamente -->
            </div>
        </div>
        <!-- Panel de orden -->
        <div class="w-1/3">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 h-fit sticky top-6">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart-icon lucide-shopping-cart"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">Orden Actual</h2>
                </div>
                <div id="orden-content"></div>
                <div class="border-t border-gray-200 pt-4 space-y-3">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal:</span>
                        <span id="subtotal">$0</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>IVA (16%):</span>
                        <span id="iva">$0</span>
                    </div>
                    <div class="flex justify-between text-xl font-bold text-gray-800 pt-2 border-t border-gray-200">
                        <span>Total:</span>
                        <span id="total">$0</span>
                    </div>
                </div>
                <button id="finalizarVenta" class="w-full mt-6 flex items-center justify-center space-x-2 py-4 px-6 rounded-xl font-bold text-lg transition-all duration-200 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card-icon lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    <span>Finalizar Venta</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const IVA_RATE = 0.16; // Tasa de IVA del 16%
    let productos = [];
    let orden = [];

    //obtener los productos desde la API
    axios.get('{{ route('productos.index') }}').then(response => {
        productos = response.data.productos || [];
        mostrarProductos();
    })
    .catch(error =>{
        console.error('Error al obtener los productos:', error);
        alert('Error al cargar los productos. Por favor, inténtelo más tarde.', error);
    });

    //funcion para mostrar productos en el card
        // ...existing code...
    function mostrarProductos(){
        const contenedor = document.getElementById('productos');
        productos.forEach(prod => {
            const isLowStock = prod.stock < 5; // Umbral para stock bajo
            const card = document.createElement('div');
            card.classList.add(
                'bg-white', 'rounded-2xl', 'shadow-lg', 'border', 'border-gray-100',
                'overflow-hidden', 'hover:shadow-xl', 'transition-all', 'duration-300', 'transform', 'hover:-translate-y-1'
            );
            card.innerHTML = `
                <div class="relative">
                    <img src="{{ asset('storage') }}/${prod.imagen}" alt="${prod.nombre}" class="w-full h-48 object-cover">
                    <div class="absolute top-3 right-3">
                        <div class="flex items-center space-x-1 px-2 py-1 rounded-full text-xs font-medium ${isLowStock ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-icon lucide-package"><path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/><path d="M12 22V12"/><polyline points="3.29 7 12 12 20.71 7"/><path d="m7.5 4.27 9 5.15"/></svg>
                            <span>${prod.stock}</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">${prod.nombre}</h3>
                        <p class="text-2xl font-bold text-green-600">$${parseFloat(prod.precio).toFixed(2)}</p>
                    </div>
                    <button class="w-full flex items-center justify-center space-x-2 py-3 px-4 rounded-xl font-semibold transition-all duration-200 ${prod.stock == 0 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95'}" data-id="${prod.id}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>${prod.stock == 0 ? 'Agotado' : 'Agregar'}</span>
                    </button>
                </div>
            `;
            contenedor.appendChild(card);
    
            card.querySelector('button').addEventListener('click', function() {
                agregarAOrden(prod);
            });
        });
    }

    //funcion para agregar productos a la orden
    function agregarAOrden(producto){
        const itemExistente = orden.find(item => item.producto_id === producto.id);
        if(itemExistente){
            itemExistente.cantidad++;
            itemExistente.subtotal = itemExistente.cantidad * itemExistente.precio_unitario;
        } else {
            orden.push({
                producto_id: producto.id,
                nombre: producto.nombre,
                precio_unitario: parseFloat(producto.precio),
                cantidad: 1,
                subtotal: parseFloat(producto.precio)
            });
        }
        actualizarPanelOrden();
    }

    //funcion para actualizar el panel de orden
        // ...existing code...
    function actualizarPanelOrden() {
        const panel = document.getElementById('orden-content');
        panel.innerHTML = '';
    
        if (orden.length === 0) {
            panel.innerHTML = `
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-basket-icon lucide-shopping-basket"><path d="m15 11-1 9"/><path d="m19 11-4-7"/><path d="M2 11h20"/><path d="m3.5 11 1.6 7.4a2 2 0 0 0 2 1.6h9.8a2 2 0 0 0 2-1.6l1.7-7.4"/><path d="M4.5 15.5h15"/><path d="m5 11 4-7"/><path d="m9 11 1 9"/></svg>
                    </div>
                    <p class="text-gray-500">No hay productos en la orden</p>
                </div>
            `;
        } else {
            orden.forEach((item, index) => {
                const itemDiv = document.createElement('div');
                itemDiv.classList.add('bg-gray-50', 'rounded-xl', 'p-4', 'mb-4');
                itemDiv.innerHTML = `
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-semibold text-gray-800">${item.nombre}</h4>
                        <p class="font-bold text-green-600">$${item.subtotal.toFixed(2)}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <button data-index="${index}" data-action="decrement" class="w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg flex items-center justify-center transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                            </button>
                            <span class="w-8 text-center font-semibold">${item.cantidad}</span>
                            <button data-index="${index}" data-action="increment" class="w-8 h-8 bg-green-100 hover:bg-green-200 text-green-600 rounded-lg flex items-center justify-center transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-sm text-gray-600">$${item.precio_unitario.toFixed(2)} c/u</p>
                    </div>
                `;
                panel.appendChild(itemDiv);
            });
        }
    
        calcularTotales();
    
        // Asignar eventos a los botones de incrementar y decrementar
        document.querySelectorAll('button[data-action="decrement"]').forEach(btn => {
            btn.addEventListener('click', () => {
                const idx = parseInt(btn.getAttribute('data-index'));
                if (orden[idx].cantidad > 1) {
                    orden[idx].cantidad--;
                    orden[idx].subtotal = orden[idx].precio_unitario * orden[idx].cantidad;
                } else {
                    orden.splice(idx, 1);
                }
                actualizarPanelOrden();
            });
        });
        document.querySelectorAll('button[data-action="increment"]').forEach(btn => {
            btn.addEventListener('click', () => {
                const idx = parseInt(btn.getAttribute('data-index'));
                orden[idx].cantidad++;
                orden[idx].subtotal = orden[idx].precio_unitario * orden[idx].cantidad;
                actualizarPanelOrden();
            });
        });
    }


    //funcion para calcular subtotal, IVA y total
    function calcularTotales() {
        const subtotal = orden.reduce((acc, curr) => acc + curr.subtotal, 0);
        const iva = subtotal * IVA_RATE;
        const total = subtotal + iva;
        document.getElementById('subtotal').textContent = subtotal.toFixed(2);
        document.getElementById('iva').textContent = iva.toFixed(2);
        document.getElementById('total').textContent = total.toFixed(2);
    }

    //finalizar la venta y enviar al backend
    document.getElementById('finalizarVenta').addEventListener('click', () => {
        if (!orden.length) {
            alert('No hay productos en la orden');
            return;
        }
        // Aquí se podría obtener el usuario_id de la sesión o del formulario
        const usuarioId = localStorage.getItem('usuario_id');
        const subtotal = orden.reduce((acc, curr) => acc + curr.subtotal, 0);
        const iva = subtotal * IVA_RATE;
        const total = subtotal + iva;

        const payload = {
            usuario_id: usuarioId,
            total: total,
            fecha_venta: new Date().toISOString().slice(0, 10),
            detalles: orden.map(item => ({
                producto_id: item.producto_id,
                cantidad: item.cantidad,
                precio_unitario: item.precio_unitario,
                subtotal: item.subtotal
            }))
        };

        axios.post('http://localhost:8000/api/venta', payload)
            .then(response => {
                alert('Venta registrada con éxito, ID: ' + response.data.venta_id);
                orden = [];
                actualizarPanelOrden();
            })
            .catch(error => {
                console.error('Error al registrar la venta', error);
                alert('Error al registrar la venta');
            });
    });

</script>
@endsection 