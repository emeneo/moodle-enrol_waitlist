<?php

require('../../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/enrol/waitlist/profile/lib.php');
require_once($CFG->dirroot.'/enrol/waitlist/profile/definelib.php');

admin_externalpage_setup('enrol_waitlist_fields');

$action   = optional_param('action', '', PARAM_ALPHA);
$categoryId = optional_param('cid', 0, PARAM_INT);

$baseurl = $CFG->wwwroot.'/enrol/waitlist/profile/assign.php';

$strchangessaved    = get_string('changessaved');
$strcancelled       = get_string('cancelled');
$strdefaultcategory = get_string('profiledefaultcategory', 'admin');
$strnofields        = get_string('profilenofieldsdefined', 'admin');
$strcreatefield     = get_string('profilecreatefield', 'local_course_fields');

if(!$categoryId){
	$categoryId = 1;
}

if ($_POST) {
	$data = $_POST;
	$fields = array();
	foreach($data as $key=>$val){
		if(substr($key,0,14) == 'profile_field_'){
			$res = $DB->get_record("waitlist_info_field",array("shortname"=>str_replace(substr($key,0,14),'',$key)));
			if($res){
				//if($res->required == 1 && $val<1){
				//	print_error(get_string('somedataerror', 'block_course_fields'));
				//	redirect($data['return']);
				//}
				foreach($val as $k=>$v){
					$fields[$k][] = array('field_id'=>$res->id,'data'=>$v);
				}
			}
		}
	}

	if(count($fields)){
		foreach($data['courseid'] as $k=>$cid){
			$DB->delete_records('course_info_data', array('course_id'=>$cid));
			$fieldData = new stdClass();
			foreach($fields[$k] as $field){
				$fieldData->course_id = $cid ;
				$fieldData->fieldid = $field['field_id'];
				$fieldData->data = $field['data'];
				$DB->insert_record('course_info_data', $fieldData);
			}
		}
	}
}

echo $OUTPUT->header();
?>
<script src='../js/jquery-1.7.1.min.js'></script>
<script>
$(document).ready(function(){
	$('#id_category').val(<?php echo $categoryId?>)
	$('#id_category').change(function(){
		window.location.href = "<?php echo $baseurl?>?cid=" + $(this).val();
	})

	$('.selField').click(function(){
		if($(this).attr('checked') == 'checked'){
			$(this).parent().find("input[type='hidden']").val(1);
		}else{
			$(this).parent().find("input[type='hidden']").val(0);
		}
	})

	$('#btnSubmit').click(function(){
		$('.selField').each(function(){
			if($(this).attr('checked') == 'checked'){
				$(this).parent().find("input[type='hidden']").val(1);
			}else{
				$(this).parent().find("input[type='hidden']").val(0);
			}
		})

		$('#assignFrm').submit();
	})
})
</script>
<?php
$currenttab = 'assign';
include_once('managetabs.php');

require_once('assign_form.php');
$assignform = new course_fields_assign_form($baseurl);

$table = new html_table();
$table->head  = array(get_string('assign::course', 'local_course_fields'), get_string('assign::course_field', 'local_course_fields'));
$table->align = array('left', 'left');
$table->width = '95%';
$table->attributes['class'] = 'generaltable profilefield';
$table->data = array();

$courses = $DB->get_records('course',array('category'=>$categoryId));

foreach ($courses as $course) {
	$categorys = $DB->get_records('course_info_categories', array('course_category'=>$course->category));
	$usedFields = $DB->get_records('course_info_data', array('course_id'=>$course->id));

	$savedFields = array();
	foreach($usedFields as $usedField){
		$savedFields[$usedField->fieldid] = $usedField->data;
	}

	if(count($categorys)){
		$build = '';
		$build.= '<div style="margin-left:15px;">';
		$build.= '<input type="hidden" name="courseid[]" value="'.$course->id.'">';
		foreach($categorys as $category){
			$fields = $DB->get_records('waitlist_info_field', array('categoryid'=>$category->categoryid));
			if(count($fields)){
				$fieldCategory = $DB->get_record('course_info_category', array('id'=>$category->categoryid));
				$build.='<div style="margin:10px 0 0 0;"><strong>'.$fieldCategory->name.'</strong></div>';
				foreach($fields as $field){
					$checked = '';
					$checkedVal = 0;
					if(isset($savedFields[$field->id])){
						if($savedFields[$field->id] == 1)$checked = 'checked';
						$checkedVal = 1;
					}else{
						if($field->defaultdata == 1){
							$checked = 'checked';
							$checkedVal = 1;
						}
					}

					$build.='<div id="fitem_id_profile_field_'.$field->shortname.'" style="margin:5px 0 0 0;">';
					$build.='<div style="float:left;padding-right:5px;">';
					$build.='<label for="id_profile_field_'.$field->shortname.'">'.$field->name.'</label>';
					$build.='</div>';
					$build.='<div class="felement fcheckbox">';
					$build.='<span><input type="hidden" name="profile_field_'.$field->shortname.'[]" value="'.$checkedVal.'"><input type="'.$field->datatype.'" class="selField" id="id_profile_field_'.$field->shortname.'" '.$checked.'></span>';
					$build.='</div>';
					$build.='</div>';
				}
			}
		}
		$build.= '</div>';
	}else{
		$build = get_string('nocursefields','local_course_fields');
	}	

	$table->data[] = array(format_string($course->fullname), $build);
}
$assignform->display();

echo '<form action="' . $baseurl . '" method="post" id="assignFrm">';
echo html_writer::table($table);
echo '<div class="buttons"><input type="button" id="btnSubmit" value="'.get_string('savechanges').'"/>';
echo '</div></form>';

echo $OUTPUT->footer();
?>