<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ProductsModel;



class ProductsController extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        return $this->respond([
            "status" => true,
            "data"   => "View product",
            "msg"    => "View product"
        ]);
    }

    public function renderProductPage()
    {
        return $this->respond([
            "status" => true,
            "data"   => "View ProductPage",
            "msg"    => "View ProductPage"
        ]);
    }

    public function AddProduct()
    {
        $data = $this->request->getPost();

        $name      = $data['name'] ?? null;
        $introduce = $data['introduce'];
        $price     = $data['price'] ?? null;
        $quantity  = $data['quantity'] ?? null;

        if($name === null || $price === null || $quantity === null) {
            return $this->fail("需輸入商品完整資訊", 404);
        }

        if($name === " " || $price === " " || $quantity === " ") {
            return $this->fail("需輸入商品完整資訊", 404);
        }


        $productsModel = new ProductsModel();

        $values = [
            'p_name'      =>  $name,
            'p_introduce' =>  $introduce,
            'p_price'     =>  $price,
            'p_quantity'  =>  $quantity,
        ];
        $productsModel->insert($values);

        return $this->respond([
            "status" => true,
            "data"   => $values,
            "msg"    => "Add new product"
        ]);
    }
}