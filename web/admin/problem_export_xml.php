<?
require_once ("../include/db_info.inc.php");
function getTestFileName($pid,$OJ_DATA) {
	$ret = "";
	$pdir = opendir ( "$OJ_DATA/$pid/" );
	while ( $file = readdir ( $pdir ) ) {
		$pinfo = pathinfo ( $file );
		if ($pinfo ['extension'] == "in" && $pinfo ['basename'] != "sample.in") {
			$ret = basename ( $pinfo ['basename'], "." . $pinfo ['extension'] );
			break;
		}
	}
	closedir ( $pdir );
	return $ret;
}

function getTestFileIn($pid, $testfile,$OJ_DATA) {
	if ($testfile != "")
		return file_get_contents ( "$OJ_DATA/$pid/" . $testfile . ".in" );
	else
		return "";
}
function getTestFileOut($pid, $testfile,$OJ_DATA) {
	if ($testfile != "")
		return file_get_contents ( "$OJ_DATA/$pid/" . $testfile . ".out" );
	else
		return "";
}
function getSolution($pid){
	$ret="";
	require_once("../include/const.inc.php");
	$sql = "select solution_id,language from solution where problem_id=$pid and result=4";
	$result = mysql_query ( $sql ) or die ( mysql_error () );
	if($row = mysql_fetch_object ( $result) ){
		$solution_id=$row->solutin_id;
		$language=$language_name[$row->language];
		$sql = "select source from source_code where solution_id=$solution_id";
		$result = mysql_query ( $sql ) or die ( mysql_error () );
		if($row = mysql_fetch_object ( $result) ){
			$ret=$row->source;
			
		}
	}
	return $ret;
}
session_start ();
if (! isset ( $_SESSION ['administrator'] )) {
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "<a href='../loginpage.php'>Please Login First!</a>";
	exit ( 1 );
}


if ($_POST ['do'] == 'do') {
	$start = addslashes ( $_POST ['start'] );
	$end = addslashes ( $_POST ['end'] );
	$sql = "select * from problem where problem_id>=$start and problem_id<=$end";
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

<fps version="1.0">
<?
	while ( $row = mysql_fetch_object ( $result ) ) {
		$testfile = getTestFileName ( $row->problem_id ,$OJ_DATA);
		?>
<item>
<title><![CDATA[<?=$row->title?>]]></title>
<time_limit><![CDATA[<?=$row->time_limit?>]]></time_limit>
<memory_limit><![CDATA[<?=$row->memory_limit?>]]></memory_limit>
<description><![CDATA[<?=$row->description?>]]></description>
<input><![CDATA[<?=$row->input?>]]></input>
<output><![CDATA[<?=$row->output?>]]></output>
<sample_input><![CDATA[<?=$row->sample_input?>]]></sample_input>
<sample_output><![CDATA[<?=$row->sample_output?>]]></sample_output>
<test_input><![CDATA[<?=getTestFileIn ( $row->problem_id, $testfile ,$OJ_DATA)?>]]></test_input>
<test_output><![CDATA[<?=getTestFileOut ( $row->problem_id, $testfile,$OJ_DATA )?>]]></test_output>
<hint><![CDATA[<?=$row->hint?>]]></hint>
<source><![CDATA[<?=$row->source?>]]></source>
<solution><![CDATA[<?=getSolution($row->solution)?>]]></solution>
<spj><![CDATA[<?
 if($row->spj!=0){
 	echo file_get_contents ( "$OJ_DATA/$pid/spj.cc" );
 }
?>]]></spj>
</item>
<?
	}
	mysql_free_result ( $result );
	
	echo "</fps>";

}
?>