<?php

require_once("db/db.php");

require_once("controllers/categories_controller.php");
require_once("controllers/products_controller.php");
require_once("controllers/login_controller.php");
require_once("controllers/home_controller.php");
require_once("controllers/products_controller.php");
require_once("controllers/cart_controller.php");
require_once("controllers/view_controller.php");
require_once("controllers/promotion_controller.php");

//$ej = new cart_model();
//$ej->setPaymentInfo(2);
//$ej->setStatus(1);
//$ej->setShippingAddress("asdad");
//$ej->setUser("user");
//$id = $ej->insertOrder();

ob_start();

session_start();

if (empty($_SESSION['usuario'])) {
    $_SESSION['usuario'] = "invitado";
}

//echo "<pre>" .print_r($_SESSION['usuario'],1). "</pre>";die;

$homeController = new home_controller();
$cart = new cart_controller();
$category = new categories_controller();

$data['cart'] = $cart->shoppingCart();
$data['categories'] = $category->getCategories();
//$homeController->view();
if (isset($_GET['controller']) && isset($_GET['action'])) {

    if ($_GET["controller"] == "log") {
        $controller = new home_controller();
        $loginFailed = "";

        if ($_GET["action"] == "login") {
            $login = new login_controller();
            $loged = $login->login();

            if (!$loged) {

                $loginFailed = $login->loginFailed();

            } else {
              if (!empty($data['cart'])) {

                $cataToDb = new cart_controller();
                $cataToDb -> insertOrder();

              } else {

              }
            }
        }
        if ($_GET['action'] == "logout") {
            $_SESSION['usuario'] = "invitado";
        }

        if ($_GET['action'] == "register") {
            $register = new login_controller();
            //$register ->
        }
        //require_once "views/templates/header_template.phtml";
        header('location: index.php');
    }

    //Mostramos el default header, el cart y las categorias



    if ($_SESSION['usuario'] != "admin") {

        if ($_GET['controller'] == "cart") {

            if ($_GET['action'] == "addToCart") {
                $id = $_POST["id"];
                $cart->addItemToCart($id);
                // muestro la pantalla principal para que cargue por primera vez el carrito (modal)
                header('location: index.php');
            }

            if ($_GET['action'] == "deleteFromCart") {
                $id = $_GET['id'];
                //echo "<pre>" .print_r($id,1). "</pre>";
                //die();
                $cart->deleteItemFromCart($id);
            }

             if ($_GET['action'] == "cartToDB") {

                 // insert code aitor
            }
        }

        $homeController->view();
    }


    // mostrar productos por categortias
    if ($_GET['controller'] == "products") {

        if ($_GET['action'] == "view") {
            ob_end_clean();
            $controller = new products_controller();
            $id = $_GET['subCategory'];
            $controller->view($id);
        }

        if ($_GET['action'] == "profileProduct"){
          $id = $_GET['id'];
          $controller = new products_controller();
          $controller->profileProduct($id);
        }

        if ($_GET['action'] == "search"){
          ob_end_clean();
          $word = $_POST['buscador'];
          $controller = new products_controller();
          $controller->searchProduct($word);
          //$controller->view();
        }

        if ($_GET['action'] == "add") {
          $controller = new products_controller();
          $controller->productAdd_view();
        }

        if ($_GET['action'] == "insert") {
          $controller = new products_controller();
          $controller->insertProduct();
          require_once "views/templates/header_template.phtml";
          $homeController->view();

        }


    }

    if ($_GET['controller'] == "categories") {

        if ($_GET['action'] == "insert") {
        $category = $_POST['parentcategory'];
        $controller = new categories_controller();
        $controller->insert_category();

    }
        if ($_GET['action'] == "add") {
          $controller = new categories_controller();
          $controller->categoryAdd_view();
        }


      }

      if ($_GET['controller'] == "promotions") {

          if ($_GET['action'] == "add") {
          //$category = $_POST['parentcategory'];
          $controller = new promotion_controller();
          $controller->promotionAdd_view();
        }

          if ($_GET['action'] == "insert") {
          $controller = new promotion_controller();
          $controller->insert_promotion();
          require_once "views/templates/header_template.phtml";
          $homeController->view();
        }

      }

} else {

    //Mostramos el default header
    $category = new categories_controller();
    $cart = new cart_controller();
    $data['cart'] = $cart->shoppingCart();
    $data['categories'] = $category->getCategories();
    require_once "views/templates/header_template.phtml";


    $homeController = new home_controller();
    $homeController->view();
}
?>
