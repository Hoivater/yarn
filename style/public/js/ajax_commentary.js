$(document).on("click", ".view_answ", function(event){
    event.preventDefault();
    var idElement = event.target.id;
    var id_html = "#two_"+idElement;
    var id_button = "#" + idElement;
    $.ajax({
        url: '/app/modules/aj/Jon.php',
        method: 'POST',
        data: {"id" : idElement, "nameAj" : "loadCommentary"},
        }).done(function(data){
            $(id_html).html(data);
            $(id_button).html("Скрыть ответы");
            $(id_button).attr('class', 'hide_answ btn btn-secondary');
        });
});
$(document).on("click", ".hide_answ", function(event){
    event.preventDefault();
    var idElement = event.target.id;
    var id_html = "#two_"+idElement;
    var id_button = "#" + idElement;
    $(id_html).html("");
    $(id_button).html("Показать ответы");
    $(id_button).attr('class', 'view_answ btn btn-success');

});
