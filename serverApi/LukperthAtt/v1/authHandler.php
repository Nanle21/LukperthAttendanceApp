<?php
//Gets all the admins in the database
$app->get('/countMember', function() use ($app){
    $db = new DbHandler();
    $response = array();
    $table_name = "users";
    $resp = $db->countRecords($table_name);

    $response["status"] = "success";
    $response["count"] = $resp['total'];

    echoResponse(200, $response);

});



$app->get('/viewUsers', function() {
    $db = new DbHandler();
    $response = array();
    $resp = $db->getAllRecords("SELECT * FROM users WHERE 1");

    $response["status"] = "success";
    $response["users"] = array();

    while ($users = $resp->fetch_assoc()) {
                $tmp = array();
                $tmp["_id"] = $users["_id"];
                $tmp["fullName"] = $users["fullName"];
                $tmp["matNumber"] = $users["matNumber"];
                $tmp["lmuMail"] = $users["lmuMail"];
                $tmp["emailAddress"] = $users["emailAddress"];
                $tmp["nickName"] = $users["nickName"];
                $tmp["gender"] = $users["gender"];
                $tmp["bday"] = $users["bday"];
                $tmp["bmonth"] = $users["bmonth"];
                $tmp["bYear"] = $users["bYear"];
                $tmp["facebookName"] = $users["facebookName"];
                $tmp["twitterName"] = $users["twitterName"];
                array_push($response["users"], $tmp);
            }
    echoResponse(200, $response);
});

$app->post('/addUsers', function() use ($app) {
    $response = array();
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('username', 'password'),$r);
    $db = new DbHandler();
    $r->ipAddress = getenv("REMOTE_ADDR");
    $username = $r->username;
    $password = $r->password;
    


    $isUserExists = $db->getOneRecord("select 1 from users where username='$username'");
    if(!$isUserExists){
            $table_name = "users";
            $column_names = array('username', 'password');
            $result = $db->insertIntoTable($r, $column_names, $table_name);
            if ($result != NULL) {
            $response["status"] = "success";
            $response["message"] = "Your user has been submitted";
            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Failed to submit user. Please try again";
            echoResponse(201, $response);
        }
    }else{
        $response["status"] = "error";
        $response["message"] = "User has already been submitted!";
       // $response["test"] = $bYear;
        echoResponse(201, $response);

    }
});


$app->post('/viewMember', function() use ($app){
    $response = array();
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('memberId'),$r);
    $db = new DbHandler();

    $id = $r->memberId;

    $resp = $db->getOneRecord("select * from users where _id=$id");

    if($resp != NULL){

        $response['status'] = "success";
        $response["member"] = $resp;

        echoResponse(200, $response);
    }else{
        $response['status'] = "error";
        $response['message'] ="Member not failed";
        echoResponse(201, $response);
    }

});

//METHODS IN REST: GET, POST, PUT
/* $app->put('/editMember/:id', function($id) use ($app) {
    $response = array();
    $r = json_decode($app->request->getBody());
    $condition = array('_id'=>$id);
     verifyRequiredParams(array('fullName', 'matNumber', 'lmuMail', 'gender',  'birthday', 'residenceAddress', 'regNum', 'state', 'sLevel', 'phone', 'interestedField'),$r);
    $db = new DbHandler();
    $fullName = $r->fullName;
    $matNumber = $r->matNumber;
    $lmuMail = $r->lmuMail;
    $emailAddress = $r->emailAddress;
    $nickName = $r->nickName;
    $gender = $r->gender;
    $instaName = $r->instaName;
    $interestedField = $r->interestedField;        
            $table_name = "users";
            $column_names = array('fullName', 'matNumber', 'lmuMail', 'emailAddress', 'nickName', 'gender', 'bday', 'bmonth', 'bYear', 'birthday', 'facebookName', 'twitterName', 'residenceAddress', 'regNum', 'state', 'homeTown', 'hallName', 'roomNum', 'sLevel', 'suggestion', 'website', 'servUnit', 'BBpin', 'phone', 'limeUser', 'instaName', 'interestedField');
            $result = $db->updateTable($r,$table_name,$condition);
            if ($result != NULL) {
            $response["status"] = "success";
            $response["message"] = "Profile update was successfull";
            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Failed to edit profile. Please try again";
            echoResponse(201, $response);
        }
}); */

$app->delete('/deleteMember/:id', function($id) use ($app) {
    $response = array();
    $r = json_decode($app->request->getBody());
    $condition = array('_id'=>$id);
    $db = new DbHandler();
            $table_name = "users";
            $result = $db->deleteTable($table_name,$condition);
            if ($result != NULL) {
            $response["status"] = "success";
            $response["message"] = "Delete profile was successfull";
            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Failed to delete Profile. Please try again";
            echoResponse(201, $response);
        }
});



?>
 