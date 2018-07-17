<?php
include 'dbcon.php';
$conn=new db();
$conn=$conn->connect();
$sql="select id, name,text,date,parent_id from comments where parent_id!=0 order by date asc"; //reads child comments

$result = $conn->query($sql);

if ($result->num_rows > 0) {
	$rows = array();
	while($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
	}
	echo json_encode($rows);
}

?>