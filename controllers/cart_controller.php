<?php

// require_once("models/categories_model.php");
require_once("models/products_model.php");
require_once("models/products_model.php");

class cart_controller {

  public function shoppingCart(){

       $product  = new products_model();
       //$_SESSION['cart'] = [];

       $data = $product->get_shopping_cart();

       //echo "<pre>" .print_r($data,1). "</pre>";
       //die();

       // añadir producto desde $_SESSION
       if(is_array($data) || is_object($data)){
            foreach ($data as $key => $producto ){
           $nUnits = $_SESSION['cart'][$producto["ID"]];
           $data[$key]["nUnits"] = $nUnits;
       }

       // añadir productos desde bd

      //  if ($_SESSION['usuario'] != "invitado" && $_SESSION['usuario'] != "admin") {
       //
      //    foreach ($data as $key => $producto ){
      //   $nUnits = $_SESSION['cart'][$producto["ID"]];
      //   $data[$key]["nUnits"] = $nUnits;
       //
      //  }


       return $data;
       }
   }

   public function shoppingCartDB(){

        $product  = new products_model();

        return $data = $product->get_shopping_cart();


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

    public function insertOrderItem($id,$data) {
      // ultimo order delete mysql ta
        $orderItem = new cart_model();
        $orderItem->insertOrderItem($id,$data);


    }

    public function insertOrder() {
        $order = new cart_model();
        $id = $order->insertOrder();

        $data = $this->shoppingCart();
        //unset($_SESSION['cart']);

        $this->insertOrderItem($id, $data);

    }

}

?>
