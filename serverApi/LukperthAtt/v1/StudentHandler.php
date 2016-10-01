<?php 

$app->post('/addMember', function() use($app){
    $response = array();
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('name', 'dept', 'matricNum', 'regNum', 'hall', 'photo', 'position', 'lmuMail', 'otherMail', 'nickName', 'dob', 'roomNum', 'level'),$r);
    $db = new DbHandler();

    $name = $r->name;
    $dept = $r->dept;
    $matricNum = $r->matricNum;
    $regNum = $r->regNum;
    $hall = $r->hall;
    $photo = $r->photo;
    $position = $r->position;
    $lmuMail = $r->lmuMail;
    $otherMail = $r->otherMail;
    $nickName = $r->nickName;
    $dob = $r->dob;
    $roomNum = $r->roomNum;
    $level = $r->level;

    $isMemberExist = $db->getOneRecord("SELECT 1 FROM members where regNum='$regNum' or matricNum='$matricNum'");
    if(!$isMemberExist){
        $table_name = "members";
        $column_names = array('name', 'dept', 'matricNum', 'regNum', 'hall', 'photo', 'position', 'lmuMail', 'otherMail', 'nickName', 'dob', 'roomNum', 'level');

        $result = $db->insertIntoTable($r, $column_names, $table_name);
        if($result != null){
            $response["status"] = "success";
            $response["message"] = "Lukperth Member Added";
            echoResponse(200, $response);

        }
        else{
            $response["status"] = "error";
            $response["message"] = "Failed to add";
            echoResponse(201, $response);
        }
    }
        $response["status"] = "error";
        $response["message"] = "Member already exist";
        echoResponse(201, $response);
});

$app->get('/getMember', function() use($app){
    $db = new DbHandler();
    $resp = $db->getallRecords("SELECT * FROM `members` WHERE 1");

    $response["status"] = "success";
    $response["members"] = array();

    while($member = $resp->fetch_assoc()){
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
});


$app->put('/editMember/:id', function($id) use($app){
    $response = array();
    $r = json_decode($app->request->getBody());
    $condition = array('_id'=>$id);
    verifyRequiredParams(array('name', 'dept', 'matricNum', 'regNum', 'hall', 'photo', 'position', 'lmuMail', 'otherMail', 'nickName', 'dob', 'roomNum', 'level'),$r);
    $db = new DbHandler();

    $name = $r->name;
    $dept = $r->dept;
    $matricNum = $r->matricNum;
    $regNum = $r->regNum;
    $hall = $r->hall;
    $photo = $r->photo;
    $position = $r->position;
    $lmuMail = $r->lmuMail;
    $otherMail = $r->otherMail;
    $nickName = $r->nickName;
    $dob = $r->dob;
    $roomNum = $r->roomNum;
    $level = $r->level;

    $table_name = "members";
    $column_names = array('name', 'dept', 'matricNum', 'regNum', 'hall', 'photo', 'position', 'lmuMail', 'otherMail', 'nickName', 'dob', 'roomNum', 'level');

    $result = $db->updateTable($r, $table_name, $condition);

    if($result != null){
        $response["status"] = "success";
        $response["message"] = "Member updated";
        echoResponse(200, $response);
    }
    else{
        $response["status"] = "error";
        $response["message"] = "Failed to Update";
        echoResponse(201, $response);
    }
})
  
?>