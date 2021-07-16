<?php
/*
   GetLeadByID.php

   Marketo REST API Sample Code
   Copyright (C) 2016 Marketo, Inc.

   This software may be modified and distributed under the terms
   of the MIT license.  See the LICENSE file for details.
*/
$lead = new Lead();
$lead->id = 1543468;
$fields = ['firstName','email'];

print_r($lead->getData());

class Lead{
	private $host = "https://122-AYM-013.mktorest.com";//CHANGE ME
	private $clientId = "7c7dfa1e-bd22-4caa-9939-d923188d2a50";//CHANGE ME
	private $clientSecret = "zywfRLO33DJdKOcn3g0gSZamc4IMV8cn";//CHANGE ME
	public $id;//id of lead to return
	public $fields;//array of fields to return
	
	public function getData(){
		$url = $this->host . "/rest/v1/lead/" . $this->id . ".json?access_token=" . $this->getToken();
		$ch = curl_init($url);
		curl_setopt($ch,  CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json',));
		$response = curl_exec($ch);
		return $response;
	}
	
	private function getToken(){
		
		$ch = curl_init($this->host . "/identity/oauth/token?grant_type=client_credentials&client_id=" . $this->clientId . "&client_secret=" . $this->clientSecret);
		
		curl_setopt($ch,  CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json',));
		$response = json_decode(curl_exec($ch));
		//print_r($response);
		curl_close($ch);
		$token = $response->access_token;
		//echo $token;
		return $token;
	}
	private static function csvString($fields){
		$csvString = "";
		$i = 0;
		foreach($fields as $field){
			if ($i > 0){
				$csvString = $csvString . "," . $field;
			}elseif ($i === 0){
				$csvString = $field;
			}
		}
		return $csvString;
	}
}