<?php

class products_model {

    public $db;
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

    // Función que extrae los productos destacados o por subcategoria
    public function get_products($subCategory = "") {

        // páginacion
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }

        // determinaciones para saber desde donde hasta donde mostrar
        // según la página en la que estemos
        $number_products_per_page = 10;
        $final_limit_product = $number_products_per_page * $page;
        $start_limit_product = $page == 1 ? 0 : $final_limit_product - $number_products_per_page;

        if (!empty($subCategory)) {
            // productos por subcategoria
            $query = "SELECT *, prod.ID, img.URL, promo.DISCOUNTPERCENTAGE, promo.ENDDATE, FORMAT((prod.PRICE * (1-(promo.DISCOUNTPERCENTAGE/100))),2) AS FINALPRICE FROM product prod join image img on prod.ID = img.product left join promotion promo on promo.product = prod.id WHERE prod.CATEGORY = {$subCategory};";
        } else {
            // aquí hará la determinación de la páginacion ya que esto hace refencia a los productos destacados
            $query = "SELECT *, prod.ID, img.URL, promo.DISCOUNTPERCENTAGE, promo.ENDDATE, FORMAT((prod.PRICE * (1-(promo.DISCOUNTPERCENTAGE/100))),2) AS FINALPRICE FROM product prod join image img on prod.ID = img.product left join promotion promo on promo.product = prod.id WHERE prod.SPONSORED = 'Y' LIMIT {$start_limit_product},{$number_products_per_page};";
        }

        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->products[] = $filas;
        }
        return $this->products;
    }

    // Función para saber el número de filas de destacado
    public function getNumRows() {

        $query = "select count(1) FROM product prod join image img on prod.ID = img.PRODUCT where SPONSORED = 'Y'";

        $consulta = $this->db->query($query);

        $row = $consulta->fetch_array();

        return $row[0];
    }


    // Función para mostrar el carrito de session cart
    public function get_shopping_cart() {

        if (!empty($_SESSION["cart"])) {

            // separa por comas las KEYS del array (ahí estan las ID de los productos)
            $idProducts = implode(",", array_keys($_SESSION["cart"]));

            $query = "SELECT *, prod.ID, img.URL, promo.DISCOUNTPERCENTAGE, promo.ENDDATE, FORMAT((prod.PRICE * (1-(promo.DISCOUNTPERCENTAGE/100))),2) AS FINALPRICE
                            FROM product prod join image img on prod.ID = img.product
                            left join promotion promo on promo.product = prod.id
                             WHERE prod.ID in ({$idProducts});";


            $consulta = $this->db->query($query);
            while ($filas = $consulta->fetch_assoc()) {
                $this->products[] = $filas;
            }

            return $this->products;
        }
    }

    // Función que devuelve los productos pasándole por parámetro la palabra
     public function get_product_searcher($word) {
        $query = "SELECT prod.*, img.URL FROM product prod join image img on prod.ID = img.product WHERE SHORTDESCRIPTION like '%$word%';";
        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->products[] = $filas;
        }
        return $this->products;
    }

    // Función que recoge los productos de la BD
    public function get_shopping_cart_db() {

        $sql = "select max(ID) from `order` WHERE PAYMENTINFO = 2 & USER = 'user'";

        $consulta = $this->db->query($sql);
        $id = $consulta->fetch_assoc();



        $user = $_SESSION['usuario'];

        $query = "SELECT prod.ID ,prod.NAME, prod.SHORTDESCRIPTION, prod.STOCK, ord.ID as ID_ORDER, ordIt.PRODUCT as ID_PROD, ordIt.QUANTITY as nUnits, ordIt.PRICE, img.URL from `order` ord JOIN product prod JOIN orderitem ordIt JOIN user JOIN image img WHERE user.USERNAME = 'user' AND ord.ID = ordIt.`ORDER` AND img.PRODUCT = ordIt.PRODUCT AND ordIt.PRODUCT = prod.ID AND ord.PAYMENTINFO = 2 AND ord.ID = {$id['max(ID)']};";
        //die($query);
        //echo "<pre>".print_r($query, 1)."</pre>";

        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->products[] = $filas;
        }
        $_SESSION['cart'] = [];

        return $this->products;
    }


    // función que recoge las marcas que están en las subcategorias
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

    // Función para insertar  productos
    public function insert_product() {

        //echo $this->shortDescription;die;

        $sql = "INSERT INTO product (NAME, STOCK, PRICE, SPONSORED, SHORTDESCRIPTION, LONGDESCRIPTION, BRAND, CATEGORY)
                    VALUES ('{$this->name}','{$this->stock}', '{$this->price}', '{$this->sponsored}','{$this->shortDescription}','{$this->longDescription}',
                    '{$this->brand}','{$this->category}')";

        //die($sql);
        $result = $this->db->query($sql);
        if ($this->db->error)
            return "$sql<br>{$this->db->error}";
        else {
            return $result;
        }
    }

    // Función que devuelve todos los productos
    public function get_all_products() {
        $query = "SELECT ID, NAME from product;";

        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->products[] = $filas;
        }
        return $this->products;
    }

    // Función para el buscador, devuelve la descripcion corta y el ID todos los productos
    public function get_product__short_descriptions() {
        $query = "SELECT SHORTDESCRIPTION as value, ID as data from product;";
        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->products[] = $filas;
        }
        return $this->products;
    }

}

?>
