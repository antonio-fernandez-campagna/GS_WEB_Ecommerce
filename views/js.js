$(document).ready(function () {

    $(".filterProduct").change(function () {
        if ($(this).hasClass("activated")) {
            $(this).removeClass("activated");
        } else {
            $(this).addClass("activated");
        }
        $idBrands = [];
        $(".activated").each(function () {
            $idBrands.push($(this).attr("id"));
        });

        $(".card").each(function () {
            $prodPrice = parseInt($(this).find(".card-text").text());
            $priceMin = $("#price-min").val();
            $priceMax = $("#price-max").val();


            $('.output').val($priceMin +" €" );




            console.log("Precio: " + $prodPrice + " PrecioMin: " + $priceMin + " PrecioMax: " + $priceMax);
            if (($prodPrice < $priceMin || $prodPrice > $priceMax)) {
                //alert($prodPrice);
                $(this).parent().hide();
            } else {
                if ($idBrands.length && jQuery.inArray($(this).attr("id"), $idBrands) === -1) {
                    alert($prodPrice);
                    $(this).parent().hide();
                } else {
                    $(this).parent().show();
                }
            }
        });

    });

    $(".buyProduct").click(function(){
        $idProduct = $(this).attr("id");

    });
});
