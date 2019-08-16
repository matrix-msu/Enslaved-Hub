<?php
/**
 *  Website Templates Kora Search Wrapper
 *
 *  Keyword search example:
 *  	koraWrapperSearch(
 *  		30,      //formIds
 *  		'ALL',   //returnedFields
 *  		array('Name_137_796_'),   //fieldsToQuery
 *  		"cuba america canada",   //query
 *  		array(array('Name_137_796_'),'ASC']), //sort
 *  		0,    //start
 *  		10,   //limit
 * 			array()  //sizeDateAndDataOptions
 *  	);
 *
 *  Multi-form keyword search example:
 *  	koraWrapperSearch(
 *  		array(30,31),      //formIds
 *  		array('ALL','ALL'),   //returnedFields
 *  		array(array('Name_1_30_'),array('Name_1_31_')),   //fieldsToQuery
 *  		array("cuba america canada","spain france germany"),   //query
 *  		array(array(array('Name_1_30_'),'ASC']),array(array('Name_1_31_'),'DESC'])), //sort
 *  		array(0, 0),    //start
 *  		array(10,10),   //limit
 * 			array(array(),array())  //sizeDateAndDataOptions
 *  	);
 *
 *  KID search example:
 *  	koraWrapperSearch(
 *  		30,      //formIds
 *  		'ALL',   //returnedFields
 *  		'kid',   //fieldsToQuery
 *  		"10-30-172 10-30-173"  //query
 *  	);
 *
 *  Multi-form KID search example:
 *  	koraWrapperSearch(
 *  		array(30,31),      //formIds
 *  		array('ALL','ALL'),   //returnedFields
 *  		array('kid','kid'),   //fieldsToQuery
 *  		array("10-30-172 10-30-173","10-30-172 10-30-173")  //query
 *  	);
 *
 *  Advanced Search query types with corresponding syntax:
 *  	TEXT | RICH TEXT | LIST | INTEGER | FLOAT
 * 			string of text to search
 *			Example: "query"
 *    BOOLEAN
 *       0 or 1
 *  	NUMBER
 *			array with the left, right and invert keys set
 *  		left: number of left bound to search (blank for -infinite)
 *  		right: number of right bound to search (blank for infinite)
 *  		invert: bitwise where 1 will search outside of bound
 *			Example: array("left":5,"right":5,"invert":0);
 *  	MULTI-SELECT LIST | GENERATED LIST
 *  		array of string options to search
 *			Example: array('option1', 'option2', 'option3')
 *    DATE
 *       string of year-month-day
 *       Example: "YYYY-MM-DD"
 *    DATETIME
 *       string of year-month-day hours-minutes-seconds
 *       Example: "YYYY-MM-DD HH-MM-SS"
 *  	HISTORICAL DATE
 *			array with all six date keys set. no exceptions.
 *  		begin_month: number representation of month to search
 *  		begin_day: number representation of day to search
 *  		begin_year: number representation of year to search
 *  		end_month: number representation of month to search
 *  		end_day: number representation of day to search
 *  		end_year: number representation of year to search
 *			example: array(
 *				'begin_month'=>'4','begin_day'=>'0','begin_year'=>'1989','end_month'=>'4','end_day'=>'32','end_year'=>'1989'
 *			)
 *  	DOCUMENTS | GALLERY | PLAYLIST | VIDEO | 3-D MODEL
 *  		string of filename to search
 *			example: "document.pdf"
 *  	GEOLOCATOR
 *			array with the type key plus keys that depend on the type of geolocator that you are querying
 *  		type (options=[geometry | description | formatted_address]): string of location type to search.
 *  		if type = geometry
 *          array(
 *             "location" => array(
 *                    			"lat" => number of latitude to search,
 *  			                  "lon" => number of longitude to search)
 *  		if type = description
 *          string of description
 *  		if type = formatted_address
 *  			address: string of text to search
 *			example: array("geometry"=>array("location"=>array("lat"=>"latitueValue", "lon"=>"longitudeValue")))
 *
 *  	Associator
 *			array of Record IDs to search
 *			example: array("1-1-1","1-1-2","1-1-3")
 *
 *  Advanced search example:
 *  	koraWrapperSearch(
 *  		30,      //formIds
 *  		'ALL',   //returnedFields
 *  		array(   //fieldsToQuery
 *				array('InternalFieldName1'=>'FieldType1'),
 *				array('InternalFieldName2'=>'FieldType2')
 *			),
 *  		array("queryForField1","queryForField2")  //query
 *  	);
 *
 *  Multi-form advanced search synax:
 *  	koraWrapperSearch(
 *  		array(30,31),      //formIds
 *  		array('ALL','ALL'),   //returnedFields
 *  		array(                 //fieldsToQuery
 *				array(            //form 1 fields to query with types array
 *					array('Form1FieldName1'=>'F1FieldType1'),
 *					array('Form1Field2'=>'F1FieldType2')
 *				),
 *				array(          //form 2 fields to query with types array
 *					array('Form2InternalFieldName1'=>'F2FieldType1'),
 *					array('Form2Field2'=>'F2FieldType2')
 *				)
 *			),
 *  		array(      //query
 *				array('QueryForForm1Field1','QueryForForm1Field2'),
 *				array('QueryForForm2Field1','QueryForForm2Field2')
 *			),
 *  	);
 *
 *  Multi-form advanced search real code example:
 *	 $recordData = json_decode(koraWrapperSearch(
 *			array(40,41),      //formIds
 *			array('ALL','ALL'),   //returnedFields
 *			array(                //fieldsToQuery
 *				array(            //form 1 fields to query with types array
 *					array('Source_24_40_'=>'LIST'),
 *					array('Status_24_40_'=>'Multi-LIST')
 *				),
 *				array(            //form 2 fields to query with types array
 *					array('Name_24_41_'=>'TEXT')
 *				)
 *			),
 *			array(              //query
 *				array(          //form 1 queries
 *					'Anti-Slavery',  //form 1 field one string query
 *					array("FREE","slave")  //form 1 field 2 Multi-LIST query
 *				),
 *				array('Maria')  //form 2 queries
 *			],
 *			array(array(),array()),  //sort
 *			array(NULL,NULL),		 //start
 *			array(NULL,NULL),		 //limit
 *			array(array("size"=>true),array())  //sizeDateAndDataOptions
 *		),true);
 *
 *  $sizeDateAndDataOptions syntax:
 *  	Include counts: array("size"=>true)
 *  	Only counts no data: array("data"=>false,"size"=>true)
 *		Can specify for multi-form searches: array(array("data"=>false,"size"=>true),array("data"=>false,"size"=>true))
 *  	Multi date-range search: **Kora 3 does not support multiple date ranges in the same query. This allows for that.**
 *			array(
 *				"datesArray" => array(
 *					'dateFieldInternalName'=>array(
 *						array(
 *							'begin_month' => '4',
 *							'begin_day' => '0',
 *							'begin_year' => '1989',
 *							'end_month' => '4',
 *							'end_day' => '32',
 *							'end_year' => '1989'
 *						),
 *						array(
 *							'begin_month' => '5',
 *							'begin_day' => '0',
 *							'begin_year' => '1989',
 *							'end_month' => '5',
 *							'end_day' => '32',
 *							'end_year' => '1989'
 *						)
 *					)
 *				),
 *				"size" => true, //optional
 *				"data" -> true  //optional
 *			)
 **/
// function koraWrapperSearch(
//   $formIds,  //40 or array(40,41)
//   $returnedFields, //'ALL' or array('field1','field2').Advanced: array(array('form1field1','form1field2'),array('form2field1','form2field2'))
//   $fieldsToQuery = [], // "KID", "kid", array('fieldName').Advanced: array of array(fieldName=>fieldType) to search
//   $query = [],  //string of space separated kids or keywords.Advanced: search array('searchTextForField1','searchTextForField2')
//   $sort = [],  //array(array('fieldName'=>'ASC')) or array('fieldName'=>'DESC').Advanced: array(array(array('fieldName'=>'ASC')),array(array('fieldName'=>'ASC')))
//   $start = NULL, //starting record to return number.Advanced: array(form1start,form2start)
//   $limit = NULL, //limit of how many records to return number.Advanced: array(form1limit,form2limit)
//   $sizeDateAndDataOptions = []  //array to specify returns of no data and sizes. Also used for date range searches. Syntax above.
// ){
//     if( !is_array($formIds) ){
//       $formIds = array($formIds);
// 	  $returnedFields = array($returnedFields);
// 	  $fieldsToQuery = array($fieldsToQuery);
// 	  $query = array($query);
// 	  $sort = array($sort);
// 	  $start = array($start);
// 	  $limit = array($limit);
// 	  $sizeDateAndDataOptions = array($sizeDateAndDataOptions);
//     }
// 	$formIndex = 0;
// 	$formsQueryArray = array();
//     foreach( $formIds as $formId ){
// 		$formQuery = [];
// 		$formQuery['form'] = $formId;
// 		$formQuery['bearer_token'] = $GLOBALS['FORMS_CONFIG'][$formId]['token'];
// 		$formQuery['fields'] = $returnedFields[$formIndex];
// 		$formQuery['realnames'] = true;
// 		if (!empty($sort) && count($sort[$formIndex]) > 0){
// 			$formQuery['sort'] = $sort[$formIndex];
// 		}
// 		$formQuery['index'] = $start[$formIndex];
// 		$formQuery['count'] = $limit[$formIndex];
//         $formattedQuery = [];
// 		if ( !empty($fieldsToQuery[$formIndex]) ){
// 			if ( $query[$formIndex] !== "" && is_string($query[$formIndex]) ){
// 				if( $fieldsToQuery[$formIndex]=='kid'||$fieldsToQuery[$formIndex]=='KID'){ //kid search
// 					$formattedQuery['search'] = 'kid';
// 					$formattedQuery['kids'] = explode(' ', $query[$formIndex]);
// 				}else {  //keyword search
// 					$formattedQuery['search'] = 'keyword';
// 					$formattedQuery['keys'] = $query[$formIndex];
// 					if (!is_array($fieldsToQuery[$formIndex]) || !empty($fieldsToQuery[$formIndex])) {
// 						if( is_string($fieldsToQuery[$formIndex]) ){
// 							$fieldsToQuery[$formIndex] = array($fieldsToQuery[$formIndex]);
// 						}
// 						$formattedQuery['fields'] = $fieldsToQuery[$formIndex];
// 					}
// 				}
// 				$formattedQuery = [$formattedQuery];
// 			}else if (isset($sizeDateAndDataOptions[$formIndex]['datesArray'])){ //advanced date search
// 				foreach( $sizeDateAndDataOptions[$formIndex]['datesArray'] as $key => $dateArray){
// 					foreach($dateArray as $date){ //adds together multiple date searches
// 						$fieldsToQuery[$formIndex][$key] = $date;
// 						$formattedQuery[] = array(
// 							'search' => 'advanced',
// 							'fields' => $fieldsToQuery[$formIndex]
// 						);
// 					}
// 				}
// 			}else{ //advanced search
//                 $formattedQuery = array();
// 				$fieldNames = $fieldsToQuery[$formIndex];
// 				$singleQuery = array();
// 				$singleQuery['search'] = 'advanced';
// 				$singleFormFieldsArray = array();
// 				for( $fieldIndex=0; $fieldIndex<count($fieldNames); $fieldIndex++){
// 					reset($fieldNames[$fieldIndex]);
// 					$queryField = key($fieldNames[$fieldIndex]);
// 					$queryType = $fieldNames[$fieldIndex][$queryField];
// 					if( !in_array($queryType,array('TEXT','RICH TEXT','LIST','DOCUMENTS','GALLERY','PLAYLIST','VIDEO','3-D MODEL','Multi-LIST')) ){
// 						$singleFormFieldsArray[$queryField] = $query[$formIndex][$fieldIndex];
// 					}else{
// 						$singleFormFieldsArray[$queryField] = array('input'=>$query[$formIndex][$fieldIndex]);
// 					}
// 				}
// 				$singleQuery['fields'] = $singleFormFieldsArray;
// 				$formattedQuery[] = $singleQuery;
// 	        }
// 			$formQuery['queries'] = $formattedQuery;
//
// 		}else{ //no query specified
// 		  $formattedQuery = NULL;
// 		}
// 		$formQuery['query'] = $formattedQuery;
// 		if (!empty($sizeDateAndDataOptions) && count($sizeDateAndDataOptions[$formIndex]) > 0){ //add in the $sizeDateAndDataOptions
// 		  foreach($sizeDateAndDataOptions[$formIndex] as $key => $value){
// 		      $formQuery[$key] = $value;
// 		  }
// 		}
// 		$formsQueryArray[] = json_encode($formQuery);
// 		$formIndex++;
//     }
// 	$formsQueryArray = '['.implode(',',$formsQueryArray).']';
//
//     $data = ['forms' => $formsQueryArray];
// // var_dump($data);
//     $ch = curl_init(KORA_SEARCH_URL);
//     curl_setopt($ch, CURLOPT_POST, 1);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//     $result = curl_exec($ch);
// // var_dump($result);die;
//     curl_close($ch);
//     return $result;
// }

function koraWrapperSearch(
  $formIds,  //40 or array(40,41)
  $returnedFields, //'ALL' or array('field1','field2').Advanced: array(array('form1field1','form1field2'),array('form2field1','form2field2'))
  $fieldsToQuery = [], // "KID", "kid", array('fieldName').Advanced: array of array(fieldName=>fieldType) to search
  $query = [],  //string of space separated kids or keywords.Advanced: search array('searchTextForField1','searchTextForField2')
  $sort = [],  //array(array('fieldName'=>'ASC')) or array('fieldName'=>'DESC').Advanced: array(array(array('fieldName'=>'ASC')),array(array('fieldName'=>'ASC')))
  $start = NULL, //starting record to return number.Advanced: array(form1start,form2start)
  $limit = NULL, //limit of how many records to return number.Advanced: array(form1limit,form2limit)
  $sizeDateAndDataOptions = []  //array to specify returns of no data and sizes. Also used for date range searches. Syntax above.
){
    if( !is_array($formIds) ){
      $formIds = array($formIds);
	  $returnedFields = array($returnedFields);
	  $fieldsToQuery = array($fieldsToQuery);
	  $query = array($query);
	  $sort = array($sort);
	  $start = array($start);
	  $limit = array($limit);
	  $sizeDateAndDataOptions = array($sizeDateAndDataOptions);
    }
	$formIndex = 0;
	$formsQueryArray = array();
    foreach( $formIds as $formId ){
		$formQuery = [];
		$formQuery['form'] = $formId;
		$formQuery['bearer_token'] = TOKEN;
		// $formQuery['return_fields'] = array($returnedFields[$formIndex]);
		$formQuery['real_names'] = true;
		if (count($sort[$formIndex]) > 0){
			$formQuery['sort'] = $sort[$formIndex];
		}
		$formQuery['index'] = $start[$formIndex];
		$formQuery['count'] = $limit[$formIndex];
		if ( !empty($fieldsToQuery[$formIndex]) ){
			if ( $query[$formIndex] !== "" && is_string($query[$formIndex]) ){
				if( $fieldsToQuery[$formIndex]=='kid'||$fieldsToQuery[$formIndex]=='KID'){ //kid search
					$formattedQuery['search'] = 'kid';
					$formattedQuery['kids'] = explode(' ', $query[$formIndex]);
				}else {  //keyword search
					$formattedQuery['search'] = 'keyword';
					$formattedQuery['key_words'] = array($query[$formIndex]);
					if (!is_array($fieldsToQuery[$formIndex]) || !empty($fieldsToQuery[$formIndex])) {
						if( is_string($fieldsToQuery[$formIndex]) ){
							$fieldsToQuery[$formIndex] = array($fieldsToQuery[$formIndex]);
						}
						$formattedQuery['key_fields'] = $fieldsToQuery[$formIndex];
					}
				}
				$formattedQuery = [$formattedQuery];
			}else if (isset($sizeDateAndDataOptions[$formIndex]['datesArray'])){ //advanced date search
				foreach( $sizeDateAndDataOptions[$formIndex]['datesArray'] as $key => $dateArray){
					foreach($dateArray as $date){ //adds together multiple date searches
						$fieldsToQuery[$formIndex][$key] = $date;
						$formattedQuery[] = array(
							'search' => 'advanced',
							'fields' => $fieldsToQuery[$formIndex]
						);
					}
				}
			}else{ //advanced search
	            $formattedQuery = array();
				$fieldNames = $fieldsToQuery[$formIndex];
				$singleQuery = array();
				$singleQuery['search'] = 'advanced';
				$singleFormFieldsArray = array();
				for( $fieldIndex=0; $fieldIndex<count($fieldNames); $fieldIndex++){
					reset($fieldNames[$fieldIndex]);
					$queryField = key($fieldNames[$fieldIndex]);
					$queryType = $fieldNames[$fieldIndex][$queryField];
					if( !in_array($queryType,array('TEXT','RICH TEXT','LIST','DOCUMENTS','GALLERY','PLAYLIST','VIDEO','3-D MODEL','Multi-LIST')) ){
						$singleFormFieldsArray[$queryField] = $query[$formIndex][$fieldIndex];
					}else{
						$singleFormFieldsArray[$queryField] = array('input'=>$query[$formIndex][$fieldIndex]);
					}
				}
				$singleQuery['fields'] = $singleFormFieldsArray;
				$formattedQuery[] = $singleQuery;
	        }
			$formQuery['queries'] = $formattedQuery;

		}else{ //no query specified
		  $formattedQuery = NULL;
		}
		// $formQuery['query'] = $formattedQuery;
		if (count($sizeDateAndDataOptions[$formIndex]) > 0){ //add in the $sizeDateAndDataOptions
		  foreach($sizeDateAndDataOptions[$formIndex] as $key => $value){
		      $formQuery[$key] = $value;
		  }
		}
		$formsQueryArray[] = json_encode($formQuery);
		$formIndex++;
    }
	$formsQueryArray = '['.implode(',',$formsQueryArray).']';
    $data = ['forms' => $formsQueryArray];
    // var_dump($data);


    $ch = curl_init(KORA_SEARCH_URL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


function publicIngestions(
  $form,		// Form ID
  $type, 		// Request Type (Create, Update, or Delete)
  $kids,		// Array with KID's for Update/Delete
  $fields		// Array with $fields => $values
){
/**
*  Create record example:
*  	publicIngestions(
*  		30,
*  		'Create',
*  		NULL,
*  		array('TextFieldName'=>'Title1','ListFieldName'=>'List1','DateFieldName'=>'1900-02-02','MultiListFieldName'=>['multi1','multi2'])
*  	);
*  Update record example:
*	  publicIngestions(
*  	  	30,
*  		'Update',
*  		'144-495-17',
*  		array('Title' => 'CHANGEDAGAIN','Description'=>'NEWDESCRIPTION'])
*  	);
*  Delete record example:
*	  publicIngestions(
*  		30,
*  		'Delete',
*  		'144-495-1',
*  		NULL
*  	);
*  Delete multiple records example:
*  	publicIngestions(
*  		30,
*  		'Delete',
*  		array('144-495-14','144-495-15','144-495-16']),
*  		NULL
*  	);
*  File upload example:
*     publicIngestions(
*        30,
*        'UPLOAD',
*        '33-60-4',
*        array('file'=>'/matrix/home/user/public_html/enslaved-project-suite/text.txt'])
*     );
*
*  For query types and syntax, refer to line 45
**/


	if ( strtoupper($type) == 'CREATE' ){//Create a record
      $query = [];
      foreach ( $fields as $key => $value ){
      	$query[$key] = $value;
      }
      $query = json_encode($query);

      $data = ['form' => $form,
      	'bearer_token' => $GLOBALS['FORMS_CONFIG'][$form]['token'], 'fields' => $query];
      $ch = curl_init(KORA_CREATE_URL);
	}

	if ( strtoupper($type) == 'UPDATE' ){//Update a record
      $query = json_encode($fields);
      $data = ['_method' => 'put', 'form' => $form,
      	'bearer_token' => $GLOBALS['FORMS_CONFIG'][$form]['token'], 'kid' => $kids,'fields' => $query];
      $ch = curl_init(KORA_EDIT_URL);
	}

	if ( strtoupper($type) == 'DELETE' ){//Delete a record(s)
      if( !is_array($kids) ){
      	$kids = array($kids);
      }
      $query = $kids;
      $query = json_encode($query);
      $data = ['_method' => 'delete', 'form' => $form,
      	'bearer_token' => $GLOBALS['FORMS_CONFIG'][$form]['token'], 'kids' => $query];
      $ch = curl_init(KORA_DELETE_URL);
	}

	if ( strtoupper($type) == 'UPLOAD' ){//Upload a file
		if( count($fields) <= 0 ){
			return 'file not found';
		}
		$zip = new ZipArchive();
		$zip->open('./nameneedschanged.zip', ZipArchive::CREATE);
		$zip->addFile($fields[key($fields)], basename($fields[key($fields)]));
		$fieldArray = [key($fields) => [[
			"name" => basename($fields[key($fields)])
		]]];
		$zip->close();
		$file = new CURLFile('./nameneedschanged.zip', 'application/zip', 'zip_file');
		$data = ['_method' => 'put', 'form' => $form,
			'bearer_token' => $GLOBALS['FORMS_CONFIG'][$form]['token'], 'kid' => $kids,'fields' => json_encode($fieldArray),'zip_file' => $file];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, KORA_BASE_URL."api/edit");
	}

	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	curl_close($ch);
   return $result;
}
