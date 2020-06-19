<?php

namespace App\Http\Resources;


use App\Product;
use http\Env\Response;
use Illuminate\Http\Request;

class PrintReceipt{

    private $filename="";
    private $request;
    /**
     * Set the size of text, as a multiple of the normal size.
     *
     * @param string $TNumber Transaction Number
     * @param Request $request Comfirm sale request object
     */
    public function __construct($TNumber,Request $request)
    {
        $this->filename = "receipt/$TNumber.txt";
        $this->request = $request;

    }

    function __toString()
    {
//        try{
            $file = fopen($this->filename,'w');
            fwrite($file,str_pad('RonalTech', 17, ' ', STR_PAD_LEFT)."\n");
            fwrite($file,new item('Kalla','23',true));

            for ($i=0;$i<sizeof($this->request->name);$i++){
                $product = Product::whereSlug($this->request->slug[$i])->first();
                $amount = $product->price*$this->request->quantity[$i];
                fwrite($file,new item(substr($product->name,0,7),number_format($amount),true));
            }
            fclose($file);


//        }catch (Exception $e){
//            $this->filename = '';
//        }
        return $this->filename;
    }

//    public function __construct()
//    {
//        try{
//            $file = fopen("receipt/1234.txt",'w');
//            fwrite($file,str_pad('RonalTech', 20, ' ', STR_PAD_LEFT)."\n");
//            fwrite($file,new item('Kalla',number_format('25000'),true));
//            fwrite($file,new item('Arnold','150000',true));
//            fwrite($file,new item('Prisca','89000',true));
//            fclose($file);
//            $this->filename = "receipt/1234.txt";
//        }catch (Exception $e){
//            return $e;
//        }
//    }
}


