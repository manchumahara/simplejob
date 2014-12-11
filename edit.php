<?php

### Check Whether User Can Manage jobs
if(!current_user_can('manage_jobs')) {
	die('Access Denied');
}

$base_name = plugin_basename('simplejob/jobs.php');
$base_page = 'admin.php?page='.$base_name;

$base_name_add = plugin_basename('simplejob/add.php');
$base_page_add = 'admin.php?page='.$base_name_add;

$text= '';
$error = false;
if(!empty ($_POST['submit'])){
    
    $title      = addslashes(trim($_POST['title']));
    if(empty ($title)){ $text .= 'Title is missing'; $error = true;}
    $salary     = intval($_POST['salary']);
    $location   = addslashes(trim($_POST['location']));
    $startdate  = addslashes(trim($_POST['startdate']));
    $enddate    = addslashes(trim($_POST['enddate']));
    $des       = addslashes(trim($_POST['des']));
    $dstartdate = addslashes(trim($_POST['dstartdate']));
    $denddate   = addslashes(trim($_POST['denddate']));
    $appmail    = addslashes(trim($_POST['appmail']));
    $highlight  = intval($_POST['highlight']);
    $published  = intval($_POST['published']);
    $jobid      = intval($_POST['jobid']);
    if(!$error){
        $sql = "UPDATE $wpdb->simplejob SET title = '$title', salary = '$salary', location = '$location', startdate = '$startdate', enddate = '$enddate', des = '$des', dstartdate = '$dstartdate', denddate = '$denddate', appmail = '$appmail', highlight = '$highlight', published = '$published' WHERE jobid=".$jobid;
        //var_dump($sql);
        $editjob = $wpdb->query($sql);
        if(!$editjob) {
                $text = '<font color="red">'.sprintf(__('Error In edit job \'%s\' (%s)' , 'simplejob'), $title, $jobid).'</font>';
        } else {
                //$jobid = intval($wpdb->insert_id);
                $text = '<font color="green">'.sprintf(__('Job \'%s (ID: %s)\' edited Successfully', 'simplejob'), $title, $jobid).' | <a href="'.$base_page.'&amp;mode=delete&amp;id='.$file_id.'" class="delete">'.__('Delete', 'wp-downloadmanager').'</a></font>';
        }
    }

}
else {
    $jobid      = intval($_GET['jobid']);
    $sql = "SELECT * FROM $wpdb->simplejob WHERE jobid=".$jobid;
    $jobs = $wpdb->query($sql);
    if(!jobs){
        $text = '<font color="red">Job id not found</font>';
    }

    $title      = $jobs->title;
    $salary     = intval($jobs->salary);
    $location   = $jobs->location;
    $startdate  = $jobs->startdate;
    $enddate    = $jobs->enddate;
    $des        = $jobs->des;
    $dstartdate = $jobs->dstartdate;
    $denddate   = $jobs->dendstartdate;
    $appmail    = $jobs->appmail;
    $highlight  = intval($jobs->highlight);
    $published  = intval($jobs->published);
    //$jobid      = (isset ($_GET['jobid'])? intval($_GET['jobid']): '');
}
///wp_redirect( $base_page_add, 301 ); exit;
//$nonce= wp_create_nonce  ('simplejobs');
?>
<?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.stripslashes($text).'</p></div>'; } ?>
<div class="wrap">
    <div class="icon32" id="icon-page"><br></div>
    <h2><?php _e('Add New Job', 'simplejob'); ?></h2>
    <form method="post" action="<?php echo $base_page_add; ?>" enctype="multipart/form-data">
        <?php //wp_nonce_field('name_of_my_action','name_of_nonce_field'); ?>
        <table class="form-table" id="jobman-job-edit">
            <tbody>
                <!--tr>
                        <th scope="row"><?php _e('Job ID', 'simplejob'); ?></th>
                        <td><?php
                            if(empty ($jobid))
                                _e('New', 'simplejob');
                            else 
                                echo $jobid;
                            ?>

                            
                        </td>
                        <td></td>
                </tr-->
                <tr>
                        <th scope="row"><?php _e('Job Title', 'simplejob'); ?></th>
                        <td><input type="text" value="<?php  echo $title; ?>" name="title" class=""></td>
                        <td></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Salary', 'simplejob'); ?></th>
                    <td><input type="text" value="<?php echo $salary; ?>" name="salary"></td>
                    <td><span class="description"></span></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Location', 'simplejob'); ?></th>
                    <td><input type="text" value="<?php echo $location; ?>" name="location"></td>
                    <td><span class="description"></span></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Start Date', 'simplejob'); ?></th>
                    <td><input type="text" value="<?php echo $startdate; ?>" name="startdate" class="jdpicker" id="dp1295862532444"></td>
                    <td><span class="description">(Format: YYYY/mm/dd)The date that the job starts. For positions available immediately, leave blank.</span></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('End Date', 'simplejob'); ?></th>
                    <td><input type="text" value="<?php echo $enddate; ?>" name="enddate" class="jdpicker" id="dp1295862532445"></td>
                    <td><span class="description">(Format: YYYY/mm/dd)The date that the job finishes. For ongoing positions, leave blank.</span></td>
                </tr>
                
                <tr>
                    <th scope="row"><?php _e('Job Description', 'simplejob'); ?></th>
                    <td colspan="2">
                        <?php
                            //if( user_can_richedit())
                            //{
                               // wp_tiny_mce(true,array("editor_selector" => "desc"));
                            //}
                        ?>
                    <textarea class="des" name="des" id="des" cols="80" rows="7"><?php echo $des; ?></textarea>
                    </td>
                </tr>
                <tr>
                        <th scope="row"><?php _e('Display Start Date', 'simplejob'); ?></th>
                        <td><input type="text" value="<?php echo $dstartdate; ?>" name="dstartdate" class="jdpicker" id="dp1295862532446"></td>
                        <td><span class="description">(Format: YYYY/mm/dd)The date this job should start being displayed on the site. To start displaying immediately, leave blank.</span></td>
                </tr>
                <tr>
                        <th scope="row"><?php _e('Display End Date', 'simplejob'); ?></th>
                        <td><input type="text" value="<?php echo $denddate; ?>" name="denddate" class="jdpicker" id="dp1295862532447"></td>
                        <td><span class="description">(Format: YYYY/mm/dd)The date this job should stop being displayed on the site. To display indefinitely, leave blank.</span></td>
                </tr>
                <tr>
                        <th scope="row"><?php _e('Application Email', 'simplejob'); ?></th>
                        <td><input type="text" value="<?php echo $appmail; ?>" name="appmail" class="regular-text"></td>
                        <td><span class="description">The email address to notify when an application is submitted for this job. For default behaviour (category email or global email), leave blank.</span></td>
                </tr>
                <tr>
                        <th scope="row"><?php _e('Highlighted', 'simplejob'); ?></th>
                        <td>
                            <input type="radio" value="0" <?php echo checked('0', $highlight); ?> name="highlight" id="highlight-0" /> No
                            <input type="radio" value="1" <?php echo checked('1', $highlight); ?> name="highlight" id="highlight-1" /> Yes
                        </td>
                        <td><span class="description">Mark this job as highlighted? For the behaviour of highlighted jobs, see the Display Settings admin page.</span></td>
                </tr>
                <tr>
                        <th scope="row"><?php _e('Published', 'simplejob'); ?></th>
                        <td>
                            <input type="radio" value="0" <?php echo checked('0', $published); ?> name="published" id="published-0" /> No
                            <input type="radio" value="1" <?php echo checked('1', $published); ?> name="published" id="published-1" /> Yes
                        </td>
                        <td><span class="description">Mark this job as pubslished?</span></td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" name="jobid" value="<?php echo $jobid; ?>" />
        <p class="submit"><input type="submit" value="<?php _e('Create Job', 'simplejob'); ?>" class="button-primary" name="submit"></p>       
    </form>
</div>

<!--script type="text/javascript">
//<![CDATA[
    jQuery(document).ready(function(){
        jQuery('.jdpicker').jdPicker({
             date_format:"YYYY/mm/dd",
             select_week:1,
             show_week:1,
             date_min:"19/05/2010",
             date_max:"21/12/2050"
        });
    });   
    	
 //]]>
</script-->