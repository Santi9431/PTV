$(document).ready(function () {
  //se ejecuta inmediatamente cargue todo el HTML

  //cargar tabla de migraciones
  crear_tabla();
  limpiarCampos();
  cargarComboPF();

  var oTable = $(".dataTables-tbl_ficha ").dataTable();

  //validar formulario
  $("#guardar").click(function(){
    $("#LoadingImage").show(); //esta es para mostrar la imagen cargando y .show lo muestra en pantalla
    let campoRequerido = validate();
    if(campoRequerido){
        $("#LoadingImage").hide() //hide es para ocultar el elemento
        swal("Datos incompletos", "Le faltan campos por rellenar","error");
    } else{
        var formData = new FormData();
        //estos campos son los que van en el html llamados en el id
        formData.append("ficha", $('#numero').val());
        formData.append("idPrograma", $('#programa').val()); 
        formData.append("estado", $('#estado').val()); 

             
        $.ajax({
            type: "POST",
            url: "../../controller/ficha/controller_ficheros.php?opcion=1",
            data: formData,
            processData: false,
            contentType: false,
            error: function (jqXHR, textStatus, errorThrown) {
                $("#LoadingImage").hide();
                swal("Error", "Hubo un error en el sistema. Comunicarse con soporte técnico", "error");
                // console.log(textStatus + "\n" + errorThrown);
            },
            success: function (result) {
                $("#LoadingImage").hide();
                var data = eval('(' + result + ')');

                var tipo_mensaje = "error";
                if (data.msg.code === 1) {
                    tipo_mensaje = "success";
                    oTable.fnDestroy();
                    crear_tabla();

                    $('#update').show();
                    $('#guardar').hide();
                    limpiarCampos();
                }
                //Mostramos mensaje de confirmación                
                swal(data.msg.title, data.msg.mensaje, tipo_mensaje);
                
            }
        });


    }
  });

  //validar campos requeridos
  $(".requerido").on("keyup", function () {
    if ($(this).val().trim() !== "") {
        $(this).removeClass("is-invalid");
    }else{
        $(this).addClass("is-invalid");
    }
  });
  
});

// Función para limpiar los campos de descripción y script
function limpiarCampos() {
  $('#numero').val('');       // Limpiar el campo con ID "numero"
  $('#programa').val('0');    // Restablecer la selección del programa a la opción predeterminada
  $('#titulo').val('0');      // Restablecer la selección del estado a la opción predeterminada
  $('#estado').val('0'); // Desmarcar la casilla de verificación
  $('#guardar').show();
  $('#update').hide();
}




//tabla fichero
function crear_tabla() {
  $(".dataTables-tbl_ficha").DataTable({
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
    ajax: "../../model/ficha/consultar_fichas.php",
    columns: [
      {
        orderable: false,
        data: null,
        defaultContent:
          '<center><input type="checkbox" class="i-checks" name="input[]"></center>',
      },
      { data: "numFicha" },
      { data: "nombre_PF" },
      { data: "estado" },
      { orderable: false, data: "horario" },
      { orderable: false, data: "Aprendices" },
      { orderable: false, data: "editar" },
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

  $(".dataTables-tbl_ficha ").dataTable().fnSetFilteringDelay();
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

//cargar combo programa
function cargarComboPF() {
  $('#programa')
          .find('option')
          .remove()
          .end()
          .append('<option value="0">Seleccione un programa</option>')
          .val('0')
          ;
  $.ajax({
      url: "../../controller/ficha/controller_ficheros.php?opcion=4",
      type: "GET",
      success: function (result) {
          var data = eval('(' + result + ')');
          $.each(data.msg.mensaje, function (item) {
              $('#programa').append($('<option value="' + data.msg.mensaje[item].id + '">' + data.msg.mensaje[item].value + '</option>'));
          });
      }
  });
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
  var
          _that = this,
          iDelay = (typeof iDelay == 'undefined') ? 250 : iDelay;

  this.each(function (i) {
      $.fn.dataTableExt.iApiIndex = i;
      var
              $this = this,
              oTimerId = null,
              sPreviousSearch = null,
              anControl = $('input', _that.fnSettings().aanFeatures.f);

      anControl.unbind('keyup').bind('keyup', function () {
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
}

jQuery.fn.dataTableExt.oApi.fnReloadAjax = function (oSettings, sNewSource, fnCallback, bStandingRedraw) {
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

  oSettings.fnServerData.call(oSettings.oInstance, oSettings.sAjaxSource, aData, function (json) {
      /* Clear the old information from the table */
      that.oApi._fnClearTable(oSettings);

      /* Got the data - add it to the table */
      var aData = (oSettings.sAjaxDataProp !== "") ?
              that.oApi._fnGetObjectDataFn(oSettings.sAjaxDataProp)(json) : json;

      for (var i = 0; i < aData.length; i++)
      {
          that.oApi._fnAddData(oSettings, aData[i]);
      }

      oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();

      that.fnDraw();

      if (bStandingRedraw === true)
      {
          oSettings._iDisplayStart = iStart;
          that.oApi._fnCalculateEnd(oSettings);
          that.fnDraw(false);
      }

      that.oApi._fnProcessingDisplay(oSettings, false);

      /* Callback user function - for event handlers etc */
      if (typeof fnCallback == 'function' && fnCallback !== null)
      {
          fnCallback(oSettings);
      }
  }, oSettings);
};
