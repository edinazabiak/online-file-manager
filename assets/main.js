$(document).ready(function () {
    $(".btn-delete").click(function () {
        $(".modal").fadeIn(500);
        $('main').addClass('modalBlur');

        //$id = $(this).closest('tr').prop("id");

        //$('#delete_id').val($id);
    });

    $(".btn-back").click(function () {
        $(".modal").fadeOut(500);
        $('main').removeClass('modalBlur');
    });

    // Custom select
    $(".selected-mode p").click(function() {
        $(".select-options").toggle(300);
    });

    $("#1").click(function() {
        $('.selected-mode p').text("Fájlok neve szerint");
        $("#sort_id").attr("value", 1);
        $(".select-options").hide(300);
    });

    $("#2").click(function() {
        $('.selected-mode p').text("Utolsó módosítás szerint");
        $("#sort_id").attr("value", 2);
        $(".select-options").hide(300);
    });

    // $(document).on('click', '.selected_files', function() {
    //     var selected_files_id = [];

    //     $('.selected_files:checked').each(function() {
    //         $selected_files_id = $(this).val();
    //     });

    //     console.log($selected_files_id);

    //     $.ajax({
    //         method: "POST", 
    //         data: {selected_files_id: selected_files_id}, 
    //         success: function(data){
    //             console.log(data);
    //         }
    //     })
    // });

});