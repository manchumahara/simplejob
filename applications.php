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

$base_name_app = plugin_basename('simplejob/applications.php');
$base_page_app = 'admin.php?page='.$base_name_app;



$mode = trim($_GET['mode']);

?>

<?php
switch ($mode) {
    case 'delete':
    echo 'need to finish';
    break;
    case 'view':
    simplejob_showapp();
    break;
    case 'edit':
    simplejob_app_edit();
    break;
    default:
    simplejob_showapps();
    break;
}//end switch for mode
function simplejob_showapp(){
    global $wpdb, $base_page_add, $base_page_app, $base_page;
    $id = intval($_GET['id']);
    $app = $wpdb->get_row("SELECT * FROM $wpdb->simplejobapp WHERE id=".$id);
    $des = $app->des;
    $des = unserialize($des);
    var_dump($des);
    ?>
    <div class="wrap">
        <div class="icon32" id="icon-edit"><br></div>
        <h2><?php _e('View an application', 'simplejob'); ?> ( id:<?php echo $id; ?> )</h2>
        <br style="clear" />
        <p><a href="<?php echo $base_page_app; ?>" class="button add-new-h2"><?php _e('Back to  Application Manager', 'simplejob'); ?></a></p>
        <?php 
        echo '<h4 class="form_sectiontitle">Personal</h4>
        <table width="792" cellspacing="0" cellpadding="0" border="0" align="center">
         <tbody>
             <tr>
               <td width="100" height="20">Full Name </td>
               <td width="295" height="20">'.$des['fullname'].'</td>
               <td width="123" height="20">Father\'s Name </td>
               <td width="286" height="20">'.$des['fname'].'</td>
           </tr>
           <tr>
               <td  width="100" height="20">Surname</td>
               <td  width="295" height="20">'.$des['surname'].'				
                   <td width="123" height="20">Mother\'s Name</td>
                   <td width="286" height="20">'.$des['mname'].'</td>
               </tr>
           </tbody>
       </table>
       <table width="792" cellspacing="0" cellpadding="0" border="0" align="center">
         <tbody>
             <tr>
               <td width="100" height="20">Gender/Sex</td>
               <td width="295" height="20">'.$des['sex'].'</td>
               <td width="123" height="20"></td>
               <td width="286" height="20"></td>
           </tr>
           <tr>
               <td width="100" height="20">Date of Birth </td>
               <td width="295" height="20">'.$des['bdate'].'</td>
               <td width="123" height="20">Place of Birth </td>
               <td width="286" height="20">'.$des['pbirth'].'</td>
           </tr>
           <tr>
               <td width="100" height="20">Nationality</td>
               <td width="295" height="20">'.$des['nationality'].'</td>
               <td width="123" height="20"></td>
               <td width="286" height="20">
               </td>
           </tr>
       </tbody>
   </table>
   <table width="792"  cellspacing="0" cellpadding="0" border="0" align="center">
     <tbody>
         <tr>
           <td width="100" height="20">Marital Status</td>
           <td width="295" height="20">'.$des['marital'].'</td>
           <td width="123" height="20"></td>
           <td width="286" height="20"></td>
       </tr>
       <tr>
           <td valign="middle" width="100" height="20">Mobile No. </td>
           <td valign="middle" height="20" width="295">'.$des['mobile'].'</td>
           <td width="123" valign="middle" height="20">&nbsp;</td>
           <td valign="middle" width="286" height="20">&nbsp;</td>
       </tr>
       <tr>
           <td valign="top" width="100" height="20">Present Address </td>
           <td valign="top" height="20" width="295">'.$des['present'].'</td>
           <td width="123" valign="middle" height="20">Permanent Address </td>
           <td valign="middle" width="286" height="20">'.$des['permanent'].'</td>
       </tr>
       <tr>
           <td valign="top" width="100" height="20">Present Phone </td>
           <td valign="top" height="20" width="295">'.$des['present_phone'].'</td>
           <td width="123" valign="middle" height="20">Permanent Phone </td>
           <td valign="middle" width="286" height="20">'.$des['permanent_phone'].'</td>
       </tr>
       <tr>
           <td valign="top" width="100" height="20">E-mail Address </td>
           <td valign="top" height="20" width="295">'.$des['email'].'</td>
           <td width="123" valign="middle" height="20">&nbsp;</td>
           <td valign="middle" width="286" height="20">&nbsp;</td>
       </tr>
       <tr>
           <td height="20">Photograph</td>
           <td height="20"><!--input type="file" name="userfile" class="text" id="userfile" size="28">
            <span class="mandetory">*</span--></td>
               <td height="20" colspan="2" class="mandetory">Photo Here</td>
           </tr>
           <tr>
               <td valign="top" height="20">Interests /Hobbies</td>
               <td height="20" colspan="3">'.$des['hobby'].'</td>
           </tr>
           <tr>
               <td valign="top" height="20">Extra Curricular Activities</td>
               <td height="20" colspan="3">'.$des['extra_act'].'</td>
           </tr>
       </tbody>
   </table>'; 
   ?>
   <p><a href="<?php echo $base_page_app; ?>" class="button add-new-h2"><?php _e('Back to Application Manager', 'simplejob'); ?></a></p>
</div>
<?php

}
function simplejob_showapps(){

    global $wpdb, $base_page_add, $base_page_app,$base_page;
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

        case 'fullname':
        $file_sortby = 'fullname';
        $file_sortby_text = __('Full Name', 'simplejob');
        break;
        case 'email':
        $file_sortby = 'email';
        $file_sortby_text = __('Email', 'simplejob');
        break;
        case 'id':
        default:
        $file_sortby = 'id';
        $file_sortby_text = __('Application Id', 'simplejob');
    }

    ### Searching
    if(!empty($file_search)) {
        $file_search_query = "AND (fullname LIKE ('%$file_search%') OR email LIKE('%$file_search%'))";
        $file_sort_url .= '&amp;search='.stripslashes($file_search);

    }

    ### Get Total jobs
    $get_total_files = $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->simplejobapp WHERE 1=1 $file_search_query");
    $total_file = $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->simplejobapp WHERE 1=1");


    ### Checking $file_page and $offset
    if(empty($file_page) || $file_page == 0) { $file_page = 1; }
    if(empty($offset)) { $offset = 0; }
    if(empty($file_perpage) || $file_perpage == 0) { $file_perpage = 20; } //

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
    $apps = $wpdb->get_results("SELECT * FROM $wpdb->simplejobapp WHERE 1=1 $file_search_query ORDER BY $file_sortby $file_sortorder LIMIT $offset, $file_perpage");

    ?>
    <div class="wrap">
        <div class="icon32" id="icon-edit"><br></div>
        <h2><?php _e('Application Manager', 'simplejob'); ?> </h2>
        <?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.stripslashes($text).'</p></div>'; } ?>
        <!-- Manage jobs -->
        <p><?php printf(__('Displaying <strong>%s</strong> To <strong>%s</strong> Of <strong>%s</strong> Files', 'simplejob'), number_format_i18n($display_on_page), number_format_i18n($max_on_page), number_format_i18n($get_total_files)); ?></p>
        <p><?php printf(__('Sorted By <strong>%s</strong> In <strong>%s</strong> Order', 'simplejob'), $file_sortby_text, $file_sortorder_text); ?></p>
        <table class="widefat">
            <thead>
                <tr>
                    <th><?php _e('ID', 'simplejob'); ?></th>
                    <th><?php _e('Email Address', 'simplejob'); ?></th>
                    <th><?php _e('Full Name', 'simplejob'); ?></th>
                    <th style="text-align: center;"><?php _e('Job Id(s)', 'simplejob'); ?></th>
                    <th style="text-align: center;" colspan="3"><?php _e('Action', 'simplejob'); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th><?php _e('ID', 'simplejob'); ?></th>
                    <th><?php _e('Email Address', 'simplejob'); ?></th>
                    <th><?php _e('Full Name', 'simplejob'); ?></th>
                    <th style="text-align: center;"><?php _e('Job Id(s)', 'simplejob'); ?></th>
                    <th style="text-align: center;" colspan="3"><?php _e('Action', 'simplejob'); ?></th>
                </tr>
            </tfoot>
            <?php
            if($apps) {;
                $i = 0;
                foreach($apps as $app) {
                    $id          = $app->id;
                    $email    = $app->email;
                    $fullname    = $app->fullname;
                    $jobids      = unserialize($app->jobids);
                                    //var_dump($jobids);
                                    //$published  = intval($job->published);
                    if($i%2 == 0) {
                        $style = '';
                    }  else {
                        $style = ' class="alternate"';
                    }
                    echo "<tr$style>\n";
                    echo '<td valign="top">'.$id.'</td>'."\n";
                    echo '<td style="text-align: left;">'.$fullname.'</td>'."\n";
                    echo '<td style="text-align: center;">'.$email.'</td>';
                    echo '<td style="text-align: center;">'.implode(",", $jobids).'</td>';
                    echo '<td style="text-align: center;"><a href="'.$base_page_app.'&amp;mode=view&amp;id='.$id.'" class="edit">'.__('View', 'simplejob').'</a> | <a href="'.$base_page_app.'&amp;mode=edit&amp;id='.$id.'" class="edit">'.__('Edit', 'simplejob').'</a></td>';                                    
                    echo '</tr>';
                    $i++;
                }
            } else {
                echo '<tr><td colspan="6" align="center"><strong>'.__('No applications Found', 'simplejob').'</strong></td></tr>';
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
                        <option value="id"<?php if($file_sortby == 'id') { echo ' selected="selected"'; }?>><?php _e('App ID', 'simplejob'); ?></option>
                        <option value="fullname"<?php if($file_sortby == 'fullname') { echo ' selected="selected"'; }?>><?php _e('Full Name', 'simplejob'); ?></option>
                        <option value="email"<?php if($file_sortby == 'email') { echo ' selected="selected"'; }?>><?php _e('Email', 'simplejob'); ?></option>
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
