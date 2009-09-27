<?require_once("./include/db_info.inc.php")?>
<?
if (!isset($_SESSION['user_id'])){
	require_once("oj-header.php");
	echo "<a href='loginpage.php'>Please Login First!</a>";
	require_once("oj-footer.php");
	exit(0);
}
$user_id=$_SESSION['user_id'];
if (isset($_POST['id'])) $id=intval($_POST['id']);
else if (isset($_POST['pid']) && isset($_POST['cid'])){
	$pid=intval($_POST['pid']);
	$cid=intval($_POST['cid']);
	// check user if private
	$sql="SELECT `private` FROM `contest` WHERE `contest_id`='$cid' AND `start_time`<=NOW() AND `end_time`>NOW()";
	$result=mysql_query($sql);
	$rows_cnt=mysql_num_rows($result);
	if ($rows_cnt!=1){
		echo "You Can't Submit Now Because Your are not invited by the contest or the contest is not running!!";
		mysql_free_result($result);
		require_once("oj-footer.php");
		exit(0);
	}else{
		$row=mysql_fetch_array($result);
		$isprivate=intval($row[0]);
		mysql_free_result($result);
		if ($isprivate==1){
			$sql="SELECT count(*) FROM `privilege` WHERE `user_id`='$user_id' AND `rightstr`='c$cid'";
			$result=mysql_query($sql) or die (mysql_error()); 
			$row=mysql_fetch_array($result);
			$ccnt=intval($row[0]);
			mysql_free_result($result);
			if ($ccnt==0){
				require_once("contest-header.php");
				echo "You are not invited!\n";
				require_once("oj-footer.php");
				exit(0);
			}
		}
	}
	$sql="SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`='$cid' AND `num`='$pid'";
	$result=mysql_query($sql);
	$rows_cnt=mysql_num_rows($result);
	if ($rows_cnt!=1){
		require_once("contest-header.php");
		echo "<h2>No Such Problem!</h2>";
		require_once("oj-footer.php");
		mysql_free_result($result);
		exit(0);
	}else{
		$row=mysql_fetch_object($result);
		$id=intval($row->problem_id);
		mysql_free_result($result);
	}
}else{
	echo "<h2>No Such Problem!</h2>";
	exit(0);
}
$source=trim($_POST['source']);
$len=strlen($source);
$language=intval($_POST['language']);
if ($language>3 || $language<0) $language=0;
$language=strval($language);
$ip=$_SERVER['REMOTE_ADDR'];
//$source=mysql_escape_string($source);
if ($len<20){
	require_once("oj-header.php");
	echo "Source Code Too Short!";
	require_once("oj-footer.php");
	exit(0);
}
if ($len>65536){
	require_once("oj-header.php");
	echo "Source Code Too Long!";
	require_once("oj-footer.php");
	exit(0);
}

// last submit

$sql="SELECT `in_date` from `solution` where `user_id`='$user_id' order by `in_date` desc limit 1";
$res=mysql_query($sql);
if (mysql_num_rows($res)==1){
	$row=mysql_fetch_row($res);
	$last=strtotime($row[0]);
	$cur=time()+28800;
	if ($cur-$last<10){
		require_once('oj-header.php');
		echo "You should not submit more than twice in 10 seconds.....<br>";
		require_once('oj-footer.php');
		exit(0);
	}
}

if (!isset($pid)){
$sql="INSERT INTO solution(problem_id,user_id,in_date,language,ip,code_length)
	VALUES('$id','$user_id',NOW(),'$language','$ip','$len')";
}else{
$sql="INSERT INTO solution(problem_id,user_id,in_date,language,ip,code_length,contest_id,num)
	VALUES('$id','$user_id',NOW(),'$language','$ip','$len','$cid','$pid')";
}
mysql_query($sql);
$insert_id=mysql_insert_id();
$source=addslashes($source);
$sql="INSERT INTO `source_code`(`solution_id`,`source`)VALUES('$insert_id','$source')";
mysql_query($sql);
//echo $sql;
if (!isset($cid)) header("Location: ./status.php");
else header("Location: ./status.php?cid=$cid");
?>
