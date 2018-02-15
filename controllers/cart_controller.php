<?php

// require_once("models/categories_model.php");
require_once("models/products_model.php");
require_once("models/cart_model.php");

class cart_controller {

    public function shoppingCart() {

        $product = new products_model();

        $data = $product->get_shopping_cart();

        if (is_array($data) || is_object($data)) {
            foreach ($data as $key => $producto) {
                $nUnits = $_SESSION['cart'][$producto["ID"]];
                $data[$key]["nUnits"] = $nUnits;
            }

            return $data;
        }
    }

   public function addItemToCart($id, $nUnits=1){
        
        $item = array($id,$nUnits);
        
        if (empty($_SESSION['cart'])){
        $_SESSION['cart'] = array();
        
        } 
        
        if (array_key_exists($id, $_SESSION['cart'])) {
            $_SESSION['cart'][$id] += $nUnits;
        } else {
            $_SESSION['cart'][$id] = $nUnits;
        }
    }

    public function deleteItemFromCart($id) {

        if (array_key_exists($id, $_SESSION['cart'])) {
            //echo "<pre>" .print_r($id,1). "</pre>";
            //die();
            unset($_SESSION['cart'][$id]);
            header("location:index.php");
        }
    }

    public function insertOrderItem($data) {
        $orderItem = new cart_model();
        $orderItem->insertOrderItem();
    }

    public function insertOrder($data) {
        $order = new cart_model();
        $order->insertOrder($data);
    }

}

?>
