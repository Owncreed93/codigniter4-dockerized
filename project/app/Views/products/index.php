<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h2 class="mb-4">Gestión de Productos</h2>

    <!-- Formulario de búsqueda -->
    <form action="<?= site_url('products/search') ?>" method="get" class="mb-3">
        <input type="text" name="query" class="form-control" placeholder="Buscar por nombre o marca">
    </form>

    <!-- Formulario de agregar producto -->
    <form action="<?= site_url('products/create') ?>" method="post" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <input type="text" name="name" class="form-control" placeholder="Nombre" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="brand" class="form-control" placeholder="Marca" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="price" class="form-control" placeholder="Precio" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Agregar</button>
            </div>
        </div>
    </form>

    <!-- Tabla de productos -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Marca</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if($products): ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= esc($product['name']) ?></td>
                        <td><?= esc($product['brand']) ?></td>
                        <td><?= esc($product['price']) ?></td>
                        <td>
                            <a href="<?= site_url('products/edit/'.$product['id']) ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="<?= site_url('products/delete/'.$product['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este producto?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else:  ?>
                <tr> <span>No hay productos para mostrar. </span> </tr>
            <?php endif ?>
        </tbody>
    </table>

</body>
</html>
