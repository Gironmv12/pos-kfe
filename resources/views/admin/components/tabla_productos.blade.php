<div id="tabla-productos">
    <div id="productos-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8"></div>
    <div id="empty-state" class="bg-white rounded-2xl shadow-lg border border-gray-100 p-12 text-center hidden">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6m16 0H4" /></svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-800 mb-2">No se encontraron productos</h3>
        <p class="text-gray-600 mb-6">Intenta ajustar la búsqueda o agrega un producto nuevo.</p>
        <button id="empty-nuevo-producto-btn" class="inline-flex items-center space-x-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            <span>Agregar Producto</span>
        </button>
    </div>
</div>

<script>
// Consumir productos vía axios y actualizar el grid
let allProductos = [];
function renderProductos(productos) {
    const grid = document.getElementById('productos-grid');
    const empty = document.getElementById('empty-state');
    grid.innerHTML = '';
    if (!productos.length) {
        grid.classList.add('hidden');
        empty.classList.remove('hidden');
    } else {
        grid.classList.remove('hidden');
        empty.classList.add('hidden');
        productos.forEach(producto => {
            const card = document.createElement('div');
            card.className = 'bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col';
            card.innerHTML = `
                <div class="relative">
                    <img src="{{ asset('storage') }}/${producto.imagen}" alt="${producto.nombre}" class="w-full h-48 object-cover" />
                    <div class="absolute top-3 right-3">
                        <div class="flex items-center space-x-1 px-2 py-1 rounded-full text-xs font-medium ${producto.stock <= 5 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}">
                            <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"w-3 h-3\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6m16 0H4\" /></svg>
                            <span>${producto.stock}</span>
                        </div>
                    </div>
                </div>
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">${producto.nombre}</h3>
                        <p class="text-sm text-gray-500 mb-2">${producto.descripcion}</p>
                        <p class="text-2xl font-bold text-green-600">$${parseFloat(producto.precio).toFixed(2)}</p>
                    </div>
                    <div class="flex space-x-2 mt-auto">
                        <button class="edit-button flex-1 flex items-center justify-center space-x-1 py-2 px-3 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg font-medium transition-colors duration-200" data-id="${producto.id}">
                            <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"w-4 h-4\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M15.232 5.232l3.536 3.536M9 13h3l8-8a2.828 2.828 0 10-4-4l-8 8v3z\" /></svg>
                            <span>Editar</span>
                        </button>
                        <button class="delete-button flex-1 flex items-center justify-center space-x-1 py-2 px-3 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg font-medium transition-colors duration-200" data-id="${producto.id}">
                            <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"w-4 h-4\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22\" /></svg>
                            <span>Eliminar</span>
                        </button>
                    </div>
                </div>
            `;
            grid.appendChild(card);
        });
        // Asignar eventos a botones de editar
        document.querySelectorAll('.edit-button').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const producto = allProductos.find(p => p.id == id);
                if(producto) {
                    document.getElementById('nombre').value = producto.nombre;
                    document.getElementById('descripcion').value = producto.descripcion;
                    document.getElementById('precio').value = producto.precio;
                    document.getElementById('stock').value = producto.stock;
                    document.getElementById('producto_id').value = producto.id;
                    document.getElementById('modal-titulo').textContent = 'Editar Producto';
                    document.getElementById('modal-producto').classList.remove('hidden');
                }
            });
        });
        // Eliminar
        const deleteEndpoint = "{{ route('productos.destroy', ['producto' => ':producto']) }}";
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                if (confirm('¿Estás seguro de eliminar este producto?')) {
                    const url = deleteEndpoint.replace(':producto', id);
                    axios.delete(url, {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('token')
                        }
                    })
                    .then(function(response) {
                        alert('Producto eliminado exitosamente');
                        location.reload();
                    })
                    .catch(function(error) {
                        alert('Error al eliminar el producto');
                    });
                }
            });
        });
    }
}

// Búsqueda
window.filtrarProductos = function(termino) {
    const filtrados = allProductos.filter(p =>
        p.nombre.toLowerCase().includes(termino) ||
        p.descripcion.toLowerCase().includes(termino)
    );
    renderProductos(filtrados);
};

// Cargar productos
axios.get("{{ route('productos.index') }}", {
    headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('token')
    }
})
.then(function(response) {
    allProductos = response.data.productos;
    renderProductos(allProductos);
    // Botón de nuevo producto en empty state
    document.getElementById('empty-nuevo-producto-btn').addEventListener('click', function() {
        document.getElementById('modal-titulo').textContent = 'Nuevo Producto';
        document.getElementById('modal-producto').classList.remove('hidden');
    });
})
.catch(function(error) {
    alert('Error al cargar los productos');
});
</script>