<?php

require_once("models/products_model.php");
require_once("models/categories_model.php");
require_once("models/promotions_model.php");
require_once("controllers/categories_controller.php");
require_once("controllers/login_controller.php");
require_once("controllers/home_controller.php");

// Función para el controlador del carrito
class cart_controller {

    // Función que devuelve los datos de $_session['cart] 
    public function shoppingCart() {

        $product = new products_model();

        $data = $product->get_shopping_cart();

        // Recogemos el número de unidades de los productos que hay en la cesta
        // para guardarlo en el array $data
        if (is_array($data) || is_object($data)) {
            foreach ($data as $key => $producto) {
                $nUnits = $_SESSION['cart'][$producto["ID"]];
                $data[$key]["nUnits"] = $nUnits;
                $bd = "no";
                $data[$key]["db"] = $bd;
            }



            return $data;
        }
    }

    // función que llama a la página final de compra
    public function finalCart_view() {

        $cart = new login_controller();
        $category = new categories_controller();

        $data['cart'] = $cart->checkCart();
        $data['categories'] = $category->getCategories();

        include("views/finalCart_view.phtml");
    }

    // función que muestra los productos de la base de datos
    public function shoppingCartDB() {

        $product = new products_model();

        $data = $product->get_shopping_cart_db();

        foreach ($data as $key => $producto) {
            $bd = "yes";
            $data[$key]["db"] = $bd;
        }

        return $data;
    }

    // función para añadir productos
    public function addItemToCart($id, $nUnits = 1) {

        $addItem = new cart_model();
        $home = new home_controller();

        $item = array($id, $nUnits);

        // comprobar si hay on order pendiente
        $id_pending = $this->checkLastPending();

        // comprobar si se ha iniciado sesión
        if ($_SESSION['usuario'] != "admin" && $_SESSION['usuario'] != "invitado") {

            // comprobar si hay on order pendiente
            if (empty($id_pending['max(id)'])) {

                // insertar un order y un OrderItem cuando se ha iniciado sesión y no se habia comprado nada antes
                // (no hay ningun order pending creado)
                $id_order = $addItem->insertOrder();
                $addItem->insertOrderItemNoLoged($id_order, $nUnits, $id);
            } else {
                // si ya hay un order en estado pendiente, crea un orderItem referenciando al orden
                // creado anteriormete
                $addItem->insertProduct($item, $id_pending);
            }
        } else {
            
            //if (empty($_SESSION['cart'])) {
            //    $_SESSION['cart'] = array();
            //}

            // si la key del array session cart existe añade items al array ya creado, si no, 
            // //lo crea con el número de unidades
            if (array_key_exists($id, $_SESSION['cart'])) {
                $_SESSION['cart'][$id] += $nUnits;
            } else {
                $_SESSION['cart'][$id] = $nUnits;
            }
        }


        header('location: index.php');
    }

    // se añade 1 producto al cart de la bd
    public function add_1_ToCart($id, $nUnits = 1) {

        $addItem = new cart_model();

        $item = array($id, $nUnits);

        // se recoge el último id en estado pendiente
        $id_pending = $this->checkLastPending();

        if ($_SESSION['usuario'] != "admin" && $_SESSION['usuario'] != "invitado") {

            // se añade 1
            $addItem->add_1_Product($item, $id_pending);
        }

        $this->finalCart_view();
    }

    // elimina 1 producto del cart de la bd
    public function remove_1_FromCart($id, $nUnits = 1) {

        $addItem = new cart_model();

        $item = array($id, $nUnits);

        $id_pending = $this->checkLastPending();

        if ($_SESSION['usuario'] != "admin" && $_SESSION['usuario'] != "invitado") {

            $addItem->remove_1_Product($item, $id_pending);
        }

        $this->finalCart_view();
    }

    // función que muestra el historial de compras
    public function historyCart() {

        $cart = new cart_model();
        $data = $cart->get_history_cart();
        $dataOrdered = [];
        
        // se ordena el array para mostrar por número de order y fecha
        foreach ($data as $producto) {
            $date = $producto['DATE'];
            if (array_key_exists($date, $dataOrdered)) {
                $dataOrdered[$date][] = $producto;
            } else {
                $dataOrdered[$date] = [];
                $dataOrdered[$date][] = $producto;
            }
        }



        include 'views/history_view.phtml';
    }

    // función que elimina un item del session cart
    public function deleteItemFromCart($id) {


        if (!empty($_SESSION['cart'])) {
            unset($_SESSION['cart'][$id]);
            header("location:index.php");
        }
    }

    // función que inserta un order y un order item
    public function insertOrder() {
        $order = new cart_model();
        $id = $order->insertOrder();

        $data = $this->shoppingCart();

        $order->insertOrderItem($id, $data);

        return $id;
    }

    // función que elimina un order item
    public function deleteOrderItem() {
        $orderitem = new cart_model();
        $home = new home_controller();

        $id = $_GET['id'];

        $orderitem->deleteOrderItem($id);

        $home->view();
    }

    // funcion que elimina un orderItem desde la página final de compra
    public function deleteOrderItem_final() {
        $orderitem = new cart_model();
        $id = $_GET['id'];

        $orderitem->deleteOrderItem($id);

        $this->finalCart_view();
    }

    // función para comprobar el último en estado pendiente
    public function checkLastPending() {
        $orderId = new cart_model();


        $id = $orderId->checkLastPending();
        return $id;
    }

    // funcion que completa una compra, cambia el estado de pendiente a PAID
    public function buyComplete() {
        $cart = new cart_model();
        $home = new home_controller();

        $cart->buyComplete();
        //$home->view();
    }

    // función que llama a la vista de historial de compras
    public function purchased_view() {
        
        $this->historyCart();
        
    }
    
    // función que vacia el cart de la BD
    public function empty_cart() {

        $deleteOrder = new cart_model();
        $deleteOrder->emptyCartDB();


        header('location: index.php');
    }

}

?>
