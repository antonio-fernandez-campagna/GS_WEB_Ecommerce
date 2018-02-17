<?php

class personas_model{

private $db;
private $personas;

private $id;
private $nombre;
private $edad;

public function __construct(){
$this->db = Conectar::conexion();
$this->personas = array();
}

/* GETTERS & SETTERS */

public function getId() {
return $this->id;
}

public function setId($id) {
$this->id = $id;
}

public function getNombre() {
return $this->nombre;
}

public function setNombre($nombre) {
$this->nombre = $nombre;
}

public function getEdad() {
return $this->edad;
}

public function setEdad($edad) {
$this->edad = $edad;
}



/**
 * Inserta un registre a la taula
 * @return [false]  si no hi ha hagut cap error,
 *         [string] amb text d'error si no ha anat bé
 */
public function insertar() {

$sql = "INSERT INTO personas (nombre, edad) VALUES ('{$this->nombre}','{$this->edad}')";
$result = $this->db->query($sql);

if ($this->db->error)
return "$sql<br>{$this->db->error}";
else {
return false;
}
}

public function searcher(){

$consulta = $this->db->query("select * from personas;");
while($filas = $consulta->fetch_assoc()){
$this->personas[] = $filas;
}

return $this->personas;

}


/**
 * Esborra un registre de la taula
 * @param  int $id Identificador del registre
 * @return [false]  si no hi ha hagut cap error,
 *         [string] amb text d'error si no ha anat bé
 */
public function delete($id) {
$sql = "DELETE FROM personas WHERE id='$id'";

$result = $this->db->query($sql);

$consulta = $this->db->query($query);
while ($filas = $consulta->fetch_assoc()) {
$this->products[] = $filas;
}
$_SESSION['cart'] = [];

return $this->products;
}
}


}
?>
