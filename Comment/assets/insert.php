<?php
include 'dbcon.php';
include 'comment.php';



if(validate()){
	$data=new datatable();
	
	$data->set_all(' ',$_POST['name'],$_POST['email'],$_POST['text'],$_POST['parent']); //sets comment 
	$data->placeData();} //saves comment
//validates inputs
function validate()
{
$errors         = array();      // array to hold validation errors
$data           = array();      // array to pass back data
$data['success'] = true;
$data['message'] = 'Success!';
    if (empty($_POST['name']))
        $errors['name'] = 'Name is required.';

    if (empty($_POST['email'])||filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)===false)
        $errors['email'] = 'Email is required.';

    if (empty($_POST['text']))
        $errors['text'] = 'Text is required';

    if ( ! empty($errors)) {

        $data['success'] = false;
        $data['errors']  = $errors;
    } else {
		
        $data['success'] = true;
        $data['message'] = 'Success!';
    }

   
    echo json_encode($data);
	return $data['success'];
}
?>