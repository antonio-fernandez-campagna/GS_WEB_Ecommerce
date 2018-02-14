<?php

//Llamada al modelo
require_once("models/categories_model.php");

class categories_controller {
    /**
     * Muestra pantalla 'add'
     * @return No
     */
    /*
      function add() {
      require_once("views/products_add.phtml");
      }

     */

    /**
     * Mostra llistat
     * @return No
     */
    /*
      function view() {
      $categories = new categories_model();

      //Uso metodo del modelo de personas
      $datos=$categories->get_categories();

      //Llamado a la vista: mostrar la pantalla
      require_once("views/home_view.phtml");
      }
     * */

     public function categoryAdd_view(){
       //Mostramos el default header
       //$category = new categories_controller();
       //$cart = new cart_controller();
       //$data['cart'] = $cart->shoppingCart();
      // $data['categories'] = $category->getCategories();
      $categories = new categories_model();
      $data = $categories-> get_parents_categories();

       require_once "views/templates/header_template.phtml";

       include("views/categoryAdd_view.phtml");
     }

    public function insert_category() {
       $categories = new categories_model();
        $categories->setName($_POST['nombre']);
        $categories->setParentCategory($_POST['parentcategory']);
        //echo "<pre>".print_r($categories,1). "</pre>";
        //die;
        $error = $categories->insert_category();
      //  print_r("hola" .$error);
        //die();
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
