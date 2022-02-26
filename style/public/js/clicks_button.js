$(document).on("click", ".click_answ", function(event){
    event.preventDefault();
    var idElement = event.target.id;
    var idname = "#name_" + idElement;
    var name = $(idname).val();
    $("#comment_text").val(name + ", ");
    $("#comment_level").val(idElement);

    jQuery("html:not(:animated),body:not(:animated)").animate({
      scrollTop: $(".addcom").offset().top
    }, 200);
});