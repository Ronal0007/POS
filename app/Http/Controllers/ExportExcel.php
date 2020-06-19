<?php

namespace App\Http\Controllers;

use App\Http\Resources\PrintReceipt;
use App\User;
use Maatwebsite\Excel\Facades\Excel;

class ExportExcel extends Controller
{
    function excel(){


        $users = User::all();
        $user_array[] = array('Name','Role','Contact','Status','Created','Updated');

        foreach ($users as $user) {
            $user_array[] = array(
                'Name' => $user->name,
                'Role' => $user->role->name,
                'Contact' => $user->contact,
                'Status'=> $user->isActive?'Active':'Not Active',
                'Created' => $user->created_at->diffForHumans(),
                'Updated' => $user->updated_at->diffForHumans()
            );
        }

        Excel::create('System Users',function ($excel) use ($user_array){
            $excel->setTitle('User data');
            $excel->sheet('User sheet 1',function ($sheet) use ($user_array){
                $sheet->fromArray($user_array,null,'A1',false,false);
            });
        })->download('xlsx');
    }

    function index(){
      return new PrintReceipt();
    }

}
