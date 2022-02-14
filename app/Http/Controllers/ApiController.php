<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return true;
    }

    /**
     * Display data from the json file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fetchJsonData(Request $request)
    {
    	$request_para = $request->all();

    	if (isset($request_para['para_filename'])) {
    		$para_filename = $request_para['para_filename'];
    	} else {
    		$para_filename = '';
    	}

    	if (isset($request_para['para_name'])) {
    		$para_name = $request_para['para_name'];
    	} else {
    		$para_name = '';
    	}

    	if (isset($request_para['para_percentage'])) {
    		$para_percentage = $request_para['para_percentage'];
    	} else {
    		$para_percentage = '';
    	}
    	
	    // file path to csvfiles folder in resources
    	$filepath = resource_path() . '/csvfiles/' . $para_filename;

	    // Response false if file empty
        if ($para_filename == '') {
        	$response = [
	        	'status'=>false,
	        	'message'=>'Please enter the CSV filename.'
	        ];

            return response()->json($response, 201);
        }

	    // Response false if file doesn't exist
        if (!file_exists($filepath)) {
        	$response = [
	        	'status'=>false,
	        	'message'=>'File does not exist.'
	        ];

            return response()->json($response, 201);
	    }

	    // Response false if can't open file
        if (!($fp = fopen($filepath, 'r'))) {
        	$response = [
	        	'status'=>false,
	        	'message'=>'Can not open file.'
	        ];

            return response()->json($response, 201);
	    }
	    
	    // Read csv headers
	    $key = fgetcsv($fp,"1024",",");
	    
	    // Parse csv rows into array
	    $json = array();
        while ($row = fgetcsv($fp,"1024",",")) {
        	$json[] = array_combine($key, $row);
	    }

	    // Release file handle
	    fclose($fp);

	    // Filter data
	    if ($para_name != '' && $para_percentage != '') {
        	$jsondata = $this->multi_array_search($json, array('name' => $para_name, 'discount_percentage' => $para_percentage));
        } elseif($para_name != '' && $para_percentage == '') {
        	$jsondata = $this->multi_array_search($json, array('name' => $para_name));
        } elseif($para_name == '' && $para_percentage != '') {
        	$jsondata = $this->multi_array_search($json, array('discount_percentage' => $para_percentage));
        } elseif ($para_name == '' && $para_percentage == '') {
        	$jsondata = $json;
        }

        if (empty($jsondata)) {
        	$res_message = 'Data not found.';
        } else {
        	$res_message = 'Data found.';
        }

        $response = [
        	'status'=>true,
        	'data'=>$jsondata,
        	'message'=>$res_message
        ];

        return response()->json($response, 201);
    }

    /**
     * Filter data
     *
     * @param  $array = array to search data from
     * @param  $search = search value
     * @return \Illuminate\Http\Response
     */
    function multi_array_search($array, $search) {
	    // Create the result array
	    $result = array();

	    // Iterate over each array element
	    foreach ($array as $key => $value){

	      // Iterate over each search condition
	      foreach ($search as $k => $v){

	        // If the array element does not meet the search condition then continue to the next element
	        if (!isset($value[$k]) || $value[$k] != $v){
	          continue 2;
	        }
	      }
	      // Add the array element's key to the result array
	      $result[] = $value;
	    }

	    // Return the result array
	    return $result;
	  }
}