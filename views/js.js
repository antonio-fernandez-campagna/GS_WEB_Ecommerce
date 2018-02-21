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

    // guardo en la variable currencies el array de productos de autocomplete_controller.php haciendo
    // una llamada vía AJAX

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

    // utiliza una libreria para autocompletar las palabras que vayan pasando por el buscador
    $('#autocomplete').autocomplete({
        lookup: currencies,

    });

    $("#hoverCart").hover(function () {
        $('.open-cart').modal({
            show: true
        });
    });

/*
    $("#salirModal").onmouseout(function{
      $('.open-cart').modal({
        show: false
      })
    }); */

    $('.hover-menu-button').hover(function () {
        $('.dropdown-menu').addClass('show');
    }, function () {
        $('.dropdown-menu').removeClass("show");
    });


    $('.img').hover(function () {
        $(this).addClass('escale');
    }, function () {
        $(this).removeClass('escale');
    });

});
