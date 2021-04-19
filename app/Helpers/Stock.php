<?php

namespace App\Helpers;

use App\Models\Debt;
use App\Models\GoodsDeliveryNote;
use App\Models\GoodsReceivedNote;
use App\Models\Partner;
use App\Models\PoNhap;
use App\Models\PoXuat;
use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class Stock
{
    public static function updateQuantityOfProducts($note, $products)
    {
        $type = get_class($note) === 'App\Models\GoodsReceivedNote' ? 'R' : 'D';
        foreach ($products as $prod) {
            $note->products()->attach($prod['product_id'], [
                'quantity' => $prod['quantity'],
                'price' => $prod['price'],
                'cost_price' => $type === 'R' ? 0 : $prod['cost_price'],
            ]);
            $product = Product::find($prod['product_id']);

            if ($type === 'D') {
                if ($product->quantity < $prod['quantity']) {
                    throw new Exception("Sản phẩm $product->name chỉ còn $product->quantity $product->unit");
                }
            }
            $product->update([
                'quantity' => $type === 'R' ? $product->quantity + $prod['quantity'] : $product->quantity - $prod['quantity'],
            ]);
            if(isset($prod['po_no'])){
            if ($type === 'D') {
                $poxuat = PoXuat::where('po_no',$prod['po_no'])->where('product_id',$prod['product_id'])->first();
                $poxuat->po_pending = $poxuat->po_pending -$prod['quantity'];
                $poxuat->save();
            }else{
                $poxuat = PoNhap::where('po_no',$prod['po_no'])->where('product_id',$prod['product_id'])->first();
                $poxuat->po_pending = $poxuat->po_pending -$prod['quantity'];
                $poxuat->save();
            }
        }
        }
    }

    public static function restoreQuantityOfProducts($note)
    {
        $type = get_class($note) === 'App\Models\GoodsReceivedNote' ? 'R' : 'D';
        $products = $note->products;
        foreach ($products as $product) {
            $product->update([
                'quantity' => $type === 'R' ? $product->quantity - $product['pivot']['quantity'] : $product->quantity + $product['pivot']['quantity'],
            ]);
        }
    }
    public static function checkQuantity($note)
    { }

    public static function updateDept($note)
    {
        $type = get_class($note) === 'App\Models\GoodsReceivedNote' ? 'R' : 'D';
        if ($type == 'R') {
            $date = Carbon::parse($note->date);
            $month = $date->month;
            $year = $date->year;
            $partner_id = $note->partner_id;
            $dept_old = Debt::where('month', $month)->where('year', $year)->where('type', true)->where('reference_id', $partner_id)->first();
            $payment = 0;
            $date_payment = null;
            if (isset($dept_old)) {
                $payment = $dept_old->amount_payment;
                $date_payment = $dept_old->date_payment;
                $dept_old->delete();
            }
            $receiveds = GoodsReceivedNote::where('partner_id', $partner_id)->where('date', '>=', $date->startOfMonth()->format('Y-m-d'))->where('date', '<=', $date->endOfMonth()->format('Y-m-d'))->get();
            $amount=0;
            foreach ($receiveds as $received) {
                $amount = $amount + $received->products()->sum(DB::raw('productables.quantity * productables.price')) + ($received->products()->sum(DB::raw('productables.quantity * productables.price')))*$received->tax/100;
            }
            Debt::create([
                'type' => true,
                'reference_id' => $partner_id,
                'month' => $month,
                'year' => $year,
                'number_hd' => $note->document_number,
                'date_hd' => null,
                'duration' => 60,
                'type_money' => $note->unit,
                'number_tk' =>  $note->document_number,
                'exchange_rate' => $note->exchange_rate,
                'date_payment' => $date_payment,
                'amount_owed' =>  $amount,
                'amount_payment' => $payment,
            ]);
        } else {
            $date = Carbon::parse($note->date);
            $month = $date->month;
            $year = $date->year;
            $partner_id = $note->partner_id;
            $dept_old = Debt::where('month', $month)->where('year', $year)->where('type', false)->where('reference_id', $partner_id)->first();
            $payment = 0;
            $partner = Partner::find($partner_id);
            $date_payment = null;
            if (isset($dept_old)) {
                $payment = $dept_old->amount_payment;
                $date_payment = $dept_old->date_payment;
                $dept_old->delete();
            }
            $receiveds = GoodsDeliveryNote::where('partner_id', $partner_id)->where('date', '>=', $date->startOfMonth()->format('Y-m-d'))->where('date', '<=', $date->endOfMonth()->format('Y-m-d'))->get();
            $amount =0;
            foreach ($receiveds as $received) {
                $amount = $amount + $received->products()->sum(DB::raw('productables.quantity * productables.price')) + ($received->products()->sum(DB::raw('productables.quantity * productables.price')))*$received->tax/100;
            }
            Debt::create([
                'type' => false,
                'reference_id' => $partner_id,
                'month' => $month,
                'year' => $year,
                'number_hd' => $note->document_number,
                'date_hd' => null,
                'duration' => 60,
                'type_money' => $note->unit,
                'number_tk' =>  $note->document_number,
                'exchange_rate' => $note->exchange_rate,
                'date_payment' => $date_payment,
                'amount_owed' =>  $amount,
                'amount_payment' => $payment,
            ]);
        }
    }
}
