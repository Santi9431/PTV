$(document).ready(function () {

  // Cargar combo de ficha usuario
  limpiarCampos();  
  cargarComboFicha();

  // Validar usuario
  $("#enviar").click(function (e) {
    e.preventDefault();
    $("#LoadingImage").show();
    let campoRequerido = validate();

    if (campoRequerido) {
      $("#LoadingImage").hide();
      swal("Datos incompletos", "Faltan campos por rellenar", "error");
    } else {
      var formData = new FormData();
      formData.append("nombre", $("#nombre").val());
      formData.append("documento", $("#documento").val());
      formData.append("email", $("#email").val());
      formData.append("direccion", $("#direccion").val());
      formData.append("celular", $("#celular").val());
      formData.append("clave", $("#clave").val());
      formData.append("estado", $("#estado").val());
      formData.append("idFichaIdP", $("#numeroFicha").val());
      formData.append("numeroFicha", $("#numeroFicha option:selected").text());
      $.ajax({
        type: "POST",
        url: "../../controller/usuario/controller_usuario.php?opcion=1",
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
                  window.location.href = "../../view/usuario/login.php";
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
  $("#nombre").val("");
  $("#documento").val("");
  $("#email").val("");
  $("#direccion").val("");
  $("#celular").val("");
  $("#hash_password").val("");
  $("#email").val("");
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

//cargar combo programa
function cargarComboFicha() {
  $('#numeroFicha')
          .find('option')
          .remove()
          .end()
          .append('<option value="0">Seleccione una ficha</option>')
          .val('0')
          ;
  $.ajax({
      url: "../../controller/usuario/controller_usuario.php?opcion=3",
      type: "GET",
      success: function (result) {
          var data = eval('(' + result + ')');
          $.each(data.msg.mensaje, function (item) {
              $('#numeroFicha').append($('<option value="' + data.msg.mensaje[item].id + ';' + data.msg.mensaje[item].idP + '">' + data.msg.mensaje[item].value + '</option>'));
          });
      }
  });
}
