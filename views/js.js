$(document).ready(function () {

    $(".filterProduct").change(function () {
        if ($(this).hasClass("activated")) {
            $(this).removeClass("activated");
        } else {
            if ($(this).hasClass("range-price") === false) {
                $(this).addClass("activated");
            }

        }
        $idBrands = [];
        console.log("lenght" + $idBrands.length);

        $(".activated").each(function () {
            $idBrands.push($(this).attr("id"));
        });

        $(".card").each(function () {
            $prodPrice = parseInt($(this).find(".card-text").text());
            $priceMin = $("#price-min").val();
            $priceMax = $("#price-max").val();


            $('.price-min-output').text($priceMin + " €");
            $('.price-max-output').text($priceMax + " €");


            //console.log("Precio: " + $prodPrice + " PrecioMin: " + $priceMin + " PrecioMax: " + $priceMax);
            //alert($prodPrice);
            //console.log("lenght 2" + $idBrands.length);

            if (($prodPrice < $priceMin || $prodPrice > $priceMax)) {
                $(this).parent().hide();
            } else {
                //console.log("Precio: " + $idBrands[0] + " PrecioMin: " + $priceMin + " PrecioMax: " + $priceMax);

                if ($idBrands.length && jQuery.inArray($(this).attr("id"), $idBrands) === -1) {
                    //alert($prodPrice);

                    $(this).parent().hide();
                } else {
                    $(this).parent().show();
                }
            }
        });

    });
    
    var currencies = function () {
        var products = null;
        $.ajax({
            'async': false,
            'type': "GET",
            'global': false,
            'url': "controllers/autocomplete_controller.php",
            'success': function (response) {
                products = $.parseJSON(response);
            }
        });
        return products;
    }();

    $('#autocomplete').autocomplete({
        lookup: currencies,
        onSelect: function (suggestion) {
            var thehtml = '<strong>Currency Name:</strong> ' + suggestion.value + ' <br> <strong>Symbol:</strong> ' + suggestion.data;
            $('#outputcontent').html(thehtml);
        }
    });
});
