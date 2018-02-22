<?php

//Llamada al modelo
require_once("models/products_model.php");
require_once("controllers/home_controller.php");

// clase que controla aádir productos, la vista de productos (la del buscador o por subcategorias), y se mostrarán las categorias
class products_controller {

    // Función muestra la vista de product_view.phtml
    // se le pasa el id de la subcategoria y muestra los productos por esta subcategoria
    function view($subCategory) {

        $home = new home_controller();

        $number_of_pages = $home->manyPages();


        $data['products'] = $this->getProducts($subCategory);

        $data['categories'] = $this->getCategories();

        $cart = new cart_controller();
        $data['cart'] = $cart->shoppingCart();

        $brand = new products_model();
        $data['brands'] = $brand->get_brands($subCategory);

        $category = new categories_controller();
        include("views/templates/header_template.phtml");

        require_once("views/product_view.phtml");
    }

    // función que muestra la página de añadir producto
    public function productAdd_view() {
        $brand = new products_model();
        $product = new categories_model();

        $data['brands'] = $brand->get_brands();
        $data['categories'] = $product->get_subCategories();
        require_once "views/templates/header_template.phtml";

        include("views/productAdd_view.phtml");
    }

    // función que inserta productos y controla sql injection
    public function insertProduct() {
        $product = new products_model();

        $conexion = $product->db;

        $name = mysqli_real_escape_string($conexion, $_POST['name']);
        $stock = mysqli_real_escape_string($conexion, $_POST['stock']);
        $price = mysqli_real_escape_string($conexion, $_POST['price']);
        $promotioned = mysqli_real_escape_string($conexion, $_POST['promotionedRadio']);
        $descS = mysqli_real_escape_string($conexion, $_POST['descShort']);
        $descL = mysqli_real_escape_string($conexion, $_POST['descLong']);
        $brand = mysqli_real_escape_string($conexion, $_POST['brand']);
        $category = mysqli_real_escape_string($conexion, $_POST['subCategory']);

        $product->setName($name);
        $product->setStock($stock);
        $product->setPrice($price);
        $product->setSponsored($promotioned);
        $product->setShortDescription($descS);
        $product->setLongDescription($descL);
        $product->setBrand($brand);
        $product->setCategory($category);

        $product->insert_product();
    }

    // función para buscar producto por palabra
    function searchProduct($word) {

        $products = new products_model();
        $home = new home_controller();
        $cart = new cart_controller();
        $data['cart'] = $cart->shoppingCart();


        $data['products'] = $products->get_product_searcher($word);
        $data['categories'] = $home->getCategories();

        include("views/templates/header_template.phtml");
        require_once("views/product_view.phtml");
    }

    // Función para devolver los productos de una subcategoria pasada por parámetro
    function getProducts($subCategory) {

        $products = new products_model();
        return $products->get_products($subCategory);
    }

    //Función para mostrar categorias
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

        return $orderedCategories;
    }

}

?>
