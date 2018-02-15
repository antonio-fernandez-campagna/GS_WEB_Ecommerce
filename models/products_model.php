<?php

class products_model {

    private $db;
    private $products;
    private $id;
    private $name;
    private $stock;
    private $price;
    private $sponsored;
    private $shortDescription;
    private $longDescription;
    private $brand;
    private $category;

    public function __construct() {
        $this->db = Conectar::conexion();
        $this->products = array();
    }

    /* GETTERS & SETTERS */

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getStock() {
        return $this->stock;
    }

    public function setStock($stock) {
        $this->stock = $stock;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getSponsored() {
        return $this->sponsored;
    }

    public function setSponsored($sponsored) {
        $this->sponsored = $sponsored;
    }

    public function getShortDescription() {
        return $this->shortdescription;
    }

    public function setShortDescription($shortdescription) {
        $this->shortDescription = $shortdescription;
    }

    public function getLongDesc() {
        return $this->longdescription;
    }

    public function setLongDescription($longdescription) {
        $this->longDescription = $longdescription;
    }

    public function getBrand() {
        return $this->brand;
    }

    public function setBrand($brand) {
        $this->brand = $brand;
    }

    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category) {
        $this->category = $category;
    }

  //  public function pagination() {
      //  $results_per_page = 3;

      //  $number
        // determinate number of total pages available
      //  $number_of_pages = ceil($number_of_results / $results_per_page);

        // determinate which page number bisistor is currently on
        // determine the sql LIMIT starrting number for the results on the displaying page
      //  echo "$page_first_result = ($page-1)*$results_per_page";

      //  $number_array = [];

        //for ($page = 1; $page < $results_per_page; $page ++){
      //      $numb
      //  }


    //    }


    /**
     * Extreu totes les persones de la taula
     * @return array Bidimensional de totes les persones
     */
    public function get_products($subCategory = "") {

        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }

        $number_products_per_page = 3;
        $final_limit_product = $number_products_per_page * $page;
        $start_limit_product = $page == 1 ? 0 : $final_limit_product - $number_products_per_page;
        // LIMIT {$start_limit_product},{$number_products_per_page}

        if (!empty($subCategory)) {
            //$query = "SELECT * FROM product WHERE category = {$subCategory};";
            $query = "SELECT *, prod.ID, img.URL, promo.DISCOUNTPERCENTAGE, promo.ENDDATE, FORMAT((prod.PRICE * (1-(promo.DISCOUNTPERCENTAGE/100))),2) AS FINALPRICE FROM product prod join image img on prod.ID = img.product left join promotion promo on promo.product = prod.id WHERE prod.CATEGORY = {$subCategory};";
            //die($query);
        } else {
            $query = "SELECT *, prod.ID, img.URL, promo.DISCOUNTPERCENTAGE, promo.ENDDATE, FORMAT((prod.PRICE * (1-(promo.DISCOUNTPERCENTAGE/100))),2) AS FINALPRICE FROM product prod join image img on prod.ID = img.product left join promotion promo on promo.product = prod.id WHERE prod.SPONSORED = 'Y';";
            //$query = "SELECT prod.* FROM PRODUCT prod WHERE prod.SPONSORED = 'Y';";
        }

        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->products[] = $filas;
        }
        return $this->products;
    }

    public function getProductProfile($id) {

        $sql = "SELECT * FROM product WHERE id='$id'";

        $result = $this->db->query($sql);
        $fila = $result->fetch_assoc();
        return $fila;
        //echo "<pre>".print_r($fila, 1)."</pre>"; die;
    }

    public function get_shopping_cart() {

        if (!empty($_SESSION["cart"])) {
            $idProducts = implode(",", array_keys($_SESSION["cart"]));
            //die($idProducts);

            $query = "SELECT *, prod.ID, img.URL, promo.DISCOUNTPERCENTAGE, promo.ENDDATE, FORMAT((prod.PRICE * (1-(promo.DISCOUNTPERCENTAGE/100))),2) AS FINALPRICE FROM product prod join image img on prod.ID = img.product left join promotion promo on promo.product = prod.id WHERE prod.ID in ({$idProducts});";
            //die($query);
            $consulta = $this->db->query($query);
            while ($filas = $consulta->fetch_assoc()) {
                $this->products[] = $filas;
            }

            return $this->products;
        }
    }




    public function get_product_searcher($word) {

        $query = "SELECT prod.*, img.URL FROM product prod join image img on prod.ID = img.product WHERE SHORTDESCRIPTION like '%$word%' or  LONGDESCRIPTION like '%$word%';";


        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->products[] = $filas;
        }


        return $this->products;
    }

    public function get_filtered_products($strIds) {
        $query = "SELECT prod.*, img.URL FROM product prod join image img on prod.ID = img.product WHERE BRAND in ({$strIds});";

        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->products[] = $filas;
        }
        return $this->products;
    }

    public function get_brands($subCategory = "") {

        if (empty($subCategory)) {
            $consulta = $this->db->query("SELECT * from brand;");
        } else {
            $consulta = $this->db->query("SELECT distinct p.BRAND, b.NAME FROM product p, brand b where p.category = $subCategory and p.BRAND = b.ID");
        }

        while ($filas = $consulta->fetch_assoc()) {
            $this->products[] = $filas;
        }

        return $this->products;
    }

    public function insert_product() {

        //echo $this->shortDescription;die;

        $sql = "INSERT INTO product (NAME, STOCK, PRICE, SPONSORED, SHORTDESCRIPTION, LONGDESCRIPTION, BRAND, CATEGORY)
                    VALUES ('{$this->name}','{$this->stock}', '{$this->price}', '{$this->sponsored}','{$this->shortDescription}','{$this->longDescription}',
                    '{$this->brand}','{$this->category}')";

        die($sql);
        $result = $this->db->query($sql);
        if ($this->db->error)
            return "$sql<br>{$this->db->error}";
        else {
            return $result;
        }
    }

    public function get_all_products() {
        $query = "SELECT ID, NAME from product;";

        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->products[] = $filas;
        }
        return $this->products;
    }

}

?>
