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



/**
* Extreu totes les persones de la taula
* @return array Bidimensional de totes les persones
*/
public function get_categories(){
    $consulta=$this->db->query("SELECT * FROM category;");


    while($filas=$consulta->fetch_assoc()){
        $this->categories[]=$filas;
    }
    return $this->categories;
}

public function lista_categorias(){
    $consulta=$this->db->query("SELECT * FROM category;");


    while($filas=$consulta->fetch_assoc()){
        $this->categories[]=$filas;
    }
    return $this->categories;
}

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

  public function get_parents_categories(){
    $consulta=$this->db->query("SELECT * FROM category WHERE PARENTCATEGORY IS NULL;");
    while($filas=$consulta->fetch_assoc()){
        $this->categoria[]=$filas;
    }
    return $this->categoria;
}

public function get_subCategories(){
  $consulta=$this->db->query("SELECT * FROM category WHERE PARENTCATEGORY IS NOT NULL;");
  while($filas=$consulta->fetch_assoc()){
      $this->categoria[]=$filas;
  }
  return $this->categoria;
}




}
?>
