<?require_once("admin-header.php");?>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<title>Add a contest</title>

<?
  
if (isset($_POST['syear']))
{
	require_once("../include/db_info.inc.php");
	$starttime=$_POST['syear']."-".$_POST['smonth']."-".$_POST['sday']." ".$_POST['shour'].":".$_POST['sminute'].":00";
	$endtime=$_POST['eyear']."-".$_POST['emonth']."-".$_POST['eday']." ".$_POST['ehour'].":".$_POST['eminute'].":00";
//	echo $starttime;
//	echo $endtime;
	
	$title=$_POST['title'];
	$private=$_POST['private'];
	
   $lang=$_POST['lang'];
   $langmask=0;
   foreach($lang as $t){
			$langmask+=1<<$t;
	} 
	$langmask=15&(~$langmask);
	//echo $langmask;	
	
	$sql="INSERT INTO `contest`(`title`,`start_time`,`end_time`,`private`,`langmask`)
		VALUES('$title','$starttime','$endtime','$private',$langmask)";
//	echo $sql;
	mysql_query($sql) or die(mysql_error());
	$cid=mysql_insert_id();
	echo "Add Contest ".$cid;
	$sql="DELETE FROM `contest_problem` WHERE `contest_id`=$cid";
	$pieces = explode(",", trim($_POST['cproblem']));
	if (count($pieces)>0 && strlen($pieces[0])>0){
		$sql_1="INSERT INTO `contest_problem`(`contest_id`,`problem_id`,`num`) 
			VALUES ('$cid','$pieces[0]',0)";
		for ($i=1;$i<count($pieces);$i++){
			$sql_1=$sql_1.",('$cid','$pieces[$i]',$i)";
		}
		echo $sql_1;
		mysql_query($sql_1) or die(mysql_error());
	}
	$sql="DELETE FROM `privilege` WHERE `rightstr`='c$cid'";
	mysql_query($sql);
	$pieces = explode("\n", trim($_POST['ulist']));
	if (count($pieces)>0 && strlen($pieces[0])>0){
		$sql_1="INSERT INTO `privilege`(`user_id`,`rightstr`) 
			VALUES ('".trim($pieces[0])."','c$cid')";
		for ($i=1;$i<count($pieces);$i++)
			$sql_1=$sql_1.",('".trim($pieces[$i])."','c$cid')";
		//echo $sql_1;
		mysql_query($sql_1) or die(mysql_error());
	}
		
}
else{
   $cid=$_GET['cid'];
   if(isset($cid)){
		   $sql="select * from contest WHERE `contest_id`='$cid'";
		   $result=mysql_query($sql) or die(mysql_error());
		   $row=mysql_fetch_object($result);
		   $title=$row->title;
		   mysql_free_result($result);
			$plist="";
			$sql="SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`=$cid ORDER BY `num`";
			$result=mysql_query($sql) or die(mysql_error());
			for ($i=mysql_num_rows($result);$i>0;$i--){
				$row=mysql_fetch_row($result);
				$plist=$plist.$row[0];
				if ($i>1) $plist=$plist.',';
			}
   }
  
   
?>
	
	<form method=POST action='<?=$_SERVER['PHP_SELF']?>'>
	<p align=center><font size=4 color=#333399>Add a Contest</font></p>
	<p align=left>Title:<input type=text name=title size=71 value="<?=$title?$title:""?>"></p>
	<p align=left>Start Time:<br>&nbsp;&nbsp;&nbsp;
	Year:<input type=text name=syear value=<?=date('Y')?> size=7 >
	Month:<input type=text name=smonth value=<?=date('m')?> size=7 >
	Day:<input type=text name=sday size=7 value=<?=date('d')?> >&nbsp;
	Hour:<input type=text name=shour size=7 value=<?=date('h')?>>&nbsp;
	Minute:<input type=text name=sminute value=00 size=7 ></p>
	<p align=left>End Time:<br>&nbsp;&nbsp;&nbsp;
	Year:<input type=text name=eyear value=<?=date('Y')?> size=7 >
	Month:<input type=text name=emonth value=<?=date('m')?> size=7 >
	
	Day:<input type=text name=eday size=7 value=<?=date('d')?>>&nbsp;
	Hour:<input type=text name=ehour size=7 value=<?=date('h')?>>&nbsp;
	Minute:<input type=text name=eminute value=00 size=7 ></p>
	Public:<select name=private><option value=0>Public</option><option value=1>Private</option></select>
	Language:<select name="lang[]" multiple>
		<option value=0 selected>C</option>
		<option value=1 selected>C++</option>
		<option value=2 selected>Pascal</option>
		<option value=3 selected>Java</option>	
	</select>
	<br>Problems:<input type=text size=60 name=cproblem value="<?=$plist?$plist:""?>">
	<br>
	Users:<textarea name="ulist" rows="10" cols="20"></textarea>
	<br />
	*可以将学生学号从Excel整列复制过来，然后要求他们用学号做UserID注册,就能进入Private的比赛作为作业和测验。
	<p><input type=submit value=Submit name=submit><input type=reset value=Reset name=reset></p>
	</form>
<?
}
require_once("../oj-footer.php");

?>

