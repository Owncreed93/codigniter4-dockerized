$(document).ready(function () {
  // const base_url = "http://0.0.0.0:8080";
  const base_url = window.location.origin;
  console.log('This is the local base_url: ', base_url);
  const productModalTitle = $("#productModal").find(".modal-title")[0];
  const productForm = $("#productForm");
  const productIdTag = productForm.find("#productId")[0];
  const productName = productForm.find("#name")[0];
  const productBrand = productForm.find("#brand")[0];
  const productPrice = productForm.find("#price")[0];

  const priceRegex = /^\d{1,6}(\.\d{1,2})?$/;
  const numberRegex = /[0-9]/g;

  let productTable = $("#productTable").DataTable({
    ajax: {
      //url: '/products/list',
      url: `${base_url}/products/list`,
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

  // Abrir modal para agregar producto
  $("#productModal").on("show.bs.modal", function (event) {


    let isValid = true;
    $("#productForm")[0].reset();
    $("#productId").val("");
    $(".btn-success").prop({ disabled: true });

    if (!$("productId").val()) {
      $("#price").val("0.00");
    }

    $("#name").on("input focus", function () {
      let value = $(this).val().trim();
      let errorSpan = $(this).next("span");

      if (value === "") {
        errorSpan.removeClass("d-none");
        $(".btn-success").prop({ disabled: true });
        isValid = false;
        console.log("Es falso", isValid);
      } else {
        errorSpan.addClass("d-none");
        $(".btn-success").prop({ disabled: false });
        isValid = true;
        console.log("Es verdadero", isValid);
      }
    });

    $("#brand").on("input focus", function () {
      let value = $(this).val().trim();
      let errorSpan = $(this).next("span");

      if (value === "") {
        errorSpan.removeClass("d-none");
        $(".btn-success").prop({ disabled: true });
        isValid = false;
        console.log("Es falso", isValid);
      } else {
        errorSpan.addClass("d-none");
        $(".btn-success").prop({ disabled: false });
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
        $(".btn-success").prop({ disabled: true });
        console.log("Es falso", isValid);
      } else {
        errorSpan.addClass("d-none");
        $(".btn-success").prop({ disabled: false });
        console.log("Es verdadero", isValid);
      }
    });
  });

  // Guardar producto
  $("#productForm").submit(function (e) {
    e.preventDefault();
    productModalTitle.innerText = "Agregar Producto";
    let id = $("#productId").val();
    // let url = id ? `/products/update/${id}` : '/products/create';
    let url = id
      ? `${base_url}/products/update/${id}`
      : `${base_url}/products/create`;
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
          alert("Error: El servidor no devolvió datos.");
          return;
        }
        if (response.status) {
          console.log(response.message);
          $("#productModal").modal("hide");
          productTable.ajax.reload(null, false);
        } else {
          console.error("Error en respuesta:", response);
          alert(
            `Corrige los errores: \n ${Object.values(response.errors).join(
              "\n"
            )}`
          );
        }
      },
      error: function (xhr) {
        console.error(`Error: ${xhr.responseText}`);
      },
    });
  });

  // Cargar producto en modal de edición
  $("#productTable").on("click", ".edit", function () {
    productModalTitle.innerText = "Editar Producto";
    const id = $(this).data("id");

    $.get(`/products/get/${id}`, function (product) {
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
  });

  // Confirmar eliminación
  $("#productTable").on("click", ".delete", function () {
    let id = $(this).data("id");
    $("#deleteProductId").val(id);
    $("#deleteModal").modal("show");
  });

  $("#confirmDelete").click(function () {
    let id = $("#deleteProductId").val();
    $.ajax({
      // url: `/products/delete/${id}`,
      url: `${base_url}/products/delete/${id}`,
      method: "DELETE",
      success: function (response) {
        if (response.success) {
          console.log("Producto borrado correctamente.");
          $("#deleteModal").modal("hide");
          productTable.ajax.reload();
        } else {
          console.error("Error en respuesta:", response);
        }
      },
      error: function (xhr) {
        console.error(`Error: ${xhr.responseText}`);
      },
      
    });
  });
});
