<?php


namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;
use PHPUnit\Framework\MockObject\ReturnValueNotConfiguredException;

class CartManagement{
    // Add Item
    static public function addItemToCart($products_id){
        $cart_items = self::getCartItemsFromCookie();
        $existing_item = null;

        // Periksa apakah produk sudah ada di keranjang
        foreach($cart_items as $key => $item){
            if($item['id'] == $products_id){
                $existing_item = $key;
                break;
            }
        }

        if($existing_item !== null){
            // Jika item sudah ada, tingkatkan kuantitasnya
            $cart_items[$existing_item]['quantity']++;
            $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] * $cart_items[$existing_item]['unit_amount'];
        } else {
            // Jika item belum ada, tambahkan sebagai item baru
            $product = Product::where('id', $products_id)->first(['id','name', 'price','images']);
            if($product){
                $cart_items[] = [
                    'id' => $products_id,
                    'name' => $product->name,
                    'images' => $product->images[0],
                    'quantity' => 1,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price,
                ];
            }
        }

        self::addCartItemsToCookie($cart_items);
        return count($cart_items); // Mengembalikan jumlah item dalam keranjang
    }
    // add item to cart with quantity
    static public function addItemToCartWithQty($products_id, $qty = 1){
        $cart_items = self::getCartItemsFromCookie();
        $existing_item = null;

        // Periksa apakah produk sudah ada di keranjang
        foreach($cart_items as $key => $item){
            if($item['id'] == $products_id){
                $existing_item = $key;
                break;
            }
        }

        if($existing_item !== null){
            // Jika item sudah ada, tingkatkan kuantitasnya
            $cart_items[$existing_item]['quantity'] = $qty;
            $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] * $cart_items[$existing_item]['unit_amount'];
        } else {
            // Jika item belum ada, tambahkan sebagai item baru
            $product = Product::where('id', $products_id)->first(['id','name', 'price','images']);
            if($product){
                $cart_items[] = [
                    'id' => $products_id,
                    'name' => $product->name,
                    'images' => $product->images[0],
                    'quantity' => $qty,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price,
                ];
            }
        }

        self::addCartItemsToCookie($cart_items);
        return count($cart_items); // Mengembalikan jumlah item dalam keranjang
    }

    

    // Remove Item
    static public function removeCartItem($product_id){
        $cart_items = self::getCartItemsFromCookie();

        foreach($cart_items as $key => $item){
            if($item['id'] == $product_id){
                unset($cart_items[$key]);
                break;
            }
        }

        // Reindex array setelah menghapus item
        $cart_items = array_values($cart_items);
        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    // Add Items to Cookie
    static public function addCartItemsToCookie($cart_items){
        Cookie::queue('cart_items', json_encode($cart_items), 60*24*30);
    }

    // Clear Cart Items from Cookie
    static public function clearCartItems(){
        Cookie::queue(Cookie::forget('cart_items'));
    }

    // Get Cart Items from Cookie
    static public function getCartItemsFromCookie(){
        $cart_items = json_decode(Cookie::get('cart_items'), true);
        if(!is_array($cart_items)){
            $cart_items = [];
        }
        return $cart_items;
    }

    // Increment Item Quantity
    static public function incrementQuantityToCartItem($product_id){
        $cart_items = self::getCartItemsFromCookie();

        foreach($cart_items as $key => $item) {
            if($item['id'] == $product_id){
                $cart_items[$key]['quantity']++;
                $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_amount'];
                break;
            }
        }

        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    // Decrement Item Quantity
    static public function decrementQuantityToCartItem($product_id){
        $cart_items = self::getCartItemsFromCookie();

        foreach($cart_items as $key => $item) {
            if($item['id'] == $product_id){
                if($cart_items[$key]['quantity'] > 1) {
                    $cart_items[$key]['quantity']--;
                    $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_amount'];
                }
                break;
            }
        }

        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    // Calculate Grand Total
    static public function calculateGrandTotal($items){
        return array_sum(array_column($items, 'total_amount'));
    }
}


