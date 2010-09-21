<?
require_once ("../include/db_info.inc.php");

function getTestFileIn($pid, $testfile,$OJ_DATA) {
	if ($testfile != "")
		return file_get_contents ( "$OJ_DATA/$pid/" . $testfile . ".in" );
	else
		return "";
}
function getTestFileOut($pid, $testfile,$OJ_DATA) {
	if ($testfile != "")
		return file_get_contents (  );
	else
		return "";
}
function printTestCases($pid,$OJ_DATA){
	$ret = "";
	$pdir = opendir ( "$OJ_DATA/$pid/" );
	while ( $file = readdir ( $pdir ) ) {
		$pinfo = pathinfo ( $file );
		if ($pinfo ['extension'] == "in" && $pinfo ['basename'] != "sample.in") {
			$ret = basename ( $pinfo ['basename'], "." . $pinfo ['extension'] );
			
			$outfile="$OJ_DATA/$pid/" . $ret . ".out";
			$infile="$OJ_DATA/$pid/" . $ret . ".in";
			if(file_exists($infile)){
				echo "<test_input><![CDATA[".file_get_contents ($infile)."]]></test_input>\n";
			}if(file_exists($outfile)){
				echo "<test_output><![CDATA[".file_get_contents ($outfile)."]]></test_output>\n";
			}
//			break;
		}
	}
	closedir ( $pdir );
	return $ret;
}
class Solution{
  var $language="";
  var $source_code="";	
}
function getSolution($pid){
	$ret=new Solution();
	require("../include/const.inc.php");
	require("../include/db_info.inc.php");
	$con = mysql_connect($DB_HOST,$DB_USER,$DB_PASS);
	if (!$con)
    {
  	    die('Could not connect: ' . mysql_error());
    }
	mysql_query("set names utf8",$con);
	mysql_set_charset("utf8",$con);
	mysql_select_db($DB_NAME,$con);
	$sql = "select `solution_id`,`language` from solution where problem_id=$pid and result=4 order by language";
//	echo $sql;
	$result = mysql_query($sql,$con ) ;
	if($result&&$row = mysql_fetch_row ( $result) ){
		$solution_id=$row[0];
		$ret->language=$language_name[$row[1]];
		
		mysql_free_result($result);
		$sql = "select source from source_code where solution_id=$solution_id";
		$result = mysql_query ( $sql ) or die ( mysql_error () );
		if($row = mysql_fetch_object ( $result) ){
			$ret->source_code=$row->source;
			
		}
		mysql_free_result($result);
	}
    mysql_close($con);
	return $ret;
}
function fixurl($img_url){
   $img_url=htmlspecialchars_decode( $img_url);
   
	if (substr($img_url,0,7)!="http://"){
	  if(substr($img_url,0,1)=="/"){
	     	$ret='http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$img_url;
     }else{
     		$path= dirname($_SERVER['PHP_SELF']);
	      $ret='http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$path."/../".$img_url;
     }
   }else{
   	$ret=$img_url;
   }
   return  $ret;
} 
function image_base64_encode($img_url){
    $img_url=fixurl($img_url);
	$handle = @fopen($img_url, "rb");
	if($handle){
		$contents = stream_get_contents($handle);
		$encoded_img= base64_encode($contents);
		fclose($handle);
		return $encoded_img;
	}else
		return false;
}
function getImages($content){
    preg_match_all("<[iI][mM][gG][^<>]+[sS][rR][cC]=\"?([^ \"\>]+)/?>",$content,$images);
    return $images;
}
session_start ();
if (! isset ( $_SESSION ['administrator'] )) {
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "<a href='../loginpage.php'>Please Login First!</a>";
	exit ( 1 );
}


if (isset($_POST ['do'])||isset($_GET['cid'])) {
   if(isset($_POST ['in'])&&strlen($_POST ['in'])>0){
   	$in=addslashes ( $_POST ['in'] );
   	$sql = "select * from problem where problem_id in($in)";
   }else if (isset($_GET['cid'])){
      $cid=intval( $_GET['cid'] );
      $sql = "select * from problem where problem_id in(select problem_id from contest_problem where contest_id=$cid)";
   }else{
	   $start = addslashes ( $_POST ['start'] );
		$end = addslashes ( $_POST ['end'] );
		$sql = "select * from problem where problem_id>=$start and problem_id<=$end";
   }

	
	//echo $sql;
	$result = mysql_query ( $sql ) or die ( mysql_error () );
	
	if ($_POST ['submit'] == "Export")
		header ( 'Content-Type:   text/xml' );
	else {
		header ( "content-type:   application/file" );
		header ( "content-disposition:   attachment;   filename=fps-".$_SESSION['user_id']."-$start-$end.xml" );
	}
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
	?>
 
<fps version="1.1" url="http://code.google.com/p/freeproblemset/">
	<generator name="HUSTOJ" url="http://code.google.com/p/hustoj/"/>
	<?
	while ( $row = mysql_fetch_object ( $result ) ) {
		
		?>
<item>
<title><![CDATA[<?=$row->title?>]]></title>
<time_limit><![CDATA[<?=$row->time_limit?>]]></time_limit>
<memory_limit><![CDATA[<?=$row->memory_limit?>]]></memory_limit>

<? 
   $description=$row->description;
   $images=getImages($description);
   foreach($images[1] as $img){
		  $description=str_replace($img,fixurl($img),$description); 
          $base64=image_base64_encode($img);
          if($base64){
			  echo "<img><src><![CDATA[";
			  echo fixurl($img);
			  echo "]]></src><base64><![CDATA[";
			  echo $base64;
			  echo "]]></base64></img>";
	      
		 }
      
   }
?>
<description><![CDATA[<?=$description?>]]></description>
<input><![CDATA[<?=$row->input?>]]></input> 
<output><![CDATA[<?=$row->output?>]]></output>
<sample_input><![CDATA[<?=$row->sample_input?>]]></sample_input>
<sample_output><![CDATA[<?=$row->sample_output?>]]></sample_output>
<?php printTestCases($row->problem_id,$OJ_DATA)?>
<hint><![CDATA[<?=$row->hint?>]]></hint>
<source><![CDATA[<?=$row->source?>]]></source>
<?
$solution=getSolution($row->problem_id);
if ($solution->language){?>
<solution language="<?=$solution->language?>"><![CDATA[<?=$solution->source_code?>]]></solution>
<?}?>
<![CDATA[<?
 if($row->spj!=0){
 	$filec="$OJ_DATA/".$row->problem_id."/spj.c";
 	$filecc="$OJ_DATA/".$row->problem_id."/spj.cc";
 	
 	if(file_exists( $filec )){
		echo "<spj language=\"C\">";
 		echo file_get_contents ($filec );
 		echo "</spj>";
	}
 	elseif(file_exists( $filecc )){
 	    echo "<spj language=\"C++\">";
 		echo file_get_contents ($filecc );
 		echo "</spj>";
 	}
 }
?>]]>
</item>
<?
	}
	mysql_free_result ( $result );
	
	echo "</fps>";

}
?>
