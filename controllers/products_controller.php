<?php

//Llamada al modelo
require_once("models/products_model.php");
require_once("controllers/home_controller.php");



class products_controller {

    /**
     * Muestra pantalla 'add'
     * @return No
     */
    function add() {
        require_once("views/products_add.phtml");
    }

    /**
     * Mostra llistat
     * @return No
     */
    function view($subCategory) {
        //$producto = new products_model();

        //Uso metodo del modelo de personas
        $data['products'] = $this->getProducts($subCategory);

        $data['categories'] = $this->getCategories();

        $cart = new cart_controller();
        $data['cart'] = $cart->shoppingCart();

        $brand = new products_model();
        $data['brands'] = $brand->get_brands($subCategory);

        //echo "<pre>" .print_r($data['brands'],1). "</pre>";
        //die();
        $category = new categories_controller();
        include("views/templates/header_template.phtml");

        //Llamado a la vista: mostrar la pantalla
        require_once("views/product_view.phtml");
    }

    public function productAdd_view(){
     $brand = new products_model();
     $product = new categories_model();

     $data['brands'] = $brand->get_brands();
     $data['categories'] = $product-> get_subCategories();
     //echo "<pre>" .print_r($data['products'],1). "</pre>";
     //die();
     require_once "views/templates/header_template.phtml";

     include("views/productAdd_view.phtml");
    }

    public function insertProduct(){
      $product = new products_model();

      $product -> setName($_POST['name']);
      $product -> setStock($_POST['stock']);
      $product -> setPrice($_POST['price']);
      $product -> setSponsored($_POST['promotionedRadio']);
      $product -> setShortDescription($_POST['descShort']);
      $product -> setLongDescription($_POST['descLong']);
      $product -> setBrand($_POST['brand']);
      $product -> setCategory($_POST['subCategory']);

      $product -> insert_product();
    }


   function searchProduct($word) {

        $products = new products_model();
        $home = new home_controller();
        $cart = new cart_controller();
        $data['cart'] = $cart->shoppingCart();


        // /$data = array();
        $data['products'] = $products->get_product_searcher($word);
        $data['categories'] = $home->getCategories();

        include("views/templates/header_template.phtml");
        require_once("views/product_view.phtml");
    }


    function getProducts($subCategory) {

        $products = new products_model();
        return $products->get_products($subCategory);
    }

    function profileProduct($id){

      $product = new products_model();
      $id = $_GET['id'];
      $product = $product->getProductProfile($id);
      return $product;

    }


    function getCategories() {

        // Creamos el objeto de la clase categorias_model
        $categories = new categories_model();

        // llamamos a la funcion get_categories y guardamos en myCategories las categorias y subcategorias de la bd
        $myCategories = $categories->get_categories();
        // cremamos un array para guardar las categorias ordenadas (las categorías con sus subcategorías)
        $orderedCategories = array();


        // guarda en un array el id de la categoria principal y dentro de su array guarda el nombre y las subcategorias que pertenecen
        // a ésta en otro array.
        foreach ($myCategories as $dato) {

            $id = $dato["ID"];
            $name = $dato["NAME"];
            $parentCategory = $dato["PARENTCATEGORY"];

            // comprobamos si es categoria principal, si el array no existe lo crea y añade el nombre de la categoria dentro de ella.
            // Si es uina subcategoria, comprueba si la categoria principal existe, si no existe lo crea y guarda dentro de ella
            // su subcategoria con su nombre e ID

            if (empty($parentCategory)) {
                if (!array_key_exists($id, $orderedCategories)) {
                    $orderedCategories[$id] = array();
                }
                $orderedCategories[$id]['NAME'] = $name;
            } else {
                if (!array_key_exists($parentCategory, $orderedCategories)) {
                    $orderedCategories[$parentCategory] = array();
                }
                $orderedCategories[$parentCategory][] = [
                    'ID' => $id,
                    'NAME' => $name
                ];
            }
        }

        //echo "<pre>" .print_r($orderedCategories,1). "</pre>";
        //die();


        return $orderedCategories;
    }
    
   // public function pagination(){
        
//        $algo = new products_model();
//        $algo -> pagination(); 
//        
//    }

}

?>
