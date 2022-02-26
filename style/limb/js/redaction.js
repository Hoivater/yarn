/*РЕДАКТОР ЗАМЕТОК*/


function insertTextZ( id, text, text2 ) {
  //ищем элемент по id
  var txtarea = document.getElementById(id);
  //ищем первое положение выделенного символа
  var start = txtarea.selectionStart;

  //ищем последнее положение выделенного символа
  var end = txtarea.selectionEnd;
  // текст до + вставка + текст после (если этот код не работает, значит у вас несколько id)
  var finText = txtarea.value.substring(0, start) + text + txtarea.value.substring(start, end) + text2 + txtarea.value.substring(end);
  //var finText = txtarea.value.substring(0, start) + text + txtarea.value.substring(end);
  // подмена значения
  txtarea.value = finText;
  // возвращаем фокус на элемент
  txtarea.focus();
  // возвращаем курсор на место - учитываем выделили ли текст или просто курсор поставили
  //txtarea.selectionEnd = ( start == end )? (end + text.length) : end ;
}


$(document).on('click', '.comInt', function (event) {
  insertTextZ('comLine', " = 'int()';", "");
}); 


$(document).on('click', '.comFloat', function (event) {
  insertTextZ('comLine', " = 'float()';", "");
}); 

$(document).on('click', '.comDouble', function (event) {
  insertTextZ('comLine', " = 'double()';", "");
}); 

$(document).on('click', '.comBoolean', function (event) {
  insertTextZ('comLine', " = 'boolean';", "");
}); 

$(document).on('click', '.comChar', function (event) {
  insertTextZ('comLine', " = 'char';", "");
}); 

$(document).on('click', '.comVarchar', function (event) {
  insertTextZ('comLine', " = 'varchar()';", "");
}); 


$(document).on('click', '.comText', function (event) {
  insertTextZ('comLine', " = 'text';", "");
}); 



