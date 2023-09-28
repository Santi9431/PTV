$(document).ready(function () {
  // Cargar combo de ficha usuario
  limpiarCampos();
  validate();

  // Validar usuario
  $("#enviar").click(function () {
    $("#LoadingImage").show();
    let campoRequerido = validate();

    if (campoRequerido) {
      $("#LoadingImage").hide();
      swal("Datos incompletos", "Faltan campos por rellenar", "error");
    } else {
      var formData = new FormData();
      formData.append("documento", $("#documento").val());
      formData.append("clave", $("#contrasena").val());

      $.ajax({
        type: "POST",
        url: "../../controller/usuario/controller_usuario.php?opcion=2",
        data: formData,
        processData: false,
        contentType: false,
        error: function (jqXHR, textStatus, errorThrown) {
          $("#LoadingImage").hide();
          swal(
            "Error",
            "Hubo un error en el sistema. Comunicarse con soporte técnico",
            "error"
          );
        },
        success: function (result) {
          $("#LoadingImage").hide();
          var data = JSON.parse(result);

          var tipo_mensaje = "error";
          var mensaje_button = "Cerrar";
          if (data.msg.code === 1) {
            tipo_mensaje = "success";
            mensaje_button = "OK";
            limpiarCampos();
          }

          swal(
            {
              icon: tipo_mensaje,
              title: data.msg.title,
              text: data.msg.mensaje,
              showCancelButton: false,
              confirmButtonColor: "#ed5565",
              confirmButtonText: mensaje_button,
              closeOnConfirm: true,
            },
            function (isConfirm) {
              if (isConfirm) {
                if (data.msg.code === 1) {
                  window.location.href = "../home_roles/home.php";
                }
              }
            }
          );
        },
      });
    }
  });

  // Validar campos requeridos
  $(".requerido").on("keyup", function () {
    if ($(this).val().trim() !== "") {
      $(this).removeClass("is-invalid");
    } else {
      $(this).addClass("is-invalid");
    }
  });
});

function limpiarCampos() {
  $("#documento").val("");
  $("#contraseña").val("");
}

function validate() {
  let campoRequerido = false;
  $(".requerido").each(function () {
    if ($(this).val().trim() === "") {
      campoRequerido = true;
      $(this).addClass("is-invalid");
    }
  });
  return campoRequerido;
}
