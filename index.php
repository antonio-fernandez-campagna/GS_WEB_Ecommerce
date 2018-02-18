<?php

require_once("db/db.php");

require_once("controllers/categories_controller.php");
require_once("controllers/products_controller.php");
require_once("controllers/login_controller.php");
require_once("controllers/home_controller.php");
require_once("controllers/cart_controller.php");
require_once("controllers/view_controller.php");
require_once("controllers/promotion_controller.php");

ob_start();

session_start();

if (empty($_SESSION['usuario'])) {
    $_SESSION['usuario'] = "invitado";
}


$homeController = new home_controller();
$category = new categories_controller();

$data['categories'] = $category->getCategories();

if (isset($_GET['controller']) && isset($_GET['action'])) {

    if ($_GET["controller"] == "log") {
        $controller = new home_controller();
        $loginFailed = "";
        $failed = "";

        if ($_GET["action"] == "login") {
            $login = new login_controller();
            $loged = $login->login();

            if (!$loged) {

                $loginFailed = $login->loginFailed();
                // require_once "views/templates/header_template.phtml";
            }
            if (!empty($data['cart'])) {

                $login->checkCart();
            } else {
                
            }
        }
        if ($_GET['action'] == "logout") {
            $_SESSION['usuario'] = "invitado";
            $_SESSION['cart'] = [];
        }

        if ($_GET['action'] == "register") {
            $register = new login_controller();
            $failed = $register -> register();
           
        }
        
        require_once "views/templates/header_template.phtml";
        $homeController->view();
    }

    //Mostramos el default header, el cart y las categorias



    if ($_SESSION['usuario'] != "admin") {

        if ($_GET['controller'] == "cart") {

            $cart = new cart_controller();

            if ($_GET['action'] == "addToCart") {
                $id = $_POST["id"];

                if (!empty($_POST['nUnits'])) {
                    $nUnits = $_POST["nUnits"];
                    
                    $cart->addItemToCart($id, $nUnits);
                }

                $cart->addItemToCart($id);
                // muestro la pantalla principal para que cargue por primera vez el carrito (modal)
                //header('location: index.php');

                $homeController->view();
            }

            if ($_GET['action'] == "add_1_ToCart") {
                $id = $_GET["id"];
       
                $cart->add_1_ToCart($id);

            }

            if ($_GET['action'] == "remove_1_FromCart") {
                $id = $_GET["id"];

                $cart->remove_1_FromCart($id);
       
            }

            if ($_GET['action'] == "deleteFromCart") {
                $id = $_GET['id'];
                //echo "<pre>" .print_r($id,1). "</pre>";
                //die();
                $cart->deleteItemFromCart($id);
                $homeController->view();
            }

            if ($_GET['action'] == "deleteFromDB") {

                $cart->deleteOrderItem();
            }

            if ($_GET['action'] == "deleteFromDB_final") {

                $cart->deleteOrderItem_final();
            }

            if ($_GET['action'] == "finalCart") {
                ob_end_clean();
                $cart->finalCart_view();
            }

            if ($_GET['action'] == "finalbuy") {
                //ob_end_clean();
                $cart->buyComplete();
                $cart->purchased_view();
            }
            
             if ($_GET['action'] == "history") {
                $cart->historyCart();
                
            }
            
             if ($_GET['action'] == "emptyCart") {
                $cart->empty_cart();
                
            }
            
            
        }
    }


    // mostrar productos por categortias
    if ($_GET['controller'] == "products") {

        if ($_GET['action'] == "view") {
            ob_end_clean();
            $controller = new products_controller();
            $id = $_GET['subCategory'];
            $controller->view($id);
        }

        if ($_GET['action'] == "profileProduct") {
            $id = $_GET['id'];
            $controller = new products_controller();
            $controller->profileProduct($id);
        }

        if ($_GET['action'] == "search") {
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
    // $category = new categories_controller();
    // $cart = new cart_controller();
    // $data['cart'] = $cart->shoppingCart();
    // $data['categories'] = $category->getCategories();
    //require_once "views/templates/header_template.phtml";


    $homeController = new home_controller();
    $homeController->view();
}
?>
