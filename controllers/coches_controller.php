<?php
//Llamada al modelo
require_once("models/coches_model.php");


class coches_controller {

  /**
   * Muestra pantalla 'add'
   * @return No
   */
  function add() {
    require_once("views/coches_add.phtml");
  }


/**
 * Mostra llistat
 * @return No
 */
  function view() {
    $coche=new coches_model();

    //Uso metodo del modelo de coches
    $datos=$coche->get_coches();

    //Llamado a la vista: mostrar la pantalla
    require_once("views/coches_view.phtml");
  }


/**
 * Inserta a la taula
 * @return No
 */
  function insert() {
      $coche = new coches_model();

      if (isset($_POST['insert'])) {
          $coche->setMarca ($_POST['marca']);
          $coche->setModelo ($_POST['modelo']);
          $coche->setFabricado ($_POST['fabricado']);

          $error = $coche->insertar();

          if (!$error) {
              header( "Location: index.php?controller=coches&action=view");
          }
          else {
              echo $error;
          }
      }
  }


/**
 * Elimina una fila de la taula
 * @return No
 */
  function delete() {
    if (isset($_GET['id'])) {
      $coche=new coches_model();

      $id = $_GET['id'];

      $error = $coche->delete($id);

      if (!$error) {
        header( "Location: index.php?controller=coches&action=view");
      }
      else {
          echo $error;
      }
    }
  }


/**
 * Mostra els cotxes ordenats per marca
 * @return No
 */
  function ordmarca() {
    $coche=new coches_model();

    //Uso metodo del modelo de coches
    $datos=$coche->ordmarca();

    //Llamado a la vista: mostrar la pantalla
    require_once("views/coches_view.phtml");
  }

}
?>
