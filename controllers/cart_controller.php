<?php

// require_once("models/categories_model.php");
require_once("models/products_model.php");
require_once("models/products_model.php");

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

    public function shoppingCartDB() {

        $product = new products_model();
        //$id = $this->insertOrder();
        //echo "<pre>" . print_r($id, 1) . "</pre>";
        //die();
        //echo $id;

        $data = $product->get_shopping_cart_db();

        foreach ($data as $key => $producto) {
            $bd = "yes";
            $data[$key]["db"] = $bd;
        }
        //echo "<pre>" . print_r($data, 1) . "</pre>";

        return $data;
    }

    public function addItemToCart($id, $nUnits = 1) {

        $addItem = new cart_model();

        $item = array($id, $nUnits);

        $id_pending = $this->checkLastPending();

        if ($_SESSION['usuario'] != "admin" && $_SESSION['usuario'] != "invitado") {

            if (empty($id_pending['max(id)'])) {

               // insert order 
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
    }

    public function deleteItemFromCart($id) {

        //echo "<pre>" .print_r($_SESSION["cart"],1). "</pre>";
        //die();
        // no hace bien la comprobacion
        if (!empty($_SESSION['cart'])) {
            echo "<pre>" . print_r("ee", 1) . "</pre>";
            //die();
            unset($_SESSION['cart'][$id]);
            header("location:index.php");
        }
    }

    // public function insertOrderItem($id,$data) {
    // ultimo order delete mysql ta
    //   $orderItem = new cart_model();
    //    $orderItem->insertOrderItem($id,$data);
    //}

    public function insertOrder() {
        $order = new cart_model();
        $id = $order->insertOrder();

        $data = $this->shoppingCart();
        //unset($_SESSION['cart']);

        $order->insertOrderItem($id, $data);

        return $id;
    }

    public function deleteOrderItem() {
        $orderitem = new cart_model();
        $id = $_GET['id'];

        $orderitem->deleteOrderItem($id);
        header("location:index.php");
    }

    public function checkLastPending() {
        $orderId = new cart_model();


        $id = $orderId->checkLastPending();
        return $id;
    }

}

?>
