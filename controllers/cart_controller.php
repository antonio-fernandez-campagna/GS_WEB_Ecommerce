<?php

// require_once("models/categories_model.php");
require_once("models/products_model.php");
require_once("models/categories_model.php");
require_once("models/promotions_model.php");
require_once("controllers/categories_controller.php");
require_once("controllers/login_controller.php");
require_once("controllers/home_controller.php");

class cart_controller {

    public function shoppingCart() {

        $product = new products_model();
        //$_SESSION['cart'] = [];
        //$_SESSION['cart'] = [];
        $data = $product->get_shopping_cart();
        //echo "<pre>" . print_r($data, 1) . "</pre>";
        // Recogemos el número de unidades de los productos que hay en la cesta
        // para guardarlo en el array $data
        if (is_array($data) || is_object($data)) {
            foreach ($data as $key => $producto) {
                $nUnits = $_SESSION['cart'][$producto["ID"]];
                $data[$key]["nUnits"] = $nUnits;
                $bd = "no";
                $data[$key]["db"] = $bd;
            }

            // echo "<pre>" . print_r($data, 1) . "</pre>";
            //die();

            return $data;
            //echo "<pre>" . print_r($data, 1) . "</pre>";
            //die;
        }
    }

    public function finalCart_view() {

        $cart = new login_controller();
        $category = new categories_controller();

        $data['cart'] = $cart->checkCart();
        $data['categories'] = $category->getCategories();

        //echo "<pre>" .print_r($data['cart'],1). "</pre>";


        include("views/finalCart_view.phtml");
    }

    public function shoppingCartDB() {

        $product = new products_model();

        $data = $product->get_shopping_cart_db();

        foreach ($data as $key => $producto) {
            $bd = "yes";
            $data[$key]["db"] = $bd;
        }

        return $data;
    }

    public function addItemToCart($id, $nUnits = 1) {

        $addItem = new cart_model();
        $home = new home_controller();

        $item = array($id, $nUnits);

        $id_pending = $this->checkLastPending();

        if ($_SESSION['usuario'] != "admin" && $_SESSION['usuario'] != "invitado") {

            if (empty($id_pending['max(id)'])) {

                // insert order 

                $id_order = $addItem->insertOrder();
                //echo "<pre> aaaaaaaaaaaa " . print_r($id_order, 1) . "</pre>";

                $addItem->insertOrderItemNoLoged($id_order, $nUnits, $id);
            } else {

                $addItem->insertProduct($item, $id_pending);
            }
        } else {
            if (empty($_SESSION['cart'])) {
                $_SESSION['cart'] = array();
            }

            if (array_key_exists($id, $_SESSION['cart'])) {
                $_SESSION['cart'][$id] += $nUnits;
            } else {
                $_SESSION['cart'][$id] = $nUnits;
            }
        }

        //echo "<pre>" . print_r($_SESSION['cart'], 1) . "</pre>";


        header('location: index.php');
    }

    public function add_1_ToCart($id, $nUnits = 1) {

        $addItem = new cart_model();

        $item = array($id, $nUnits);

        $id_pending = $this->checkLastPending();

        if ($_SESSION['usuario'] != "admin" && $_SESSION['usuario'] != "invitado") {

            $addItem->add_1_Product($item, $id_pending);
        }

        $this->finalCart_view();
    }

    public function remove_1_FromCart($id, $nUnits = 1) {

        $addItem = new cart_model();

        $item = array($id, $nUnits);

        $id_pending = $this->checkLastPending();

        if ($_SESSION['usuario'] != "admin" && $_SESSION['usuario'] != "invitado") {

            $addItem->remove_1_Product($item, $id_pending);
        }

        $this->finalCart_view();
    }

    public function deleteItemFromCart($id) {


        if (!empty($_SESSION['cart'])) {
            unset($_SESSION['cart'][$id]);
            header("location:index.php");
        }
    }

    public function insertOrder() {
        $order = new cart_model();
        $id = $order->insertOrder();

        $data = $this->shoppingCart();

        $order->insertOrderItem($id, $data);

        return $id;
    }

    public function deleteOrderItem() {
        $orderitem = new cart_model();
        $home = new home_controller();

        $id = $_GET['id'];

        $orderitem->deleteOrderItem($id);

        $home->view();
    }

    public function deleteOrderItem_final() {
        $orderitem = new cart_model();
        $id = $_GET['id'];

        $orderitem->deleteOrderItem($id);

        $this->finalCart_view();
    }

    public function checkLastPending() {
        $orderId = new cart_model();


        $id = $orderId->checkLastPending();
        return $id;
    }

    public function buyComplete() {
        $cart = new cart_model();
        $home = new home_controller();

        $cart->buyComplete();
        //$home->view();
    }

    public function purchased_view() {
        $category = new categories_controller();

        $data['category'] = $category->getCategories();


        include('views/purchased_view.phtml');
    }

}

?>
