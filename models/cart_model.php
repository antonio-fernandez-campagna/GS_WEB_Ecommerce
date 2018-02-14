<?php

class cart_model {

    private $db;
    private $orderItem;
    private $order;
    private $orderline;
    private $orderItemOrder;
    private $product;
    private $quantity;
    private $price;
    private $paymentInfo;
    private $status;
    private $shippingAddress;
    private $user;

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

    public function insertOrderItem() {

        $sql = "INSERT INTO orderitem (ORDER, PRODUCT, QUANTITY, PRICE)
                    VALUES ('{$this->order}','{$this->$product}', '{$this->$quantity}', '{$this->$price}')";


        $result = $this->db->query($sql);
        if ($this->db->error)
            return "$sql<br>{$this->db->error}";
        else {
            return false;
        }
    }

    public function insertOrder() {

        $sql = "INSERT INTO `order` (PAYMENTINFO, STATUS, SHIPPINGADDRESS, USER)
                    VALUES ('{$this->paymentInfo}','{$this->status}', '{$this->shippingAddress}', '{$this->user}')";

        $result = $this->db->query($sql);
        if ($this->db->error)
            return "$sql<br>{$this->db->error}";
        else {
            return $this->db->insert_id;
        }
    }

}

?>
