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
        <div class="w-1/3 p-4 border">
            <h2 class="text-lg font-semibold mb-2">Orden actual</h2>
            <div id="orden"></div>
            <p class="mt-4"><strong>Subtotal:</strong> <span id="subtotal">0</span></p>
            <p><strong>IVA (16%):</strong> <span id="iva">0</span></p>
            <p><strong>Total:</strong> <span id="total">0</span></p>
            <button id="finalizarVenta" class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-3 mt-4">
                Finalizar Venta
            </button>
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
    function mostrarProductos(){
        const contenedor = document.getElementById('productos');
        productos.forEach(prod => {
            const card = document.createElement('div');
            card.classList.add('border', 'p-4', 'rounded', 'shadow');
            card.innerHTML = `
            <img src="{{ asset('storage') }}/${prod.imagen}" alt="${prod.nombre}" class="w-full h-32 object-cover mb-2">
                <h3 class="font-semibold">${prod.nombre}</h3>
                <p>Precio: $${prod.precio}</p>
                <p>Stock: ${prod.stock}</p>
                <button class="bg-green-500 text-white px-2 py-1 mt-2" data-id="${prod.id}">
                  Agregar
                </button>
            `;
            contenedor.appendChild(card);

            card.querySelector('button').addEventListener('click', function() {
                agregarAOrden(prod);
            });
        })
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
    function actualizarPanelOrden() {
        const panel = document.getElementById('orden');
        panel.innerHTML = '';
        orden.forEach((item, index) => {
            const div = document.createElement('div');
            div.classList.add('flex', 'items-center', 'justify-between', 'mb-2');

            div.innerHTML = `
                <div>
                    <span>${item.nombre}</span> 
                    <input type="number" min="1" value="${item.cantidad}" style="width:50px" />
                    <span>Subtotal: $${item.subtotal.toFixed(2)}</span>
                </div>
                <button class="bg-red-500 text-white px-2 py-1" data-index="${index}">Eliminar</button>
            `;
            panel.appendChild(div);

            const inputCantidad = div.querySelector('input');
            inputCantidad.addEventListener('change', (e) => {
                item.cantidad = parseInt(e.target.value) || 1;
                item.subtotal = item.precio_unitario * item.cantidad;
                actualizarPanelOrden();
            });

            const btnEliminar = div.querySelector('button');
            btnEliminar.addEventListener('click', () => {
                orden.splice(index, 1);
                actualizarPanelOrden();
            });
        });
        calcularTotales();
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