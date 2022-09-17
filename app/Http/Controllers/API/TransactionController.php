<?php

namespace App\Http\Controllers\API;

use Exception;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function all(Request $request){

        $id = $request->input('id');
        $limit = $request->input('limit');
        $status = $request->input('status');

        if ($id){
            $transaction = Transaction::with(['items.food',])->find($id);
            if ($transaction){
                return $this->sendResponse($transaction,'Data transaksi berhasil diambil');
            }
            return $this->sendError(null , 'Data transaksi tidak ada', 404);
        }


        $transaction = Transaction::with(['items.food',])->where('users_id', Auth::user()->id);

        if($status)
          {
            $transaction->where('status', $status);
         }
         return $this->sendResponse($transaction->paginate($limit),'Data transaksi berhasil diambil');
    }

    public function checkout(Request $request){
        
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'exists:foods,id',
            'total_price' => 'required',
            'shipping_price' => 'required',
            'status' => 'required|in:PENDING,SUCCESS,CANCELED,FAILED,SHIPPING,SHIPPED',
        ]);
        
        $transaction = Transaction::create([
            'users_id' => Auth::user()->id,
            'address' => $request->address,
            'total_price' => $request->total_price,
            'shipping_price' => $request->shipping_price,
            'payment_url' => '',
            'status' => $request->status,
        ]);

        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        foreach ($request->items as $food) {
            TransactionItem::create([
                'users_id' => Auth::user()->id,
                'food_id' => $food['id'],
                'transactions_id' => $transaction->id,
                'quantity' => $food['quantity'],
            ]);
        }
         $midtrans = [
            'transaction_details' =>[
                'order_id' => $transaction->id,
                'gross_amount' => (int) $transaction->total,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'item_details' => [
                'id' => $transaction->id,
                'price' => (int) $transaction->total,
                'quantity' => 1,
                'name' => 'Foods',
            ],
            'enabled_payments' => ['gopay', 'bank_transfer'],
            'vtweb' => []
        ];
        
        try {
            //ambil halaman  payment midtrans
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;
            $transaction->payment_url = $paymentUrl;
            $transaction->save();
            return $this->sendsuccess($transaction,'Transaksi berhasil');
        }
        catch (Exception $e){
            return $this->senderror($e->getMessage(), 'Transaksi gagal');
        }
         


        return $this->sendsuccess($transaction->load('items.food'), 'Transaksi berhasil ');
    }
}
