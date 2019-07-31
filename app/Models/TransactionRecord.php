<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TransactionRecord extends Model
{  
    protected $table = 'transaction_record';
    
    public function addTransactionRecord($details_id,$val)
    {
      
        $obj = new TransactionRecord();
        $obj->title = $val->title;
        $obj->description = $val->description;
        $obj->price = $val->price;
        $obj->details_id = $details_id;
        $obj->save();
               
    }
    
    
    
    
    public function editTransactionRecord($val)
    {
      
        $obj = TransactionRecord::find($val->record_id);
        
        $val = $val->transactionRecord;
        
        if(isset($val->title) and trim($val->title) !==''){
            $obj->title = $val->title;
        }
        
        if(isset($obj->description) and trim($obj->description) !=='')
        {
            $obj->description = $val->description;
        }
        
        if(isset($val->price) and trim($val->price) !=='')
        {
            $obj->price = $val->price;
        }
     
        $obj->save();
               
    }
}
