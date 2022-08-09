<?php

namespace App\Http\Controllers\API;

use Midtrans\Config;
use Midtrans\Notification;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MidtransController extends Controller
{
    public function callback(Request $request){
        //set konfigurasi midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        //buat instance midtrans notifikasi

        $notification = new Notification();


        //Assign ke variable untuk memudahkan coding
        $status = $notification->transaction_status;
        $type = $notification->payment_type;
        $fraud = $notification->fraud_status;
        $order_id = $notification->order_id;

        //Cari transaksi berdasarkan id

        $transaction = Transaction::findOrFail($order_id);
        //handle notifikasi status midtrans
        if($status == 'capture'){

            if($type == 'credit_card'){  

                if ($fraud == 'challenge')
                {
                    $transaction->status = 'PENDING'; 
                }
                else{
                    $transaction->status = 'SUCCESS';
                }

            } 

        }
        elseif($status == 'settlement'){
            $transaction->status = 'SUCCESS';
        }
        elseif($status == 'pending'){
            $transaction->status = 'PENDING';
        }
        elseif($status == 'deny'){
            $transaction->status = 'CANCELED';

        }elseif($status == 'expire'){
            $transaction->status = 'CANCELED';

        
        }elseif($status == 'cancel'){
            $transaction->status = 'CANCELED';        
        }
            
        //simpan transaksi ke database
             $transaction->save();

    }


    public function success(){
        return view('midtrans.success');
    }
    public function unfinish(){
        return view('midtrans.unfinish');
    }
    public function error(){
        return view('midtrans.error');
    }
}
