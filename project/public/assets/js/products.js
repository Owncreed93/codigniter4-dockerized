$(document).ready(function () {
    const base_url = 'http://0.0.0.0:8080'
    const productModalTitle = $('#productModal').find('.modal-title')[0]
    const productForm = $('#productForm')
    const productIdTag = productForm.find('#productId')[0]
    const productName =  productForm.find('#name')[0]
    const productBrand =  productForm.find('#brand')[0]
    const productPrice =  productForm.find('#price')[0]

    /**
     * NEXT STEPS
     * RESEARCH WHY CODEIGNITER FAILS WHEN App $baseURL fails when set to ''.
     * ADD FLOAT NUMBER DISPLAY TO PRODUCT HTML INPUT TAG
     * VALIDATE PRODUCT FORM TO AVOID EMPTY DATA OR WRONG DATA, 
     * SERIALIZE INFORMATION SENT BACKEND AND FRONTEND
     * ADD TRY CATCH CONFIGURATION TO PHP AND JAVASCRIPT
     * ADD DATA TABLE SPANISH CONFIGURATION IMPROVE TITLES AND BUTTON STYLES
     * RESEARCH TEMPLATING IN CODEIGNITER TO AVOID HTML AND JS DUPLICATION
     * CONSIDER ADDING "STOCK" AND "CURRENCY" TABLES TO ALTER PRODUCTS TABLE BEHAVIOUR
     * CONSIDER GROUPING ROUTES
     * ADD UNIT TESTING TO THE PRODUCTS CLASS
     */

    let productTable = $('#productTable').DataTable({
        ajax: {
            // url: '/products/list',
            url: `${base_url}/products/list`,
            dataSrc: ''
        },
        columns: [
            { data: 'name' },
            { data: 'brand' },
            { data: 'price' },
            {
                data: 'id',
                render: function (data) {
                    return `
                        <button class="btn btn-warning btn-sm edit" data-id="${data}">Editar</button>
                        <button class="btn btn-danger btn-sm delete" data-id="${data}">Eliminar</button>
                    `;
                }
            }
        ]
    });

    // Abrir modal para agregar producto
    $('#productModal').on('show.bs.modal', function (event) {
        $('#productForm')[0].reset();
        $('#productId').val('');
    });

    // Guardar producto
    $('#productForm').submit(function (e) {
        e.preventDefault();
        productModalTitle.innerText = 'Agregar Producto'
        let id = $('#productId').val();
        // let url = id ? `/products/update/${id}` : '/products/create';
        let url = id ? `${base_url}/products/update/${id}` : `${base_url}/products/create`;
        let method = 'POST';

        $.ajax({
            url: url,
            method: method,
            data: {
                name: $('#name').val(),
                brand: $('#brand').val(),
                price: $('#price').val()
            },
            success: function (response) {
                if (response.success){
                    $('#productModal').modal('hide');
                    productTable.ajax.reload(null, false);
                } else {
                    console.error('Error en respuesta:', response);
                }
                
            }
        });
    });

    // Cargar producto en modal de edición
    $('#productTable').on('click', '.edit', function () {
        productModalTitle.innerText = 'Editar Producto'
        const id = $(this).data('id');

        // const product = await $.ajax({
        //     url: `${base_url}/products/get/${id}`,
        //     method: 'GET'
        // });

        // console.log(product);
        // if (product){
        //     productIdTag.value = product.id
        //     $('#productForm').find('#name')[0].value = product.name
        //     productBrand.value = product.brand
        //     productPrice.value = product.price
        //     // $('#productModal').modal('show');
        // }
        $.get(`/products/get/${id}`, function (product) {
            
            if (!product || Object.keys(product).length === 0 ) {
                console.error('Error: No se pudo cargar la información del producto.')
                return;
            } 
            // REALIZAR HTML TRAVERSE EN #PRODUCTFORM PARA LLENAR LOS ELEMENTOS
            productForm[0].reset();
            productIdTag.value = product.id
            productName.value = product.name
            productBrand.value = product.brand
            productPrice.value = product.price
            
            // $('#productModal').modal('show');
        })
        .fail( function (xhr, status, error){
            console.error('Error en la petición AJAX', error);
        });


        // $('#productForm').find('#name')[0].value = 'GAA'
        $('#productModal').modal('show')

    });

    // Confirmar eliminación
    $('#productTable').on('click', '.delete', function () {
        let id = $(this).data('id');
        $('#deleteProductId').val(id);
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').click(function () {
        let id = $('#deleteProductId').val();
        $.ajax({
            url: `${base_url}/products/delete/${id}`,
            method: 'DELETE',
            success: function (response) {

                if (response.success){
                    $('#deleteModal').modal('hide');
                    productTable.ajax.reload();
                } else {
                    console.error('Error en respuesta:', response);
                }
                
            }
        });
    });
});
