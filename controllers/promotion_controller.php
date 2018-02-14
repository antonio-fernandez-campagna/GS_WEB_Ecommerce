<?php
//Llamada al modelo
require_once("models/promotions_model.php");
require_once("models/products_model.php");


class promotion_controller {
/**
 * Inserta a la taula
 */

  function insert_promotion() {
      $promotion = new promotions_model();
      $promotion->setProduct($_POST['product']);

      $promotion->setDiscountPercentage($_POST['discount']);
      $promotion->setEndDate($_POST['fechaFinal']);

      $promotion->insert_promotion();
  }

  public function promotionAdd_view(){

    $products = new products_model();
    $data = $products->get_all_products();

    require_once "views/templates/header_template.phtml";

    include("views/promotionAdd_view.phtml");
  }

}
?>
