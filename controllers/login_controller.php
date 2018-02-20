<?php

require_once("models/login_model.php");
require_once("models/cart_model.php");
require_once("controllers/home_controller.php");

//require_once("controllers/cart_controller.php");


// clase que controla el Login, registro y comprueba el estado del cart
class login_controller {

    // Función para logearse
    function login() {
        $usuario = new login_model();

        $conexion = $usuario->db;
        $user = mysqli_real_escape_string($conexion, $_POST['username']);
        $contrasenya = mysqli_real_escape_string($conexion, $_POST['username']);


        $username = !empty($user) ? $contrasenya : "";
        $password = !empty($user) ? $contrasenya : "";

        $usuario->setUsername($username);
        $usuario->setPassword($password);

        $ok = $usuario->verifyUser();

         // si se ha logeado correctamente muestra la página principal y devuelve true, sino false y mostrará un intento 
        // de inicio de sesión fallido
        if ($ok) {
            $_SESSION['usuario'] = $username;

            $cart = new cart_model();
            $id = $cart->checkLastPending();

            // si hay algo en session cart y hay un order pendiente pone el orden estado rejected
            if (!empty($_SESSION['cart']) && $id['max(id)'] != null) {
                $cart->reject_order($id);
            }

            $home = new home_controller();
            $home->view();

            return true;
        } else {
            return false;
        }
    }

    // Función para registrarse
    function register() {

        $user = new login_model();

        $user->setName($_POST['name']);
        $user->setUsername($_POST['username']);
        $user->setEmail($_POST['email']);
        $user->setAddress($_POST['address']);
        $user->setPostalCode($_POST['postalCode']);
        $user->setPassword($_POST['password']);

        $register = $user->insert_user();

        // si al registrarse el usuario ya existe no dejará registrarse
        if ($register === false) {

            $obj = array();
            $obj['message'] = "El nombre de usuario ya existe";
            $obj['openModel'] = "<script type='text/javascript'>
         $(document).ready(function(){
         $('#loginModal').modal('show');
         });
         </script>";
            return $obj;
        }
    }

    // Función que abre la pantalla de login y muestra un error
    function loginFailed() {
        $obj = array();
        $obj['message'] = "Usuario o contraseña incorrecta";
        $obj['openModel'] = "<script type='text/javascript'>
         $(document).ready(function(){
         $('#loginModal').modal('show');
         });
         </script>";
        return $obj;
    }

    // Función para comprobar el estado del cart
    function checkCart() {

        $cart = new cart_controller();
        $cartMod = new cart_model();

        // si hay algo en session cart
        if (!empty($_SESSION['cart'])) {
            // y si el usuario no es invitado (es un usuario ya logeado)
            // mostrará el cart desde session, si no, mostrará el cart desde la BD
            if ($_SESSION['usuario'] == "invitado") {

                $data = $cart->shoppingCart();

                return $data;
            } else {
                $_SESSION['$id'] = $cart->insertOrder();

                $data = $cart->shoppingCartDB();


                return $data;
            }
        }
        
        // si el usuario esta logeado y hay un order pendiente mostrará el cart desde BD
        if ($_SESSION['usuario'] != "invitado" && $_SESSION['usuario'] != "admin") {

            $ordID = $cartMod->checkLastPending();

            $sumaTotal = 0;

            // se comprueba que hay un order pendiente
            if (!empty($ordID['max(id)'])) {

                $cart = new cart_controller();
                $data = $cart->shoppingCartDB();

                // se guarda el valor de precio en el array por si tiene una promoción aplicada
                foreach ($data as $key => $producto) {

                    if ((!empty($producto['FINALPRICE']))) {

                        $precio = $producto['FINALPRICE'];
                    } else {
                        $precio = $producto['PRICE'];
                    }

                    $sumaTotal += $precio * $producto['nUnits'];

                    $data[$key]["TOTAL"] = $sumaTotal;
                }

                return $data;
            }
        }
    }

}

?>
