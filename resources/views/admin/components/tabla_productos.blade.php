<div id="tabla-productos">
    <h2>Lista de Productos</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Aquí se rellenará la tabla con data consumida vía axios -->
        </tbody>
    </table>
</div>

<script>
    // Consumir productos vía axios y actualizar la tabla
    axios.get("{{ route('productos.index') }}", {
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        }
    })
    .then(function(response) {
        const tbody = document.querySelector('#tabla-productos tbody');
        tbody.innerHTML = '';
        // Almacenamos el array de productos para accederlos en la edición
        const allProductos = response.data.productos;
        allProductos.forEach(producto => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${producto.nombre}</td>
                <td>${producto.descripcion}</td>
                <td>${producto.precio}</td>
                <td>${producto.stock}</td>
                <td><img src="{{ asset('storage') }}/${producto.imagen}" alt="${producto.nombre}" style="width: 50px; height: 50px;"></td>
                <td>
                    <button class="edit-button" data-id="${producto.id}">Editar</button>
                    <button class="delete-button" data-id="${producto.id}">Eliminar</button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        // Asignar eventos a botones de editar
        document.querySelectorAll('.edit-button').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                // Encontrar el producto correspondiente
                const producto = allProductos.find(p => p.id == id);
                if(producto) {
                    // Rellenar el formulario con los datos del producto
                    document.getElementById('nombre').value = producto.nombre;
                    document.getElementById('descripcion').value = producto.descripcion;
                    document.getElementById('precio').value = producto.precio;
                    document.getElementById('stock').value = producto.stock;
                    // Es opcional: en caso de editar imagen se podría dejar en blanco para no sobrescribirla
                    // Colocar el id en el input oculto para indicar que es edición.
                    document.getElementById('producto_id').value = producto.id;
                }
            });
        });

        // Agregar el evento de eliminar (código preexistente)
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
                        console.error('Error al eliminar el producto:', error);
                        alert('Error al eliminar el producto');
                    });
                }
            });
        });
    })
    .catch(function(error) {
        console.error('Error al cargar los productos:', error);
    });
</script>