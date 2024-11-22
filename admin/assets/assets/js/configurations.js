

$(function() {
    $("#default_currency").on("change", function(){
        var id = $(this).val();
        $.ajax({
			type 	: "POST",
			url  	: base_url+"update_default_currency",
			data    : {"default_currency_id": id},
			success : function(data){
				data = JSON.parse(data);
                console.log(data);
            }
        });
    });

    $("#update_rates").on("click", function(){
        var usdtogbp = $("#usdtogbp").val();
        var gbptousd = $("#gbptousd").val();

        $.ajax({
			type 	: "POST",
			url  	: base_url+"update_conversion_rates",
			data    : {"usdtogbp": usdtogbp, "gbptousd": gbptousd},
			success : function(data){
				data = JSON.parse(data);
                console.log(data);
            }
        });


        console.log(usdtogbp);
        console.log(gbptousd);

    });
});