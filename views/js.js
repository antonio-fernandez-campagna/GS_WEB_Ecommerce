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

  /*  $('.hover-menu-button').hover(function () {
        $('.dropdown-menu').addClass('show');
    }, function () {
        $('.dropdown-menu').removeClass("show");
    });
 */

    $('.img').hover(function () {
        $(this).addClass('escale');
    }, function () {
        $(this).removeClass('escale');
    });

});


function enableReg(item){

    // comprobar que la validación de las contraseñas es correcta
    // y no tenga espacios
    var cpError = true;
    var passwdError = true;
    var passwdError2 = true;
    var nameUserError = true;
    var userError = true;
    var emailError = true;
    var streetError = true;

    if (item.id == "nameUser") {
      console.log("nameuser");
        var nameU = document.getElementById("nameUser").value;

          if(!document.getElementById("alert-name")){

            if (nameU == "") {
              var alert = document.createElement('div');
              var text = document.createTextNode("El nombre de usuario no puede estar vacío");
              alert.appendChild(text);
              alert.className = 'alert alert-danger mt-4';
              alert.id = "alert-name";
              document.getElementById("appendName").appendChild(alert);
              return true;
            }

        } else {

          if (nameU != ""){
            var alert = document.getElementById("alert-name");
            alert.parentNode.removeChild(alert);
            nameUserError = false;
          } else {
            nameUserError = true;
          }
        }
    }

    if (item.id == "user") {
        var user = document.getElementById("user").value;

          if(!document.getElementById("alert-user")){

            if (user == "") {
              var alert = document.createElement('div');
              var text = document.createTextNode("El nombre de usuario no puede estar vacío");
              alert.appendChild(text);
              alert.className = 'alert alert-danger mt-4';
              alert.id = "alert-user";
              document.getElementById("appendUser").appendChild(alert);
              return true;
            }

        } else {

          if (user != ""){
            var alert = document.getElementById("alert-user");
            alert.parentNode.removeChild(alert);
            nameUserError = false;
          } else {
            nameUserError = true;
          }
        }
    }


    if (item.id == "email") {
        var email = document.getElementById("email").value;

        var emailValidation = /^\w+([\.-]?\w+)@\w+([\.-]?\w+)(\.\w{2,3})+$/;

        if (!document.getElementById("alert-email")) {
          if (!email.match(emailValidation)) {
            var alert = document.createElement('div');
            var text = document.createTextNode("Tiene que tener formato de email");
            alert.appendChild(text);
            alert.className = 'alert alert-danger mt-4';
            alert.id = "alert-email";
            document.getElementById("appendEmail").appendChild(alert);
          }
        } if (email.match(emailValidation)) {
          var alert = document.getElementById("alert-email");
          alert.parentNode.removeChild(alert);
          emailError = false;
        } else {
          emailError = true;
        }
    }

    if (item.id == "street") {
        var street = document.getElementById("street").value;

        console.log("pene");
          if(!document.getElementById("alert-street")){

            if (street == "") {
              var alert = document.createElement('div');
              var text = document.createTextNode("La dirección no puede estar vacía");
              alert.appendChild(text);
              alert.className = 'alert alert-danger mt-4';
              alert.id = "alert-street";
              document.getElementById("appendStreet").appendChild(alert);
              return true;
            }
        } else {
          if (street != ""){
            var alert = document.getElementById("alert-street");
            alert.parentNode.removeChild(alert);
            streetError = false;
          } else {
            streetError = true;
          }
        }
    }



    // comprobar que el codigo postal tenga 5 numeros
    if(item.id == "cp"){
      var cp = document.getElementById("cp").value;

        if(!document.getElementById("alert-cp")){

          if (cp.toString().length != 5) {
            var alert = document.createElement('div');
            var text = document.createTextNode("el código postal debe tener 5 números");
            alert.appendChild(text);
            alert.className = 'alert alert-danger mt-4';
            alert.id = "alert-cp";
            document.getElementById("appendCP").appendChild(alert);
            return true;
          }

      } else {

        if (cp.toString().length == 5){
          var alert = document.getElementById("alert-cp");
          alert.parentNode.removeChild(alert);
          cpError = false;
        } else {
          cpError = true;
        }
      }
    }

    if (item.id == "passwd1") {
        var passwd1 = document.getElementById("passwd1").value;

        var passwdValidation = /^(?=.\d)(?=.[a-z])(?=.*[A-Z]).{8,20}$/;

        console.log("maricon");

        if (passwd1.match(passwdValidation)) {
          console.log("passwd1");
        }

        if (!document.getElementById("alert-passwd")) {
          if (!passwd1.match(passwdValidation)) {
            var alert = document.createElement('div');
            var text = document.createTextNode("La contraseña debe contener mayúsculas, minúsculas, números y carácteres especiales");
            alert.appendChild(text);
            alert.className = 'alert alert-danger mt-4';
            alert.id = "alert-passwd";
            document.getElementById("appendPasswd").appendChild(alert);
          }
        } if (passwd1.match(passwdValidation)) {
          console.log("vlida");
          var alert = document.getElementById("alert-passwd");
          alert.parentNode.removeChild(alert);
          passwdError = false;
        } else {
          passwdError = true;
        }
    }

    if (item.id == "passwd2") {

      var passwd1 = document.getElementById("passwd1").value;
      var passwd2 = document.getElementById("passwd2").value;

      var check = passwd1.localeCompare(passwd2);

        if(!document.getElementById("alert-passwd2")){
          if (check != 0) {
            var alert = document.createElement('div');
            var text = document.createTextNode("Las contraseñas no son iguales");
            alert.appendChild(text);
            alert.className = 'alert alert-danger mt-4';
            alert.id = "alert-passwd2";
            document.getElementById("appendPasswd2").appendChild(alert);
          }
        } else {
          if (check == 0) {
            var alert = document.getElementById("alert-passwd2");
            alert.parentNode.removeChild(alert);
            passwdError2 = false;
          } else {
            passwdError2 = true;
          }
        }
    }

    if(!(cpError && passwdError && passwdError2 && nameUserError && userError && emailError && streetError)){
      registr.disabled = false;
    }


  }
