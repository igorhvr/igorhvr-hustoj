<?require_once("admin-header.php");
if (!(isset($_SESSION['administrator']))){
	echo "<a href='../loginpage.php'>Please Login First!</a>";
	exit(1);
}?>
<?
if(isset($_POST['do'])){
	$from=intval($_POST['from']);
	$to=intval($_POST['to']);
	$start=intval($_POST['start']);
	$end=intval($_POST['end']);
	$sql="update `solution` set `user_id`='$to' where `user_id`='$from' and problem_id>=$start and problem_id<=$end and result=4";
	echo $sql;
	mysql_query($sql);
	echo mysql_affected_rows()." source file given!";
	
}
?>
<form action='source_give.php' method=post>
	<b>Give source:</b><br />
	From:<input type=text size=10 name="from" value="zhblue"><br />
	To:<input type=text size=10 name="to" value="standard"><br />
	start pid:<input type=text size=10 name="start"><br />
	end pid:<input type=text size=10 name="end"><br />
	<input type='hidden' name='do' value='do'>
	<input type=submit value='GiveMySourceToHim'>
</form>
