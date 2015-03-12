<?

require_once 'userAccess.php';

// get variable names as they are in the register form
if(import_request_variables('p', 'bam'))
{
	print_r(bam_reg_name);
	print_r(bam_reg_uname);
	print_r(bam_reg_pword);
}

// all variables i have and their values
print_r($_POST);

$connection = mysql_connect("mysql.bamgruz.com", "hoffpauir", "generallee");
$database = mysql_select_db("bamgruz_messageboard");

$result = MYSQL_QUERY("INSERT INTO users (id, bgname, bguname, bgpword) VALUES ('NULL', '$bam_reg_name', '$bam_reg_uname', '$bam_reg_pword')");

?>