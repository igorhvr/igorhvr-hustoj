<?php
		ob_start();
		header ( "content-type:   application/file" );
		
?>
<?
require_once("./include/db_info.inc.php");
global $mark_base,$mark_per_problem,$mark_per_punish;
 $mark_base=60;
 $mark_per_problem=10;
 $mark_per_punish=1;
if(isset($OJ_LANG)){
		require_once("./lang/$OJ_LANG.php");
}
require_once("./include/const.inc.php");
require_once("./include/my_func.inc.php");
class TM{
	var $solved=0;
	var $time=0;
	var $p_wa_num;
	var $p_ac_sec;
	var $user_id;
    var $nick;
    var $mark=0;
	function TM(){
		$this->solved=0;
		$this->time=0;
		$this->p_wa_num=array(0);
		$this->p_ac_sec=array(0);
	}
	function Add($pid,$sec,$res,$mark_base,$mark_per_problem,$mark_per_punish){
//		echo "Add $pid $sec $res<br>";
	
		if (isset($this->p_ac_sec[$pid])&&$this->p_ac_sec[$pid]>0)
			return;
		if ($res!=4) 
			if(isset($this->p_wa_num[$pid]))
				$this->p_wa_num[$pid]++;
			else
				$this->p_wa_num[$pid]=1;
		else{
			$this->p_ac_sec[$pid]=$sec;
			$this->solved++;
			$this->time+=$sec+$this->p_wa_num[$pid]*1200;
			if($this->mark==0){
				$this->mark=$mark_base;
			}else{
				$this->mark+=$mark_per_problem;
			}
			if($this->p_wa_num[$pid]*$mark_per_punish<$mark_per_problem)
					$this->mark-=$this->p_wa_num[$pid]*$mark_per_punish;
			
//			echo "Time:".$this->time."<br>";
//			echo "Solved:".$this->solved."<br>";
		}
	}
}

function s_cmp($A,$B){
//	echo "Cmp....<br>";
	if ($A->solved!=$B->solved) return $A->solved<$B->solved;
	else return $A->time>$B->time;
}

// contest start time
if (!isset($_GET['cid'])) die("No Such Contest!");
$cid=intval($_GET['cid']);
//require_once("contest-header.php");
$sql="SELECT `start_time`,`title` FROM `contest` WHERE `contest_id`='$cid'";
$result=mysql_query($sql) or die(mysql_error());
$rows_cnt=mysql_num_rows($result);
$start_time=0;
if ($rows_cnt>0){
	$row=mysql_fetch_array($result);
	$start_time=strtotime($row[0]);
	$title=$row[1];
	header ( "content-disposition:   attachment;   filename=contest".$_GET['cid']."_".$title.".xls" );
}
mysql_free_result($result);
if ($start_time==0){
	echo "No Such Contest";
	//require_once("oj-footer.php");
	exit(0);
}

if ($start_time>time()){
	echo "Contest Not Started!";
	//require_once("oj-footer.php");
	exit(0);
}

$sql="SELECT count(1) FROM `contest_problem` WHERE `contest_id`='$cid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$pid_cnt=intval($row[0]);

$mark_per_problem=(100-$mark_base)/$pid_cnt;
mysql_free_result($result);

$sql="SELECT 
	users.user_id,users.nick,solution.result,solution.num,solution.in_date 
		FROM 
			(select * from solution where solution.contest_id='$cid') solution 
		left join users 
		on users.user_id=solution.user_id 
	ORDER BY users.user_id,in_date";
//echo $sql;
$result=mysql_query($sql);
$user_cnt=0;
$user_name='';
$U=array();
while ($row=mysql_fetch_object($result)){
	$n_user=$row->user_id;
	if (strcmp($user_name,$n_user)){
		$user_cnt++;
		$U[$user_cnt]=new TM();
		$U[$user_cnt]->user_id=$row->user_id;
                $U[$user_cnt]->nick=$row->nick;

		$user_name=$n_user;
	}
	$U[$user_cnt]->Add($row->num,strtotime($row->in_date)-$start_time,intval($row->result),$mark_base,$mark_per_problem,$mark_per_punish);
}
mysql_free_result($result);
usort($U,"s_cmp");
$rank=1;
//echo "<style> td{font-size:14} </style>";
//echo "<title>Contest RankList -- $title</title>";
//echo "<center><h3>Contest RankList -- $title</h3></center>";
echo "<table><tr><td>Rank<td>User<td>Nick<td>Solved<td>Mark";
for ($i=0;$i<$pid_cnt;$i++)
	echo "<td>$PID[$i]";
echo "</tr>";
for ($i=0;$i<$user_cnt;$i++){
	if ($i&1) echo "<tr class=oddrow align=center>";
	else echo "<tr class=evenrow align=center>";
	echo "<td>$rank";
	$rank++;
	$uuid=$U[$i]->user_id;
        
	$usolved=$U[$i]->solved;
	echo "<td>$uuid";
	echo "<td>".$U[$i]->nick."";
	echo "<td>$usolved";
	echo "<td>".($U[$i]->mark);
	for ($j=0;$j<$pid_cnt;$j++){
		echo "<td>";
		if(isset($U[$i])){
			if (isset($U[$i]->p_ac_sec[$j])&&$U[$i]->p_ac_sec[$j]>0)
				echo sec2str($U[$i]->p_ac_sec[$j]);
			if (isset($U[$i]->p_wa_num[$j])&&$U[$i]->p_wa_num[$j]>0) 
				echo "(-".$U[$i]->p_wa_num[$j].")";
		}
	}
	echo "</tr>";
}
echo "</table>";

?>
