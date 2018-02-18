<?php

class login_model {

    public $db;
    private $name;
    private $username;
    private $password;
    private $creationDate;
    private $email;
    private $address;
    private $postalCode;

    public function __construct() {
        $this->db = Conectar::conexion();
    }

    /* GETTERS & SETTERS */

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function getPostalCode() {
        return $this->postalcode;
    }

    public function setPostalCode($postalCode) {
        $this->postalCode = $postalCode;
    }

    /**
     * Extreu tots els usuaris de la taula
     * @return array Bidimensional de tots els usuaris de la taula
     */
    public function insert_user() {
      
        $cripted = crypt($this->password, '$4$rounds=5000$contraseña$');
        //die($encriptada);
        //comprobar que no haya ningún usuario con ese nombre de usuario antes de insertar.
        $sql = "SELECT USERNAME FROM `user` WHERE USERNAME = '{$this->username}'";

        $consulta = $this->db->query($sql);
        $repeatedUsername = $consulta->fetch_assoc();

        //var_dump($repeatedUsername);

        if ($repeatedUsername['USERNAME'] == null) {
            $sql2 = "INSERT INTO USER (USERNAME, PASSWORD, NAME,EMAIL,ADDRESS,POSTALCODE) VALUES ('{$this->username}','{$cripted}','{$this->name}','{$this->email}','{$this->address}','{$this->postalCode}');";

            $consulta = $this->db->query($sql2);
            if ($this->db->error)
                return "$consulta<br>{$this->db->error}";
            else {
                return false;
            }
        } elseif ($repeatedUsername['USERNAME'] != null) {
            return false;   
        }
    }

    public function verifyUser() {
        
        $cripted = crypt($this->password, '$4$rounds=5000$contraseña$');
        
        //die($encriptada);
        $consulta = "SELECT * FROM user WHERE USERNAME ='{$this->username}' AND PASSWORD = '{$cripted}';";
        $resultado = $this->db->query($consulta) or trigger_error(mysqli_error($this->db) . " " . $consulta);
        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                return true;
            }
        } else {
            return false;
        }
    }

}

?>
