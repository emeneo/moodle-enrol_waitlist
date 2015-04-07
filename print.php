<?php
/**
 * *************************************************************************
 * *                  Course Fields Block                                 **
 * *************************************************************************
 * @copyright   emeneo.com                                                **
 * @link        emeneo.com                                                **
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later  **
 * *************************************************************************
 * ************************************************************************
*/
require('../../config.php');
require('lib.php');

global $CFG, $COURSE, $USER, $DB;

$data = $_POST['selCourse'];
/*$courseMarkings = $DB->get_records('course_marking', array('user_id'=>$data['userid']));*/

for($i=0;$i<count($data);$i++){
	$courseMarkings = $DB->get_record_sql('select count(*) as total from '.$CFG->prefix.'course_marking where course_id ='.$data[$i].' and user_id='.$USER->id);
	if(!$courseMarkings->total){
		$record = new stdClass();
		$record->course_id = $data[$i];
		$record->user_id = $USER->id;
		$DB->insert_record('course_marking', $record);
	}
}
$data = implode(",",$_POST['selCourse']);
$courseMarkings = $DB->get_records_sql('select c.id as certificate_id,m.* from '.$CFG->prefix.'course_marking as m left join '.$CFG->prefix.'certificate as c on m.course_id=c.course right join mdl_enrol as e on m.course_id=e.courseid where e.enrol="waitlist" and e.customchar1="0" and m.course_id in ('.$data.') and m.user_id='.$USER->id);
$courseMarkings = processData($courseMarkings);

$courseMarkingsFaculty = $DB->get_records_sql('select c.id as certificate_id,m.* from '.$CFG->prefix.'course_marking as m left join '.$CFG->prefix.'certificate as c on m.course_id=c.course right join mdl_enrol as e on m.course_id=e.courseid where e.enrol="waitlist" and e.customchar1<>"0" and e.status=0 and m.course_id in ('.$data.') and m.user_id='.$USER->id);
$courseMarkingsFaculty = processData($courseMarkingsFaculty);

function getTitleByLang($filterName){
	global $CFG, $DB, $SESSION;
	@$lang = $SESSION->lang;
	if(empty($lang))$lang = 'en';
	if($lang){
		$filters = explode("</span>",$filterName);
		foreach($filters as $filter){
			if(strpos($filter,'lang="'.$lang.'"')){
				return strip_tags($filter);
			}
		}
	}
	return $filterName;
}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<style type="text/css">
body { 
		font-family:arial, verdana, sans-serif;
		font-size:80%;
		margin:30px; 
	}
	
	h1 {
		font-size:200%;
     	color:#008E82;
     	padding-top: 100px;
     }
     
     h2 {
		font-size:120%;
     	color:#000;
     }
     
	p  {
		line-height:140%;
    	letter-spacing:0.1em;
    	word-spacing:0.3em;
    	}
    	
    div {
    
    }
    
    #id1 {
    	align: right;
    }
        
	table {	
		font-size:100%;
		border-collapse: collapse;
        border: none;
        width: 90%;
    }
    
    td {
        border: solid #ccc 1px;
		padding: 10px;
    }

	.table-no-style td {
		border: none;
	}
	
	.table-footer-style td {
		border: none;
		font-size:70%;
	}
	
	@media print	{   
    .no-print, .no-print *
    {
        display: none !important;
    }
}
	
	</style>
</head>

<body>

<p>
<img src="logo_studplus.png" alt="studierenplus Logo" align="left">
<img src="hsrt-logo.jpg" alt="HSRT Logo" align="right">
<h1>&nbsp;</h1>
<!--<h1><?php echo get_string('portfolio_title', 'block_certificate_print');?></h1><br>-->
<?php echo get_string('portfoliotext1', 'block_certificate_print');?><br>
<h2><?php echo @$USER->firstname;?>&nbsp;<?php echo @$USER->lastname;?></h2>
<?php echo get_string('portfoliotext2', 'block_certificate_print');?></br></br>

<div>
<?php if(count($courseMarkings)):?>
<table align="left">
	<tr  style="font-weight:bold; background-color:#ccc">
		<td width="30%"><?php echo get_string('coursename', 'block_certificate_print');?></td>
		<td><?php echo get_string('coursecategory', 'block_certificate_print');?></td>
		<td>Semester</td>
		<td width="10%"><?php echo get_string('gesamt_workload', 'block_certificate_print');?></td>
		<td width="10%"><?php echo get_string('ects_credits', 'block_certificate_print');?></td>	
	</tr>
	<?php foreach($courseMarkings as $courseMarking):?>
	
	<?php 
		$course      = $DB->get_record('course', array('id'=>$courseMarking->course_id));
		$category = $DB->get_record('course_categories', array('id'=>$course->category));
		/*G. Hagelberg: if any course has more than one certificate an error occurs, because there are multiple records*/		
		$certificate = $DB->get_record('certificate', array('id'=>$courseMarking->certificate_id));

	?>
	<tr>
		<td><?php echo getTitleByLang($course->fullname);?></td>
		<td><?php echo $category->name;?></td>
		<td><?php echo $courseMarking->semester;?></td>	
		<td><?php echo $certificate->intro;?></td>
		<td><?php echo $certificate->printhours;?></td>
	</tr>
	<?php endforeach;?>

</table>
<?php endif?>

<?php if(count($courseMarkingsFaculty)):?>
<p><?php echo get_string('faculty', 'block_certificate_print');?></p>
<table align="left">
	<tr  style="font-weight:bold; background-color:#ccc">
		<td width="30%"><?php echo get_string('coursename', 'block_certificate_print');?></td>
		<td><?php echo get_string('coursecategory', 'block_certificate_print');?></td>
		<td>Semester</td>
		<td width="10%"><?php echo get_string('gesamt_workload', 'block_certificate_print');?></td>
		<td width="10%"><?php echo get_string('ects_credits', 'block_certificate_print');?></td>	
	</tr>
	<?php foreach($courseMarkingsFaculty as $courseMarking):?>
	
	<?php 
		$course      = $DB->get_record('course', array('id'=>$courseMarking->course_id));
		$category = $DB->get_record('course_categories', array('id'=>$course->category));
		/*G. Hagelberg: if any course has more than one certificate an error occurs, because there are multiple records*/		
		$certificate = $DB->get_record('certificate', array('id'=>$courseMarking->certificate_id));

	?>
	<tr>
		<td><?php echo getTitleByLang($course->fullname);?></td>
		<td><?php echo $category->name;?></td>
		<td><?php echo $courseMarking->semester;?></td>	
		<td><?php echo $certificate->intro;?></td>
		<td><?php echo $certificate->printhours;?></td>
	</tr>
	<?php endforeach;?>

</table>
<?php endif?>
</div>

<div style="float:left;">
	<?php echo get_string('credits_valid', 'block_certificate_print');?></br>
	<?php echo get_string('computer_print', 'block_certificate_print');?></br>
	<?php echo get_string('created_on_date', 'block_certificate_print');?>&nbsp;<?php echo date('d.m.Y') ."\n";?>

</div>

<div style="position:absolute; bottom:30px;">
<table class="table-footer-style">
	<tr>
		<td>
		<img src="silhouette.png" alt="Silhouette" align="left">
		</td>
	</tr>
	<tr>
		<td>
		Hochschule Reutlingen, Alteburgstra&szlig;e 150, D-72762 Reutlingen, studierenplus@reutlingen-university.de, www.reutlingen-university.de
		</td>
	</tr>
</table>
</div>
		
</p>
<br /><br /><br />

<div class="no-print">
	<table align="center" class="table-no-style">
		<tr align="center">
			<td align="center">
			<input type="button" value="<?php echo get_string('print', 'block_certificate_print');?>" onclick="javascript:window.print();">
			<input type="button" value="<?php echo get_string('cancel', 'block_certificate_print');?>" onclick="window.close()">
			</td>
		</tr>
		
	</table>
</div>

</body>
</html>