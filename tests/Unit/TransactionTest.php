<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionController2;
use Faker\Factory as Faker;
use App\Models\TransactionDetails;
use App\Models\TransactionRecord;

class TransactionTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    
    // tests add Transaction passes given the right values
    public function testAddTransactionPass()
    {
          
        $this->fakeData();    
        $api_url = public_path()."/data/data.json" ;
        $transaction_obj = new TransactionController();
        $response = $transaction_obj->addTransaction($api_url);
        $this->assertJsonStringEqualsJsonString(
            $response, json_encode(['success' => True])
        );
       
    }
    
    public function fakeData()
    {
        $faker =  Faker::create();
       $data = '[  
               {
		"sender": {
			"email": "'.$faker->unique()->email.'",
			"phone": "'.$faker->phoneNumber.'"
		},
		"receipient": {
			"email": "'.$faker->unique()->email.'",
			"phone": "'.$faker->phoneNumber.'"
		},
		"transactionRecord": [

			{
				"title": "'.$faker->unique()->text($maxNbChars = 100).'",
				"description": "'.$faker->text.'",
				"price": '.$faker->numberBetween($min = 1000, $max = 9000).'
			},
			{
				"title": "'.$faker->unique()->text($maxNbChars = 100).'",
				"description": "'.$faker->text.'",
				"price": '.$faker->numberBetween($min = 1000, $max = 9000).'
			},
			{
				"title": "'.$faker->unique()->text($maxNbChars = 100).'",
				"description": "'.$faker->text.'",
				"price": '.$faker->numberBetween($min = 1000, $max = 9000).'
			}
		]

	   }
     ]';
        
       $myfile = fopen(public_path()."/data/data.json", "w") or die("Unable to open file!");

       fwrite($myfile, $data);
       fclose($myfile);
    }
    
    // tests add Transaction fails
    public function testAddTransactionFail()
    {
        $api_url = public_path()."/data/empty.json";
        $transaction_obj = new TransactionController();
        $response = $transaction_obj->addTransaction($api_url);
        //$this->expectOutputString($response); 
        $this->assertJsonStringEqualsJsonString(
            $response, json_encode(['failed' => True])
        );
    }
    
    public function fakeDataDelete()
    {
        
        $this->fakeData();    
        $api_url = public_path()."/data/data.json" ;
        $transaction_obj = new TransactionController();
        $response = $transaction_obj->addTransaction($api_url);
        
        $details_id = TransactionDetails::orderBy('id','desc')
                                        ->first()->id;
        
        $faker =  Faker::create();
        $data = '     {
        "details_id":'.$details_id.'
        }';
        
       $myfile = fopen(public_path()."/data/delete.json", "w") or die("Unable to open file!");

       fwrite($myfile, $data);
       fclose($myfile);
        
        
    }
    
    
    public function fakeDataEdit()
    {
        $this->fakeData();    
        $api_url = public_path()."/data/data.json" ;
        $transaction_obj = new TransactionController();
        $response = $transaction_obj->addTransaction($api_url);
        
        $details_id = TransactionDetails::orderBy('id','desc')
                                        ->first()->id;
        
        $record_id =  TransactionRecord::where('details_id',$details_id)
                                        ->orderBy('id','desc')
                                        ->first()->id;
        $faker =  Faker::create();
        $data = '     {
        "record_id":'.$record_id.',
        "details_id":'.$details_id.',
		"sender": {
			"email": "'.$faker->unique()->email.'",
			"phone": "'.$faker->phoneNumber.'"
		},
		"receipient": {
			"email": "'.$faker->unique()->email.'",
			"phone": "'.$faker->phoneNumber.'"
		},
		"transactionRecord": 

			{
				"title": "'.$faker->unique()->text($maxNbChars = 100).'",
				"description": "'.$faker->text.'",
				"price": '.$faker->numberBetween($min = 1000, $max = 9000).'
			}
	   }';
        
       $myfile = fopen(public_path()."/data/edit.json", "w") or die("Unable to open file!");

       fwrite($myfile, $data);
       fclose($myfile);
    }
    
    
    // tests edit Transaction Passed
    public function testEditTransactionPass()
    {
        
        
        $this->fakeDataEdit();    
        $api_url = public_path()."/data/edit.json" ;
        $transaction_obj = new TransactionController2();
        $response = $transaction_obj->editTransaction($api_url);
        $this->assertJsonStringEqualsJsonString(
            $response, json_encode(['success' => True])
        );
    }
    
    // tests delete Transaction Passed
    public function testDeleteTransactionPass()
    {
        
        $this->fakeDataDelete();
        $api_url = public_path()."/data/delete.json" ;
        $transaction_obj = new TransactionController2();
        $response = $transaction_obj->deleteTransaction($api_url);
        $this->assertJsonStringEqualsJsonString(
            $response, json_encode(['success' => True])
        );
    }
    
    
}
