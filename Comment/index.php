<!DOCTYPE html>
<html lang="en">
<title>Coments</title>
<head>

<style>
.form{
	display:none;
}

.v{
	border: none;
    height: 5px;
   
    color: #333; /* old IE */
    background-color: #9400D3;
}
</style>

    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css"> <!-- load bootstrap via CDN -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script> <!-- load jquery via CDN -->
   
</head>
<body>

<div class ="container">

<hr class="v">
    <h1 id="title">Comment Form</h1>
<hr class="v">
    <!-- OUR FORM -->
    <form id="form1" action="assets/insert.php" method="POST">

        <!-- NAME -->
        <div id="name-group" class="form-group">
            <label for="name">Name*</label>
            <input id="name" type="text" class="form-control" name="name" placeholder="name">
            <!-- errors will go here -->
        </div>

        <!-- EMAIL -->
        <div id="email-group" class="form-group">
            <label for="email">Email*</label>
            <input id="email" type="text" class="form-control" name="email" placeholder="example@ecample.com">
            <!-- errors will go here -->
        </div>

        <!-- SUPERHERO ALIAS -->
        <div id="text-group" class="form-group">
            <label for="text">Comment*</label>
            <textarea id="text" class="form-control" name="text" placeholder="text"></textarea>
            <!-- errors will go here -->
        </div>
		 <div id="hiden-group" class="form-group">
            <input type="hidden" id="parent" name="parent" value="0">
            <!-- errors will go here -->
        </div>
		
        <button type="submit" class="btn">Submit <span class="fa fa-arrow-right"></span></button>

    </form>


</div>
<div class ="container">

<h1>Comments</h1>
<div id="comments">
<ul class="list-group" id="com">
</ul>
</div>
</div>


<script>


$(document).ready(function() {
var ajax= new XMLHttpRequest();
var method ="GET";
var url="assets/read.php";
var asynch=true;

//saves date of last comment read as a starting point
var lastdate=' ';

//reading comments

setInterval (function(){

ajax.open(method,url,asynch);
ajax.send();
ajax.onreadystatechange=function()
{
	if(this.readyState==4&&this.status==200)
	{
		if(this.responseText.length>2)
		{
		
		var data=JSON.parse(this.responseText);
		
		data.forEach(function(element) {
			// if there are new comments myFunction() appends them to top
			if(lastdate<element.date)
				myFunction(element);
			
		})
		
		//sets last date as last read element
		leng=data.length;
		lastdate=data[leng-1].date;
		
		}
	}
}
},1000);


//saves date of last subcomment read as a starting point
var lastsubdate='';

setInterval (function(){

ajax.open(method,"assets/readsub.php",asynch);
ajax.send();
ajax.onreadystatechange=function()
{
	if(this.readyState==4&&this.status==200)
	{
		if(this.responseText.length>2)
		{
		
		var data=JSON.parse(this.responseText);
		
		data.forEach(function(element) {
			// if there are new comments myFunctionSub() appends them to top
			if(lastsubdate<element.date)
				myFunctionSub(element);
			
		})
		leng=data.length;
		lastsubdate=data[leng-1].date;
		
		}
	}
}
},1500);
// appends sub comments 
function myFunctionSub(data) {

   $(document.getElementById(data.parent_id+"parent")).prepend("<div style='padding-top: 10px;'><li id="+data.id+" class='list-group-item' ><p><b><span style='margin-right:10px'>"+data.name+"</span></b><b><span>"+data.date+"</span></b></p>"+data.text+"</li></div>"); 
  var obj=data
}
//appends parrent comments
function myFunction(data) {
   
    //node.appendChild(textnode); 
	
	
   //document.getElementById("notif"); 
   
   $(document.getElementById("com")).prepend("<div id="+data.id+" style='padding-top:10px'><li class='list-group-item' <p><b><span style='margin-right:10px'>"+data.name+"</span></b><b><span>"+data.date+"</span></b><button style='float:right'class='reply' onclick=displayform("+data.id+")>reply</button></p>"+data.text+"</li><div>");
   //appends form to create subcomments
   
   $(document.getElementById(data.id)).append('<form class="form" id='+data.id+'form action="assets/insert.php" onsubmit="input('+data.id+')" method="POST"><div id="name-group" class="form-group"><label for="name">Name*</label><input type="text" class="form-control" name="name" placeholder="name"></div><div id="email-group" class="form-group"><label for="email">Email*</label><input type="text" class="form-control" name="email" placeholder="example@ecample.com"></div> <div id="text-group" class="form-group"><label for="text">Comment*</label><textarea  class="form-control" name="text" placeholder="text"></textarea></div><div id="hiden-group" class="form-group"><input type="hidden" id="parent" name="parent" value='+data.id+'></div><button type="button"onclick="input('+data.id+')" id="submit" class="btn ">Submit <span class="fa fa-arrow-right"></span></button></form>');
   //defines subcomment list
   $(document.getElementById(data.id)).append("<ul id="+data.id+"parent class='list-group' style='padding-left: 100px;padding-top:10px'/>");
   
 
}


});

//saves subcomments to database




function input(location){
	//var event;
	//var event = document.createEvent('Event');
	//event.initEvent('build', true, true);
	//event.preventDefault();
	//removes error
	$('.text-danger').remove();
	 
	 var x = document.getElementById(location+"form").getElementsByTagName("input");
	 var z = document.getElementById(location+"form").getElementsByTagName("textarea");
	
	var formData = {
            'name'              : x[0].value,
            'email'             : x[1].value,
            'text'    			: z[0].value,
			'parent'			: x[2].value
        };
		
		 $.ajax({
            type        : 'POST', 
            url         : 'assets/insert.php',
            data        : formData, 
            dataType    : 'json',
                        encode          : true
        }).done(function(data) {
                console.log(data); 
		if ( ! data.success) {

				x=document.getElementById(location+"form").getElementsByTagName("div");
				$('.text-danger').remove();
            if (data.errors.name) {
               
				$(x[0]).append("<span class=' text-danger'>"+ data.errors.name +"</span>"); // add the actual error 
            }
            if (data.errors.email) {
				$(x[1]).append("<span class=' text-danger'>"+ data.errors.email +"</span>");
               }
            if (data.errors.text) {
				$(x[2]).append("<span class=' text-danger'>"+ data.errors.text +"</span>");
               }

        } 
		else {
			
            //alert('success'); // if comment is posted for testing purpouse
			displayform(location) //hides form
        }
		
               
            });
			// stop the form from submitting the normal way and refreshing the page
	//event.preventDefault();
		
}

//displays subcomment form.
function displayform(data)
{
	 //event.preventDefault();
	var x = document.getElementById(data+"form");
    if (x.style.display === "block") {
        x.style.display = "none";
	document.getElementById("title").innerHTML = "Comment form";
    } else {
        x.style.display = "block";
	document.getElementById("title").innerHTML = "Clicked to reply";
    }	
}



    // process the form
    $('form').submit(function(event) {
	$('.form-group').removeClass('has-error'); // remove the error class
    $('.help-block').remove();
	
        var formData = {
            'name'              : $('input[name=name]').val(),
            'email'             : $('input[name=email]').val(),
            'text'    			: $('textarea[name=text]').val(),
			'parent'			: $('input[name=parent]').val()
        };

        // process the form
        $.ajax({
            type        : 'POST', 
            url         : 'assets/insert.php',
            data        : formData, 
            dataType    : 'json',
                        encode          : true
        })
          
            .done(function(data) {
                console.log(data); 
				 if ( ! data.success) {

           
            if (data.errors.name) {
                $('#name-group').addClass('has-error'); // add the error class to show red input
                $('#name-group').append('<div class="help-block">' + data.errors.name + '</div>'); // add the actual error 
            }
            if (data.errors.email) {
                $('#email-group').addClass('has-error'); // add the error class to show red input
                $('#email-group').append('<div class="help-block">' + data.errors.email + '</div>'); // add the actual error
            }
            if (data.errors.text) {
                $('#text-group').addClass('has-error'); // add the error class to show red input
                $('#text-group').append('<div class="help-block">' + data.errors.text + '</div>'); // add the actual error 
            }

        } else {

            //alert('success'); // if comment is posted for testing purpouse

        }
               
            });

        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });


</script>
</body>
</html>







