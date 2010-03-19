<?session_start();?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Edit Problem</title>
</head>
<body>
<center>
<?require_once("../include/db_info.inc.php");?>
<?require_once("admin-header.php");?>
<?php
include_once("../fckeditor/fckeditor.php") ;
?>
<p align="center"><font color="#333399" size="4">Welcome To Administrator's Page of Judge Online of ACM ICPC, <?=$OJ_NAME?>.</font>
<td width="100"></td>
</center>
<hr>
<?if(isset($_GET['id'])):?>
<h1>Edit problem</h1>
<form method=POST action=problem_edit.php>
<input type=hidden name=problem_id value=New Problem>
<?
$sql="SELECT * FROM `problem` WHERE `problem_id`=".$_GET['id'];
$result=mysql_query($sql);
$row=mysql_fetch_object($result);
?>
<p>Problem Id: <?=$row->problem_id?></p>
<input type=hidden name=problem_id value='<?=$row->problem_id?>'>
<p>Title:<input type=text name=title size=71 value='<?=htmlspecialchars($row->title)?>'></p>
<p>Time Limit:<input type=text name=time_limit size=20 value='<?=$row->time_limit?>'>S</p>
<p>Memory Limit:<input type=text name=memory_limit size=20 value='<?=$row->memory_limit?>'>MByte</p>

<!--
<p>Description:<br><textarea rows=13 name=description cols=120><?=htmlspecialchars($row->description)?></textarea></p>
<p>Input:<br><textarea rows=13 name=input cols=120><?=htmlspecialchars($row->input)?></textarea></p>
<p>Output:<br><textarea rows=13 name=output cols=120><?=htmlspecialchars($row->output)?></textarea></p>
-->
<p align=left>Description:<br><!--<textarea rows=13 name=description cols=80></textarea>-->

<?php
$description = new FCKeditor('description') ;
$description->BasePath = '../fckeditor/' ;
$description->Height = 600 ;
$description->Width=600;

$description->Value = $row->description ;
$description->Create() ;
?>
</p>

<p align=left>Input:<br><!--<textarea rows=13 name=input cols=80></textarea>-->

<?php
$input = new FCKeditor('input') ;
$input->BasePath = '../fckeditor/' ;
$input->Height = 600 ;
$input->Width=600;

$input->Value = $row->input ;
$input->Create() ;
?>
</p>

</p>
<p align=left>Output:<br><!--<textarea rows=13 name=output cols=80></textarea>-->


<?php
$output = new FCKeditor('output') ;
$output->BasePath = '../fckeditor/' ;
$output->Height = 600 ;
$output->Width=600;

$output->Value = $row->output;
$output->Create() ;
?>

<p>Sample Input:<br><textarea rows=13 name=sample_input cols=120><?=htmlspecialchars($row->sample_input)?></textarea></p>
<p>Sample Output:<br><textarea rows=13 name=sample_output cols=120><?=htmlspecialchars($row->sample_output)?></textarea></p>
<p>Hint:<br><textarea rows=13 name=hint cols=120><?=htmlspecialchars($row->hint)?></textarea></p>
<p>SpecialJudge: 
N<input type=radio name=spj value='0' <?=$row->spj=="0"?"checked":""?>>
Y<input type=radio name=spj value='1' <?=$row->spj=="1"?"checked":""?>></p>
<p>Source:<br><textarea name=source rows=1 cols=70><?=htmlspecialchars($row->source)?></textarea></p>
<div align=center>
<input type=submit value=Submit name=submit>
</div></form>
<p>
<?require_once("../oj-footer.php");?>
<?else:

$id=$_POST['problem_id'];
$title=$_POST['title'];
$time_limit=$_POST['time_limit'];
$memory_limit=$_POST['memory_limit'];
$description=$_POST['description'];
$input=$_POST['input'];
$output=$_POST['output'];
$sample_input=$_POST['sample_input'];
$sample_output=$_POST['sample_output'];
$hint=$_POST['hint'];
$source=$_POST['source'];
$spj=$_POST['spj'];

$sql="UPDATE `problem` set `title`='$title',`time_limit`='$time_limit',`memory_limit`='$memory_limit',
	`description`='$description',`input`='$input',`output`='$output',`sample_input`='$sample_input',`sample_output`='$sample_output',`hint`='$hint',`source`='$source',`spj`=$spj,`in_date`=NOW()
	WHERE `problem_id`=$id";

@mysql_query($sql) or die(mysql_error());
echo "Edit OK!";
$basedir="/home/judge/data/$id";
echo "Sample data file in $basedir Updated!<br>";

	//mkdir($basedir);
	$fp=fopen($basedir."/sample.in","w");
	fputs($fp,stripslashes(str_replace("\r\n","\n",$sample_input)));
	fclose($fp);
	
	$fp=fopen($basedir."/sample.out","w");
	fputs($fp,stripslashes(str_replace("\r\n","\n",$sample_output)));
	fclose($fp);
		
echo "<a href='../problem.php?id=$id'>See The Problem!</a>";
endif?>
</body>
</html>

