
//Универсальное принятие мини формы
$( "body" ).on( "click", ".sbm_form", function() {
    $(this).closest(".short_form").submit();
});

//Универсальное принятие мини формы со скрытым полем типа нажатия кнопки
$( "body" ).on( "click", ".sbm_with_type", function() {
    $(this).closest(".short_form").find("input[name='sbm_type']").val($(this).data("type"));
    //console.log( $(this).closest(".short_form").find("input[name='sbm_type']").val());
    $(this).closest(".short_form").submit();
});

//BS tooltip
$('[data-toggle="tooltip"]').tooltip(); 

//bootstrap'овский поповер
$('[data-toggle="popover"]').popover(); 

//поповер для выполнения задачи
$('[data-toggle="popover_task"]').popover({
        html : true, 
        content: function() {
          return $(this).children(".task-popover-hidden").html();
        }
    }); 

//Показать или скрыть кнопку начала в зависимости от показанного попапа
$("[data-toggle='popover_task']").on('show.bs.popover', function(){
    $(this).find('.stast-box').show();
});
//Если раскомментировать это, то кнопка пропадает раньше, чем форма сабмититься
//$("[data-toggle='popover_task']").on('hide.bs.popover', function(){
//    $(this).find('.stast-box').hide();
//});
