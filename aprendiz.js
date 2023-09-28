//varaibles globales
var srcFoto = "";
var filesToUpload = [];

$(document).ready(function () {
  //se ejecuta inmediatamente cargue todo el HTML

  seleccionArchivos = $("#foto");
  imagenPrevisualizacion = $("#fotoiconDiv");

  //cargar tabla de migraciones
  crear_tabla();
  limpiarCampos();
  gestionFoto();
  cargarComboPF();

  seleccionArchivos.change(function (e) {
    const archivos = e.target.files;
    if (!archivos || !archivos.length) {
      htmlFoto = '<i class="fa fa-user-circle-o fa-4x rounded-circle"></i>';
      imagenPrevisualizacion.html(htmlFoto);
      return;
    }
    const primerArchivo = archivos[0];
    const objectURL = URL.createObjectURL(primerArchivo);
    htmlFoto =
      '<img alt="image" class="rounded-circle" id="foto_profile" src="' +
      objectURL +
      '" width="100px" height="100px">  ';
    imagenPrevisualizacion.html(htmlFoto);

    // Verificar si hay una foto previamente seleccionada (en caso de edición)
    if (srcFoto !== "") {
      // Mostrar la foto previamente seleccionada
      const imgFoto = new Image();
      imgFoto.src = srcFoto;
      imgFoto.width = 100;
      imgFoto.height = 100;
      imagenPrevisualizacion.append(imgFoto);
    }

    filesToUpload = [];
    for (var i = 0; i < e.target.files.length; i++) {
      filesToUpload.push(e.target.files[i]);
    }
  });

  var oTable = $(".dataTables-aprendiz").dataTable();

  //boton guardar
  $("#guardar").click(function () {
    $("#LoadingImage").show(); //esta es para mostrar la imagen cargando y .show lo muestra en pantalla
    let errores = validarCampos(); // Validar campos con la función que definiste

    if (errores.length > 0) {
      const mensajeErrores = errores.join("\n");
      $("#LoadingImage").hide(); // Ocultar la imagen de carga
      swal("Campos inválidos", mensajeErrores, "error");
      return;
    } else {
      var formData = new FormData();
      //estos campos son los que van en el html llamados en el id
      formData.append("identificacion", $("#identificacion").val());
      formData.append("nombre", $("#nombre").val());
      formData.append("correoeletronico", $("#correoeletronico").val());
      formData.append("direccion", $("#direccion").val());
      formData.append("celular", $("#celular").val());
      formData.append("ficha", $("#ficha").val());
      formData.append("foto", filesToUpload[0]);
      formData.append("estado", $("#estado").val());
      formData.append("numeroFicha", $("#ficha option:selected").text());

      $.ajax({
        type: "POST",
        url: "../../controller/aprendiz/controller_aprendiz.php?opcion=2",
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
          // console.log(textStatus + "\n" + errorThrown);
        },
        success: function (result) {
          console.log(result);
          $("#LoadingImage").hide();
          var data = eval("(" + result + ")");

          var tipo_mensaje = "error";
          if (data.msg.code === 1) {
            tipo_mensaje = "success";
            oTable.fnDestroy();
            crear_tabla();

            $("#guardar").show();
            $("#update").hide();
            limpiarCampos();
          }
          //Mostramos mensaje de confirmación
          swal(data.msg.title, data.msg.mensaje, tipo_mensaje);
        },
      });
    }
  });

  //boton actualizar
  $("#update").click(function () {
    $("#LoadingImage").show();

    let errores = validarCampos(); // Validar campos con la función que definiste

    if (errores.length > 0) {
      const mensajeErrores = errores.join("\n");
      $("#LoadingImage").hide();
      swal("Campos inválidos", mensajeErrores, "error");
      return;
    } else {
      var formData = new FormData();
      formData.append("id_usuario", $("#miidFchaH").val());
      formData.append("identificacion", $("#identificacion").val());
      formData.append("nombre", $("#nombre").val());
      formData.append("correoeletronico", $("#correoeletronico").val());
      formData.append("direccion", $("#direccion").val());
      formData.append("celular", $("#celular").val());
      formData.append("ficha", $("#ficha").val());
      formData.append("foto", filesToUpload[0]);
      formData.append("estado", $("#estado").val());
      formData.append("numeroFicha", $("#ficha option:selected").text());

      $.ajax({
        type: "POST",
        url: "../../controller/aprendiz/controller_aprendiz.php?opcion=3",
        data: formData,
        processData: false,
        contentType: false,
        error: function (jqXHR, textStatus, errorThrown) {
          $("#LoadingImage").hide();
          console.log(textStatus + "\n" + errorThrown);
        },
        success: function (result) {
          $("#LoadingImage").hide();
          var data = eval("(" + result + ")");

          if (data.msg.code === 1) {
            tipo_mensaje = "success";

            oTable.fnDestroy();
            crear_tabla();

            $("#update").show();
            $("#guardar").hide();
          }
          // Mostramos mensaje de confirmación
          limpiarCampos();
          swal(data.msg.title, data.msg.mensaje, "info");
          
        },
      });
    }
  });
});

// Controlador de eventos para actualizar datos
$(".dataTables-aprendiz").on("click", "a.edit_aprendiz", function () {

  limpiarCampos();
  // Capturamos información de la fila de la tabla
  var $tr = $(this).closest("tr");

  // id
  var id = $tr.attr("id").replace("row_", ""); // Obtenemos el id desde el atributo id del tr

  var identificacion = $tr.find("td:eq(1)").text();
  var nombre = $tr.find("td:eq(2)").text();
  var correoeletronico = $tr.find("td:eq(3)").text();
  var direccion = $tr.find("td:eq(4)").text();
  var celular = $tr.find("td:eq(5)").text();
  var ficha = $tr.find("td:eq(6)").text();
  var estado = $tr.find("td:eq(7)").text();

  // Obtener la imagen en base64 desde el atributo data-imagen del ícono de foto
  var fotoDiv = $tr.find("td:eq(8)").find("a.fotoView").attr("href");

  // Complete el formulario con los datos para actualizar
  $("#miidFchaH").val(id);
  $("#identificacion").val(identificacion);
  $("#nombre").val(nombre);
  $("#correoeletronico").val(correoeletronico);
  $("#direccion").val(direccion);
  $("#celular").val(celular);

  $("#ficha option").each(function () {
    if ($(this).text() === ficha) {
      $(this).prop("selected", true);
      return false; // Stop the loop
    }
  });

  $("#estado option").each(function () {
    if ($(this).text() === estado) {
      $(this).prop("selected", true);
      return false; // Stop the loop
    }
  });

  // Mostrar la imagen
  htmlFoto =
    '<img alt="image" class="rounded-circle" id="foto_profile" src="data:image/png;base64,' +
    fotoDiv +
    '" width="100px" height="100px">  ';
  imagenPrevisualizacion.html(htmlFoto);

  // Mostrar/ocultar botones relevantes
  $("#guardar").hide();
  $("#update").show();
});

// Función para limpiar los campos de descripción y script
function limpiarCampos() {
  $("#identificacion").val(""); // Limpiar el campo con ID "numero"
  $("#nombre").val(""); // Restablecer la selección del programa a la opción predeterminada
  $("#correoeletronico").val(""); // Restablecer la selección del estado a la opción predeterminada
  $("#direccion").val("");
  $("#celular").val("");
  $("#ficha").val("");
  $("#foto").val("");
  $("#estado").val(""); // Desmarcar la casilla de verificación
  $("#guardar").show();
  $("#update").hide();
  filesToUpload = [];
}

//cargar combo programa
function cargarComboPF() {
  $("#ficha")
    .find("option")
    .remove()
    .end()
    .append('<option value="0">Seleccione la Ficha</option>')
    .val("0");
  $.ajax({
    url: "../../controller/aprendiz/controller_aprendiz.php?opcion=1",
    type: "GET",
    success: function (result) {
      var data = eval("(" + result + ")");
      $.each(data.msg.mensaje, function (item) {
        $("#ficha").append(
          $(
            '<option value="' +
              data.msg.mensaje[item].id +
              ";" +
              data.msg.mensaje[item].idP +
              '">' +
              data.msg.mensaje[item].value +
              "</option>"
          )
        );
      });
    },
  });
}

//gestion foto
function gestionFoto() {
  let htmlFoto = "";
  if (srcFoto === "") {
    htmlFoto =
      '<div class="icon-container"><i class="fa fa-user-circle-o fa-3x rounded-circle"></i></div>';
    imagenPrevisualizacion.html(htmlFoto);
  }
}

//tabla modulos
function crear_tabla() {
  $(".dataTables-aprendiz").DataTable({
    info: false,
    pageLength: 10,
    bAutoWidth: false,
    bLengthChange: false,
    responsive: true,
    language: {
      url: "../../i18n/DataTableSpanish.json",
    },
    dom: '<"html5buttons"B>lTfgitp',
    buttons: [
      { extend: "copy" },
      { extend: "csv" },
      { extend: "excel", title: "Aprendiz del sistema" },
      { extend: "pdf", title: "Aprendiz del sistema" },

      {
        extend: "print",
        customize: function (win) {
          $(win.document.body).addClass("white-bg");
          $(win.document.body).css("font-size", "10px");

          $(win.document.body)
            .find("table")
            .addClass("compact")
            .css("font-size", "inherit");
        },
      },
    ],
    processing: true,
    serverSide: true,
    ajax: "../../model/aprendiz/consultar_aprendiz.php",
    columns: [
      {
        orderable: false,
        data: null,
        defaultContent:
          '<center><input type="checkbox" class="i-checks" name="input[]"></center>',
      },
      { data: "miDocumento" },
      { data: "minombre" },
      { data: "mimail" },
      { data: "midir" },
      { data: "micel" },
      { data: "mificha" },
      { data: "estado" },
      {
        orderable: false,
        data: "mifoto",
      },

      {
        orderable: false,
        data: null,
        defaultContent:
          '<center><a href="#" data-toggle="modal" class="roles"><i class="fa fa-comments fa-2x"></i></a></center>',
      },

      {
        orderable: false,
        data: null,
        defaultContent:
          '<center><a href="#" data-toggle="modal" class="edit_aprendiz"><i class="fa fa-pencil-square-o fa-2x"></i></a></center>',
      },
    ],
    drawCallback: function () {
      //presenta conflicto con el checkbox de toogleswitcth
      $("input.i-checks").iCheck({
        checkboxClass: "icheckbox_square-green",
        radioClass: "iradio_square-green",
      });
    },
    select: {
      style: "multi",
      selector: "td:first-child",
    },
    order: [[1, "asc"]],
    //stateSave: true
  });

  $(".dataTables-aprendiz").dataTable().fnSetFilteringDelay();
}

//funcion validar campos
function validarCampos() {
  let errores = [];

  // Validar identificación (números)
  const identificacion = $("#identificacion").val();
  if (!/^\d+$/.test(identificacion)) {
    errores.push("La identificación debe contener solo números.");
  }

  // Validar nombre (texto)
  const nombre = $("#nombre").val();
  if (nombre.trim() === "") {
    errores.push("El nombre es un campo obligatorio.");
  }

  // Validar correo electrónico
  const correo = $("#correoeletronico").val();
  if (!/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/.test(correo)) {
    errores.push("Por favor, ingrese un correo electrónico válido.");
  }

  // Validar dirección (texto)
  const direccion = $("#direccion").val();
  if (direccion.trim() === "") {
    errores.push("La dirección es un campo obligatorio.");
  }

  // Validar celular (números)
  const celular = $("#celular").val();
  if (!/^\d+$/.test(celular)) {
    errores.push("El número de celular debe contener solo números.");
  }

  // Validar ficha (seleccionado)
  const ficha = $("#ficha").val();
  if (ficha === "0") {
    errores.push("Por favor, seleccione una ficha.");
  }

  // Validar estado (seleccionado)
  const estado = $("#estado").val();
  if (estado === "0") {
    errores.push("Por favor, seleccione un estado.");
  }

  // // Validar foto (seleccionado)
  // const foto = $("#foto").val();
  // if (foto === "") {
  //   errores.push("Por favor, seleccione una foto.");
  // }

  return errores;
}

jQuery.fn.dataTableExt.oApi.fnSetFilteringDelay = function (oSettings, iDelay) {
  /*
   * Inputs:      object:oSettings - dataTables settings object - automatically given
   *              integer:iDelay - delay in milliseconds
   * Usage:       $('#example').dataTable().fnSetFilteringDelay(250);
   * Author:      Zygimantas Berziunas (www.zygimantas.com) and Allan Jardine
   * License:     GPL v2 or BSD 3 point style
   * Contact:     zygimantas.berziunas /AT\ hotmail.com
   */
  var _that = this,
    iDelay = typeof iDelay == "undefined" ? 250 : iDelay;

  this.each(function (i) {
    $.fn.dataTableExt.iApiIndex = i;
    var $this = this,
      oTimerId = null,
      sPreviousSearch = null,
      anControl = $("input", _that.fnSettings().aanFeatures.f);

    anControl.unbind("keyup").bind("keyup", function () {
      var $$this = $this;

      if (sPreviousSearch === null || sPreviousSearch != anControl.val()) {
        window.clearTimeout(oTimerId);
        sPreviousSearch = anControl.val();
        oTimerId = window.setTimeout(function () {
          $.fn.dataTableExt.iApiIndex = i;
          _that.fnFilter(anControl.val());
        }, iDelay);
      }
    });
    return this;
  });
  return this;
};

jQuery.fn.dataTableExt.oApi.fnReloadAjax = function (
  oSettings,
  sNewSource,
  fnCallback,
  bStandingRedraw
) {
  // DataTables 1.10 compatibility - if 1.10 then `versionCheck` exists.
  // 1.10's API has ajax reloading built in, so we use those abilities
  // directly.
  if (jQuery.fn.dataTable.versionCheck) {
    var api = new jQuery.fn.dataTable.Api(oSettings);

    if (sNewSource) {
      api.ajax.url(sNewSource).load(fnCallback, !bStandingRedraw);
    } else {
      api.ajax.reload(fnCallback, !bStandingRedraw);
    }
    return;
  }

  if (sNewSource !== undefined && sNewSource !== null) {
    oSettings.sAjaxSource = sNewSource;
  }

  // Server-side processing should just call fnDraw
  if (oSettings.oFeatures.bServerSide) {
    this.fnDraw();
    return;
  }

  this.oApi._fnProcessingDisplay(oSettings, true);
  var that = this;
  var iStart = oSettings._iDisplayStart;
  var aData = [];

  this.oApi._fnServerParams(oSettings, aData);

  oSettings.fnServerData.call(
    oSettings.oInstance,
    oSettings.sAjaxSource,
    aData,
    function (json) {
      /* Clear the old information from the table */
      that.oApi._fnClearTable(oSettings);

      /* Got the data - add it to the table */
      var aData =
        oSettings.sAjaxDataProp !== ""
          ? that.oApi._fnGetObjectDataFn(oSettings.sAjaxDataProp)(json)
          : json;

      for (var i = 0; i < aData.length; i++) {
        that.oApi._fnAddData(oSettings, aData[i]);
      }

      oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();

      that.fnDraw();

      if (bStandingRedraw === true) {
        oSettings._iDisplayStart = iStart;
        that.oApi._fnCalculateEnd(oSettings);
        that.fnDraw(false);
      }

      that.oApi._fnProcessingDisplay(oSettings, false);

      /* Callback user function - for event handlers etc */
      if (typeof fnCallback == "function" && fnCallback !== null) {
        fnCallback(oSettings);
      }
    },
    oSettings
  );
};
