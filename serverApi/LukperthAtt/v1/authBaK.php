<?php
$app->get('/session', function() {
    $db = new DbHandler();
    $session = $db->getSession();
    $response["_id"] = $session['_id'];
    $response["username"] = $session['username'];
    $response["createdAt"] = $session['createdAt'];
    $response["modifiedAt"] = $session['modifiedAt'];
    echoResponse(200, $session);
});

/*$app->post('/login1', function() use ($app) {
   // require_once 'passwordHash.php';
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('username', 'password1'),$r);
    $response = array();
    $db = new DbHandler();
    $username = $r->username;
    $password1 = $r->password1;

    //if user record exists
    $user = $db->getOneRecord("select _id, username, password1 from admin");

    if ($user != NULL) {
        if(passwordHash::check_password($user['password1'],$password1)){
          if($user['verified'] == 1){
              if($subStatus == NULL && $subStatus['status'] != 'active' ){
                  // if($user['logged'] == 1){
                  //Variable settings
                  $table_name = "users";
                  $condition = array('_id'=>$user['_id']);
                  //Operation
                  //$result = $db->updateTable(array('logged'=>1,'login_count'=>+1, FALSE),$table_name,$condition);
                  $response['status'] = "success";
                  $response['message'] = 'Login was successful';
                  $response['_id'] = $user['_id'];
                  $response['msisdn'] = $user['msisdn'];
                  $response['devType'] = $user['device_type'];
                  $response['uuid'] = $user['IMEI'];
                  $response['devVer'] = $user['device_version'];
                  $response['platform'] = $user['platform'];
                  $response['createdAt'] = $user['created'];
                  $response['modifiedAt'] = $user['modified'];
                  if (!isset($_SESSION)) {
                      session_start();
                  }
                  $_SESSION['_id'] = $user['_id'];
                  $_SESSION['msisdn'] = $user['msisdn'];
                  $_SESSION['createdAt'] = $user['created'];
                  $_SESSION['modifiedAt'] = $user['modified'];
                  echoResponse(200, $response);
                // }else{
                //   $response['status'] = "error";
                //   $response['message'] = 'You are logged in on another device';
                //   echoResponse(201, $response);
                // }
              }else{
                $condition = array('_id'=>$user['_id']);
                $condition2 = array('id'=>$subStatus['id']);
                $result = $db->updateTable(array('status'=>'unsubscribed'),'subscription',$condition2);
                $result = $db->updateTable(array('status'=>'unsubscribed'),'users',$condition);
                $response['status'] = "error";
                $response['message'] = 'Login Failed! Your subscription has expired';
                echoResponse(201, $response);

            }

          }else{
            $code = $user['code'];
            $response['status'] = "error";
            $response['message'] = 'Login Failed. Your msisdn has not been verified';
            echoResponse(201, $response);
          }
        }
         else {
            $response['status'] = "error";
            $response['message'] = 'Login failed. Incorrect credentials';
            echoResponse(201, $response);
        }

    }else {
            $response['status'] = "error";
            $response['message'] = 'msisdn is not registered';
            echoResponse(201, $response);
        }



});*/


$app->post('/login', function() use ($app){
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('username', 'password'),$r);
    $response = array();
    $db = new DbHandler();
    $username = $r->username;
    $password = $r->password;

    $user = $db->getOneRecord("select _id, username, password from user where username='$username' && password='$password'"); 
    
    if($user != null){
        $table_name = "user";
        $condition = array('_id'=>$user['_id']);

        $response['status'] = "success";
        $response['message'] = 'Login was successful';
        $response['_id'] = $user['_id'];
        $response['username'] = $user['username'];
        echoResponse(200, $response);

    }
    else{
      $response["status"] = "error";
      $response["message"] = "Incorrect cridentials";
      echoResponse(201, $response);
    }
});
  


$app->post('/logout', function() use ($app) {
    $response = array();
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('username'),$r);
    $db = new DbHandler();
    $msisdn = $r->msisdn;

    //conversion (+ and 0 in digits)
//    if (substr($msisdn, 0, 1) == "+"){
  //    $msisdn = preg_replace("/[^0-9]/",'',$msisdn);
    //}else{
      //$msisdn = preg_replace('/^0/','234',$msisdn);
    //}

    //Reparking objects
    $r->msisdn = $msisdn;

    //Get User ID
    $user = $db->getOneRecord("select _id,logged from users where msisdn='$msisdn' and status='active'");
    //Variable settings
    if($user != NULL){
        $table_name = "users";
        $condition = array('_id'=>$user['_id']);
        //Operation
        $result = $db->updateTable(array('logged'=>0),$table_name,$condition);
        if($result != NULL){
          $session = $db->destroySession();
          $response["status"] = "info";
          $response["message"] = "Logged out successfully";
          echoResponse(200, $response);
        }else{
          $response["status"] = "error";
          $response["message"] = "Logout Failed, Please try again.";
          echoResponse(201, $response);
        }
    }else{
      $response["status"] = "error";
      $response["message"] = "Logout Failed, Unexpected Parameter";
      echoResponse(201, $response);
    }


});

$app->post('/addUser', function() use ($app){
    $response = array();
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('username','password1'),$r);
    $db = new Dbhandler();
    $username = $r->username;
    $password1 = $r->password1;



    $isCourseExist = $db->getOneRecord("SELECT * FROM admin WHERE username='$username'");
    if(!$isCourseExist){
      $table_name = "admin";
      $column_name = array('username','password1');
      $result = $db->insertIntoTable($r, $column_name, $table_name);

      if($result != null){
        $response["status"] = "success";
        $response["message"] = "User Added";
        echoResponse(200, $response);
      }
      else{
        $response["status"] = "error";
        $response["message"] = "Failed to User";
        echoResponse(201, $response);
      }
    }
    else{
      $response["status"] = "error";
      $response["message"] = "User already exist";
      echoResponse(201, $response);
    }
   });
  

 $app->get('/viewAdmin', function() {
    $db = new Dbhandler;
    $resp = $db->getallRecords("SELECT * FROM admin WHERE 1");
    $response["status"] = "success";
    $response["admin"] = array();

    while($admin = $resp->fetch_assoc()){
      $tmp = array();
      $tmp["_id"] = $admin["_id"];
      $tmp["username"] = $admin["username"];
      
      array_push($response["admin"], $tmp);
    }
    echoResponse(200, $response);
   });


$app->delete('/delAdmin/:id', function($id) use($app){
    $response = array();
    $r = json_decode($app->request->getBody());
    $condition = array('_id'=>$id);
    $db = new Dbhandler;
    $table_name = "admin";
    $result = $db->deleteTable($table_name,$condition);
    if($result!=null){
      $response["status"] = "success";
      $response["message"] = "User deleted";
      echoResponse(200, $response);
    }
    else{
      $response["status"] = "error";
      $response["message"] = "Failed to delete";
      echoResponse(201, $response);
    }
   });
?>
