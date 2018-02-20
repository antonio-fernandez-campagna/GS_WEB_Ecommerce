<?php

//Llamada al modelo
require_once("models/categories_model.php");


// clase para las categorias, lista añade 
class categories_controller {


    // Función que llama a la vista de añadir categorias
    public function categoryAdd_view() {
        
        $categories = new categories_model();
        $data = $categories->get_parents_categories();

        require_once "views/templates/header_template.phtml";

        include("views/categoryAdd_view.phtml");
    }

    
    // Función que inserta categorias en la BD
    public function insert_category() {
        $categories = new categories_model();

        $conexion = $categoies->db;

        // comprobaciíon de mysql injection
        $categoryName = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $categoryParent = mysqli_real_escape_string($conexion, $_POST['parentcategory']);

        $categories->setName($categoryName);
        $categories->setParentCategory($categoryParent);

        $error = $categories->insert_category();
   
    }

    // Función que muestra las categorias
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
