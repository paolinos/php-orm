<?php

//	Include ORM
//require_once __DIR__ . "/../orm/orm.init.php";
require_once "./../orm/orm.init.php";

// Set DB connection
DBConnector::Inst()->setConn('localhost', 'example-orm', 'user', 'pass');


class User extends DBObject{

	//	Properties
	public $id;
	public $firstname;
	public $lastname;
	public $born_date;
    public $countryId;


    // Table name
    protected $_tableName = "User";    
}

class UserMapp {

	//	KEY is important. We use the KEY value to update and delete the objects.
	//	the field with the KEY value, need to be unike, like your PrimaryKey.
	public $id = [KEY,INT];
	public $firstname = [STRING];
	public $lastname = [STRING];
	public $born_date = [DATE];
    public $countryId = [INT];
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="">
</head>
<body>
	<div>
		For documentation see:
		<a href="https://github.com/paolinos/php-orm" target="_blank">github.com/paolinos/php-orm</a>
	</div>


	<h3>Show all Users</h3>
	<div>
		<span>
			To get all user we need to use te <b>get</b> method
			<br>
			like: <b>User::get()</b>
		</span>
	</div>
	<br>
	<?php

	//	Allways we obtain a User object
	$allUsers = User::get();

	foreach ($allUsers as $user) {
		echo("<div>");
		echo("<div>Name: " . $user->firstname . " " . $user->lastname . " - CountryId:". $user->countryId ." </div>" );
		echo("</div>");
	}

	?>

	<h3>Get User by Id</h3>
	<div>
		<span>
			To get One user we need to use te <b>getOne</b> method and send the query.
			<br>
			like: <b>User::getOne(array('id={0}', 2))</b>
			<br> the '{0}' value will be reaplce with the first value in the array.
		</span>
	</div>
	<br>
	<div>
	<?php

	//	Allways we obtain a User object
	$oneUser = User::getOne(array('id={0}', 2) );
	echo("<div>Name: " . $oneUser->firstname . " " . $oneUser->lastname . "</div>" );
	?>
	</div>

</body>
</html>


