<?php 

	/*$app->post('/takeAttendance', function() use($app){
		$response = array();
		$r = json_decode($app->request->getBody());
		verifyRequiredParams(array('rfId', 'c_id'), $r);
		$db = new Dbhandler();
		$rfId = $r->rfId;
		//$reg = $r->s_id;
		$c_id = $r->c_id;
		$dateTaken = date("Y-m-d");
		$timeTaken = date('H:i:s', time());
		$r->dateTaken = $dateTaken;
		$r->timeTaken = $timeTaken;

		$rfId1 = $db->getOneRecord("SELECT * from student where rfId='$rfId'");
		if($rfId1 != null){

			$table_name = "student";
        	$condition = array('rfId'=>$rfId1['rfId']);
        	
        	$s_id = $rfId1["_id"];
			$r->s_id = $s_id;

        	$r->s_id = $rfId1["_id"];
        	$r->regNo = $rfId1["regNo"];
        	$r->firstName = $rfId1["firstName"];
        	$r->lastName = $rfId1["lastName"];

			$inRecTaken  = $db->getOneRecord("SELECT * FROM attendance where s_id='$s_id' and c_id='$c_id'"); //checks for existence
			if($inRecTaken == null){

			$table_name = "attendance";
			$column_name = array('s_id','c_id', 'regNo', 'firstName', 'lastName', 'rfId', 'dateTaken', 'timeTaken');
			$result = $db->insertIntoTable($r, $column_name, $table_name);

			if($result != null){
				$response["status"] = "success";
				$response["message"] = "Attendance  taken";
	        	$response['_id'] = $rfId1['_id'];
	       		$response['regNo'] = $rfId1['regNo'];

	        	$response['matricNo'] = $rfId1['matricNo'];
	        	$response['fistName'] = $rfId1['firstName'];
	        	$response['lastName'] = $rfId1['lastName'];
	        	$response['department'] = $rfId1['department'];
	        	$response['college'] = $rfId1['college'];
	        	$response['level'] = $rfId1['Level'];
	        	$response['dateCreated'] = $rfId1['dateCreated'];
	        	$response['debug-firstname'] = $r->firstName;
	        	$response['debug-lastname'] = $r->lastName;
	        	echoResponse(200, $response);

			}
			else{
				$response["status"] = "error";
				$response["message"] = "Failed to take Attendance";
				echoResponse(201, $response);
			}}
			else{
			$response["status"] = "error";
			$response["message"] = "Attendance already Taken for current date";
			echoResponse(201, $response);
			}
			
		}
		else{
			$table_name = "student";
			$response["status"] = "error";
			$response["message"] = "Sorry you haven't been registered";
			echoResponse(201, $response);
		}	
		

	});
*/


	$app->post('/takeAttendance', function() use($app){
		$response = array();
		$r = json_decode($app->request->getBody());
		verifyRequiredParams(array('regNum'), $r);
		$db = new Dbhandler();
		$regNum = $r->regNum;

		$getId = $db->getOneRecord("SELECT * from members where regNum='regNum'");
		if($getId !=null){
			$table_name = "members";
			$condition = array('regNum'=>$getId['regNum']);

			$student_id = $getId['_id'];

			$taken = $db->getOneRecord("SELECT * from attendance where student_id='student_id'");
			if($taken == null){
				$table_name = "attendance";
				$column_name = array('student_id');

				$results = $db->insertIntoTable($r, $column_name, $table_name);

				if($results != null){
					$response["status"] = "success";
					$response["message"] = "Attendance Taken";
					echoResponse(200, $response);
				}
				else{
					$response["status"] = "error";
					$response["message"] = "Failed to take Attendance";
					echoResponse(201, $response);
				}
			}
			else{
				$response["status"] = "error";
				$response["message"] = "Lukperth Member not registered";
				echoResponse(201, $response);
			}
			
		}
		else{
				$response["status"] = "eror";
				$response["message"] = "Failed";
				echoResponse(201, $response);
			}
		
	});

$app->post('/viewDate', function() use($app){
	$response = array();
	$r  = json_decode($app->request->getBody());
	verifyRequiredParams(array('date'), $r);
	$db = new Dbhandler();

	$date1 = date("Y-m-d");

	$dateAtt = $db->getOneRecord("SELECT * from attendance where date1='$date1'");
	if($dateAtt != null){
		$table_name = "attendance";
		$condition = array('student_id'=>$dateAtt['student_id']);

		$_id = $dateAtt["student_id"];
		$r->_id = $_id;

		/*$condition = array('Code1'=>$attend['Code1']);
		$c_id = $attend["_id"];
		$r->c_id = $c_id; */

		$response["status"] = "success";
		$response["records"] = array();

		$gottenAtt = $db->getAllRecords("SELECT * from members where _id='$_id'");
		if($gottenAtt != null){
			$table_name = "members";

			while($check = $gottenAtt->fetch_assoc()){
				$tmp = array();
		        $tmp["_id"] = $member["_id"];
		        $tmp["name"] = $member["name"];
		        $tmp["dept"] = $member["dept"];
		        $tmp["matricNum"] = $member["matricNum"];
		        $tmp["regNum"] = $member["regNum"];
		        $tmp["hall"] = $member["hall"];
		        $tmp["photo"] = $member["photo"];
		        $tmp["position"] = $member["position"];
		        $tmp["lmuMail"] = $member["lmuMail"];
		        $tmp["otherMail"] = $member["otherMail"];
		        $tmp["nickName"] = $member["nickName"];
		        $tmp["dob"] = $member["dob"];
		        $tmp["roomNum"] = $member["roomNum"];
		        $tmp["level"] = $member["level"];

		        array_push($response["members"], $tmp);

			}
			echoResponse(200, $response);
		}
		else{
			$response["status"] = "error";
			$response["message"] = "No attendance for that date";
			echoResponse(201, $response);
		}
	}
	else{
		$response["status"] = "error";
		$response["message"] = "Failed to get Atteendance";
		echoResponse(201, $response);
	}

});

$app->post('/selDate', function() use($app){
	$response = array();
	$r = json_decode($app->request->getBody());
	verifyRequiredParams(array('c_id'),$r);
	$db = new Dbhandler();
	$c_id = $r->c_id;

	$select = $db->getOneRecord("SELECT dateTaken from attendance where c_id='$c_id'");
	if($select != null){
		$table_name = "attendance";
		
		$response["status"] = "success";
		$response["dateTaken"] = $select["dateTaken"];
		echoResponse(200, $response);
	}
	else{
		$response["status"] = "error";
		$response["message"] = "Failed to get date";
		$response['debug'] = $r->c_id;
		echoResponse(201, $response);
	}
});




$app->post('/viewAtt', function() use($app){
	$response = array();
	$r = json_decode($app->request->getBody());
	verifyRequiredParams(array('Code1'),$r);
	$db = new Dbhandler();
	$Code1 = $r->Code1;
	//$dateTaken = $r->dateTaken;
	

	$attend = $db->getOneRecord("SELECT * FROM courses where Code1='$Code1'");
	if($attend != null){
		$table_name = "courses";

		$condition = array('Code1'=>$attend['Code1']);
		$c_id = $attend["_id"];
		$r->c_id = $c_id;

		//$response["title"] = $attend['title'];
		//echoResponse(200, $response);
		$response["status"] = "success";
		$response["records"] = array();
		
		$checkQry = $db->getAllRecords("SELECT * from attendance where c_id='$c_id'");
		if($checkQry != null){
			$table_name = "attendance";
			//$condition = array('c_id'=>$check['c_id']);
			while($check = $checkQry->fetch_assoc()){
				$tmp = array();
				$tmp["_id"] = $check['_id'];
				$tmp["regNo"] = $check['regNo'];
				$tmp["rfId"] = $check['rfId'];
				$tmp["firstName"] = $check["firstName"];
				$tmp["lastName"] = $check["lastName"];
				$tmp["timeTaken"] = $check["timeTaken"];
				$tmp["dateTaken"] = $check["dateTaken"];
				$tmp["debug"] = $r->c_id;
				array_push($response["records"], $tmp);
		}
			echoResponse(200, $response);
		}		else{

			$response["status"] = "error";
			$response["message"] = "No attendance for that date";
			$response["debug"] = $dateTaken;
			$response["debug2"] = $c_id;
			echoResponse(201, $response);
		}

	}
	else{
		$response["status"] = "error";
		$response["message"] = "Failed to get attendance";

		echoResponse(201, $response);
	}
});


$app->get('/getC', function(){
	$db = new DbHandler;
	$resp = $db->getallRecords("SELECT _id, Code1 from courses");

	$response["status"] = "success";
	$response["students"] = array();

	while($attendance = $resp->fetch_assoc()){
		$tmp = array();
		$tmp["Code1"] = $attendance["Code1"];
		$tmp["_id"] = $attendance["_id"];
		
		array_push($response["students"], $tmp);
	}
	echoResponse(200, $response);
});	


?>