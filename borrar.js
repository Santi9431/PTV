      
for ($i = 0; $i < CantidadArray.length; $i++) {
    var $row = $("#integrantes").find('[data-book-index="' + CantidadArray[$i] + '"]');
    var $row1 = $(this).parents(".form-group");
    var i = $row.attr("data-book-index");
    $row.remove();
}
