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


            $('.output').val($priceMin + " €");

            //console.log("Precio: " + $prodPrice + " PrecioMin: " + $priceMin + " PrecioMax: " + $priceMax);
            //alert($prodPrice);
            console.log("lenght 2" + $idBrands.length);

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

    $(".buyProduct").click(function () {
        $idProduct = $(this).attr("id");

    });
});
