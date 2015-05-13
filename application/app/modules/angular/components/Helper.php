<?php

namespace app\angular\components;

use app\api\models\master\Product;
use app\api\models\master\ProductUom;
use app\api\models\master\Uom;
use app\api\models\master\ProductChild;
use app\api\models\master\PriceCategory;
use app\api\models\master\Price;
use app\api\models\master\Customer;
use app\api\models\master\Supplier;
use app\api\models\master\ProductSupplier;
use app\api\models\master\ProductStock;
use app\api\models\accounting\Coa;

/**
 * Helper
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Helper
{

    public static function getMasters($masters)
    {

        $masters = array_flip($masters);
        $result = [];

        // master product
        if (isset($masters['products'])) {
            $query_product = Product::find()
                ->select(['id', 'code', 'name'])
                ->andWhere(['status' => Product::STATUS_ACTIVE])
                ->indexBy('id')
                ->asArray();

            $result['products'] = $query_product->all();
        }

        // uoms
        if (isset($masters['uoms'])) {
            $_uoms = Uom::find()->indexBy('id')->asArray()->all();
            $uoms = [];
            foreach (ProductUom::find()->asArray()->all() as $row) {
                $uoms[$row['product_id']][] = [
                    'id' => $row['uom_id'],
                    'name' => $_uoms[$row['uom_id']]['name'],
                    'isi' => $row['isi']
                ];
            }
            $result['uoms'] = $uoms;
        }
        // barcodes
        if (isset($masters['barcodes'])) {
            $barcodes = [];
            $query_barcode = ProductChild::find()
                ->select(['barcode' => 'lower(barcode)', 'id' => 'product_id'])
                ->union(Product::find()->select(['lower(code)', 'id']))
                ->asArray();
            foreach ($query_barcode->all() as $row) {
                $barcodes[$row['barcode']] = $row['id'];
            }
            $result['barcodes'] = $barcodes;
        }

        // price_category
        if (isset($masters['price_category'])) {
            $price_category = [];
            $query_price_category = PriceCategory::find()->asArray();
            foreach ($query_price_category->all() as $row) {
                $price_category[$row['id']] = $row['name'];
            }
            $result['price_category'] = $price_category;
        }

        // prices
        if (isset($masters['prices'])) {
            $prices = [];
            $query_prices = Price::find()->asArray();
            foreach ($query_prices->all() as $row) {
                $prices[$row['product_id']][$row['price_category_id']] = $row['price'];
            }
            $result['prices'] = $prices;
        }

        // customer
        if (isset($masters['customers'])) {
            $result['customers'] = Customer::find()
                    ->select(['id', 'name'])
                    ->asArray()->all();
        }

        // supplier
        if (isset($masters['suppliers'])) {
            $result['suppliers'] = Supplier::find()
                    ->select(['id', 'name'])
                    ->asArray()->all();
        }

        // product_supplier
        if (isset($masters['product_supplier'])) {
            $prod_supp = [];
            $query_prod_supp = ProductSupplier::find()
                ->select(['supplier_id', 'product_id'])
                ->asArray();
            foreach ($query_prod_supp->all() as $row) {
                $prod_supp[$row['supplier_id']][] = $row['product_id'];
            }
            $result['product_supplier'] = $prod_supp;
        }

        // product_stock
        if (isset($masters['product_stock'])) {
            $prod_stock = [];
            $query_prod_stock = ProductStock::find()
                ->select(['warehouse_id', 'product_id'])
                ->asArray();
            foreach ($query_prod_stock->all() as $row) {
                $prod_stock[$row['warehouse_id']][$row['product_id']] = $row['qty'];
            }
            $result['product_stock'] = $prod_stock;
        }

        // supplier
        if (isset($masters['coa'])) {
            $result['coa'] = Coa::find()
                    ->select(['id', 'cd' => 'code', 'text' => 'name', 'label' => 'name'])
                    ->asArray()->all();
        }
        return $result;
    }

    public static function getProducts()
    {
        $query_product = Product::find()
            ->select(['id', 'code', 'name'])
            ->andWhere(['status' => Product::STATUS_ACTIVE])
            ->indexBy('id')
            ->orderBy('id')
            ->asArray();

        return $query_product->all();
    }

    public static function getBarcodes()
    {
        $barcodes = [];
        $query_barcode = ProductChild::find()
            ->select(['barcode' => 'lower(barcode)', 'id' => 'product_id'])
            ->union(Product::find()->select(['lower(code)', 'id']))
            ->asArray();
        foreach ($query_barcode->all() as $row) {
            $barcodes[$row['barcode']] = $row['id'];
        }
        return $barcodes;
    }

    public static function getSuppliers()
    {
        return Supplier::find()
                ->select(['id', 'name'])
                ->asArray()->all();
    }

    public static function getProductUoms()
    {
        $_uoms = Uom::find()->indexBy('id')->asArray()->all();
        $uoms = [];
        foreach (ProductUom::find()->asArray()->all() as $row) {
            $uoms[$row['product_id']][] = [
                'id' => $row['uom_id'],
                'name' => $_uoms[$row['uom_id']]['name'],
                'isi' => $row['isi']
            ];
        }
        return $uoms;
    }
}