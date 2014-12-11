<?php
### Check Whether User Can Manage jobs

if(!current_user_can('manage_jobs')) {
	die('Access Denied');
}


### Variables Variables Variables
$base_name = plugin_basename('simplejob/jobs.php');
$base_page = 'admin.php?page='.$base_name;

$base_name_add = plugin_basename('simplejob/add.php');
$base_page_add = 'admin.php?page='.$base_name_add;
$mode = trim($_GET['mode']);

$text = '';
?>

<?php
### Form Processing 
if(!empty($_POST['do'])) {
	// Decide What To Do
	switch($_POST['do']) {
		// Delete File
		case __('Delete  a Job', 'simplejob');
       $jobid  = intval($_POST['jobid']);
       $title = trim($_POST['title']);
       
       $deletejob = $wpdb->query("DELETE FROM $wpdb->simplejob WHERE jobid = $jobid");
       if(!$deletejob) {
        $text .= '<font color="red">'.sprintf(__('Error In Deleting job \'%s (%s)\'', 'simplejob'), $title, $jobid).'</font>';
    } else {
        $text .= '<font color="green">'.sprintf(__('Job \'%s (%s)\' Deleted Successfully', 'simplejob'), $title, $jobid).'</font>';
    }
    break;
	}//end switch
}//end submit

switch ($mode) {
    case 'delete':
    simplejob_deletejob();
    break;
    case 'view':
    simplejob_showjob();
    break;
    default:
    simplejob_showjobs();
    break;
}//end switch for mode

//delete a single job
function simplejob_deletejob(){
	global $wpdb, $base_page_add, $base_page;
	$jobid  = intval($_REQUEST['jobid']);
	$job = $wpdb->get_row("SELECT * FROM $wpdb->simplejob WHERE jobid = $jobid");
	?>
  <?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.stripslashes($text).'</p></div>'; } ?>
  <!-- Delete A File -->
  <form method="post" action="<?php echo $base_page; ?>">
   <input type="hidden" name="jobid" value="<?php echo intval($job->jobid); ?>" />
   <input type="hidden" name="title" value="<?php echo stripslashes($job->title); ?>" />			
   <div class="wrap">
    <div class="icon32" id="icon-edit"><br></div>
    <h2><?php _e('Delete A Job', 'wp-downloadmanager'); ?></h2>
    <br style="clear" />
    <table class="widefat">
     <tr>
      <td valign="top"><strong><?php _e('Title:', 'simplejob') ?></strong></td>
      <td><span dir="ltr"><?php echo stripslashes($job->title); ?></span></td>
  </tr>							
  <tr>
      <th scope="row"><?php _e('Salary', 'simplejob'); ?></th>
      <td><?php echo $job->salary; ?></td>
  </tr>
  <tr class="alternate">
      <th scope="row"><?php _e('Location', 'simplejob'); ?></th>
      <td><?php echo $job->location; ?></td>
  </tr>
					<!--tr>
						<th scope="row"><?php _e('Start Date', 'simplejob'); ?></th>
						<td><?php echo $job->startdate; ?></td>
					</tr>
					<tr class="alternate">
						<th scope="row"><?php _e('End Date', 'simplejob'); ?></th>
						<td><?php echo $job->enddate; ?></td>
						
					</tr-->

					<tr>
						<th scope="row"><?php _e('Job Description', 'simplejob'); ?></th>
						<td >
							<?php echo $job->des; ?>
						</td>
					</tr>
					<!--tr class="alternate">
							<th scope="row"><?php _e('Display Start Date', 'simplejob'); ?></th>
							<td><?php echo $job->dstartdate; ?></td>
					</tr>
					<tr>
							<th scope="row"><?php _e('Display End Date', 'simplejob'); ?></th>
							<td><?php echo $job->denddate; ?></td>
					</tr>
					<tr class="alternate">
							<th scope="row"><?php _e('Application Email', 'simplejob'); ?></th>
							<td><?php echo $job->appmail; ?></td>
					</tr>
					<tr>
							<th scope="row"><?php _e('Highlighted', 'simplejob'); ?></th>
							<td>
								<?php echo simplejob_getstatue($job->highlight); ?>
							</td>
							
                     </tr-->
                     <tr class="alternate">
                       <th scope="row"><?php _e('Published', 'simplejob'); ?></th>
                       <td>
                        <?php echo simplejob_getstatue($job->published); ?>
                    </td>                
                </tr>
                <tr class="alternate">
                  <td colspan="2" align="center"><input type="submit" name="do" value="<?php _e('Delete  a Job', 'simplejob'); ?>" class="button"  onclick="return confirm('You Are About To The Delete This job \'<?php echo stripslashes(strip_tags($job->title)); ?> (<?php echo stripslashes($job->jobid); ?>)\'.\nThis Action Is Not Reversible.\n\n Choose \'Cancel\' to stop, \'OK\' to delete.')"/>&nbsp;&nbsp;<input type="button" name="cancel" value="<?php _e('Cancel', 'simplejob'); ?>" class="button" onclick="javascript:history.go(-1)" /></td>
              </tr>
          </table>
      </div>
  </form>
  <?php
}

//view a single job
function simplejob_showjob(){
    global $wpdb, $base_page_add, $base_page;
    $jobid = intval($_GET['jobid']);
    $job = $wpdb->get_row("SELECT * FROM $wpdb->simplejob WHERE jobid=".$jobid);
    ?>
    <div class="wrap">
        <div class="icon32" id="icon-edit"><br></div>
        <h2><?php _e('View A job', 'simplejob'); ?> ( id:<?php echo $jobid; ?> )<a class="button add-new-h2" href="<?php echo $base_page_add; ?>">Add New</a></h2>
        <br style="clear" />
        <p><a href="<?php echo $base_page; ?>" class="button add-new-h2"><?php _e('Back to Job Manager', 'simplejob'); ?></a></p>
        
        <table class="widefat">
            <thead>
                <tr>
                    <th colspan="2"><?php _e('Job Details', 'simplejob'); ?></th>                        
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th colspan="2"><?php _e('Job Details', 'simplejob'); ?></th>
                </tr>
            </tfoot>
            <?php
            if($job)
                {   ?>
            
            <tr>
                <th scope="row"><?php _e('Job ID', 'simplejob'); ?></th>
                <td><?php
                 echo $jobid;
                 ?>
             </td>               
         </tr>
         
         <tr class="alternate">
            <th scope="row"><?php _e('Job Title', 'simplejob'); ?></th>
            <td><?php  echo $job->title; ?></td>
            
        </tr>
        <tr>
            <th scope="row"><?php _e('Salary', 'simplejob'); ?></th>
            <td><?php echo $job->salary;  echo '  '.get_option('simplejob_currency'); ?></td>
        </tr>
        <tr class="alternate">
            <th scope="row"><?php _e('Location', 'simplejob'); ?></th>
            <td><?php echo $job->location; ?></td>
        </tr>
        <!--tr>
            <th scope="row"><?php _e('Start Date', 'simplejob'); ?></th>
            <td><?php echo $job->startdate; ?></td>
        </tr>
        <tr class="alternate">
            <th scope="row"><?php _e('End Date', 'simplejob'); ?></th>
            <td><?php echo $job->enddate; ?></td>
            
        </tr-->

        <tr>
            <th scope="row"><?php _e('Job Description', 'simplejob'); ?></th>
            <td colspan="2">
                <?php echo $job->des; ?>
            </td>
        </tr>
        <!--tr class="alternate">
                <th scope="row"><?php _e('Display Start Date', 'simplejob'); ?></th>
                <td><?php echo $job->dstartdate; ?></td>
        </tr>
        <tr>
                <th scope="row"><?php _e('Display End Date', 'simplejob'); ?></th>
                <td><?php echo $job->denddate; ?></td>
        </tr>
        <tr class="alternate">
                <th scope="row"><?php _e('Application Email', 'simplejob'); ?></th>
                <td><?php echo $job->appmail; ?></td>
        </tr>
        <tr>
                <th scope="row"><?php _e('Highlighted', 'simplejob'); ?></th>
                <td>
                    <?php echo simplejob_getstatue($job->highlight); ?>
                </td>
                
            </tr-->
            <tr class="alternate">
                <th scope="row"><?php _e('Published', 'simplejob'); ?></th>
                <td>
                    <?php echo simplejob_getstatue($job->published); ?>
                </td>                
            </tr>

            <?php
        }else {
         echo '<tr><td colspan="2" align="center"><strong>'.__('No job Found', 'simplejob').'</strong></td></tr>';
     }
     ?>
 </table>
 <p><a href="<?php echo $base_page; ?>" class="button add-new-h2"><?php _e('Back to Job Manager', 'simplejob'); ?></a></p>
</div>
<?php

}
function simplejob_showjobs(){
    
    global $wpdb, $base_page_add, $base_page;
    //var_dump($base_page);
    $file_page = intval($_GET['filepage']);
    $file_sortby = trim($_REQUEST['by']);
    $file_sortby_text = '';
    $file_sortorder = trim($_REQUEST['order']);
    $file_sortorder_text = '';
    $file_perpage = intval($_REQUEST['perpage']);
    $file_sort_url = '';
    $file_search = addslashes($_REQUEST['search']);
    //$file_catsearch = addslashes($_GET['catsearch']);
    $file_search_query = '';

    ### Form Sorting URL
    if(!empty($file_sortby)) {
        $file_sort_url .= '&amp;by='.$file_sortby;
    }
    if(!empty($file_sortorder)) {
        $file_sort_url .= '&amp;order='.$file_sortorder;
    }
    if(!empty($file_perpage)) {
        $file_sort_url .= '&amp;perpage='.$file_perpage;
    }

    ### Get Sort Order
    switch($file_sortorder) {
        case 'asc':
        $file_sortorder = 'ASC';
        $file_sortorder_text = __('Ascending', 'simplejob');
        break;
        case 'desc':
        default:
        $file_sortorder = 'DESC';
        $file_sortorder_text = __('Descending', 'simplejob');
        
    }
    ### Get Order By
    switch($file_sortby) {
        
        case 'title':
        $file_sortby = 'title';
        $file_sortby_text = __('Title', 'simplejob');
        break;
        case 'jobid':                    
        default:
        $file_sortby = 'jobid';
        $file_sortby_text = __('Job Id', 'simplejob');
    }

    ### Searching
    if(!empty($file_search)) {
        $file_search_query = "AND (title LIKE ('%$file_search%') OR des LIKE('%$file_search%'))";
        $file_sort_url .= '&amp;search='.stripslashes($file_search);

    }

    ### Get Total jobs
    $get_total_files = $wpdb->get_var("SELECT COUNT(jobid) FROM $wpdb->simplejob WHERE 1=1 $file_search_query");
    $total_file = $wpdb->get_var("SELECT COUNT(jobid) FROM $wpdb->simplejob WHERE 1=1");
    

    ### Checking $file_page and $offset
    if(empty($file_page) || $file_page == 0) { $file_page = 1; }
    if(empty($offset)) { $offset = 0; }
    if(empty($file_perpage) || $file_perpage == 0) { $file_perpage = 10; } //

    ### Determin $offset
    $offset = ($file_page-1) * $file_perpage;

    ### Determine Max Number Of Polls To Display On Page
    if(($offset + $file_perpage) > $get_total_files) {
        $max_on_page = $get_total_files;
    } else {
        $max_on_page = ($offset + $file_perpage);
    }

    ### Determine Number Of Polls To Display On Page
    if (($offset + 1) > ($get_total_files)) {
        $display_on_page = $get_total_files;
    } else {
        $display_on_page = ($offset + 1);
    }

    ### Determing Total Amount Of Pages
    $total_pages = ceil($get_total_files / $file_perpage);

    ### Get Files
    $jobs = $wpdb->get_results("SELECT * FROM $wpdb->simplejob WHERE 1=1 $file_search_query ORDER BY $file_sortby $file_sortorder LIMIT $offset, $file_perpage");

    ?>
    <div class="wrap">
        <div class="icon32" id="icon-edit"><br></div>
        <h2><?php _e('Job Manager', 'simplejob'); ?> <a class="button add-new-h2" href="<?php echo $base_page_add; ?>">Add New</a> </h2>
        <?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.stripslashes($text).'</p></div>'; } ?>
        <!-- Manage jobs -->    
        <p><?php printf(__('Displaying <strong>%s</strong> To <strong>%s</strong> Of <strong>%s</strong> Files', 'simplejob'), number_format_i18n($display_on_page), number_format_i18n($max_on_page), number_format_i18n($get_total_files)); ?></p>
        <p><?php printf(__('Sorted By <strong>%s</strong> In <strong>%s</strong> Order', 'simplejob'), $file_sortby_text, $file_sortorder_text); ?></p>
        <table class="widefat">
            <thead>
                <tr>
                    <th><?php _e('ID', 'simplejob'); ?></th>
                    <th><?php _e('Title', 'simplejob'); ?></th>
                    <th style="text-align: center;"><?php _e('Published', 'simplejob'); ?></th>
                    <th style="text-align: center;" colspan="3"><?php _e('Action', 'simplejob'); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th><?php _e('ID', 'simplejob'); ?></th>
                    <th><?php _e('Title', 'simplejob'); ?></th>
                    <th style="text-align: center;"><?php _e('Published', 'simplejob'); ?></th>
                    <th style="text-align: center;" colspan="3"><?php _e('Action', 'simplejob'); ?></th>
                </tr>
            </tfoot>
            <?php
            if($jobs) {;
                $i = 0;
                foreach($jobs as $job) {
                    $jobid      = intval($job->jobid);
                    $title      = stripslashes($job->title);
                    $published  = intval($job->published);
                    if($i%2 == 0) {
                        $style = '';
                    }  else {
                        $style = ' class="alternate"';
                    }
                    echo "<tr$style>\n";
                    echo '<td valign="top">'.$jobid.'</td>'."\n";
                    echo '<td style="text-align: left;">'.$title.'</td>'."\n";
                    echo '<td style="text-align: center;">'.simplejob_getstatue($published).'</td>'."\n";
                    echo '<td style="text-align: center;"><a href="'.$base_page.'&amp;mode=view&amp;jobid='.$jobid.'" class="view">'.__('View', 'simplejob').'</a></td>';
                    echo '<td style="text-align: center;"><a href="'.$base_page_add.'&amp;mode=edit&amp;jobid='.$jobid.'" class="edit">'.__('Edit', 'simplejob').'</a></td>';
                    echo '<td style="text-align: center;"><a href="'.$base_page.'&amp;mode=delete&amp;jobid='.$jobid.'" class="delete">'.__('Delete', 'simplejob').'</a></td>';
                    echo '</tr>';
                    $i++;
                }
            } else {
                echo '<tr><td colspan="6" align="center"><strong>'.__('No Files Found', 'simplejob').'</strong></td></tr>';
            }
            ?>
        </table>
        <!-- <Paging> -->
        <?php
        if($total_pages > 1) {
            ?>
            <br />
            <table class="widefat">
                <tr>
                    <td align="<?php echo ('rtl' == $text_direction) ? 'right' : 'left'; ?>" width="50%">
                        <?php
                        if($file_page > 1 && ((($file_page*$file_perpage)-($file_perpage-1)) <= $get_total_files)) {
                            echo '<strong>&laquo;</strong> <a href="'.$base_page.'&amp;filepage='.($file_page-1).$file_sort_url.'" title="&laquo; '.__('Previous Page', 'simplejob').'">'.__('Previous Page', 'simplejob').'</a>';
                        } else {
                            echo '&nbsp;';
                        }
                        ?>
                    </td>
                    <td align="<?php echo ('rtl' == $text_direction) ? 'left' : 'right'; ?>" width="50%">
                        <?php
                        if($file_page >= 1 && ((($file_page*$file_perpage)+1) <= $get_total_files)) {
                            echo '<a href="'.$base_page.'&amp;filepage='.($file_page+1).$file_sort_url.'" title="'.__('Next Page', 'simplejob').' &raquo;">'.__('Next Page', 'simplejob').'</a> <strong>&raquo;</strong>';
                        } else {
                            echo '&nbsp;';
                        }
                        ?>
                    </td>
                </tr>
                <tr class="alternate">
                    <td colspan="2" align="center">
                        <?php _e('Pages', 'simplejob'); ?> (<?php echo number_format_i18n($total_pages); ?>):
                        <?php
                        if ($file_page >= 4) {
                            echo '<strong><a href="'.$base_page.'&amp;filepage=1'.$file_sort_url.'" title="'.__('Go to First Page', 'simplejob').'">&laquo; '.__('First', 'simplejob').'</a></strong> ... ';
                        }
                        if($file_page > 1) {
                            echo ' <strong><a href="'.$base_page.'&amp;filepage='.($file_page-1).$file_sort_url.'" title="&laquo; '.__('Go to Page', 'simplejob').' '.number_format_i18n($file_page-1).'">&laquo;</a></strong> ';
                        }
                        for($i = $file_page - 2 ; $i  <= $file_page +2; $i++) {
                            if ($i >= 1 && $i <= $total_pages) {
                                if($i == $file_page) {
                                    echo '<strong>['.number_format_i18n($i).']</strong> ';
                                } else {
                                    echo '<a href="'.$base_page.'&amp;filepage='.($i).$file_sort_url.'" title="'.__('Page', 'simplejob').' '.number_format_i18n($i).'">'.number_format_i18n($i).'</a> ';
                                }
                            }
                        }
                        if($file_page < $total_pages) {
                            echo ' <strong><a href="'.$base_page.'&amp;filepage='.($file_page+1).$file_sort_url.'" title="'.__('Go to Page', 'simplejob').' '.number_format_i18n($file_page+1).' &raquo;">&raquo;</a></strong> ';
                        }
                        if (($file_page+2) < $total_pages) {
                            echo ' ... <strong><a href="'.$base_page.'&amp;filepage='.($total_pages).$file_sort_url.'" title="'.__('Go to Last Page', 'simplejob'), 'simplejob'.'">'.__('Last', 'simplejob').' &raquo;</a></strong>';
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <!-- </Paging> -->
            <?php
    }//end paging
    ?>
    <br/>
    <form action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>" method="post">
        <table class="widefat">
            <tr>
                <th><?php _e('Filter Options: ', 'wp-downloadmanager'); ?></th>
                <td>
                    <?php _e('Keywords:', 'wp-downloadmanager'); ?><input type="text" name="search" size="30" maxlength="200" value="<?php echo stripslashes($file_search); ?>" />&nbsp;&nbsp; Searches title and description<br/>
                </td>
            </tr>
            <tr>
                <th><?php _e('Sort Options:', 'simplejob'); ?></th>
                <td>
                    <input type="hidden" name="page" value="<?php echo $base_name; ?>" />
                    <select name="by" size="1">
                        <option value="jobid"<?php if($file_sortby == 'jobd') { echo ' selected="selected"'; }?>><?php _e('Job ID', 'simplejob'); ?></option>
                        <option value="title"<?php if($file_sortby == 'title') { echo ' selected="selected"'; }?>><?php _e('Title', 'simplejob'); ?></option>
                    </select>
                    &nbsp;&nbsp;&nbsp;
                    <select name="order" size="1">
                        <option value="asc"<?php if($file_sortorder == 'ASC') { echo ' selected="selected"'; }?>><?php _e('Ascending', 'simplejob'); ?></option>
                        <option value="desc"<?php if($file_sortorder == 'DESC') { echo ' selected="selected"'; } ?>><?php _e('Descending', 'simplejob'); ?></option>
                    </select>
                    &nbsp;&nbsp;&nbsp;
                    <select name="perpage" size="1">
                        <?php
                        for($k=10; $k <= 100; $k+=10) {
                            if($file_perpage == $k) {
                                echo "<option value=\"$k\" selected=\"selected\">".__('Per Page', 'simplejob').": ".number_format_i18n($k)."</option>\n";
                            } else {
                                echo "<option value=\"$k\">".__('Per Page', 'simplejob').": ".number_format_i18n($k)."</option>\n";
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input type="submit" value="<?php _e('Submit', 'simplejob'); ?>" class="button" /></td>
            </tr>
        </table>
    </form>
    <p>Simple Job 1.0 plugin for wordpress.  Developed by <a href="http://codeboxr.com" target="_blank">Codeboxr</a>.</p> 
</div>
<?php
}//end function


?>
