<?php
require_once("models/categories_model.php");
require_once("models/products_model.php");
require_once("models/promotions_model.php");


class home_controller {

    function view() {
        $promo = new promotions_model();

        //$data = array();
        $data['products'] = $this->getProducts();
        $data['categories'] = $this->getCategories();
        //echo "<pre>" .print_r($data['products'],1). "</pre>";
        //die;

        $data['promo'] = $promo->get_promos();

        require_once("views/home_view.phtml");
    }

    function getProducts() {

        $products = new products_model();
        return $products->get_products();
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



}
?>
