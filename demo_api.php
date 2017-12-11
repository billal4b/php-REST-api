<?php
header("Content-type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

  case 'GET':
    getData();
 // echo '{"output" : "get request "}';
    break;

  case 'POST':
     $data = json_decode(file_get_contents('php://input'),true);
     postData($data);
    break;

  case 'PUT':
    $data = json_decode(file_get_contents('php://input'),true);
    putData($data);
    break;

  case 'DELETE':
    $data = json_decode(file_get_contents('php://input'),true);
    deleteData($data);
    break;

  default:
    echo '{"output" : "request not match"}';
    break;
}


function getData(){
  require_once "db.php";

  $sql = $conn->query("SELECT * FROM new_table");
  $flag = false;
  while($result = $sql->fetch(PDO::FETCH_ASSOC))  {
    $rows["result"][] = $result;
    $flag = true;
  }

  if($flag){
    echo json_encode($rows);
  } else {
      echo '{"result":"No data found"}';
  }
  return;
}

function postData($data){
    require_once "db.php";

    $name = $data["name"];
    $phone= $data["phone"];

    $sql = $conn->prepare("insert into new_table(name,phone,time) values ('$name','$phone', now())");
    $sql->execute();
    if ($sql) {
      echo '{"result" : "success"}';
    } else {
      echo '{"result" : "sql error" }';
    }
}

function putData($data){
  require_once "db.php";
  $id = $data["id"];
  $name = $data["name"];
  $phone= $data["phone"];

  $sql = $conn->prepare("UPDATE new_table SET name='$name',phone='$phone',time =now() WHERE id='$id'");
  $sql->execute();

  if ($sql) {
    echo '{"result" : "success"}';
  } else {
    echo '{"result" : "sql error" }';
  }

}

function deleteData($data){
  require_once "db.php";

  $id = $data["id"];
  $sql = $conn->prepare("DELETE FROM new_table WHERE id='$id' ");
  $sql->execute();

  if ($sql) {
    echo '{"result" : "success"}';
  } else {
    echo '{"result" : "sql error" }';
  }

}
