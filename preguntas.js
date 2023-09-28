/*
 * Funciones JS para la página gestión de roles.
 */
var srcFoto = "";
var filesToUpload = [];

var CantidadArray = new Array(); //Aca se guardan los index de los integrantes
var cantidadTotal = 0;
var cantidadTotalRespuestas = 0;
var infoRespuestas = "";

$(document).ready(function () {
  seleccionArchivos = $("#foto");
  imagenPrevisualizacion = $("#fotoiconDiv");

  // Cuando el documento está listo
  // Oculta el campo de entrada de la foto al inicio
  $("#fotoDiv").hide();

  // Escucha el cambio en el estado del checkbox pregunta
  $("#toggleCheckbox").change(function () {
    if ($(this).is(":checked")) {
      $("#fotoDiv").show(); // Muestra el campo de entrada de la foto si el checkbox está marcado
    } else {
      $("#fotoDiv").hide(); // Oculta el campo de entrada de la foto si el checkbox no está marcado
    }
  });

  // Resto de tu código...

  crear_tabla();
  gestionFoto();
  limpiarCampos();

  var oTable = $(".dataTables-tbl_pregunta").dataTable();

  //se ejecuta inmediatamente cargue todo el HTML
  seleccionArchivos = $("#foto");
  imagenPrevisualizacion = $("#fotoiconDiv");

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

  //cargar tabla de bancoPreguntas
  //Boton guardar
  $("#guardar").click(function () {
    $("#LoadingImage").show();
    let campoRequerido = validate();
    if (campoRequerido) {
      $("#LoadingImage").hide();
      swal("Datos incompletos", "Le faltan campos por rellenar", "error");
    } else {
      //agregar datos para Preguntas
      var formData = new FormData();
      formData.append("nombrePregunta", $("#nombrePregunta").val());
      var estado_imagen;
      if ($("#toggleCheckbox").prop("checked")) {
        estado_imagen = "1";
        formData.append("foto", filesToUpload[0]);
      } else {
        estado_imagen = "0";
      }
      formData.append("toggleCheckbox", estado_imagen);
      var estado;
      if ($("#estado").prop("checked")) {
        estado = "1";
      } else {
        estado = "0";
      }
      formData.append("estado", estado);
      //haciendo un ciclo, agrego las cantidades de respuesta que tengo
      //agragar datos para respuestas
      for ($i = 0; $i < CantidadArray.length; $i++) {
        var $row = $("#integrantes").find(
          '[data-book-index="' + CantidadArray[$i] + '"]'
        );
        var nombreRespuesta = $row
          .find('[id="nombreRespuesta' + CantidadArray[$i] + '"]')
          .val();
        var numero = $row.find('[id="numero' + CantidadArray[$i] + '"]').val();
        //comparamos si los campos estan vacios
        if (nombreRespuesta === "" || numero === "") {
        } else {
          infoRespuestas += nombreRespuesta + "@@" + numero + "##";
        }
      }
      //en esta parte agragamos al controller una sola varible que se llama respuesta
      formData.append("respuestas", infoRespuestas);
      $.ajax({
        type: "POST",
        url: "../../controller/preguntas/preguntas_controller.php?opcion=1",
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
          var data = eval("(" + result + ")");
          var tipo_mensaje = "error";
          if (data.msg.code === 1) {
            tipo_mensaje = "success";
            oTable.fnDestroy();
            crear_tabla();
            $("#update").show();
            $("#guardar").hide();
            limpiarCampos();
          }
          swal(data.msg.title, data.msg.mensaje, tipo_mensaje);
        },
      });
    }
  });

  $("#cancelar").click(function (e) {
    limpiarCampos();
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
      formData.append("idPreguntaH", $("#idPreguntaH").val());
      formData.append("nombrePregunta", $("#nombrePregunta").val());
      var estado_imagen;
      if ($("#toggleCheckbox").prop("checked")) {
        estado_imagen = "1";
        formData.append("foto", filesToUpload[0]);
      } else {
        estado_imagen = "0";
      }
      formData.append("toggleCheckbox", estado_imagen);
      var estado;
      if ($("#estado").prop("checked")) {
        estado = "1";
      } else {
        estado = "0";
      }
      formData.append("estado", estado);
      //agragar datos para respuestas
      for ($i = 0; $i < CantidadArray.length; $i++) {
        var $row = $("#integrantes").find(
          '[data-book-index="' + CantidadArray[$i] + '"]'
        );
        var nombreRespuesta = $row
          .find('[id="nombreRespuesta' + CantidadArray[$i] + '"]')
          .val();
        var numero = $row.find('[id="numero' + CantidadArray[$i] + '"]').val();
        var ID_respuesta = $row.find('[id="idRespuestaH' + CantidadArray[$i] + '"]').val();

        if (typeof ID_respuesta === "undefined" || ID_respuesta === "") {
          ID_respuesta=-1;
        }
        //comparamos si los campos estan vacios
        if (nombreRespuesta === "" || numero === "" || ID_respuesta === "") {
  
        } else {
          infoRespuestas += ID_respuesta + "@@" + nombreRespuesta + "@@" + numero + "##";
        }
      }
      //en esta parte agragamos al controller una sola varible que se llama respuesta
      formData.append("respuestas", infoRespuestas);
      gestionFoto();

      $.ajax({
        type: "POST",
        url: "../../controller/preguntas/preguntas_controller.php?opcion=2",
        data: formData,
        processData: false,
        contentType: false,
        error: function (jqXHR, textStatus, errorThrown) {
          $("#LoadingImage").hide();
          console.log(textStatus + "\n" + errorThrown);
          limpiarCampos();
        },
        success: function (result) {
          $("#LoadingImage").hide();
          var data = eval("(" + result + ")");

          if (data.msg.code === 1) {
            tipo_mensaje = "success";

            oTable.fnDestroy();
            // crear_tabla();

            $("#update").show();
            $("#guardar").hide();
            $("#idPreguntaH").val("");
          }
          // Mostramos mensaje de confirmación
          limpiarCampos();
          swal(data.msg.title, data.msg.mensaje, "info");
        },
      });
    }
  });
});

//validar campos requeridos
$(".required").on("keyup", function () {
  if ($(this).val().trim() !== "") {
    $(this).removeClass("is-invalid");
  } else {
    $(this).addClass("is-invalid");
  }
});

// Esta es la zona donde va el nuevo js para agragar nuevas respuestas

//agregar un nueva Pregunta
$("#integrantes").on("click", ".insertparticipante", function () {
  cantidadTotal = cantidadTotalRespuestas;
  var $template = $("#bookTemplate"),
    $clone = $template
      .clone(true)
      .removeAttr("style")
      .removeAttr("id")
      .attr("data-book-index", cantidadTotal)
      .insertBefore($template);
  // le cambio el id , por name , debo de cambiarles todos los nombres del id que sean distintos para que funcione ! OJO ¡
  $clone
    .find('[name="idRespuestaH"]')
    .removeAttr("id")
    .attr("id", "idRespuestaH" + cantidadTotal)
    .end()
    .find('[name="idREspuesta"]')
    .removeAttr("id")
    .attr("id", "idREspuesta" + cantidadTotal)
    .end()
    .find('[name="nombreRespuesta"]')
    .removeAttr("id")
    .attr("id", "nombreRespuesta" + cantidadTotal)
    .end()
    .find('[name="numero"]')
    .removeAttr("id")
    .attr("id", "numero" + cantidadTotal)
    .end();

  CantidadArray.push("" + cantidadTotal);
  cantidadTotalRespuestas++;
});

//eliminar Nueva Pregunta
// Manejador de eventos para eliminar elementos clonados
$("#integrantes").on("click", ".removePariticipante", function () {
  var $row = $(this).parents(".form-group");
  var i = $row.attr("data-book-index");

  var $row = $("#integrantes").find('[data-book-index="' + i + '"]');
  var idRespuesta = $row.find('[name="idRespuestaH' + i + '"]').val();

  if (typeof idRespuesta === "undefined") {
    //Indicamos que ese formulario no existe
    var index = CantidadArray.indexOf("" + i);
    if (index !== -1) {
      CantidadArray.splice(index, 1);
    }
    $row.remove();
  }

  //$(this).closest(".form-group").remove(); // Elimina el form-group padre más cercano
  //cantidadTotalRespuestas--; // Decrementa el contador de elementos clonados
});

// Controlador de eventos para actualizar datos
$(".dataTables-tbl_pregunta").on("click", "a.edit_pregunta", function () {
  limpiarCampos();
  // Capturamos información de la fila de la tabla
  var $tr = $(this).closest("tr");

  // id
  var id = $tr.attr("id").replace("row_", ""); // Obtenemos el id desde el atributo id del tr
  var pregunta = $tr.find("td:eq(1)").text();
  var estado = $tr.find("td:eq(2)").text();
  // Obtener la imagen en base64 desde el atributo data-imagen del ícono de foto
  var fotoDiv = $tr.find("td:eq(4)").find("a.fotoView").attr("href");

  // Complete el formulario con los datos para actualizar
  $("#idPreguntaH").val(id);
  $("#nombrePregunta").val(pregunta);
  $("#estadodiv").val(estado);
  $("#fotoDiv").val(fotoDiv);

  var $id_pregunta;
  // Mostrar la imagen
  htmlFoto =
    '<img alt="image" class="rounded-circle" id="foto_profile" src="data:image/png;base64,' +
    fotoDiv +
    '" width="100px" height="100px">  ';
  imagenPrevisualizacion.html(htmlFoto);

  var formData = new FormData();
  formData.append("idPreguntaH", id);
  $.ajax({
    type: "POST",
    url: "../../controller/preguntas/preguntas_controller.php?opcion=3",
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
      var data = eval("(" + result + ")");
      var tipo_mensaje = "error";
      
      if (data.msg.code === 1) {
        var respuestaTexto = data.msg.mensaje;
        let arregloRespuesta = respuestaTexto.split("##");
        arregloRespuesta.forEach(function (elementos) {
          cantidadTotal = cantidadTotalRespuestas;
          let elemento = elementos.split("@@");
          //espacio para clonar 
          var $template = $("#bookTemplate"),
            $clone = $template
              .clone(true)
              .removeAttr("style")
              .removeAttr("id")
              .attr("data-book-index", cantidadTotal)
              .insertBefore($template);
          $clone
            .find('[name="idRespuestaH"]')
            .removeAttr("id")
            .attr("id", "idRespuestaH" + cantidadTotal)
            .val(elemento[0])
            .end()
            .find('[name="idREspuesta"]')
            .removeAttr("id")
            .attr("id", "idREspuesta" + cantidadTotal)
            .val(elemento[0])
            .end()
            .find('[name="nombreRespuesta"]')
            .removeAttr("id")
            .attr("id", "nombreRespuesta" + cantidadTotal)
            .val(elemento[1])
            .end()
            .find('[name="numero"]')
            .removeAttr("id")
            .attr("id", "numero" + cantidadTotal)
            .val(elemento[2])
            .end();
          CantidadArray.push("" + cantidadTotal);
          cantidadTotalRespuestas++;
        });
      } else {
        swal(data.msg.title, data.msg.mensaje, tipo_mensaje);
      }
    },
  });

  // Mostrar/ocultar botones relevantes
  $("#guardar").hide();
  $("#update").show();
});

// Función para limpiar los campos de descripción y script
function limpiarCampos() {
  $("#idPreguntaH").val(""); // Después de completar la actualización, limpiar el campo oculto
  $("#nombrePregunta").val(""); // Limpiar campo de nombre
  $("#foto").val(""); // Limpiar id
  $("#toggleCheckbox").val(""); // Limpiar campo de descripción           // Limpiar campo de script
  $("#estado").bootstrapToggle("off"); // Desmarcar el checkbox de estado (si está marcado)
  $("#guardar").show();
  $("#update").hide();

  for ($i = 0; $i < CantidadArray.length; $i++) {
    var $row = $("#integrantes").find(
      '[data-book-index="' + CantidadArray[$i] + '"]'
    );
    $row.remove();
  }

  CantidadArray = new Array(); //igualando en 0
  cantidadTotal = 0;
  cantidadTotalRespuestas = 0;
  infoRespuestas = "";
  filesToUpload = [];
  // Limpiar la previsualización de la imagen
  imagenPrevisualizacion.html("");
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

//Crea la tabla en la vista
function crear_tabla() {
  $(".dataTables-tbl_pregunta").DataTable({
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
      { extend: "excel", title: "Pregunta del sistema" },
      { extend: "pdf", title: "Pregunta del sistema" },

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
    ajax: "../../model/preguntas/consultar_preguntas.php",
    columns: [
      {
        orderable: false,
        data: null,
        defaultContent:
          '<center><input type="checkbox" class="i-checks" name="input[]"></center>',
      },
      { data: "miPregunta" },
      {
        orderable: false,
        data: "respuestas",
      },
      { data: "miestado" },
      {
        orderable: false,
        data: "mifoto",
      },
      {
        orderable: false,
        data: null,
        defaultContent:
          '<center><a href="#" data-toggle="modal" class="edit_pregunta"><i class="fa fa-pencil-square-o fa-2x"></i></a></center>',
      },
      {
        orderable: false,
        data: "estado_icon",
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

  $(".dataTables-tbl_pregunta").dataTable().fnSetFilteringDelay();
}

//funcion validar campos
function validate() {
  let campoRequerido = false;
  $(".requerido").each(function () {
    if ($(this).val().trim() === "") {
      console.log("prueba");
      campoRequerido = true;
      $(this).addClass("is-invalid");
    }
  });
  return campoRequerido;
}
//funcion validar campos
function validarCampos() {
  let errores = [];

  // Validar nombre (texto)
  const nombrePregunta = $("#nombrePregunta").val();
  if (nombrePregunta.trim() === "") {
    errores.push("La pregunta es un campo obligatorio.");
  }

  // Validar dirección (texto)
  const toggleCheckbox = $("#toggleCheckbox").prop("checked");
  if (toggleCheckbox === 0) {
    errores.push("La dirección es un campo obligatorio.");
  }

  // Validar estado (seleccionado)
  const estado = $("#estado").prop("checked");
  if (estado === "0") {
    errores.push("Por favor, seleccione un estado.");
  }

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
