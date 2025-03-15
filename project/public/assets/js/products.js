$(document).ready(function () {
  // const base_url = "http://127.0.1.0:8080";
  const base_url = window.location.origin;
  const products_base_url = `${base_url}/api/products`;
  console.log("This is the local base_url: ", products_base_url);

  /**
   * TODO: 2 ADD ANOTHER MODAL TO SHOW CONFIRMATION OR ERROR AFTER ACTIONS.
   * TODO: 3 THIS NEW MODAL'S WILL ALSO HAVE THE COLOUR PATERN IN CRUD'S ACTIONS.
   * TODO: 4 ADD PREVENT DEFAULT'S TO FORM
   */
  let productModalHeader = $("#productModal").find(".modal-header");
  let productModalTitle = $("#productModal").find(".modal-title");
  const productForm = $("#productForm");
  let buttonForm = $("#productModal").find(".btn");
  const productIdTag = productForm.find("#productId")[0];
  const productName = productForm.find("#name")[0];
  const productBrand = productForm.find("#brand")[0];
  const productPrice = productForm.find("#price")[0];

  const priceRegex = /^\d{1,6}(\.\d{1,2})?$/;
  const numberRegex = /[0-9]/g;

  let productTable = $("#productTable").DataTable({
    ajax: {
      //url: 'api/products/list',
      url: `${products_base_url}/list`,
      dataSrc: "",
    },
    columns: [
      { data: "name" },
      { data: "brand" },
      { data: "price" },
      {
        data: "id",
        render: function (data) {
          return `
                          <button class="btn btn-warning btn-sm edit" data-id="${data}">
                              <i class="fa-solid fa-pen-to-square me-1"></i>Editar
                          </button>
                          <button class="btn btn-danger btn-sm delete" data-id="${data}">
                              <i class="fa-solid fa-trash me-1"></i>Eliminar
                          </button>
                      `;
        },
      },
    ],
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
    },
  });

  $("#productForm input").each(function () {
    $(this).after(
      '<span class="text-danger t-1 d-none">Campo obligatorio.</span>'
    );
  });

  /**
   * 
   * @param {string} modal modal's title 
   * @param {string} message message to display 
   * @param {string} type success or something else
   */
  function showNotification(title, message, type) {
    const modalTitle = $("#notificationTitle");
    const modalMessage = $("#notificationMessage");
    const modalHeader = $("#notificationModal").find(".modal-header");

    // Resetear estilos anteriores
    modalHeader.removeClass("bg-success bg-danger text-white");

    // Configurar estilos según el tipo de mensaje
    if (type === "success") {
      modalTitle.text(title || "Éxito");
      modalHeader.addClass("bg-success text-white");
    } else {
      modalTitle.text(title || "Error");
      modalHeader.addClass("bg-danger text-white");
    }

    // Insertar mensaje
    modalMessage.html(message);

    // Mostrar el modal
    $("#notificationModal").modal("show");
  }

  // Abrir modal para agregar producto
  $("#productModal").on("show.bs.modal", function (event) {
    productModalHeader
      .removeClass("border-bottom border-warning") // Elimina clases anteriores
      .addClass("border-bottom border-primary"); // Agrega la nueva clase
    productModalTitle.text("Agregar Producto");
    buttonForm.text("Guardar");
    buttonForm.removeClass("btn btn-warning").addClass("btn btn-primary");
    let isValid = true;
    $("#productForm")[0].reset();
    $("#productId").val("");
    buttonForm.prop({ disabled: true });

    if (!$("productId").val()) {
      $("#price").val("0.00");
    }

    $("#name").on("input focus", function () {
      let value = $(this).val().trim();
      let errorSpan = $(this).next("span");

      if (value === "") {
        errorSpan.removeClass("d-none");
        buttonForm.addClass("btn-disabled");
        buttonForm.prop({ disabled: true });
        isValid = false;
        console.log("Es falso", isValid);
      } else {
        errorSpan.addClass("d-none");
        buttonForm.removeClass("btn-disabled");
        buttonForm.prop({ disabled: false });
        isValid = true;
        console.log("Es verdadero", isValid);
      }
    });

    $("#brand").on("input focus", function () {
      let value = $(this).val().trim();
      let errorSpan = $(this).next("span");

      if (value === "") {
        errorSpan.removeClass("d-none");
        buttonForm.addClass("btn-disabled");
        buttonForm.prop({ disabled: true });
        isValid = false;
        console.log("Es falso", isValid);
      } else {
        errorSpan.addClass("d-none");
        buttonForm.removeClass("btn-disabled");
        buttonForm.prop({ disabled: false });
        isValid = true;
        console.log("Es verdadero", isValid);
      }
    });

    $("#price").on("input focus", function () {
      let value = $(this).val().trim();
      let errorSpan = $(this).next("span");

      if (value === "" || !priceRegex.test(value) || value === "0.00") {
        errorSpan
          .text("El precio debe ser un número válido. Formato: #.##")
          .removeClass("d-none");
        buttonForm.addClass("btn-disabled");
        buttonForm.prop({ disabled: true });
        console.log("Es falso", isValid);
      } else {
        errorSpan.addClass("d-none");
        buttonForm.removeClass("btn-disabled");
        buttonForm.prop({ disabled: false });
        console.log("Es verdadero", isValid);
      }
    });
  });

  // Guardar producto
  $("#productForm").submit(function (e) {
    e.preventDefault();
    let id = $("#productId").val();
    // let url = id ? `api/products/update/${id}` : 'api/products/create';
    let url = id
      ? `${products_base_url}/update/${id}`
      : `${products_base_url}/create`;
    let method = "POST";

    let priceValue = Number(parseFloat($("#price").val()).toFixed(2));

    $.ajax({
      url: url,
      method: method,
      data: {
        name: $("#name").val(),
        brand: $("#brand").val(),
        // price: $('#price').val()
        price: priceValue,
      },
      success: function (response) {
        if (!response) {
          console.error("Error: Respuesta vacía del servidor.");
          showNotification('Error', 'Error en el servidor', 'error')
          return;
        }
        if (response.status) {
          console.log(response.message);
          $("#productModal").modal("hide");
          id ? showNotification('Éxito', 'Producto actualizado correctamente.', 'success')
             : showNotification('Éxito', 'Producto cerrado correcamente.', 'success')            
          productTable.ajax.reload(null, false);
        } else {
          console.error("Error en respuesta:", response);
          showNotification(
            'Error',
            `Corrige los errores: \n ${Object.values(response.errors).join("\n")}`,
            'success'
          )
          // alert(
          //   `Corrige los errores: \n ${Object.values(response.errors).join(
          //     "\n"
          //   )}`
          // );
        }
      },
      error: function (xhr) {
        console.error(`Error: ${xhr.responseText}`);
        showNotification('Error', xhr.responseText, 'error');
      },
    });
  });

  // Cargar producto en modal de edición
  $("#productTable").on("click", ".edit", function () {
    const id = $(this).data("id");

    $.get(`${products_base_url}/get/${id}`, function (product) {
      if (!product || Object.keys(product).length === 0) {
        console.error("Error: No se pudo cargar la información del producto.");
        return;
      }
      // REALIZAR HTML TRAVERSE EN #PRODUCTFORM PARA LLENAR LOS ELEMENTOS
      productForm[0].reset();
      productIdTag.value = product.id;
      productName.value = product.name;
      productBrand.value = product.brand;
      productPrice.value = product.price;

      // $('#productModal').modal('show');
    }).fail(function (xhr, status, error) {
      console.error("Error en la petición AJAX", error);
    });

    $("#productModal").modal("show");
    productModalHeader
      .removeClass("border-bottom border-primary")
      .addClass("border-bottom border-warning");
    productModalTitle.text("Editar Producto");
    buttonForm.text("Actualizar");
    buttonForm.removeClass("btn btn-primary").addClass("btn btn-warning");
  });

  // Confirmar eliminación
  $("#productTable").on("click", ".delete", function () {
    let id = $(this).data("id");
    $("#deleteProductId").val(id);
    $("#deleteModal").modal("show");
    $("#deleteModal")
      .find(".modal-header")
      .addClass("border-bottom border-danger");
  });

  $("#confirmDelete").click(function () {
    let id = $("#deleteProductId").val();
    $.ajax({
      // url: `api/products/delete/${id}`,
      url: `${products_base_url}/delete/${id}`,
      method: "DELETE",
      success: function (response) {
        if (response.success) {
          console.log("Producto eliminado correctamente.");
          $("#deleteModal").modal("hide");
          showNotification('Éxito', 'Producto eliminado correctamente.', 'success')
          productTable.ajax.reload();
        } else {
          console.error("Error en respuesta:", response.errors);
          showNotification('Error', response.errors, 'error');
        }
      },
      error: function (xhr) {
        console.error(`Error: ${xhr.responseText}`);
        showNotification('Error', xhr.responseText, 'error');
      },
    });
  });

  $("#productModal").on("hidden.bs.modal", function () {
    productModalHeader.removeClass(
      "border-bottom border-primary border-warning border-danger"
    );
    $("#productId").val("");
  });
});
