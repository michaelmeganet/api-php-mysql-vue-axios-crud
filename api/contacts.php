<?php
$host = "localhost"; 
$user = "root"; 
$password = "5105458"; 
$dbname = "vuedb"; 
$id = '';
$server_method = $_SERVER['REQUEST_METHOD'];


echo "\$server_method = $server_method <br>";
print_r($_SERVER);
echo "<br>";
$con = mysqli_connect($host, $user, $password,$dbname);

$method = $_SERVER['REQUEST_METHOD'];
//$path = $_SERVER['PATH_INFO'];
$URI =$_SERVER['REQUEST_URI'];
//$SCRIPT_NAME = $_SERVER['SCRIPT_NAME'];
echo "\$URI = $URI  <br>";
$request = explode('/', trim($URI,'/'));
echo "list down array \$request: <br>";
print_r($request);
echo "<br>";
//$input = json_decode(file_get_contents('php://input'),true);


if (!$con) {
  die("Connection failed: " . mysqli_connect_error());
}


// switch ($method) {
//     case 'GET':
//       //$id = $_GET['id'];
//      // $sql = "select * from contacts".($id?" where id=$id":''); 
//       break;
//     case 'POST':
//       $name = $_POST["name"];
//       $email = $_POST["email"];
//       $country = $_POST["country"];
//       $city = $_POST["city"];
//       $job = $_POST["job"];

//       $sql = "insert into contacts (name, email, city, country, job) values ('$name', '$email', '$city', '$country', '$job')"; 
//       break;
// }
$sql = "select * from contacts";
// run SQL statement
$result = mysqli_query($con,$sql);

// die if SQL statement failed
if (!$result) {
  http_response_code(404);
  die(mysqli_error($con));
}

if ($method == 'GET') {
    if (!$id) echo '[';
    for ($i=0 ; $i<mysqli_num_rows($result) ; $i++) {
      echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
    }
    if (!$id) echo ']';
  } elseif ($method == 'POST') {
    echo json_encode($result);
  } else {
    echo mysqli_affected_rows($con);
  }

$con->close();