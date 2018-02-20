<?php

require_once("../db/db.php");
require_once '../models/products_model.php';

$product = new products_model();

$allProducts = $product->get_product__short_descriptions();

// hace un echo del array de los productos que recojo en anteriormente en formato JSON
echo json_encode($allProducts, 1);
