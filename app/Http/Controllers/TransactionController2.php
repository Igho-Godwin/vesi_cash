<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\TransactionDetails;
use App\Models\TransactionRecord;
use Validator;


class TransactionController2 extends BaseController
{
    //Edit Transaction
    function editTransaction()
    {
        
        $api_url = public_path()."/data/edit.json" ;
        $data = json_decode(file_get_contents($api_url));
        $validator = $this->validateTransactionDetails($data);
              
        if($validator->fails() ){
            
            return json_encode(['error'=>$validator->errors(),'failed'=>True]);   
        }
        else{
            
            $this->editTransactionRecord($data);
            
        }
        
        $validator = $this->validateTransactionRecord($data);
            
        if($this->validateTransactionRecord($data)->fails() )
        {
            return json_encode(['error'=>$validator->errors(),'failed'=>True]);  
        }
        else{
            $this->editTransactionDetails($data);
        }
        
        return json_encode(['success'=>True]);
        
    }
    
    // Validate transaction Details
    function validateTransactionDetails($val)
    {
        
         $values = [
             
                     'sender_email' => $val->sender->email,
             
                     'sender_phone' => $val->sender->phone,
             
                     'receipient_email' =>  $val->receipient->email,
             
                     'receipient_phone' =>  $val->receipient->phone,
             
                     'details_id' => $val->details_id
                     
             
                   ]; 
  
        
         $rules = [
            
                'sender_email' => 'max:100|unique:transaction_detail,sender_email,'.$val->details_id.'|email',
             
                'sender_phone' => 'max:100',
             
                'receipient_email' => 'max:100|unique:transaction_detail,receipient_email,'.$val->details_id.'|email',
             
                'receipient_phone' => 'max:100',
             
                'details_id'=>'required'
                
            
         ];
        
        return  Validator::make($values,$rules);
        
        
    }
    
    // Validate Transaction  Record
    
    function validateTransactionRecord($val)
    {
        
       $values = [
             
                     'title' => $val->transactionRecord->title,
             
                     'description' => $val->transactionRecord->description,
             
                     'price' =>  $val->transactionRecord->price,
                     
                 ];
         
        
       $rules = [
            
                'title' => 'max:100|unique:transaction_record,title,'.$val->record_id.'',
             
                'description' => 'max:255',
             
                'price' => 'numeric|max:10000000000',
             
               ];
        
        return  Validator::make($values,$rules);
        
        
    }
    
    // Edit Transaction Details
    
    function editTransactionDetails($val)
    {
        
      $transaction_details_model = new TransactionDetails();
        
      $transaction_details_model->editTransactionDetails($val);
          
    }
    
    //Validate Details for Delete Function
    
    function validateDelete($val)
    {
        
        $values = [
             
                   'details_id'=> $val->details_id  
                     
                 ];
         
        
        $rules = [
            
                'details_id' => 'required',
             
               ];
        
        return  Validator::make($values,$rules);
        
    }
    
    // Delete a Transaction
    
    function deleteTransaction()
    {
        $api_url = public_path()."/data/delete.json" ;
        
        $data = json_decode(file_get_contents($api_url));
        
        if($this->validateDelete($data)->fails() ){
            
            return json_encode(['error'=>$validator->errors(),'failed'=>True]);   
        }
        
        $transaction_details_model = new TransactionDetails();
        
        $transaction_details_model->deleteTransactionDetails($data);
        
        return json_encode(['success'=>True]);
    }
    
    // Edit A Transaction Record
    function editTransactionRecord($val)
    {
        $transaction_record_model = new TransactionRecord();
        
        $transaction_record_model->editTransactionRecord($val);
       
    }
    
}