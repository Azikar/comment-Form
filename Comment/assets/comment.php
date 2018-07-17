<?php
class datatable extends db{
private $id;
private $email;
private $name;
private $text;
private $parent_id;
	
	
public function set_all($id,$name,$email,$text,$parent){ //sets variables
		$this->id=$id;
		$this->email=$email;
		$this->name=$name;
		$this->text=$text;	
		$this->parent_id=$parent;
	}
public function test_input($data) { 
  $data = trim($data);     			//removes whitespace and other predefined characters from both sides of a string.
  $data = stripslashes($data);		// removes backslashes
  $data = htmlspecialchars($data);	//converts some predefined characters to HTML entities.
  $data= strip_tags($data);  		//strips a string from HTML, XML, and PHP tags.
  return $data;
} 	
public function placeData()
	{
			$conn=$this->connect();			
			$email=$this->test_input($this->email);
			$name=$this->test_input($this->name);
			$text=$this->test_input($this->text);
			$parent_id=$this->test_input($this->parent_id);
			if($parent_id==0){ //if comments doesn't have parent then parent_id is null
				$stmt = $conn->prepare("INSERT INTO comments (email,name,text,parent_id,date) VALUES (?, ?,?,NULL,CURRENT_TIMESTAMP())");
				$stmt->bind_param("sss", $email,$name,$text);}
			else{ //else inserts parent id
			$stmt = $conn->prepare("INSERT INTO comments (email,name,text,parent_id,date) VALUES (?, ?,?,?,CURRENT_TIMESTAMP())");
			$stmt->bind_param("ssss", $email,$name,$text,$parent_id);
			}
    		
			$stmt->execute();
}


}
?>
