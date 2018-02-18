<?php

require_once("models/login_model.php");
require_once("models/cart_model.php");
require_once("controllers/home_controller.php");

//require_once("controllers/cart_controller.php");



class login_controller {

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

        if ($ok) {
            $_SESSION['usuario'] = $username;

            $cart = new cart_model();
            $id = $cart->checkLastPending();


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

    function register() {

        $user = new login_model();

        $user->setName($_POST['name']);
        $user->setUsername($_POST['username']);
        $user->setEmail($_POST['email']);
        $user->setAddress($_POST['address']);
        $user->setPostalCode($_POST['postalCode']);
        $user->setPassword($_POST['password']);

        $register = $user->insert_user();

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

    function loginFailed() {
        $obj = array();
        $obj['message'] = "Usuario o contraseña incorrecta";
        $obj['openModel'] = "<script type='text/javascript'>
         $(document).ready(function(){
         $('#loginModal').modal('show');
         });
         </script>";
        return $obj;
//var_dump($obj);
//  die;
    }

    function checkCart() {

        $cart = new cart_controller();
        $cartMod = new cart_model();

        if (!empty($_SESSION['cart'])) {
            if ($_SESSION['usuario'] == "invitado") {

                $data = $cart->shoppingCart();
//echo "<pre>" . print_r($data, 1) . "</pre>";

                return $data;
            } else {
                $_SESSION['$id'] = $cart->insertOrder();

                $data = $cart->shoppingCartDB();
//echo "<pre>" . print_r($data, 1) . "</pre>";


                return $data;
            }
        }

        if ($_SESSION['usuario'] != "invitado" && $_SESSION['usuario'] != "admin") {

            $ordID = $cartMod->checkLastPending();

            $sumaTotal = 0;

            if (!empty($ordID['max(id)'])) {

                $cart = new cart_controller();
                $data = $cart->shoppingCartDB();


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
