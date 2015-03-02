# php-orm - v1
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

