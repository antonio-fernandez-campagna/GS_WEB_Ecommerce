<?php

class cart_model {

    private $db;
    private $orderId;

    function getOrderItemOrder() {
        return $this->orderItemOrder;
    }

    function getPaymentInfo() {
        return $this->paymentInfo;
    }

    function getStatus() {
        return $this->status;
    }

    function getShippingAddress() {
        return $this->shippingAddress;
    }

    function getUser() {
        return $this->user;
    }

    function setOrderItemOrder($orderItemOrder) {
        $this->orderItemOrder = $orderItemOrder;
    }

    function setPaymentInfo($paymentInfo) {
        $this->paymentInfo = $paymentInfo;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setShippingAddress($shippingAddress) {
        $this->shippingAddress = $shippingAddress;
    }

    function setUser($user) {
        $this->user = $user;
    }

    function getOrderItem() {
        return $this->orderItem;
    }

    function getOrderline() {
        return $this->orderline;
    }

    function getOrder() {
        return $this->order;
    }

    function getProduct() {
        return $this->product;
    }

    function getQuantity() {
        return $this->quantity;
    }

    function getPrice() {
        return $this->price;
    }

    function setOrderItem($orderItem) {
        $this->orderItem = $orderItem;
    }

    function setOrderline($orderline) {
        $this->orderline = $orderline;
    }

    function setOrder($order) {
        $this->order = $order;
    }

    function setProduct($product) {
        $this->product = $product;
    }

    function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    function setPrice($price) {
        $this->price = $price;
    }

    public function __construct() {
        $this->db = Conectar::conexion();
    }

    // Función que inserta un orderItem pasandole el id y el producto
    public function insertOrderItem($id, $data) {

        $contador = 1;

        foreach ($data as $item) {

            //echo $contador;die;
            //var_dump($data);

            if (empty($item['FINALPRICE'])) {

                $price = $item['PRICE'];
            } else {

                $price = $item['FINALPRICE'];
            }

            $sql = "INSERT INTO orderitem (ORDERLINE, `ORDER`, PRODUCT, QUANTITY, PRICE)
                    VALUES ({$contador},{$id},{$item['ID']}, {$item['nUnits']}, {$price})";

            //die($sql);
            $result = $this->db->query($sql);
            //var_dump($sql);
            //var_dump($result);
            $contador ++;
        }

        if ($this->db->error)
            return "$sql<br>{$this->db->error}";
        else {
            $result_id = $this->db->insert_id;
        }
    }

    // Función que inserta un order en estado pendiente
    public function insertOrder() {

        $sql = "INSERT INTO `order` (SHIPPINGADDRESS, USER)
                    VALUES ('dirección ___', '{$_SESSION['usuario']}')";

        $result = $this->db->query($sql);
        if ($this->db->error)
            return "$sql<br>{$this->db->error}";
        else {
            return $this->db->insert_id;
        }
    }

    // Función que elimina un orderItem
    public function deleteOrderItem($id) {

        //$user = $_SESSION['usuario'];
        //die($user);

        $sql = "DELETE from orderitem where PRODUCT = {$id}";

        $result = $this->db->query($sql);

        if ($this->db->error)
            return "$sql<br>{$this->db->error}";
        else {
            return false;
        }
    }

    // Función que comprueba cual es el ID en estado pendiente
    // el max(id) era para comprobar bien, se puede quitar ya que solo habrá
    // uno en estado pendiente
    public function checkLastPending() {

        //$user = $_SESSION['usuario'];
        //die($user);

        $sql = "SELECT max(id) from `order` where PAYMENTINFO = 2 and  USER = '{$_SESSION['usuario']}'";

        $consulta = $this->db->query($sql);
        $id = $consulta->fetch_assoc();


        //echo "<pre> aa" . print_r($id, 1) . "</pre>";
        return $id;
    }

    // Función que inserta un orderline, pasandole el item (id del producto y número de unidades) y el
    // ID en estado pendiente
    public function insertProduct($item, $id_pending) {

        // se recoge el max orderline de ese usuario para poder insertar un orderline y que se sume 1
        // al orderline que se vaya a insertar despuñes
        $sql = "SELECT max(ORDERLINE) FROM `orderitem` join `order` on user = '{$_SESSION['usuario']}'";

        $consulta = $this->db->query($sql);
        $maxOrderLine = $consulta->fetch_assoc();
        $orderLine = $maxOrderLine['max(ORDERLINE)'] + 1;

        // se recoge el precio (por si tiene una promoción aplicada)
        $sql2 = "SELECT prod.PRICE, FORMAT((prod.PRICE * (1-(promo.DISCOUNTPERCENTAGE/100))),2) AS FINALPRICE FROM product prod LEFT join promotion promo on prod.ID = promo.PRODUCT where prod.ID = {$item[0]}";

        $consulta = $this->db->query($sql2);
        $price = $consulta->fetch_assoc();


        if (empty($price['FINALPRICE'])) {

            $price = $price['PRICE'];
        } else {

            $price = $price['FINALPRICE'];
        }

        // se secoge el id del producto y la cantidad del orderItem para que se pueda
        // sumar la cantidad

        $sql3 = "SELECT product, QUANTITY FROM `orderitem` where PRODUCT = {$item[0]} and `order` = {$id_pending['max(id)']}";

        $consulta = $this->db->query($sql3);
        $prodId = $consulta->fetch_assoc();

        // se comprueba si el producto ya estaba, si no estaba se crea, y si estaba
        // se suman las unidades
        if (empty($prodId)) {
            $sql4 = "INSERT into orderitem (ORDERLINE, `ORDER`, PRODUCT, QUANTITY, PRICE) VALUES "
                    . "({$orderLine}, {$id_pending['max(id)']}, {$item[0]}, {$item[1]}, {$price} )";
        } else {
            $sql4 = "UPDATE orderitem SET QUANTITY = ({$prodId['QUANTITY']} + {$item[1]}) WHERE PRODUCT = {$item[0]} ";
        }


        $consulta = $this->db->query($sql4);

        if ($this->db->error)
            return "$consulta<br>{$this->db->error}";
        else {
            return false;
        }
    }

    // se añade un producto al orderline (se modifica desde la pantalla final de compra)
    public function add_1_Product($item, $id_pending) {

        $sql1 = "SELECT product, QUANTITY FROM `orderitem` where PRODUCT = {$item[0]} and `order` = {$id_pending['max(id)']}";

        //die($sql3);
        $consulta = $this->db->query($sql1);
        $prodId = $consulta->fetch_assoc();


        $sql2 = "UPDATE orderitem SET QUANTITY = ({$prodId['QUANTITY']} + {$item[1]}) WHERE PRODUCT = {$item[0]} ";



        $consulta = $this->db->query($sql2);

        if ($this->db->error)
            return "$consulta<br>{$this->db->error}";
        else {
            return false;
        }
    }

    // se quita un producto al orderline (se modifica desde la pantalla final de compra)
    public function remove_1_Product($item, $id_pending) {

        $sql1 = "SELECT product, QUANTITY FROM `orderitem` where PRODUCT = {$item[0]} and `order` = {$id_pending['max(id)']}";

        //die($sql3);
        $consulta = $this->db->query($sql1);
        $prodId = $consulta->fetch_assoc();


        $sql2 = "UPDATE orderitem SET QUANTITY = ({$prodId['QUANTITY']} - {$item[1]}) WHERE PRODUCT = {$item[0]} ";



        $consulta = $this->db->query($sql2);

        if ($this->db->error)
            return "$consulta<br>{$this->db->error}";
        else {
            return false;
        }
    }

    // Función para pasar de estado pendiente a Pagado (cuando el usuario le da
    // a finalizar compra)
    public function buyComplete() {

        $sql = "SELECT MAX(ID) FROM `ORDER` WHERE PAYMENTINFO = 2 AND USER = '{$_SESSION['usuario']}'";

        $consulta = $this->db->query($sql);
        $id_order = $consulta->fetch_assoc();


        $sql2 = "UPDATE `ORDER` SET PAYMENTINFO = 1 WHERE ID = '{$id_order['MAX(ID)']}'";

        $consulta = $this->db->query($sql2);

        if ($this->db->error)
            return "$consulta<br>{$this->db->error}";
        else {
            return false;
        }
    }

    // Función que inserta un orderItem después de haber logeado y no tener un Order creado
    public function insertOrderItemNoLoged($id_order, $nUnits, $id) {

        $sql = "SELECT prod.PRICE, FORMAT((prod.PRICE * (1-(promo.DISCOUNTPERCENTAGE/100))),2) AS FINALPRICE FROM product prod LEFT join promotion promo on prod.ID = promo.PRODUCT where prod.ID = {$id}";


        $consulta = $this->db->query($sql);
        $price = $consulta->fetch_assoc();

        if (empty($price['FINALPRICE'])) {

            $price = $price['PRICE'];
        } else {

            $price = $price['FINALPRICE'];
        }

        $sql2 = "INSERT INTO orderitem (ORDERLINE, `ORDER`, PRODUCT, QUANTITY, PRICE) VALUES (1,{$id_order},{$id}, {$nUnits}, {$price})";


        if ($this->db->error)
            return "$sql2<br>{$this->db->error}";
        else {
            $result_id = $this->db->insert_id;
        }
    }

    // Función que marca el order en estado rechazado
    public function reject_order($id) {

        $sql = "UPDATE `ORDER` SET PAYMENTINFO = 3 WHERE ID = '{$id['max(id)']} '";

        $consulta = $this->db->query($sql);

        if ($this->db->error)
            return "$consulta<br>{$this->db->error}";
        else {
            return false;
        }
    }

    // Función para mostrar el historial de compras
    public function get_history_cart() {

        $sql = "SELECT DISTINCT prod.NAME, prod.SHORTDESCRIPTION, orderitem.QUANTITY as nUnits, orderitem.PRICE, ord.DATE, img.URL from product PROD JOIN orderitem on prod.ID = orderitem.PRODUCT JOIN `order` ord on orderitem.`ORDER` = ord.ID JOIN user on ord.USER = '{$_SESSION['usuario']}' JOIN image img on prod.ID = img.PRODUCT WHERE ord.PAYMENTINFO = 1 ORDER BY ord.DATE DESC";

        $consulta = $this->db->query($sql);
        while ($filas = $consulta->fetch_assoc()) {
            $this->products[] = $filas;
        }

        return $this->products;
    }

    // función para vaciar el cart de BD
    public function emptyCartDB() {

        $sql = "DELETE ORDERITEM from orderitem JOIN `order` on orderitem.`ORDER` = (SELECT MAX(ID) FROM `order` WHERE USER = '{$_SESSION['usuario']}');";
        $consulta = $this->db->query($sql);

        $sql2 = "DELETE FROM `ORDER` WHERE USER = '{$_SESSION['usuario']}' AND PAYMENTINFO = 2;";
        $consulta = $this->db->query($sql2);

        if ($this->db->error)
            return "$consulta<br>{$this->db->error}";
        else {
            return false;
        }
    }

}

?>
