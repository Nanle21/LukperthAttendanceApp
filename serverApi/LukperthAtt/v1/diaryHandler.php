<?php
	$app->post('/addDiary', function() use($app){
		$response = array();
		$r = json_decode($app->request->getBody());
		verifyRequiredParams(array('reportTitle', 'body'),$r);
		$db = new Dbhandler();

		$reportTitle = $r->reportTitle;
		$body = $r->body;

		$checkDiary = $db->getOneRecord("SELECT * from reports where reportTitle='$reportTitle'");
		if(!$checkDiary){
			$table_name = "reports";
			$column_name = array('reportTitle', 'body');

			$result = $db->insertIntoTable($r, $column_name, $table_name);

			if($result != null){
				$response["status"] = "success";
				$response["message"] = "Just Added a new Report";
				echoResponse(200, $response);
			}
			else{
				$response["status"] = "error";
				$response["message"] = "Failed to add a new reports";
				echoResponse(201, $response);
			}
		}
		else{
			$response["status"] = "error";
			$response["message"] = "Report Already Exist";
			echoResponse(201, $response);
		}
	});


	$app->get('/getDiary', function() use($app){
		$db = new DbHandler();
		$resp = $db->getallRecords("SELECT * FROM reports where 1");

		$response["status"] = "success";
		$response["message"] = array();

		while($diary = $resp->fetch_assoc()){
			$tmp = array();
			$tmp["reportTile"] = $diary["reportTitle"];
			$tmp["body"] = $diary["body"];

			array_push($response["reports"], $tmp);
		}
		echoResponse(200, $response);
	});

	$app->put('/editDiary/:id', function($id) use($app){
		$response = array();
		$r = json_decode($app->request->getBody());
		$condition = array('_id'=>$id);
		verifyRequiredParams(array('reportTitle', 'body'), $r);
		$db = new DbHandler();

		$reportTitle = $r->reportTitle;
		$body = $r->body;

		$table_name = "reports";
		$column_name = array('reportTitle', 'body');

		$result = $db->updateTable($r, $table_name, $condition);

		if($result != null){
			$response["status"] = "success";
			$response["message"] = "Update sucess";
			echoResponse(200, $response);
		}
		else{
			$response["status"] = "error";
			$response["message"] = "Failed to update Please try again Later";
			echoResponse(201, $response);
		}
	});

?>