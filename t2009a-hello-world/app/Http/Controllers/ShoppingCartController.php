<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use stdClass;
use function Psy\sh;

class ShoppingCartController extends Controller
{
    public static $menu_parent = 'shopping-cart';
    //Thêm sản phẩm vào giỏ hàng
    public function add(Request $request){
        //Lấy thông tin sản phẩm
        $productId = $request->get('id');
        //Lấy số lg sp cần cho vào giỏ hàng
        $productQuantity = $request->get('quantity');
        if ($productQuantity <= 0){
            return view('admin.errors.404', [
                'msg' => 'Số lượng sản phẩm cần lớn hơn 0.',
                'menu_parent' => self::$menu_parent,
                'menu_action' => 'create'
            ]);
        }
        //1.Kt sự tồn tại của giỏ hàng
        $obj = Product::find($productId);
        //Nếu ko tồn tại trả về 404
        if ($obj == null){
            return view('admin.errors.404', [
                'msg' => 'Không tìm thấy sản phẩm.',
                'menu_parent' => self::$menu_parent,
                'menu_action' => 'create'
            ]);
        }
        $shoppingCart = null;
        if (Session::has('shoppingCart')){
            $shoppingCart = Session::get('shoppingCart');
        }else{
            $shoppingCart = [];
        }
        if (array_key_exists($productId, $shoppingCart)){
            $existingCartItem = $shoppingCart[$productId];
            $existingCartItem ->quantity += $productQuantity;
            $shoppingCart[$productId] = $existingCartItem;
        }else{
            $cartitem = new stdClass();
            $cartitem-> id = $obj -> id;
            $cartitem-> name = $obj -> name;
            $cartitem-> unitPrice = $obj -> price;
            $cartitem-> quantity = $productQuantity;
            $shoppingCart[$productId] = $cartitem;
        }
        Session::put('shoppingCart', $shoppingCart);
        return redirect('/cart/show');
    }
    public function show(){
        if (Session::has('shoppingCart')){
            $shoppingCart = Session::get('shoppingCart');
        }else{
            $shoppingCart = [];
        }
        return view('cart', [
            'shoppingCart' => $shoppingCart
        ]);
    }
    public function update(Request $request){
        //Lấy thông tin sản phẩm
        $productId = $request->get('id');
        //Lấy số lg sp cần cho vào giỏ hàng
        $productQuantity = $request->get('quantity');
        if ($productQuantity <= 0){
            return view('admin.errors.404', [
                'msg' => 'Số lượng sản phẩm cần lớn hơn 0.',
                'menu_parent' => self::$menu_parent,
                'menu_action' => 'create'
            ]);
        }
        $obj = Product::find($productId);
        if ($obj == null){
            return view('admin.errors.404', [
                'msg' => 'Không tìm thấy sản phẩm.',
                'menu_parent' => self::$menu_parent,
                'menu_action' => 'create'
            ]);
        }
        $shoppingCart = null;
        if (Session::has('shoppingCart')){
            $shoppingCart = Session::get('shoppingCart');
        }else{
            $shoppingCart = [];
        }
        if (array_key_exists($productId, $shoppingCart)){
            $existingCartItem = $shoppingCart[$productId];
            $existingCartItem->quantity = $productQuantity;
            $shoppingCart[$productId] = $existingCartItem;
        }
        Session::put('shoppingCart', $shoppingCart);
        return redirect('/cart/show');
    }
    public function delete(Request $request){
        $productId = $request->get('id');
        $shoppingCart = null;
        if (Session::has('shoppingCart')){
            $shoppingCart = Session::get('shoppingCart');
        }else{
            $shoppingCart = [];
        }
        unset($shoppingCart[$productId]);
        Session::put('shoppingCart', $shoppingCart);
        return redirect('/cart/show');
    }
}
