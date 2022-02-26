
$(document).on('click', '.copy', function (event) {
  event.preventDefault();
  var idElement = event.target.id;
  var value = "textarea#t_" + idElement;
  
  var value_link = $(value).val();
  copytextInput(value);
  $('#'+idElement).html('Скопировано');
});

function copytextInput(el) {
  var $tmp = $("<textarea>");
  $("body").append($tmp);
  $tmp.val($(el).val()).select();
  document.execCommand("copy");
  $tmp.remove();
} // КОММЕНТАРИИ