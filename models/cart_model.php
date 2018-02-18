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

    public function checkLastPending() {

        //$user = $_SESSION['usuario'];
        //die($user);

        $sql = "SELECT max(id) from `order` where PAYMENTINFO = 2 and  USER = '{$_SESSION['usuario']}'";

        $consulta = $this->db->query($sql);
        $id = $consulta->fetch_assoc();


        //echo "<pre> aa" . print_r($id, 1) . "</pre>";
        return $id;
    }

    public function insertProduct($item, $id_pending) {

        $sql = "SELECT max(ORDERLINE) FROM `orderitem` join `order` on user = '{$_SESSION['usuario']}'";

        $consulta = $this->db->query($sql);
        $maxOrderLine = $consulta->fetch_assoc();
        $orderLine = $maxOrderLine['max(ORDERLINE)'] + 1;

        // echo "<pre>" .print_r($id_pending,1). "</pre>";


        $sql2 = "SELECT prod.PRICE, FORMAT((prod.PRICE * (1-(promo.DISCOUNTPERCENTAGE/100))),2) AS FINALPRICE FROM product prod LEFT join promotion promo on prod.ID = promo.PRODUCT where prod.ID = {$item[0]}";

        $consulta = $this->db->query($sql2);
        $price = $consulta->fetch_assoc();


        if (empty($price['FINALPRICE'])) {

            $price = $price['PRICE'];
        } else {

            $price = $price['FINALPRICE'];
        }

        $sql3 = "SELECT product, QUANTITY FROM `orderitem` where PRODUCT = {$item[0]} and `order` = {$id_pending['max(id)']}";

        //die($sql3);
        $consulta = $this->db->query($sql3);
        $prodId = $consulta->fetch_assoc();

        //echo "<pre>" .print_r($orderLine,1). "</pre>";
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

    public function insertOrderItemNoLoged($id_order, $nUnits, $id) {

        // echo "<pre>" .print_r($id,1). "</pre>";


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

    public function reject_order($id) {

        $sql = "UPDATE `ORDER` SET PAYMENTINFO = 3 WHERE ID = '{$id['max(id)']} '";

        $consulta = $this->db->query($sql);

        if ($this->db->error)
            return "$consulta<br>{$this->db->error}";
        else {
            return false;
        }
    }

}

?>
