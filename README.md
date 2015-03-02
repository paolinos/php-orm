# php-orm - v1 - based on PDO php.
Easy and basic orm with php. Powerfull and help you to access the data allways mapping with a model.
<br>
How to start?<br>
1) Download your ORM folder.<br>
2) Include in your project the "orm.init.php" file, that is inside the ORM folder.<br>
3) Create your DB.<br>
4) Set the connection to DB. 
```
DBConnector::Inst()->setConn('localhost', 'example-orm', 'user', 'pass');
```
5) Create your Models and Mapping file. Your model need to be the same that each table.<br>
Example:
If you a have a table called "User", with this fields:
```
User:{
	id:(int, autoincrement, PK),
	firstname:(varchar),
	lastname:(varchar),
	bornDate:(Date)
}
```

Yo will create a model class with the same properties, extending from DBObject, and add a protected protected property called $_tableName, that will be cointan the real table name.
```
class User extends DBObject{
	//	Properties
	public $id;
	public $firstname;
	public $lastname;
	public $bornDate;


    // Table name
    protected $_tableName = "User";  
}
```
Then, you need to create your mapping class. The name of the mapping class need to be the same that your model class adding the "Mapp" in the end.<br>
Example: Model: User 	=> Mapping: UserMapp
```
class UserMapp {
	public $id = [KEY,INT];
	public $firstname = [STRING];
	public $lastname = [STRING];
	public $bornDate = [DATE];
}
```
Here we add some attributes like KEY, INT, STRING, etc, that we will use in the second version.<br>
But for this version the only attrubte required is KEY.<br>
We need to set the KEY attribute to the PrimaryKey or UNIKE key.<br>

When we did it, we are ready to start using this <b>Fantastic ORM</b>
<br>

When your model extends from <b>DBObject</b>, you have some static method and other methods<br>
<div>Static methods</div>
<ul>
	<li>get</li>
	<li>getOne</li>
</ul>
<div>Inherit methods</div>
<ul>
	<li>save</li>
	<li>update</li>
</ul>
Your static method are to find your model. You only need to call your classname model and the method.<br>

<h3>get</h3>
```
static function get($where=[], $order=[],$limit=0,$skip=0)
```
When:<br>
$where => array. The first parameter is the query, and the other are the values to replace.
<br>Example:<br>

```
//	Find by name='jhon'
$where = array("name='{0}'","jhon");

//	Find by firstname='jhon' and lastname='doe'
$where = array("firstname='{0}' AND lastname='{1}'","jhon","doe");
$where = array("firstname=like '{0}' AND lastname like '{1}'","jhon","doe");

User::get($where)
```
$order => is an array too, but you need to send the parameter and the order.
<br>Example:<br>
```
$order = array("id"=>"ASC");

$order = array("id"=>"DESC");

$order = array("id"=>"ASC", "name"=>"DESC");

User::get([],$order);
```
Then $limit and $skip, are numbers values.<br>
<h3>getOne</h3>
```
static function getOne($where=[], $order=[])
```
Has the same filter like <b>get</b> and allways with get a One fild as result.
```
//	Find by id=5
User::getOne(array("id={0}",5));

//	Get the oldest user
User::getOne([], array("bornDate"=>"ASC"));

```

<br><br>
One time that we have the model, we can update, or we can create one.
<h3>save</h3>
```
$user = new User();
$user->firstname = "John";
$user->lastname = "Mclane";
$user->bornDate = '1950-01-01';
$user->save();
``` 

The <b>save</b> method will insert the user in the db.<br>

<h3>update</h3>
To update a model, first we need to get the model, then change de values, and then update!
```
$user = User::getOne(array("firstname='{0}'' AND lastname='{1}'","John","Mclane"))
if($user != null)
{
	$user->lastname = "McClane";
	$user->update();
}
``` 
<h3>delete</h3>
```
$user = User::getOne(array("firstname='{0}'' AND lastname='{1}'","John","Mclane"))
if($user != null)
{
	$user->delete();
}
``` 
Also you have a:
```
try{
	DBConnector::Inst()->beginTransaction();	
	/*
		Put your queries, update, delete, etc.
	*/
	DBConnector::Inst()->commit();
}
catch (Exception $e) {
	DBConnector::Inst()->rollback();
}
```

And you can get the last Id, doing:
```
DBConnector::Inst()->lastId();
```