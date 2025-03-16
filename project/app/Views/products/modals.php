    <!-- Modal para Crear/Editar Producto -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="productForm">
                        <input type="hidden" id="productId">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" id="name" placeholder="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Marca</label>
                            <input type="text" id="brand" placeholder="marca" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Precio</label>
                            <input type="number" id="price" min="0.00" max="10000.00" step=".01" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de eliminar este producto?</p>
                    <input type="hidden" id="deleteProductId">
                    <button id="confirmDelete" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Notificación -->
    <div class="modal fade" id="notificationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="notificationMessage">
                    <!-- Aquí irá el mensaje dinámico -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>