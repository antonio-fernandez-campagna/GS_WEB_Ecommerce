<?php

require_once("models/login_model.php");
require_once("models/cart_model.php");
require_once("controllers/home_controller.php");


class login_controller {

    function login() {
        $usuario = new login_model();

        $username = !empty($_POST['username']) ? $_POST['username'] : "";
        $password = !empty($_POST['password']) ? $_POST['password'] : "";

        $usuario->setUsuario($username);
        $usuario->setPassword($password);

        $ok = $usuario->verifyUser();

        if ($ok) {
            $_SESSION['usuario'] = $username;

            $cart = new cart_model();
            $id = $cart->checkLastPending();

            // var_dump($id['max(id)']);die;

            if (!empty($_SESSION['cart']) && $id['max(id)'] != null) {
                $cart->reject_order($id);
            }

            $home = new home_controller();
            $home -> view();
            
            return true;
        } else {
            return false;
        }
    }

    function register() {

        $user = new login_model();

        $user->setName($_POST['name']);
        $user->setUserName($_POST['username']);
        $user->setEmail($_POST['email']);
        $user->setAddress($_POST['address']);
        $user->setPostalCode($_POST['postalCode']);
        $user->setPassword($_POST['password']);

        $user->insert_user();
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
        $cartDb = new cart_controller();

        if (!empty($_SESSION['cart'])) {
            if ($_SESSION['usuario'] == "invitado") {

                $data = $cart->shoppingCart();
                //echo "<pre>" . print_r($data, 1) . "</pre>";

                return $data;
            } else {
                $_SESSION['$id'] = $cartDb->insertOrder();

                $data = $cartDb->shoppingCartDB();
                //echo "<pre>" . print_r($data, 1) . "</pre>";


                return $data;
            }
        }

        if ($_SESSION['usuario'] != "invitado" && $_SESSION['usuario'] != "admin") {
            $cart = new cart_controller();
            $data = $cart->shoppingCartDB();
            //echo "<pre>" . print_r($data, 1) . "</pre>";

            return $data;
        }
    }

}

?>
