/*
 * Funciones JS para el la pagina gestion de roles.
 */ $(document).ready(function () {
  //se ejecuta inmeditamente cargue el codigo html

  //Lipmiar form
  cleanForm();

  //check estado inactivo al inico de la pagina
  $("#estado").bootstrapToggle("off");

  //cargar tabla de modulo
  crear_tabla();

  //Variable para manejar la informacion de la tabla
  var oTable = $(".dataTables-roles").dataTable();

  //cargar modulos
  cargar_roles();

  $("#cancelarRolPermiso").click(function () {
    $("#LoadingImage").hide();
    $("#permisosRoles").modal("toggle");
  });

  //validar formulario de modulo boton guardar
  $("#guardar").click(function () {
    $("#LoadingImage").show(); //esta es para mostrar la imagen cargando y .show lo muestra en pantalla
    let campoRequerido = validate();
    if (campoRequerido) {
      $("#LoadingImage").hide(); //hide es para ocultar el elemento
      swal("Datos incompletos", "Le faltan campos por rellenar", "error");
    } else {
      var formData = new FormData();
      formData.append("nombre_rol", $("#nombre").val());

      var estado;
      if ($("#estado").prop("checked")) {
        estado = "1";
      } else {
        estado = "0";
      }
      formData.append("estado", estado);

      $.ajax({
        type: "POST",
        url: "../../controller/roles/roles__controller.php?opcion=1",
        data: formData,
        processData: false,
        contentType: false,
        error: function (jqXHR, textStatus, errorThrown) {
          $("#LoadingImage").hide();
          swal(
            "Error",
            "Hubo un error en el sistema. Cominicarse con soporte técnico",
            "error"
          );
          console.log(textStatus + "\n" + errorThrown);
        },
        success: function (result) {
          $("#LoadingImage").hide();
          var data = eval("(" + result + ")");

          var tipo_mensaje = "error";
          if (data.msg.code === 1) {
            tipo_mensaje = "success";
            oTable.fnDestroy();
            crear_tabla();

            $("#guardar").show();
            $("#update").hide();
          }
          //Mostamos mensaje de confirmacion
          cleanForm();
          swal(data.msg.title, data.msg.mensaje, tipo_mensaje);
        },
      });
    }
  });

  //Actualizar
  $("#update").click(function () {
    $("#LoadingImage").show();
    if ($("#nombre").val() !== "") {
      // Crear un objeto FormData con los datos actualizados
      var formData = new FormData();
      formData.append("nombre_rol", $("#nombre").val());
      formData.append("estado", $("#estado").val());
      formData.append("id_roles", $("#miIdRol").val());

      var estado;
      if ($("#estado").prop("checked")) {
        estado = "1";
      } else {
        estado = "0";
      }
      formData.append("estado", estado);

      // Realizar la solicitud AJAX para actualizar el módulo
      $.ajax({
        type: "POST",
        url: "../../controller/roles/roles__controller.php?opcion=2",
        data: formData,
        processData: false,
        contentType: false,
        error: function (jqXHR, textStatus, errorThrown) {
          $("#LoadingImage").hide();
          console.log(textStatus + "\n" + errorThrown);
        },
        success: function (result) {
          var data = eval("(" + result + ")");

          if (data.msg.code === 1) {
            tipo_mensaje = "success";

            oTable.fnDestroy();
            crear_tabla();

            $("#update").show();
            $("#guardar").hide();
          }
          // Mostramos mensaje de confirmación
          cleanForm();
          swal(data.msg.title, data.msg.mensaje, "info");
          $("#LoadingImage").hide();
        },
      });
    }
  });

  // habilitar
  $(".dataTables-roles").on("click", "a.enabled_disabled", function () {
    $("#LoadingImage").show();
    //Capturamos informacion de la fila de la tabla
    var $tr = $(this).closest("tr");
    //id
    var id = $tr[0].id.replace("row_", "");
    var rowIndex = $tr.index();
    selectedRow = $(".dataTables-roles tbody tr:eq(" + rowIndex + ")");
    var td = $(selectedRow).children("td");

    var formData = new FormData();
    formData.append("id_roles", id);

    var estado;
    if (td[3].innerText === "Habilitado") {
      estado = "0";
    } else {
      estado = "1";
    }
    formData.append("estado", estado);

    $.ajax({
      type: "POST",
      url: "../../controller/roles/roles__controller.php?opcion=3",
      data: formData,
      processData: false,
      contentType: false,
      error: function (jqXHR, textStatus, errorThrown) {
        $("#LoadingImage").hide();
        console.log(textStatus + "\n" + errorThrown);
      },
      success: function (result) {
        tipo_mensaje = "success";

        var data = eval("(" + result + ")");

        if (data.msg.code === 1) {
          oTable.fnDestroy();
          crear_tabla();
        }
        //Mostamos mensaje de confirmacion
        swal(data.msg.title, data.msg.mensaje, "info");
        $("#LoadingImage").hide();
      },
    });
  });

  //Asignar los roles a cada modulo esta es la opcionn de html donde dice Guardar Roles parte final
  $("#guardarRolPermiso").click(function () {
    var formDatarolPermisos = new FormData();
    var nombre_modulo = $("#nombre_moduloH").val();
    formDatarolPermisos.append("idModuloRolH", $("#idModuloRolH").val());
    formDatarolPermisos.append("idRol", $("#idModuloRolH").val());

    var array_rol_state = [];
    $(".permisos").each(function () {
      var $this = $(this);
      var datosrolstate;
      var id_rol_permiso = $this.attr("id");
      id_rol_permiso = id_rol_permiso.replace("_checkbox", "");
      if ($this.is(":checked")) {
        datosrolstate = { id_modulo: id_rol_permiso, estado: "1" };
      } else {
        datosrolstate = { id_modulo: id_rol_permiso, estado: "0" };
      }
      array_rol_state.push(datosrolstate);
    });
    var json_array_rol_stateRendered = JSON.stringify(array_rol_state);
    formDatarolPermisos.append("modulos_estadop", json_array_rol_stateRendered);

    swal(
      {
        title: "Desea continuar?",
        text:
          "Se va a realizar el cambio del estado de los modulos asociados al roles " +
          nombre_modulo +
          ". ¿Está seguro de realizar dicha operación?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ed5565",
        confirmButtonText: "Si, Continuar!",
        cancelButtonText: "No, cancelar !",
        closeOnConfirm: false,
      },
      function (isConfirm) {
        if (isConfirm) {
          $("#LoadingImage").show();
          $.ajax({
            type: "POST",
            url: "../../controller/roles/roles__controller.php?opcion=6",
            data: formDatarolPermisos,
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
                //oTableRoles.fnDestroy();
                //crear_tabla_roles_asociados($('#idModuloRolH').val());
                swal("Actualizado!", data.msg.mensaje, "success");
                $("#permisosRoles").modal("hide");
              } else if (data.msg.code === 0) {
                swal("Error!", data.msg.mensaje, "error");
              }
            },
          });
        }
      }
    );
  });

  //clicl sobre la opcion de roles asociados y abre directamente el formulario
  // de asociar roles     lo debo cambiar
  $(".dataTables-roles").on("click", "a.modulos_asociados", function () {
    $("#LoadingImage").show();
    $("#permisosRoles").modal(); //Modal para asignar roles --> ahi en el id incuentro como lo llame
    document.getElementById("formPermisosRoles").reset(); //<!-- Formulario para seleccion de roles--> lo encuentro en el id

    //Capturamos informacion de la fila de la tabla
    var $tr = $(this).closest("tr");
    //id del modulo
    var id = $tr[0].id.replace("row_", "");
    var rowIndex = $tr.index();
    selectedRow = $(".dataTables-roles tbody tr:eq(" + rowIndex + ")");
    var td = $(selectedRow).children("td");

    $("#idModuloRolH").val(id);
    $("#idModulo").val(id);
    $("#nombre_moduloH").val(td[2].innerText);
    $("#nombre_modulo").val(td[2].innerText);

    var formData = new FormData();
    formData.append("idModulo", id);
    $.ajax({
      type: "POST",
      url: "../../controller/roles/roles__controller.php?opcion=7",
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
          datos = data.msg.mensaje;
          datos.forEach(function (dato) {
            if (dato["estado"] === 1) {
              check_name = "#" + dato["id_rol"] + "_checkbox";
              console.log($(check_name));
              $(check_name).bootstrapToggle("on");
            }
          });
        }
      },
    });
  });

  //Validar campos requeridos modulo
  $(".requerido").on("keyup", function () {
    if ($(this).val().trim() !== "") {
      $(this).removeClass("is-invalid");
    } else {
      $(this).addClass("is-invalid");
    }
  });

  //evento limpiar
  $("#cancelar").click(function (e) {
    $("#estado").bootstrapToggle("off");
    $("#miIdRol").val(""); //limpiar id;
    // Mostrar/ocultar botones relevantes
    $("#guardar").show();
    $("#update").hide();
  });

  // Controlador de eventos para actualizar datos
  $(".dataTables-roles").on("click", "a.edit_roles", function () {
    //Capturamos informacion de la fila de la tabla
    var $tr = $(this).closest("tr");
    //id
    var id = $tr[0].id.replace("row_", "");

    var id = $(this).closest("tr").find("td:eq(1)").text();
    var nombre_rol = $(this).closest("tr").find("td:eq(2)").text();
    var estado = $(this).closest("tr").find("td:eq(3)").text();

    // Complete el formulario con los datos para actualizar
    $("#miIdRol").val(id);
    $("#nombre").val(nombre_rol);

    // Si el estado es "Activo", marque la casilla; de lo contrario, desmárcalo
    if (estado === "Habilitado") {
      $("#estado").bootstrapToggle("on");
    } else {
      $("#estado").bootstrapToggle("off");
    }

    // Mostrar/ocultar botones relevantes
    $("#guardar").hide();
    $("#update").show();
  });

  //abrir dialogo roles
  $(".dataTables-roles").on("click", "a.modulos_asociados", function () {
    $("#permisosRoles").modal();
  });
});

//Cambiar el estado de un rol asociado de manera indiviual

$(".dataTables-rolesasoaciados").on(
  "click",
  "a.cambiar_estado_role_permiso",
  function () {
    $("#LoadingImage").show();
    //Capturamos informacion de la fila de la tabla
    var $tr = $(this).closest("tr");
    //id
    var id = $tr[0].id.replace("row_", "");
    var rowIndex = $tr.index();
    selectedRow = $(
      ".dataTables-rolesasoaciados tbody tr:eq(" + rowIndex + ")"
    );
    var td = $(selectedRow).children("td");

    var formData = new FormData();
    formData.append("roles__modulo_id", id);

    var estado;
    if (td[2].innerText === "Habilitado") {
      estado = "0";
    } else {
      estado = "1";
    }
    formData.append("roles_modulo_estado", estado);

    $.ajax({
      type: "POST",
      url: "../../controller/roles/roles__controller.php?opcion=8",
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
          oTableRoles.fnDestroy();
          crear_tabla_roles_asociados($("#idModuloIntH").val());
        }
        //Mostamos mensaje de confirmacion
        swal(data.msg.title, data.msg.mensaje, "info");
      },
    });
  }
);

//tabla modulos
function crear_tabla() {
  $(".dataTables-roles").DataTable({
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
      { extend: "excel", title: "Roles del sistema" },
      { extend: "pdf", title: "Roles del sistema" },

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
    ajax: "../../model/roles/consultar_roles.php",
    columns: [
      {
        orderable: false,
        data: null,
        defaultContent:
          '<center><input type="checkbox" class="i-checks" name="input[]"></center>',
      },
      { data: "miroles" },
      { data: "minombre_rol" },
      { data: "miestado" },
      {
        orderable: false,
        data: null,
        defaultContent:
          '<center><a href="#" data-toggle="modal" class="modulos_asociados"><i class="fa fa-share-alt-square fa-2x"></i></a></center>',
      },
      {
        orderable: false,
        data: null,
        defaultContent:
          '<center><a href="#" data-toggle="modal" class="edit_roles"><i class="fa fa-pencil-square-o fa-2x"></i></a></center>',
      },
      { orderable: false, data: "estado_icon" },
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

  $(".dataTables-roles").dataTable().fnSetFilteringDelay();
}

//funcion cargar modulos en modal
function cargar_roles() {
  $(".roles_asociados").empty();
  $.ajax({
    type: "POST",
    url: "../../controller/roles/roles__controller.php?opcion=5",
    processData: false,
    contentType: false,
    error: function (jqXHR, textStatus, errorThrown) {
      $("#LoadingImage").hide();
      console.log(textStatus + "\n" + errorThrown);
    },
    success: function (result) {
      tipo_mensaje = "success";
      var data = eval("(" + result + ")");
      var html_roles = "";
      $.each(data.msg, function (item) {
        html_roles =
          '<label class="col-lg-4 col-form-label">' +
          data.msg[item].value +
          '</label></br></br><div class="col-lg-2">' +
          '<input type="checkbox" class="switch switch-on toggle permisos" id="' +
          data.msg[item].id +
          '_checkbox" name="' +
          data.msg[item].value +
          '_checkbox" data-toggle="toggle" data-on="Si" data-off="No" data-onstyle="primary" data-offstyle="danger">' +
          "</div>";
        $(".roles_asociados").append(html_roles);
        $(".permisos").bootstrapToggle("enable");
        $(".permisos").bootstrapToggle("off");
      });      
    },
  });
}

//Tabla modulos asociados
function crear_tabla_roles_asociados(rolid) {
  $(".dataTables-rolesasoaciados").DataTable({
    info: false,
    pageLength: 10,
    bAutoWidth: false,
    bLengthChange: false,
    responsive: true,
    language: {
      url: "../../i18n/DataTableSpanish.json",
    },
    processing: true,
    serverSide: true,
    ajax: "../../model/roles/consultar_roles.php?param1=" + rolid,
    columns: [
      { data: "miroles", target: 0 },
      { data: "minombre_rol", target: 1 },
      { data: "miestado", target: 2 },
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
}

//funcion validar campos
function validate() {
  let campoRequerido = false;
  $(".requerido").each(function () {
    if ($(this).val().trim() == "") {
      campoRequerido = true;
      $(this).addClass("is-invalid");
    }
  });
  return campoRequerido;
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

  // El procesamiento del lado del servidor solo debe llamar a fnDraw
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

//funcion limpiar
function cleanForm() {
  $("#estado").bootstrapToggle("off");
  $("#miIdRol").val(""); //limpiar id;
  $("#nombre").val(""); //limpiar id;

  // Mostrar/ocultar botones relevantes
  $("#guardar").show();
  $("#update").hide();
}
