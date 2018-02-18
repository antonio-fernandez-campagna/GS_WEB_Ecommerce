<?php

require_once("../db/db.php");
require_once '../models/products_model.php';

$handleProducts = new products_model();

$allProducts = $handleProducts->get_product__short_descriptions();

echo json_encode($allProducts, 1);
