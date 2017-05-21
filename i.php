<?php
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

session_start();
require_once __DIR__ . '/Facebook/autoload.php';
$fb = new Facebook([
  'app_id' => '1775680989409354',
  'app_secret' => '078a4a03a9ed8e7f905bce2babee28388',
  'default_graph_version' => 'v2.9',
  ]);
$helper = $fb->getRedirectLoginHelper();
//$permissions = ['email']; // optional

$permissions =  array("email","user_friends");	
try {
	if (isset($_SESSION['facebook_access_token'])) {
		$accessToken = $_SESSION['facebook_access_token'];
	} else {
  		$accessToken = $helper->getAccessToken();
	}
} catch(Facebook\Exceptions\FacebookResponseException $e) {
 	// When Graph returns an error
 	echo 'Graph returned an error: ' . $e->getMessage();
  	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
 	// When validation fails or other local issues
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
  	exit;
 }
if (isset($accessToken)) {
	if (isset($_SESSION['facebook_access_token'])) {
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	} else {
		// getting short-lived access token
		$_SESSION['facebook_access_token'] = (string) $accessToken;
	  	// OAuth 2.0 client handler
		$oAuth2Client = $fb->getOAuth2Client();
		// Exchanges a short-lived access token for a long-lived one
		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
		$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
		// setting default access token to be used in script
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	}
	// redirect the user back to the same page if it has "code" GET variable
	if (isset($_GET['code'])) {
		header('Location: ./');
	}
    
    
    // Getting user facebook profile info
    try {
 
        $profileRequest = $fb->get('/me?fields=name,first_name,last_name,birthday,email,link,gender,locale,picture',$_SESSION['facebook_access_token']);
        $profileRequest1 = $fb->get('/me?fields=name');
        $requestPicture = $fb->get('/me/picture?redirect=false&height=310&width=300'); //getting user picture
        $profileRequest3 = $fb->get('/me?fields=gender');
        $requestFriends = $fb->get('/me/taggable_friends?fields=name&limit=20');
		$fbUserProfile = $profileRequest->getGraphNode()->asArray();
		$friends = $requestFriends->getGraphEdge();
		$birthday= $fb->get('/me?fields=age_range,timezone');
		$a = $fb->get('/me/friends?fields=name,gender');
		$b = $a ->getGraphEdge();
        $fbUserProfile1 = $profileRequest1->getGraphNode();
        $picture = $requestPicture->getGraphNode();
 		$bday = $birthday->getGraphNode();
        $fbUserProfile3 = $profileRequest3->getGraphNode();
        
		
		// If button is clicked a photo with a caption will be uploaded to facebook
		if(isset($_POST['insert'])){
     	$data = ['source' => $fb->fileToUpload(__DIR__.'/photo.jpg'), 'message' => 'Check out this app! It is awesome http://localhost/Oauth/i.pnp '];
		$request = $fb->post('/me/photos', $data);
		$response = $request->getGraphNode()->asArray();
		header("Location: http://facebook.com");
     
    }
        
        
        
    } catch(FacebookResponseException $e) {
    
    	
        echo 'Graph returned an errrrrrror: ' . $e->getMessage();
        session_destroy();
        header("Location: ./");
        exit;
    } catch(FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
   // assigning a country according to the timezone
  $randomInteger = rand(0,19);
  $name= $friends[$randomInteger]['name'];
  $timeZone=$bday['timezone'];
  if($timeZone=='5.5'){
  	
  	$country = array("America","Japan","Italy","china","Hawai");
  }
  else{
  	$country = array("Iceland","Australia","Ethiopia","Uganda","Gana");
  }

  $selected_country=$country[array_rand($country)];
  $output = $fbUserProfile1;
  
  
  
  // getting gender
  if ($fbUserProfile['gender']=='male'){
  	$gender = 'female';
  }
  else{
  	$gender = 'male';
  }
  
  // Reasons
  
  $reasons = array(
  "You go to fishing",
  "you ate this person's ice cream",
  "you hit this man",
  "you did not do the exam",
  "you loved her"
  );
  $selected_reason=$reasons[array_rand($reasons)];
  
  
  
    
}else{

}
?>
<html>
<head>
<title>Facebook app</title>
 <script src="html2canvas.js"></script> 
<style type="text/css">
body {
    background-image: url("wallpaper.jpg");
    background-size: 1600px 800px;
  	background-repeat: no-repeat;
  
}
    .warning{font-family:Arial, Helvetica, sans-serif;color:#FFF; top:0px;position:relative;left:450px;}
    .you { position: relative; top: -200px; left: 300px; } 
    .cross { position: absolute; top: -200px; left: 270px; } 
    .blackboard{position:absolute; top:-200px; left:800px;}
    .content{font-family: Papyrus,fantasy;top:-450px;left:830px;position:relative;font-size:20px; }
    
    
    .loader{
    
    border: 16px solid #f3f3f3;
    border-radius: 50%;
    border-top: 16px solid #3498db;
    width: 120px;
    height: 120px;
    -webkit-animation: spin 1s linear 3;
    animation: spin 1s linear 3;
    position:relative;
    top:130px;
    left:350px;
    
    
    }
    .loader2{
    
    border: 16px solid #f3f3f3;
    border-radius: 50%;
    border-top: 16px solid #3498db;
    width: 120px;
    height: 120px;
    -webkit-animation: spin 1s linear 3;
    animation: spin 1s linear 3;
    position:relative;
    top:-35px;
    left:900px;
    
    
    }
    
    
    @-webkit-keyframes spin {
    0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
    }
    
    .button{
    background-image: url("share.png");
    background-size: 400px 50px;
    width: 400px;
    height:50px;
    }
 
    
    </style>
    <script>
    var hidden = false;


setTimeout(function(){


document.getElementById("you").style.visibility='hidden';
document.getElementById("cross").style.visibility='hidden';
document.getElementById("blackboard").style.visibility='hidden';
document.getElementById("content").style.visibility='hidden';
},1);


setTimeout(function(){


document.getElementById("you").style.visibility='visible';
document.getElementById("cross").style.visibility='visible';
document.getElementById("blackboard").style.visibility='visible';
document.getElementById("content").style.visibility='visible';
},3000);



</script>

 
</head>
<body>
<form method="post"><center><input type="submit" name="insert" class="button" value=""/></center></form>


	<h1 class="warning"><b><?php echo $name." is your next birth country"; ?></b></h1>
    <section><div class="loader"></div><div class="loader2"></div><div class="images" style="position:relative;left:0;"><?php echo "<img src='".$picture['url']."' class='you' id='you' /><img src='kill_cross.png'  width='400' height='300' class='cross' id='cross'/><img src='blackboard.png'  width='550' height='350' class='blackboard' id='blackboard'/> <p class='content' id='content' style='color:white;'><b>Gender: $gender <br> Place : $selected_country <br> Reason : $selected_reason</b></p>"; ?></div></section>


    </body>
</html>
