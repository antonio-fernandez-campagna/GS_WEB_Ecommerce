<?php

class promotions_model {

    private $db;
    private $promotion;
    private $id;
    private $discountPercentage;
    private $startDate;
    private $endDate;
    private $product;

    public function __construct() {
        $this->db = Conectar::conexion();
    }

    /* GETTERS & SETTERS */

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getDiscountPercentage() {
        return $this->discountpercentage;
    }

    public function setDiscountPercentage($discountPercentage) {
        $this->discountPercentage = $discountPercentage;
    }

    public function getStartDate() {
        return $this->startdate;
    }

    public function setStartDate($startDate) {
        $this->startDate = $startDate;
    }

    public function getEndDate() {
        return $this->enddate;
    }

    public function setEndDate($endDate) {
        $this->endDate = $endDate;
    }

    public function getProduct() {
        return $this->product;
    }

    public function setProduct($product) {
        $this->product = $product;
    }

    // Función para insertar promociones
    public function insert_promotion() {

        $sql = "INSERT INTO promotion (PRODUCT, DISCOUNTPERCENTAGE, ENDDATE ) VALUES ('{$this->product}','{$this->discountPercentage}','{$this->endDate}')";


        $result = $this->db->query($sql);
        if ($this->db->error)
            return "$sql<br>{$this->db->error}";
        else {
            return false;
        }
    }

    // función para recoger las promociones de los productos
    public function get_promos() {

        $query = "SELECT prod.NAME, prod.SHORTDESCRIPTION, img.URL, promo.DISCOUNTPERCENTAGE, prod.PRICE, FORMAT((prod.PRICE * (1-(promo.DISCOUNTPERCENTAGE/100))),2) AS FINALPRICE FROM promotion promo join product prod on prod.ID = promo.PRODUCT join image img on img.PRODUCT = prod.ID ;";

        $consulta = $this->db->query($query);

        while ($filas = $consulta->fetch_assoc()) {
            $this->promotion[] = $filas;
        }

        return $this->promotion;
    }

}
