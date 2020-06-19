<?php

namespace App\Listeners;

use App\Http\Resources\FormatItem;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class PrintReceiptListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        try{
            $file = fopen('receipt/12346.txt','w');
            fwrite($file,json_encode($event));
//            fwrite($file,str_pad('RonalTech', 17, ' ', STR_PAD_LEFT)."\n");
//            fwrite($file,new FormatItem('Kalla','23',true));

//            for ($i=0;$i<sizeof($this->request->name);$i++){
//                $product = Product::whereSlug($this->request->slug[$i])->first();
//                $amount = $product->price*$this->request->quantity[$i];
//                fwrite($file,new FormatItem(substr($product->name,0,7),number_format($amount),true));
//            }
            fclose($file);
        }catch (\Exception $e){
            Log::log(null,'Error printing receipt No. '.$event->transaction->number);
        }
    }
}
