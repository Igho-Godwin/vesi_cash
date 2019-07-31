<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\TransactionDetails;
use App\Models\TransactionRecord;
use Validator;


class TransactionController extends BaseController
{
    // Adds a Transaction
    function addTransaction($api_url)
    {
        
        if(file_get_contents($api_url) == '')
        {
            return json_encode(["failed"=>True]);
        }
        
        $data =json_decode(file_get_contents($api_url));// access url data
        
        $transaction_details_model = new TransactionDetails();
        
        foreach($data as $val)
        {    
            
            $validator = $this->validateTransactionDetails($val);
            
            if($validator->fails()){
                
               
                return response()->json(['error'=>$validator->errors(),'failed'=>True]);
            
            }
            
             
            $details_id = $this->addTransactionDetails($val);
            
            foreach($val->transactionRecord as $val2)
            {
                
                $validator = $this->validateTransactionRecord($val2);
                
                if($validator->fails()){
                     
                    $transaction_details_model->deleteTransactionDetails($details_id);
                    return response()->json(['error'=>$validator->errors(),'failed'=>True]);   
                }
                
                $this->addTransactionRecord($details_id,$val2);
            }
                        
        }
        
        return json_encode(["success"=>True]);
        
    }
    
    // Validate transaction Details
    
    function validateTransactionDetails($val)
    {
   
        
         $values = [
             
                     'sender_email' => $val->sender->email,
             
                     'sender_phone' => $val->sender->phone,
             
                     'receipient_email' =>  $val->receipient->email,
             
                     'receipient_phone' =>  $val->receipient->phone,
                     
             
                   ];
        
             $details_id = '';
        
             $data = TransactionDetails::where('sender_email',$val->sender->email)
                                        ->where('status','1')
                                        ->orderBy('id','desc')->first();
        
             if($data != null)
             {
                 $details_id = $data->id;
             }
  
         $rules = [
            
                'sender_email' => 'required|max:100|unique:transaction_detail,sender_email,'.$details_id.'|email',
             
                'sender_phone' => 'required|max:100',
             
                'receipient_email' => 'required|max:100|unique:transaction_detail,receipient_email,'.$details_id.'|email',
             
                'receipient_phone' => 'required|max:100',
                
            
         ];
        
         
        
        
        
        return  Validator::make($values,$rules);
        
        
    }
    
    // Validate Record like title and price
    
    function validateTransactionRecord($val)
    {
         
        
       $values = [
             
                     'title' => $val->title,
             
                     'description' => $val->description,
             
                     'price' =>  $val->price,
                     
                 ];
         
        
       $rules = [
            
                'title' => 'required|max:100|unique:transaction_record,title',
             
                'description' => 'required|max:255',
             
                'price' => 'required|numeric|max:10000000000',
             
               ];
        
        return  Validator::make($values,$rules);
        
        
    }
    
    //Store Details
    
    function addTransactionDetails($val)
    {
        
      $transaction_details_model = new TransactionDetails();
        
      $details_id = $transaction_details_model->addTransactionDetails($val);
        
      return $details_id;
        
    }
    
    // Store Record
    
    function addTransactionRecord($details_id,$val)
    {
        $transaction_record_model = new TransactionRecord();
        
        $transaction_record_model->addTransactionRecord($details_id,$val);
       
    }
    
}