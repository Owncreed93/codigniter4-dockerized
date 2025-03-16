<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>


    <div class="container mt-5">
        <div class="container">
            <h2 class="mb-4 border-bottom border-secondary-subtle">Gestión de Productos</h2>

            <!-- Botón para abrir el modal de agregar producto -->
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#productModal">
                <i class="fa-solid fa-pen me-1"></i>Agregar Producto
            </button>
        </div>
        

        <!-- Tabla con DataTables -->
        <table id="productTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Marca</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- AJAX llenará esta tabla -->
            </tbody>
        </table>


        <?= $this->include('products/modals') ?>
    </div>



<?= $this->endSection() ?>

