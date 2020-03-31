<?php
require_once('db_config.php');
$method = $_SERVER['REQUEST_METHOD'];
//$request = explode('/',trim($_SERVER['PATH_INFO'],'/'));
//
switch($method)
{
   case 'GET':
    $id=$_GET['id'];
    $stmt= $DB_con->prepare("SELECT * FROM contacts WHERE id=:id");
    $stmt->bindParam(":id",$id);
    break;
case 'POST':
    $name = $_POST['name'];
    $email = $_POST['email'];
    $country= $_POST['country'];
    $city= $_POST['city'];
    $job=$_POST['job'];
    $stmt= $DB_con->prepare("INSERT INTO contacts(name,email,country,city,job)
                             VALUES(:name,:email,:country,:city,:job)");
    $stmt->bindParam(":name",$name);
    $stmt->bindParam(":email",$email);
    $stmt->bindParam(":country",$country);
    $stmt->bindParam(":city",$city);
    $stmt->bindParam(":job",$job);
    //$stmt2->execute();
    break;
}
$results = $stmt->execute();
if(!$results)
{
    http_response_code(404);
    echo "Error";
}
if($method == 'GET')
{
    if(!$id)
    echo '[';
    for($i=0;$i<$stmt->rowCount(); $i++)
    {
        echo ($i>0?',':'').json_encode($stmt->fetch(PDO::FETCH_OBJECT));
    }
    if(!$id) echo ']';
}
elseif($method=="POST")
{
    echo json_encode($results);
}
else
{
   echo $stmt->rowCount(); 
}
?>