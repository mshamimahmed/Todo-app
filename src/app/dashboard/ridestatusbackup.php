<?php
	class RideStatus{
		
        private $conn;
        
        function __construct() {
			require_once '../DB_Connect.php';
			$db = new Db_Connect();
			$this->conn = $db->connect();
		}
		
		public function UpdateRideStatus($data){
                
                $currentStatus= "";
                $currentAddress="";
                $currentLatLong="";
                
                $rideId = $data->rideId;
                $userId  = $data->userId;
                $driverId  = $data->driverId;
                
                $requestFrom = "USER";
                
                $currentStatus  = $data->currentStatus;
                $currentAddress  = $data->currentAddress;
                $currentLatLong  = $data->currentLatLong;
                $driverStmt = $this->conn->prepare("SELECT * FROM partner WHERE user_id=:user_id") or die("failed!");
                $driverStmt->bindparam(':user_id',$driverId);
                $driverStmt->execute();
                $driverRows = $driverStmt->fetch(PDO::FETCH_ASSOC);
                if($driverStmt->rowCount()==1){
                
                    $driverName = $driverRows['fullname'];
                    $phoneno = $driverRows['phoneno'];
                    
                    $userStmt = $this->conn->prepare("SELECT * FROM users WHERE user_id=:user_id") or die("failed!");
                    $userStmt->bindparam(':user_id',$userId);
                    $userStmt->execute();
                    $userRows = $userStmt->fetch(PDO::FETCH_ASSOC);
                    if($userStmt->rowCount()==1){
                        
                        $tokenId = $userRows['tokenid'];
                        
                        $stmt = $this->conn->prepare("SELECT * FROM bookingRequest WHERE ride_id=:ride_id AND user_id=:user_id AND requestFrom=:requestFrom") or die("failed!");
                        $stmt->bindparam(':ride_id',$rideId);
                        $stmt->bindparam(':user_id',$userId);
                        $stmt->bindparam(':requestFrom',$requestFrom);
                        $stmt->execute();
                        $rows = $stmt->fetch(PDO::FETCH_ASSOC);
                        if($stmt->rowCount()==1){
                            
                            $ride_status = $rows['ride_status'];
                            
                            if($ride_status=="ACCEPTED"){
                                
                                return $response = array("status" => "failed","error" => "Ride Already Accepted other user");
                                
                            }else{
                                if($currentStatus=="INTRESTED" || $currentStatus=="REJECTED"){
                                   return $this->UpdateInterestedStatus($rideId,$driverId,$currentStatus);
                                    
                                }else{
                                    
                                    $RideStatus = $this->UpdateStatus($rideId,$userId,$driverId,$requestFrom,$currentStatus,$currentAddress,$currentLatLong);
                                     error_log($RideStatus);
                                    if($RideStatus==true){
                                        
                                        $notiMessage = $this->getNotificationMessgae($driverName,$currentStatus);
                                        
                                        $this->sendMessageThroughFCM($tokenId,$notiMessage);
                                        
                                        return $response = array("status" => "success","error" => "");    
                                    
                                    } 
                                } 
                            }
                            $response = array("status" => "failed","error" => "Somthing Went Wrong");
                            
                            
                        }else{ return $response = array("status" => "failed","error" => "Somthing Went Wrong Ride Not Fount");  }
                        
                    
                    }else{ return $response = array("status" => "failed","error" => "Somthing Went Wrong 2");  } 
                    
                }else{ return $response = array("status" => "failed","error" => "Somthing Went Wrong 1");  } 
         }
         
        public function UpdateInterestedStatus($rideId,$driverId,$currentStatus){
                
                
                $stmt = $this->conn->prepare("SELECT * FROM bookingUserStatus WHERE ride_id=:ride_id") or die("failed!");
                $stmt->bindparam(':ride_id',$rideId);
                $stmt->execute();
                $rows = $stmt->fetch(PDO::FETCH_ASSOC);
                if($stmt->rowCount()==1){
                    
                    error_log("Update");
                    $stmt1 = $this->conn->prepare("UPDATE bookingUserStatus SET currentStatus=:currentStatus WHERE ride_id=:ride_id AND user_Id=:user_Id") or die("failed!");
                    $stmt1->bindparam(':currentStatus',$currentStatus);
                    $stmt1->bindparam(':ride_id',$currentLatLong);
                    $stmt1->bindparam(':user_Id',$currentAddress);
                    $stmt1 -> execute();
                    if($stmt1==TRUE){ return $response = array("status" => "success","error" => ""); }else{ $response = array("status" => "failed","error" => ""); }
            
            
                }else{
                    error_log("Insert");
                    $stmt1 = $this->conn->prepare("INSERT IGNORE INTO bookingUserStatus SET user_Id=:user_Id,ride_id=:ride_id,currentStatus=:currentStatus") or die("failed!");
                    $stmt1 -> bindParam(':user_Id', $driverId);
                    $stmt1 -> bindParam(':ride_id', $rideId);
                    $stmt1 -> bindParam(':currentStatus', $currentStatus);
                    $stmt1 -> execute();
                    if($stmt1==TRUE){ return $response = array("status" => "success","error" => ""); }else{ $response = array("status" => "failed","error" => ""); }
                     
                }
            
        }
        
        public function UpdateDriverReview($rideId,$driverId,$ratting){
                
                
                $stmt = $this->conn->prepare("SELECT * FROM bookingUserStatus WHERE ride_id=:ride_id") or die("failed!");
                $stmt->bindparam(':ride_id',$rideId);
                $stmt->execute();
                $rows = $stmt->fetch(PDO::FETCH_ASSOC);
                if($stmt->rowCount()==1){
                    
                    $stmt1 = $this->conn->prepare("INSERT IGNORE INTO bookingUserStatus SET user_Id=:user_Id,ride_id=:ride_id,currentStatus=:currentStatus") or die("failed!");
                    $stmt1 -> bindParam(':user_Id', $driverId);
                    $stmt1 -> bindParam(':ride_id', $rideId);
                    $stmt1 -> bindParam(':currentStatus', $currentStatus);
                    $stmt1 -> execute();
                    if($stmt1==TRUE){ 
                        return $response = array("status" => "success","error" => ""); 
                    }else{ 
                        return $response = array("status" => "failed","error" => ""); 
                    }
                    
                }else{
                    return $response = array("status" => "failed","error" => "Something went wrong");  
                }
            
        }
        
        public function UpdateStatus($rideId,$userId,$driverId,$requestFrom,$currentStatus,$currentAddress,$currentLatLong){
            
            $stmt = $this->conn->prepare("UPDATE bookingRequest SET ride_status=:ride_status,currentLatLong=:currentLatLong,currentAddress=:currentAddress,driver_id=:driver_id WHERE ride_id=:ride_id AND user_id=:user_id AND requestFrom=:requestFrom") or die("failed!");
            $stmt->bindparam(':ride_status',$currentStatus);
            $stmt->bindparam(':currentLatLong',$currentLatLong);
            $stmt->bindparam(':currentAddress',$currentAddress);
            $stmt->bindparam(':driver_id',$driverId);
            $stmt->bindparam(':ride_id',$rideId);
            $stmt->bindparam(':user_id',$userId);
            $stmt->bindparam(':requestFrom',$requestFrom);
            $stmt -> execute();
            if($stmt==TRUE){return true;}else{return false;}
        }
        
        public function getNotificationMessgae($driverName,$currentStatus){
            
            
            $response = array();
            $title = "";
            $userMessage = "";
            if($currentStatus=="ACCEPTED"){
                    
                $title = "RIDE ACCEPTED";
                $userMessage = $driverName." has Acceted Your Ride. He is on the Way.";
                    
            }else if($currentStatus=="ARRIVED"){
                        
                $title = "VEHICLE ARRIVED";
                $userMessage = $driverName." has Arrived to you location.";
    
            }else if($currentStatus=="STARTED"){
                        
                $title = "RIDE STARTED";
                $userMessage = "Your Ride is started.";
                        
            }else if($currentStatus=="COMPLETED"){
                        
                $title = "RIDE COMPLETED";
                $userMessage = "Your Ride is completed.";
                        
            }else if($currentStatus=="CANCELLED"){
                        
                $title = "RIDE CANCELLED";
                $userMessage = "Your Ride is Cancelled.";
                        
            }
            $response['title']=$title;
            $response['message']=$userMessage;
            return $response;
            
        }
        
        
        public function VerifiyWithUser($data){
            
                $rideId  = $data->rideId;
                $userId  = $data->userId;
                $userType  = $data->userType;
                $userPin  = $data->userPin;
                $driverId  = $data->driverId;
                
                error_log($rideId);
                error_log($driverId);
                $stmt = $this->conn->prepare("SELECT * FROM bookingUserStatus WHERE ride_id=:ride_id AND user_Id=:user_Id") or die("failed!");
                $stmt->bindparam(':ride_id',$rideId);
                $stmt->bindparam(':user_Id',$driverId);
                $stmt->execute();
                $rows = $stmt->fetch(PDO::FETCH_ASSOC);
                if($stmt->rowCount()==1){
                    
                   
                    $stmt1 = $this->conn->prepare("SELECT * FROM users WHERE user_id=:user_id") or die("failed!");
                    $stmt1->bindparam(':user_id',$userId);
                    $stmt1->execute();
                    $rows1 = $stmt1->fetch(PDO::FETCH_ASSOC);
                    if($stmt1->rowCount()==1){ 
                        
                        $DBuserPin = $rows1['bookingPin'];
                        if($DBuserPin==$userPin){
                            
                              error_log($userId);
                              $currentStatus = "VERIFIED";
                              $stmt3 = $this->conn->prepare("UPDATE bookingUserStatus SET currentStatus=:currentStatus WHERE ride_id=:ride_id AND user_id=:user_id") or die("failed!");
                              $stmt3->bindparam(':currentStatus',$currentStatus);
                              $stmt3->bindparam(':ride_id',$rideId);
                              $stmt3->bindparam(':user_id',$driverId);
                              $stmt3 -> execute();
                              if($stmt3==TRUE){
                                  return $response = array("status" => "success","error" => "");
                              }else{
                                  return $response = array("status" => "failed","error" => "somthing Went Wrong");
                              }
                        }else{
                                  return $response = array("status" => "failed","error" => "Invalid Pin");
                        }
                        
                    }else{
                        return $response = array("status" => "failed","error" => "somthing Went Wrong");
                    }
                    
                }else{
                    return $response = array("status" => "failed","error" => "somthing Went Wrong");
                }
            
        }
        
        public function sendMessageThroughFCM($tokenId,$notiMessage) {
            
                    $title = $notiMessage['title'];
                    $message = $notiMessage['message'];
		            
		            
		            // user sender id
		            $server_key = "AAAAujUnBDo:APA91bECLn8b1jtZtR3lOxsnIMX8X_mJk32pJHcQqexKuQRM11qaAOjv1-5FM9tRYGVwTpt7wdSgzGevJlWNfHTiOmIJajJlWc1rGnCwgzN92G6Q2tqLWHN9Bw1AwvNlRgng1XQIVuFr";
                    $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
            
                    $notification = [
                        'title' =>$title,
                        'body' => $message
                    ];
                    $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];
            
                    $fcmNotification = [
                        //'registration_ids' => $tokenArray, //multple token array
                        'to'        => $tokenId, //single token
                        'notification' => $notification,
                        'data' => $extraNotificationData
                    ];
            
                    $headers = [
                        'Authorization: key=' .$server_key,
                        'Content-Type: application/json'
                    ];
            
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,$fcmUrl);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
                    $result = curl_exec($ch);
                    curl_close($ch);
        }
         
	  }
	?>									