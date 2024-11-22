$(function (){
    $('.table-hide').hide();
    $(document).on('click','.show-table',function (){
        $('.table-hide').hide();
        var table = $(this).data('table');
        $('.'+table).show();
    });
    var id = $('#date').val();
    if(id == 4){
        $('.date_from_to').show();
    }else{
        $('.date_from_to').hide();
    }
    $('#date_from').focus();
    $('#date_to').focus();
    $(document).on('change','#date',function (){
        var id = $(this).val();
        if(id == 4){
            $('.date_from_to').show();
        }else{
            $('.date_from_to').hide();
            $('#form_search').submit();
        }
    });

});