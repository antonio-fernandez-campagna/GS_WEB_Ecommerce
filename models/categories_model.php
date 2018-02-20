<?php
class categories_model{

public $db;
private $categories;

private $id;
private $name;
private $parentCategory;


public function __construct(){
    $this->db=Conectar::conexion();
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

public function getParentCategory() {
  return $this->$parentCategory;
}

public function setParentCategory($parentCategory) {
  $this->parentCategory = $parentCategory;
}



// Función que extrae todas las categorias
public function get_categories(){
    $consulta=$this->db->query("SELECT * FROM category;");


    while($filas=$consulta->fetch_assoc()){
        $this->categories[]=$filas;
    }
    return $this->categories;
}

// Función que inserta categorias
public function insert_category() {

    if ($this->parentCategory == 'NULL') {
      $sql = "INSERT INTO category (NAME ) VALUES ('{$this->name}')";
    }else {
     $sql = "INSERT INTO category (NAME, PARENTCATEGORY ) VALUES ('{$this->name}','{$this->parentCategory}')";
    }

     $result = $this->db->query($sql);
     if ($this->db->error)
         return "$sql<br>{$this->db->error}";
     else {
         return false;
     }
  }

  // Función que extrae las categorias padre
  public function get_parents_categories(){
    $consulta=$this->db->query("SELECT * FROM category WHERE PARENTCATEGORY IS NULL;");
    while($filas=$consulta->fetch_assoc()){
        $this->categoria[]=$filas;
    }
    return $this->categoria;
}

// Función que extrae las subcategorias
public function get_subCategories(){
  $consulta=$this->db->query("SELECT * FROM category WHERE PARENTCATEGORY IS NOT NULL;");
  while($filas=$consulta->fetch_assoc()){
      $this->categoria[]=$filas;
  }
  return $this->categoria;
}




}
?>
