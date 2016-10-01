<?php 
	 $app->post('/addCourse', function() use($app){
	 	$response = array();
	 	$r = json_decode($app->request->getBody());
	 	verifyRequiredParams(array('Code1','title','lecturer'),$r);
	 	$db = new Dbhandler();
	 	$Code1 = $r->Code1;
	 	$title = $r->title;
	 	$lecturer = $r->lecturer;


	 	$isCourseExist = $db->getOneRecord("SELECT * FROM courses WHERE Code1='$Code1'");
	 	if(!$isCourseExist){
	 		$table_name = "courses";
	 		$column_name = array('Code1','title','lecturer');
	 		$result = $db->insertIntoTable($r, $column_name, $table_name);

	 		if($result != null){
	 			$response["status"] = "success";
	 			$response["message"] = "Course Added";
	 			echoResponse(200, $response);
	 		}
	 		else{
	 			$response["status"] = "error";
	 			$response["message"] = "Failed to course";
	 			echoResponse(201, $response);
	 		}
	 	}
	 	else{
	 		$response["status"] = "error";
	 		$response["message"] = "Course already exist";
	 		echoResponse(201, $response);
	 	}
	 });


	 $app->get('/viewCourse', function() {
	 	$db = new Dbhandler;
	 	$resp = $db->getallRecords("SELECT * FROM courses WHERE 1");
	 	$response["status"] = "success";
	 	$response["courses"] = array();

	 	while($courses = $resp->fetch_assoc()){
	 		$tmp = array();
	 		$tmp["_id"] = $courses["_id"];
	 		$tmp["Code1"] = $courses["Code1"];
	 		$tmp["title"] = $courses["title"];
	 		$tmp["lecturer"] = $courses["lecturer"];
	 		array_push($response["courses"], $tmp);
	 	}
	 	echoResponse(200, $response);
	 });

	 $app->delete('/delCourse/:id', function($id) use($app){
	 	$response = array();
	 	$r = json_decode($app->request->getBody());
	 	$condition = array('_id'=>$id);
	 	$db = new Dbhandler;
	 	$table_name = "courses";
	 	$result = $db->deleteTable($table_name,$condition);
	 	if($result!=null){
	 		$response["status"] = "success";
	 		$response["message"] = "Course deleted";
	 		echoResponse(200, $response);
	 	}
	 	else{
	 		$response["status"] = "error";
	 		$response["message"] = "Failed to delete";
	 		echoResponse(201, $response);
	 	}
	 });

	 $app->put('/editCourse/:id', function($id) use($app){
	 	$response = array();
	 	$r = json_decode($app->request->getBody);
	 	verifyRequiredParams(array('_id'),$r);
	 	$db = new Dbhandler;
	 	$code1 = $r->code1;
	 	$title = $r->title;
	 	$lecturer = $r->lecturer;

	 	$table_name =  "courses";
	 	$column_name = array('code1', 'title', 'lecturer');
	 	$result = $db->updateTable($r,$column_name,$table_name);
	 	if(!$result = null){
	 		$response["status"] = "success";
	 		$response["message"] = "Update successfull";
	 		echoResponse(200, $response);
	 	}
	 	else{
	 		$response["status"] = "error";
	 		$response["message"] = "Failed to Update";
	 	}
	 });

?>