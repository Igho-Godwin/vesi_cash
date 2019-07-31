<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TransactionDetails extends Model
{  
    protected $table = 'transaction_detail';
    
   function addTransactionDetails($val)
    {
        $obj = new TransactionDetails();
        $obj->sender_email = $val->sender->email;
        $obj->sender_phone_no = $val->sender->phone;
        $obj->receipient_email = $val->receipient->email;
        $obj->receipient_phone_no = $val->receipient->phone;
        $obj->save();
        
        $details_id = TransactionDetails::orderBy('id','desc')
                                        ->first()->id;
       
        return $details_id;
       
    }
    
    
    public function editTransactionDetails($val)
    {
        $obj = TransactionDetails::find($val->details_id);
        
        if(isset($val->sender->email) and trim($val->sender->email)!=='')
        {
            $obj->sender_email = $val->sender->email;
        }
        
        if(isset($obj->sender_phone_no) and trim($obj->sender_phone_no)!=='')
        {
            $obj->sender_phone_no = $val->sender->phone;
        }
        
        if(isset($val->receipient->email) and trim($val->receipient->email)!=='')
        {
            $obj->receipient_email = $val->receipient->email;
        }
        
        if(isset($obj->receipient_phone_no) and trim($obj->receipient_phone_no)!=='')
        {
            $obj->receipient_phone_no = $val->receipient->phone;
        }
        
        $obj->save();
        
        
    }
    
    public function deleteTransactionDetails($val)
    {
      
        $obj = TransactionDetails::find($val->details_id);
        $obj->status='1';
        $obj->save();
           
    }
}
