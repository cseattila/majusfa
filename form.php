<?php
   $conn=mysqli_connect('localhost','majus','majus12345','majus');
if ($conn->connect_error) {
				die("Dase connection failed: " . $dbconnect->connect_error);
	}
function get_SzalagDaTa() {
 	global $conn;
		
	$sql = "SELECT * FROM kivansag";
	$sth = $conn->query($sql);


	$rows = array();
	while($r = mysqli_fetch_assoc($sth)) {
		$rows[] = $r;
	}
	print json_encode($rows);
}
?>

<?php
	function make_seed()
{
  list($usec, $sec) = explode(' ', microtime());
  return $sec + $usec * 1000000;
}

  
  function getRendomPastelColour(){
	  return  "".dechex(rand ( 100 , 255 )).dechex(rand ( 100 , 255 )).dechex(rand ( 100 , 255 )) ;
  }
  
function create_NewSzalag() {
srand(make_seed());

 global $conn;
  $email;$comment;$captcha;
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
  $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
  $captcha = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
  if(!$captcha){
    echo '<h2>Please check the the captcha form.</h2>';
    exit;
  }
  $secretKey = "6LfLH-8UAAAAAO9EPK04rXEunT2rK0-Yuij3i6Zy";
  $ip = $_SERVER['REMOTE_ADDR'];

  // post request to server
  $url = 'https://www.google.com/recaptcha/api/siteverify';
  $data = array('secret' => $secretKey, 'response' => $captcha);

  $options = array(
    'http' => array(
      'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
      'method'  => 'POST',
      'content' => http_build_query($data)
    )
  );
  $context  = stream_context_create($options);
  $response = file_get_contents($url, false, $context);

  $responseKeys = json_decode($response,true);
  header('Content-type: application/json');
  if($responseKeys["success"]) {
	  
	 
	$stmt = $conn->prepare("INSERT INTO kivansag (szerzo,leiras,x,y,szin) VALUES (?,?,?,?,?)");
	 $stmt->bind_param("ssiis", $szerzo,$commentP,$X,$Y,$szin);
	 $commentP=$comment;
	 $szerzo=$email;
	 $X=rand ( 100 , 500 ) ;
	  $Y=rand ( 20 , 430 ) ;
	  $szin=getRendomPastelColour();
	$stmt->execute();

	
	$stmt->close();
    echo json_encode(array('success' => 'true'));
  
  
  
  
  } else {
    echo json_encode(array('success' => 'false'));
  }
}
  
  if(isset($_GET['f']))
{
 switch ($_GET['f']) {
    case 'uj':
        create_NewSzalag();
        break;
    case 'regi':
       get_SzalagDaTa();
        break;
    
	}       // Do something
}


 $captcha = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);

 if(isset($captcha))
{
        create_NewSzalag();
}

?>

<?php
$conn->close();
		
?>