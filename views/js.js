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

    $('[data-toggle="popover"]').popover();

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

function verify(item){


    if (item.id == "nameUser") {
      var nameUserError = nameUser();
    }

    if (item.id == "user") {
      var userError = user();
    }

    if (item.id == "email") {
      var emailError = email();
    }

    if (item.id == "street") {
      var streetError = street();
    }

    if(item.id == "cp"){
      var cpError = cp();
    }

    if (item.id == "passwd1") {
      var passwdError = passwd1();
    }

    if (item.id == "passwd2") {
      var passwdError2 = passwd2();
    }

    console.log("cpError:  " + cpError + "  passEr1:  " +  passwdError + "  pass2:  " + passwdError2 + "  nameuser:  "  + nameUserError  + "  userError:  " +  userError + "  emailErr:  " + emailError + "  street: " + streetError);



}

function verifar(){

  var alertName = document.getElementById("alert-name");
  var alertUser = document.getElementById("alert-user");
  var alertEmail = document.getElementById("alert-email");
  var alertStreet = document.getElementById("alert-street");
  var alertCp = document.getElementById("alert-cp");
  var alertPasswd = document.getElementById("alert-passwd2");
  var alertPasswd2 = document.getElementById("alert-passwd2");

  if (alertName || alertUser || alertEmail || alertStreet || alertCp || alertPasswd || alertPasswd2) {
    alert("Hay errorres en el formulario de registro");
  } else {
  //  window.location.replace("localhost/final/");
  }

}


    function nameUser(){

        var nameU = document.getElementById("nameUser").value;

          if(!document.getElementById("alert-name")){

            if (nameU == "") {
              var alert = document.createElement('div');
              var text = document.createTextNode("El nombre de usuario no puede estar vacío");
              alert.appendChild(text);
              alert.className = 'alert alert-danger mt-4';
              alert.id = "alert-name";
              document.getElementById("appendName").appendChild(alert);
            } else {
              if (nameU != ""){
                var alert = document.getElementById("alert-name");
                if (alert) {
                  alert.parentNode.removeChild(alert);
                }
                return  false;
              } else {
                return  true;
              }
            }

        } else {

          if (nameU != ""){
            console.log("3");
            var alert = document.getElementById("alert-name");
            if (alert) {
              alert.parentNode.removeChild(alert);
            }
            return  false;
          } else {
            return  true;
          }
        }
  }


  function user(){
        var user = document.getElementById("user").value;

          if(!document.getElementById("alert-user")){

            if (user == "") {
              var alert = document.createElement('div');
              var text = document.createTextNode("El nombre de usuario no puede estar vacío");
              alert.appendChild(text);
              alert.className = 'alert alert-danger mt-4';
              alert.id = "alert-user";
              document.getElementById("appendUser").appendChild(alert);
            } else {
              if (user != ""){
                var alert = document.getElementById("alert-user");
                if (alert) {
                alert.parentNode.removeChild(alert);
              }
                 return false;
                console.log("false user");
              } else {
                return true;
              }
            }

        } else {

          if (user != ""){
            var alert = document.getElementById("alert-user");
            if (alert) {
              alert.parentNode.removeChild(alert);
            }
            return false;
            console.log("false user");
          } else {
            return true;
          }
        }
}

  function email(){
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
          } else {
            if (email.match(emailValidation)) {
              var alert = document.getElementById("alert-email");
              if (alert) {
              alert.parentNode.removeChild(alert);
              }
              return false;
              console.log("false email");
            } else {
              return true;
            }
          }
        } if (email.match(emailValidation)) {
          var alert = document.getElementById("alert-email");
          if (alert) {
          alert.parentNode.removeChild(alert);
          }
          return false;
          console.log("false email");
        } else {
          return true;
        }

}

function street(){
        var street = document.getElementById("street").value;

          if(!document.getElementById("alert-street")){

            if (street == "") {
              var alert = document.createElement('div');
              var text = document.createTextNode("La dirección no puede estar vacía");
              alert.appendChild(text);
              alert.className = 'alert alert-danger mt-4';
              alert.id = "alert-street";
              document.getElementById("appendStreet").appendChild(alert);
            } else {
              if (street != ""){
                var alert = document.getElementById("alert-street");
                if (alert) {
                alert.parentNode.removeChild(alert);
              }
                return false;
                console.log("false street");
              } else {
                return true;
              }
            }
        } else {
          if (street != ""){
            var alert = document.getElementById("alert-street");
            if (alert) {
              alert.parentNode.removeChild(alert);
            }
            return false;
            console.log("false street");
          } else {
            return true;
          }
        }
    }



    // comprobar que el codigo postal tenga 5 numeros
    function cp (){
      var cp = document.getElementById("cp").value;

        if(!document.getElementById("alert-cp")){

          if (cp.toString().length != 5) {
            var alert = document.createElement('div');
            var text = document.createTextNode("el código postal debe tener 5 números");
            alert.appendChild(text);
            alert.className = 'alert alert-danger mt-4';
            alert.id = "alert-cp";
            document.getElementById("appendCP").appendChild(alert);
          } else {
            if (cp.toString().length == 5){
              var alert = document.getElementById("alert-cp");
              if (alert) {
              alert.parentNode.removeChild(alert);
              }
              return false;
              console.log("false cp");
            } else {
               return true;
            }
          }

      } else {

        if (cp.toString().length == 5){
          var alert = document.getElementById("alert-cp");
          alert.parentNode.removeChild(alert);
          return false;
          console.log("false cp");
        } else {
          return true;
        }
      }

  }


function passwd1(){
        var passwd1 = document.getElementById("passwd1").value;

        var passwdValidation =  /^(?=.*[\d])(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*])[\w!@#$%^&*]{8,}$/;

        console.log(passwd1);

        if (!document.getElementById("alert-passwd")) {
          if (!passwd1.match(passwdValidation)) {
            alert = document.createElement('div');
            text = document.createTextNode("La contraseña debe contener mayúsculas, minúsculas, números y carácteres especiales");
            alert.appendChild(text);
            alert.className = 'alert alert-danger mt-4';
            alert.id = "alert-passwd";
            document.getElementById("appendPasswd").appendChild(alert);
          } else {
            if (passwd1.match(passwdValidation)) {
              var alert = document.getElementById("alert-passwd");
              if (alert) {
              alert.parentNode.removeChild(alert);
              }
              return false;
            } else {
              return true;
            }
          }
        } if (passwd1.match(passwdValidation)) {
          var alert = document.getElementById("alert-passwd");
          if (alert) {
          alert.parentNode.removeChild(alert);
        }
          return false;
        } else {
          return true;
        }
    }


function passwd2(){

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
          } else {
            if (check == 0) {
              var alert = document.getElementById("alert-passwd2");
              if (alert) {
              alert.parentNode.removeChild(alert);
              }
              return false;
            } else {
              return true;
            }
          }
        } else {
          if (check == 0) {
            var alert = document.getElementById("alert-passwd2");
            if (alert) {
            alert.parentNode.removeChild(alert);
          }
            return false;
          } else {
            return true;
          }
        }
    }
