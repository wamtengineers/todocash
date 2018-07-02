# todocash
A ready to use Point of Sale system for small cafes or shops. Multi user access level and full control on inventory and sales.

# Installation

1. Clone the repository in your document root folder. (You must have xampp/lamp installed on your pc.)
2. Create new database in mysql and import db/db.sql.
3. Open include/db.php in editor and replace the database details in following lines and save the file.

$db_host="HOST";
$db_username="USER";
$db_password="PASSWORD";
$db_name="DATABASE_NAME";

4. Run http://localhost/ in browser and login with these details

Username: admin
Password: admin
