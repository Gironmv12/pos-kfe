@extends('layouts.pos')

@section('title', 'Gestión de productos')

@section('content')
<div class="min-h-screen  p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Gestión de Productos</h1>
                <p class="text-gray-600">Administra el inventario de tu cafetería</p>
            </div>
            <button id="nuevo-producto-btn"
                class="flex items-center space-x-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                <span>Nuevo Producto</span>
            </button>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z" /></svg>
                        <input type="text" id="busqueda-producto" placeholder="Buscar productos..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid de productos -->
        <div id="contenedor-productos">
            @include('admin.components.tabla_productos')
        </div>

        <!-- Modal para crear/editar producto -->
        <div id="modal-producto" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 id="modal-titulo" class="text-2xl font-bold text-gray-800">Nuevo Producto</h2>
                        <button id="cerrar-modal-btn" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <form id="create-product-form" enctype="multipart/form-data" class="space-y-4">
                        <input type="hidden" id="producto_id" name="producto_id" value="">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="nombre">Nombre del Producto</label>
                            <input type="text" id="nombre" name="nombre" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200" placeholder="Ej: Cappuccino Grande" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="descripcion">Descripción</label>
                            <input type="text" id="descripcion" name="descripcion" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200" placeholder="Descripción del producto" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="precio">Precio</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">$</span>
                                    <input type="number" id="precio" name="precio" required step="0.01" class="w-full pl-8 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200" placeholder="0.00" />
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="stock">Stock</label>
                                <input type="number" id="stock" name="stock" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200" placeholder="0" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="imagen">Imagen</label>
                            <input type="file" id="imagen" name="imagen" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200" />
                        </div>
                        <div class="flex space-x-3 pt-4">
                            <button type="button" id="cancelar-modal-btn" class="flex-1 py-3 px-4 border border-gray-200 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors duration-200">Cancelar</button>
                            <button type="submit" class="flex-1 flex items-center justify-center space-x-2 py-3 px-4 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl font-medium shadow-lg hover:shadow-xl transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                <span>Guardar</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Mostrar/ocultar modal
const modal = document.getElementById('modal-producto');
const nuevoBtn = document.getElementById('nuevo-producto-btn');
const cerrarBtn = document.getElementById('cerrar-modal-btn');
const cancelarBtn = document.getElementById('cancelar-modal-btn');
const form = document.getElementById('create-product-form');
const tituloModal = document.getElementById('modal-titulo');

function abrirModal(titulo = 'Nuevo Producto') {
    tituloModal.textContent = titulo;
    modal.classList.remove('hidden');
}
function cerrarModal() {
    modal.classList.add('hidden');
    form.reset();
    document.getElementById('producto_id').value = '';
}
nuevoBtn.addEventListener('click', function() {
    abrirModal('Nuevo Producto');
});
cerrarBtn.addEventListener('click', cerrarModal);
cancelarBtn.addEventListener('click', cerrarModal);

// Manejo del envío del formulario (igual que antes)
form.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const productoId = document.getElementById('producto_id').value;
    let url = '';
    let method = '';
    if(productoId) {
        url = `{{ url('api/productos') }}/${productoId}`;
        method = 'post';
        formData.append('_method', 'PUT');
    } else {
        url = "{{ route('productos.store') }}";
        method = 'post';
    }
    axios({
        method: method,
        url: url,
        data: formData,
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        }
    })
    .then(function(response) {
        alert(response.data.message);
        form.reset();
        document.getElementById('producto_id').value = '';
        cerrarModal();
        location.reload();
    })
    .catch(function(error) {
        alert('Error en la operación: ' + (error.response?.data?.message || 'Error desconocido'));
    });
});

// Búsqueda de productos
const inputBusqueda = document.getElementById('busqueda-producto');
inputBusqueda.addEventListener('input', function() {
    const termino = this.value.toLowerCase();
    window.filtrarProductos && window.filtrarProductos(termino);
});
</script>
@endsection