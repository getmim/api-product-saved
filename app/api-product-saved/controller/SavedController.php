<?php
/**
 * SavedController
 * @package api-product-saved
 * @version 0.0.1
 */

namespace ApiProductSaved\Controller;

use ProductSaved\Model\ProductSaved as PSaved;
use LibFormatter\Library\Formatter;
use LibForm\Library\Form;
use Product\Model\Product;

class SavedController extends \Api\Controller
{
    public function createAction(){
        if(!$this->user->isLogin())
            return $this->resp(401);

        $form = new Form('api.product-saved.create');

        if(!($valid = $form->validate()))
            return $this->resp(422, $form->getErrors());

        $product_id = $valid->product;

        $saved = PSaved::getOne([
            'user'    => $this->user->id,
            'product' => $product_id
        ]);

        if(!$saved){
            $saved_id = PSaved::create([
                'user'    => $this->user->id,
                'product' => $product_id
            ]);

            $saved = PSaved::getOne(['id'=>$saved_id]);
        }

        $product = Product::getOne(['id'=>$saved->product]);

        $fmt = ['user'];
        if(module_exists('product-category'))
            $fmt[] = 'category';
        if(module_exists('product-collateral'))
            $fmt[] = 'collateral';

        $product = Formatter::format('product', $product, $fmt);

        $this->resp(0, $product);
    }

    public function indexAction(){
        if(!$this->user->isLogin())
            return $this->resp(401);

        list($page, $rpp) = $this->req->getPager();

        $cond = [
            'user' => $this->user->id,
            'product.status' => 2
        ];

        $result = [];

        $saved = PSaved::get($cond, $rpp, $page, ['created'=>false]);
        if($saved){
            $products_id = array_column($saved, 'product');
            $products = Product::get(['id'=>$products_id]);
            if($products){
                $products = Formatter::formatMany('product', $products, ['user'], 'id');
                foreach($saved as $svd){
                    if(isset($products[$svd->product]))
                        $result[] = $products[$svd->product];
                }
            }
        }

        $this->resp(0, $result, null, [
            'meta' => [
                'page'  => $page,
                'rpp'   => $rpp,
                'total' => PSaved::count($cond)
            ]
        ]);
    }

    public function removeAction(){
        if(!$this->user->isLogin())
            return $this->resp(401);

        $identity = $this->req->param->identity;
        $product = Product::getOne([
            '$or' => [
                [ 'id'   => $identity ],
                [ 'slug' => $identity ]
            ]
        ]);

        if(!$product)
            return $this->show404();

        $saved = PSaved::getOne([
            'user' => $this->user->id,
            'product' => $product->id
        ]);

        if(!$saved)
            return $this->show404();

        PSaved::remove(['id'=>$saved->id]);

        $this->resp(0, 'success');
    }

    public function singleAction(){
        if(!$this->user->isLogin())
            return $this->resp(401);

        $identity = $this->req->param->identity;
        $product = Product::getOne([
            '$or' => [
                [ 'id'   => $identity ],
                [ 'slug' => $identity ]
            ]
        ]);

        if(!$product)
            return $this->show404();

        $saved = PSaved::getOne([
            'user' => $this->user->id,
            'product' => $product->id
        ]);

        if(!$saved)
            return $this->show404();

        $fmt = ['user'];
        if(module_exists('product-category'))
            $fmt[] = 'category';
        if(module_exists('product-collateral'))
            $fmt[] = 'collateral';

        $product = Formatter::format('product', $product, $fmt);

        $this->resp(0, $product);
    }

    public function truncateAction(){
        if(!$this->user->isLogin())
            return $this->resp(401);

        PSaved::remove(['user'=>$this->user->id]);

        $this->resp(0, 'success');
    }
}