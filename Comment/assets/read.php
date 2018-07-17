
<?php
include 'dbcon.php';
$conn=new db();
$conn=$conn->connect(); //creates connection to db
$sql="select id, name,text,date from comments where parent_id IS NULL order by date asc"; //reads parent comments
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	$rows = array();
	while($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
	}
	echo json_encode($rows); //returns JSON
}

?>
