@extends('layouts.pos')

@section('title', 'Gestión de productos')

@section('content')
    <h1 class="text-2xl font-bold">Productos</h1>
    <!-- Formulario reutilizable: crear y editar -->
    <form id="create-product-form" enctype="multipart/form-data">
        <!-- Campo oculto para identificar si es edición -->
        <input type="hidden" id="producto_id" name="producto_id" value="">

        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        <div>
            <label for="descripcion">Descripción:</label>
            <input type="text" id="descripcion" name="descripcion" required>
        </div>
        <div>
            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" required step="0.01">
        </div>
        <div>
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" required>
        </div>
        <div>
            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagen" >
        </div>
        <button type="submit">Guardar Producto</button>
    </form>

    @include('admin.components.tabla_productos')

    <script>
        // Manejo del envío del formulario
        document.getElementById('create-product-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            // Verificar si se está editando (existe id) o creando
            const productoId = document.getElementById('producto_id').value;
            let url = '';
            let method = '';

            if(productoId) {
                // Para update usamos POST y agregamos _method=PUT
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
                    // Elimina la asignación manual de Content-Type para que axios lo asigne automáticamente
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(function(response) {
                console.log('Operación exitosa:', response.data);
                alert(response.data.message);
                document.getElementById('create-product-form').reset();
                document.getElementById('producto_id').value = '';
                location.reload();
            })
            .catch(function(error) {
                console.error('Error en la operación:', error.response.data);
                alert('Error en la operación: ' + (error.response.data.message || 'Error desconocido'));
            });
        });
    </script>
@endsection