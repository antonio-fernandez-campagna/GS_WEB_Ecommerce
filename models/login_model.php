<?php

class login_model{
  private $db;

  private $usuario;
  private $password;

  public function __construct(){
      $this->db=Conectar::conexion();
  }

  /* GETTERS & SETTERS */

  public function getUsuario() {
      return $this->usuario;
  }

  public function setUsuario($usuario) {
      $this->usuario = $usuario;
  }

  public function getPassword() {
      return $this->password;
  }

  public function setPassword($password) {
      $this->password = $password;
  }

  /**
  * Extreu tots els usuaris de la taula
  * @return array Bidimensional de tots els usuaris de la taula
  */
  public function verifyUser(){

      $encriptada = crypt($this->password,'$4$rounds=5000$contraseña$');
      //die($encriptada);

      $consulta= "SELECT * FROM user WHERE USERNAME ='{$this->usuario}' AND PASSWORD = '{$encriptada}';";

       $resultado = $this->db->query($consulta) or trigger_error(mysqli_error($this->db)." ".$consulta);
       if ($resultado->num_rows > 0) {
       while($row=$resultado->fetch_assoc()){
         return true;
       }
     } else {
         return false;
       }
  }

}

 ?>
