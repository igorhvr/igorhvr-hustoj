<?require_once("admin-header.php");?>
<?
if(isset($_POST['do'])){
	$user_id=addslashes($_POST['user_id']);
	$rightstr =$_POST['rightstr'];
	$sql="insert into `privilege` values('$user_id','$rightstr','N')";
	mysql_query($sql);
	if (mysql_affected_rows()==1) echo "$user_id $rightstr added!";
	else echo "No such user!";
}
?>
<form method=post>
	<b>Add privilege for User:</b><br />
	User:<input type=text size=10 name="user_id"><br />
	Privilege:
	<select name="rightstr">
<?php
$rightarray=array("administrator","source_browser" );
while(list($key, $val)=each($rightarray)) {
	if (isset($rightstr) && ($rightstr == $val)) {
		echo '<option value="'.$val.'" selected>'.$val.'</option>';
	} else {
		echo '<option value="'.$val.'">'.$val.'</option>';
	}
}
?></select><br />
	<input type='hidden' name='do' value='do'>
	<input type=submit value='Add'>
</form>
<form method=post>
	<b>Add contest for User:</b><br />
	User:<input type=text size=10 name="user_id"><br />
	Contest:<input type=text size=10 name="rightstr">c1000 for Contest1000<br />
	<input type='hidden' name='do' value='do'>
	<input type=submit value='Add'>
</form>
