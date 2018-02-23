$(document).ready(function () {

// función para poner "activated" de clase a .filterProduct para posteriormente filtrar
    $(".filterProduct").change(function () {
      // si la clase filterProduct tiene la clase "activated" se la quita, sino, se la pone
        if ($(this).hasClass("activated")) {
            $(this).removeClass("activated");
        } else {
            if ($(this).hasClass("range-price") === false) {
                $(this).addClass("activated");
            }

        }

        $idBrands = [];

        // los que tienen la clase "activated" los pondrá en un array
        $(".activated").each(function () {

            $idBrands.push($(this).attr("id"));
        });

        // función para filtrar y recoger los valores de los precios
        $(".card").each(function () {
          // guardamos los precios en variables
            $prodPrice = parseInt($(this).find(".card-text").text());
            $priceMin = $("#price-min").val();
            $priceMax = $("#price-max").val();

            // mostramos el valor del slider
            $('.price-min-output').text($priceMin + " €");
            $('.price-max-output').text($priceMax + " €");

            // si el producto no está en el rango de precio lo esconde
            if (($prodPrice < $priceMin || $prodPrice > $priceMax)) {
                $(this).parent().hide();
            } else {
              // si hay algo en el array de marcas y el id del card no está en el array lo esconde, sino, lo muestra
                if ($idBrands.length && jQuery.inArray($(this).attr("id"), $idBrands) === -1) {
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

    // función para al hacer hover sobre el carrito abra el modal del carrito
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

 // función para que al pasar el ratón por encima de la imagen la agrande
 // le añade la clase si el ratón está encima, sino se la quita,
 // la clase escale hace una transicion para aumentar el tamaño de la imagen
    $('.img').hover(function () {
        $(this).addClass('escale');
    }, function () {
        $(this).removeClass('escale');
    });

});


var img1Src = "imgs/img1.jpg";
var img2Src = "imgs/img2.jpg";
var img3Src = "imgs/img3.jpg";

function changeImage(src){
  document.getElementById("myImage").src = src;
}

var clicks = 0;

function badgeNumber(){
  var number = document.getElementById("badgeN");
  clicks ++;

  number.innerHTML = clicks;
}



// función para verificar los errores de los inputs
// cada función se ejecuta al hacer FOCUSOVER y el id del input llama a su respectiva función para que fitre
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

// función para verificar los campos
function verificar(){

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
    window.location.replace("http://localhost/FINAL-proyecto/FINAL/");
  }

}


    function nameUser(){

        var nameU = document.getElementById("nameUser").value;

        // si no existe la alerta mira si el nombre está vacío crea la alerta, sino, mira si el nombre no está vacia
        // y quitaría la alerta en caso de que el campo no esté vacio
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

// función para comprobar que el campo username no está vacio
  function user(){
        var user = document.getElementById("user").value;

        // si no existe la alerta mira si el user está vacío crea la alerta, sino, mira si el user no está vacia
        // y quitaría la alerta en caso de que el campo no esté vacio
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
          } else {
            return true;
          }
        }
}

// función para verficar que el campo email tiene formato de email
  function email(){
        var email = document.getElementById("email").value;

        var emailValidation = /^\w+([\.-]?\w+)@\w+([\.-]?\w+)(\.\w{2,3})+$/;

        // si no existe la alerta mira si el email está vacío crea la alerta, sino, mira si el email tiene formato de email
        // y quitaría la alerta en caso de que el campo tenga formato de email y se haya creado la alerta
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

        // si no existe la alerta mira si la dirección está vacío crea la alerta, sino, mira si la dirección no está vacia
        // y quitaría la alerta en caso de que el campo no esté vacio
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

      // si no existe la alerta mira si el codigo postal no tiene 5 números crea la alerta, sino, mira si el codigo postal tiene 5 numeros
      // y quitaría la alerta en caso de que el código postal tenga 5 números

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

// función para comproar que la contraseña tenga al menos 8 caracteres, de los cuales 1 caracter especial,
// 1 caracter númerico, 1 mnúscula y 1 mayúscula
function passwd1(){
        var passwd1 = document.getElementById("passwd1").value;

        var passwdValidation =  /^(?=.*[\d])(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*])[\w!@#$%^&*]{8,}$/;

        // se mira si la alerta está creada, si no está creada comprueba si la contraseña tiene formato de la contraseña requerida
        // si no tiene el formatio requerido crea la alerta, si cumple la condición en caso de que se hibieses creado la alerta la quita,
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

// fución para comprobar que las 2 contraseñas introducidas son iguales
function passwd2(){

      var passwd1 = document.getElementById("passwd1").value;
      var passwd2 = document.getElementById("passwd2").value;

      var check = passwd1.localeCompare(passwd2);

      // se comprueba que no se haya creado la alerta, si la contraseña no coincide crea la alert.
      //  En caso de que conincida pero se haya creado la alerta anteriormente la quita.
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
