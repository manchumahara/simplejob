<?php

function simplejob_getstatue($published){
    $status = 'yes';
    if($published == 0 ){$status = 'No';}
    return $status;
}
function simplejob_showjobs_frontend() {
    global $wpdb, $base_page_add, $base_page, $base_page_app, $apply_url, $listing_url;
    
    //var_dump($id);
    $mode = trim($_GET['mode']);
    
    switch ($mode) {
        case 'view':
            $id = intval($_GET['jobid']);
            simplejob_showjobs_frontend_single($id);
            break;

        default:
            simplejob_showjobs_frontend_all();
            break;
    }         
}//end fucntion

function simplejob_showjobs_frontend_single($id){

    global $wpdb, $base_page_add, $base_page, $base_page_app;

    //will be used in front end    
    $listing_url    = get_permalink(get_option('simplejob_joblistpage')); //next time will use from option
	$apply_url      = get_permalink(get_option('simplejob_jobapplypage')); //next time will use from option
	
    if(strpos($apply_url, '?') !== false) {
        $apply_url .= '&amp;';
    } else {
        $apply_url .= '?';
    }

    if(strpos($listing_url, '?') !== false) {
        $listing_url .= '&amp;';
    } else {
        $listing_url .= '?';
    }

    $jobid = $id;
    $job = $wpdb->get_row("SELECT * FROM $wpdb->simplejob WHERE jobid=".$jobid);    
    ?>
    <h3 class="jobdetails_title"><?php _e('Job Details', 'simplejob'); ?></h3>
    <table class="widefat">
        <!--thead>
                <tr>
                        <th colspan="2"><?php _e('Job Details', 'simplejob'); ?></th>
                </tr>
        </thead-->
        <!--tfoot>
                <tr>
                        <th colspan="2"><?php _e('Job Details', 'simplejob'); ?></th>
                </tr>
        </tfoot-->
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
        <?php if($job->salary != ''): ?>
        <tr>
            <th scope="row"><?php _e('Salary', 'simplejob'); ?></th>
            <td><?php echo $job->salary;  echo '  '.get_option('simplejob_currency');?></td>
        </tr>
        <?php endif; ?>
        <?php if($job->location != ''): ?>
        <tr class="alternate">
            <th scope="row"><?php _e('Location', 'simplejob'); ?></th>
            <td><?php echo $job->location; ?></td>
        </tr>
        <?php endif; ?>
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
        </tr-->
        
        
        <tr class="alternate">
                   <?php echo '<td colspan="2" style="text-align: center;"><a href="'.$apply_url.'jobid='.$id.'" class="view">'.__('Apply Now', 'simplejob').'</a></td>'; ?>
        </tr>

    <?php
    }else {
         echo '<tr><td colspan="2" align="center"><strong>'.__('No job Found', 'simplejob').'</strong></td></tr>';
    }
            ?>
    </table>    
    
    <?php
}

function simplejob_showjobs_frontend_all(){
    global $wpdb, $base_page_add, $base_page, $base_page_app;

    //will be used in front end
    $listing_url    = get_permalink(get_option('simplejob_joblistpage')); //next time will use from option
	$apply_url      = get_permalink(get_option('simplejob_jobapplypage')); //next time will use from option
    

    if(strpos($apply_url, '?') !== false) {
        $apply_url .= '&amp;';
    } else {
        $apply_url .= '?';
    }

    if(strpos($listing_url, '?') !== false) {
        $listing_url .= '&amp;';
    } else {
        $listing_url .= '?';
    }

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
    $get_total_files = $wpdb->get_var("SELECT COUNT(jobid) FROM $wpdb->simplejob WHERE published = 1 $file_search_query");
    $total_file = $wpdb->get_var("SELECT COUNT(jobid) FROM $wpdb->simplejob WHERE published = 1");


    ### Checking $file_page and $offset
    if(empty($file_page) || $file_page == 0) { $file_page = 1; }
    if(empty($offset)) { $offset = 0; }
    if(empty($file_perpage) || $file_perpage == 0) { $file_perpage = 20; } // 20 is enough for now

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
    $jobs = $wpdb->get_results("SELECT * FROM $wpdb->simplejob WHERE published=1 $file_search_query ORDER BY $file_sortby $file_sortorder LIMIT $offset, $file_perpage");



    ?>
       
    <?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.stripslashes($text).'</p></div>'; } ?>
    <!-- Manage jobs -->
            <?php if($total_pages > 1) { ?>
            <p><?php printf(__('Displaying <strong>%s</strong> To <strong>%s</strong> Of <strong>%s</strong> Files', 'simplejob'), number_format_i18n($display_on_page), number_format_i18n($max_on_page), number_format_i18n($get_total_files)); ?></p>
            <p><?php printf(__('Sorted By <strong>%s</strong> In <strong>%s</strong> Order', 'simplejob'), $file_sortby_text, $file_sortorder_text); ?></p>
            <?php } ?>
            <form method="post" action="<?php echo $apply_url; ?>">
            <table class="widefat">
                    <thead>
                            <tr>
                                    <th>#</th>
                                    <!--th><?php _e('ID', 'simplejob'); ?></th-->
                                    <th><?php _e('Title', 'simplejob'); ?></th>
                                    <!--th style="text-align: center;"><?php _e('Published', 'simplejob'); ?></th-->
                                    <th style="text-align: center;" colspan="3"><?php _e('Action', 'simplejob'); ?></th>
                            </tr>
                    </thead>
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
                                        echo '<td class="check-column"><input type="checkbox" value="'.$jobid.'" name="simple-joblist[]"></td>';
                                        //echo '<td valign="top">'.$jobid.'</td>'."\n";
                                        echo '<td style="text-align: left;"><a href="'.$listing_url.'mode=view&jobid='.$jobid.'">'.$title.'</a></td>'."\n";
                                        //echo '<td style="text-align: center;">'.simplejob_getstatue($published).'</td>'."\n";
                                        echo '<td style="text-align: center;"><a href="'.$apply_url.'jobid='.$jobid.'" class="view">'.__('Apply', 'simplejob').'</a></td>';
                                        //echo '<td style="text-align: center;"><a href="'.$base_page_add.'&amp;mode=edit&amp;jobid='.$jobid.'" class="edit">'.__('Edit', 'simplejob').'</a></td>';
                                        //echo '<td style="text-align: center;"><a href="'.$base_page.'&amp;mode=delete&amp;jobid='.$jobid.'" class="delete">'.__('Delete', 'simplejob').'</a></td>';
                                    echo '</tr>';
                                    $i++;
                            }
                            echo '<tr><td colspan="4" style="text-align: left;"><input type="submit" value="Apply Multiple" name="submit"></td></tr>';
                    } else {
                            echo '<tr><td colspan="4" align="center"><strong>'.__('No Files Found', 'simplejob').'</strong></td></tr>';
                    }
            ?>
            </table>
            </form>
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
    //}   //end paging
    ?>
    <br/>
    <form action="<?php echo get_permalink(get_option('simplejob_joblistpage')); ?>" method="post">
        <table class="widefat">
            <tr>
                <th><?php _e('Filter Options: ', 'wp-downloadmanager'); ?></th>
                <td>
                    <?php _e('Keywords:', 'wp-downloadmanager'); ?><input type="text" name="search" size="30" maxlength="200" value="<?php echo stripslashes($file_search); ?>" />
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
                    <input type="submit" value="<?php _e('Submit', 'simplejob'); ?>" class="button" />
                </td>
            </tr>
            <!--tr>
                    <td colspan="2" align="center"><input type="submit" value="<?php _e('Submit', 'simplejob'); ?>" class="button" /></td>
            </tr-->
        </table>
        <?php } //next time will remove that, and enable filter option in front end ?>
    </form>   
   <?php
}

function date_day_helper($selected = 0, $name='', $id =''){
    $output = '<select id="'.$id.'" class="text" name="'.$name.'">';
    $output .= '<option '.selected('0', $selected, false).' value="0">day</option>';
    for($i=1;$i<=31;$i++){
       if($i<10)
            $output .= '<option '.selected('0'.$i, $selected, false).' value="0'.$i.'">0'.$i.'</option>';
       else
           $output .= '<option '.selected($i, $selected, false).' value="'.$i.'">'.$i.'</option>';
    }
    $output .= '</select>';
    echo $output;
}

function date_month_helper($selected = 0, $name='', $id =''){
    $month = array('Jan','Feb','Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
    $output = '<select id="'.$id.'" class="text" name="'.$name.'">';
    $output .= '<option '.selected('0', $selected, false).'value="0">month</option>';
    for($i=1;$i<=12;$i++){
        if($i<10)
            $output .= '<option '.selected('0'.$i, $selected, false).' value="0'.$i.'">'.$month[$i-1].'</option>';
        else
            $output .= '<option '.selected($i, $selected, false).' value="'.$i.'">'.$month[$i-1].'</option>';
    }
    $output .= '</select>';
    echo $output;
}

function date_year_helper($selected = 0, $name='', $id =''){
    
    $output = '<select id="'.$id.'" class="text" name="'.$name.'">';
    $output .= '<option '.selected('0', $selected, false).'value="0">year</option>';
    for($i=2020;$i>=1931;$i--){
        $output .= '<option '.selected($i, $selected, false).' value="'.$i.'">'.$i.'</option>';        
        
    }
    $output .= '</select>';
    echo $output;   
}
function expart_helper($selected, $name='byear',$id='byear'){
    $output = '<select id="'.$id.'" class="text" name="'.$name.'">';
    $output .= '<option '.selected('0', $selected, false).' value="0">Select One</option>';
    $output .= '<option '.selected('Administration', $selected, false).' value="Administration">Administration</option>
                <option '.selected('Accounts', $selected, false).' value="Accounts">Accounts</option>
                <option '.selected('Civil Works', $selected, false).' value="Civil Works">Civil Works</option>
                <option '.selected('Commercial', $selected, false).' value="Commercial">Commercial</option>
                <option '.selected('Customer Relation', $selected, false).' value="Customer Relation">Customer Relation</option>
                <option '.selected('Distribution', $selected, false).' value="Distribution">Distribution</option>
                <option '.selected('Electrical', $selected, false).' value="Electrical">Electrical</option>
                <option '.selected('Finance', $selected, false).' value="Finance">Finance</option>
                <option '.selected('Human Resources', $selected, false).' value="Human Resources">Human Resources</option>
                <option '.selected('IT', $selected, false).' value="IT">IT</option>
                <option '.selected('Legal', $selected, false).' value="Legal">Legal</option>
                <option '.selected('Marketing', $selected, false).' value="Marketing">Marketing</option>
                <option '.selected('Procurement', $selected, false).' value="Procurement">Procurement</option>
                <option '.selected('Sales', $selected, false).' value="Sales">Sales</option>
                <option '.selected('Telecommunication', $selected, false).' value="Telecommunication">Telecommunication</option>
                <option '.selected('Others', $selected, false).' value="Others">Others</option>';
      $output .= '</select>';
      echo  $output;
}
function simplejob_showjobs_frontend_form(){
	global $wpdb, $base_page_add, $base_page, $base_page_app;

    //will be used in front end    
    $listing_url    = get_permalink(get_option('simplejob_joblistpage')); //next time will use from option
	$apply_url      = get_permalink(get_option('simplejob_jobapplypage')); //next time will use from option

    if(strpos($apply_url, '?') !== false) {
        $apply_url .= '&amp;';
    } else {
        $apply_url .= '?';
    }

    if(strpos($listing_url, '?') !== false) {
        $listing_url .= '&amp;';
    } else {
        $listing_url .= '?';
    }

    //var_dump($_REQUEST['jobid']);
    //var_dump($_REQUEST['simple-joblist']);
    $error = false;
    $submitted = false;
    $text = '';
    if(!empty($_REQUEST['cmdSubmit'])){				        
		
        $des        = array(); //this will hold the full cv
        //personal
        $fullname  =   addslashes(trim($_POST['fullname']));
        if(empty($fullname)){$error = true; $text .= 'Full name missing<br/>';}
        else{$des['fullname'] = $fullname;}
        
        $fathername      =   addslashes(trim($_POST['fathername']));
        if(fathername == ''){$error = true; $text .= 'Father\'s name is missing<br/>';}
        else{$des['fathername'] = $fathername;}
        
        $surname    =   addslashes(trim($_POST['surname']));
        if($surname == ''){$error = true; $text .= 'Surname name is missing<br/>';}
        else{$des['surname'] = $surname;}

        $mname      =   addslashes(trim($_POST['mname']));
        if($mname == ''){$error = true; $text .= 'Mother\'s name is missing<br/>';}
        else{$des['mname'] = $mname;}
        //birthday
        $bday       = addslashes(trim($_POST['bday']));
        $bmon       = addslashes(trim($_POST['bmon']));
        $byear      = addslashes(trim($_POST['byear']));
        if($bday == '' || $bmon == '' || $byear == ''){$error = true; $text .= 'Please choose date of birth propery<br/>';}
        else{$bdate= $bday.'-'.$bmon.'-'.$byear; $des['bdate'] = $bdate;}
              
        $email      = addslashes(trim($_POST['email']));
        if($email  == ''){$error = true; $text .= 'Email address is missing<br/>';}
        else{$des['email'] = $email ;}

        

        $pbirth   =   addslashes(trim($_POST['pbirth']));
        if($pbirth == '0'){$error = true; $text .= 'Select place of birth<br/>';}
        else if($pbirth == 'Other'){ 
            $pbirtht   =   addslashes(trim($_POST['pbirtht']));
            if($pbirtht  == ''){$error = true; $text .= 'Select place of birth<br/>';}
            else{$des['pbirth'] = $pbirtht ;}
        }
        else{$des['pbirth'] = $pbirth ;}

        $nationality   =   addslashes(trim($_POST['nationality']));
        if($nationality == '0'){$error = true; $text .= 'Select nationality<br/>';}
        else if($nationality == 'Other'){
            $nationalityt   =   addslashes(trim($_POST['nationalityt']));
            if($nationalityt  == ''){$error = true; $text .= 'Select nationality<br/>';}
            else{$des['nationality'] = $nationalityt;}
        }
        else{$des['nationality'] = $nationality ;}

        $marital    = addslashes(trim($_POST['marital']));
        if($marital == '0'){$error = true; $text .= 'Select Marital Status<br/>';}
        else{$des['marital'] = $marital ;}

        $sex      = addslashes(trim($_POST['sex']));
        if($sex == '0'){$error = true; $text .= 'Select sex<br/>';}
        else{$des['sex'] = $sex ;}

        $mobile      = addslashes(trim($_POST['mobile']));
        if($mobile == ''){$error = true; $text .= 'Select mobile<br/>';}
        else{$des['mobile'] = $mobile ;}

        $present      = addslashes(trim($_POST['present']));
        if($present == ''){$error = true; $text .= 'Present address is missing<br/>';}
        else{$des['present'] = $present ;}

        $permanent      = addslashes(trim($_POST['permanent']));
        if($permanent == ''){$error = true; $text .= 'Permanent is missing<br/>';}
        else{$des['permanent'] = $permanent ;}


        $pyear[]    = $_REQUEST['pyear'];       
        

        $present_phone = addslashes(trim($_POST['present_phone']));
        if($present_phone == ''){$error = true; $text .= 'Present phone is missing<br/>';}
        else{$des['present_phone'] = $present_phone ;}

        $permanent_phone = addslashes(trim($_POST['permanent_phone']));        
        if($permanent_phone == ''){$error = true; $text .= 'Permanent phone is missing<br/>';}
        else{$des['permanent_phone'] = $permanent_phone;}


        $hobby = addslashes(trim($_POST['hobby']));
        $des['hobby'] = $hobby;
        $extra_act = addslashes(trim($_POST['extra_act']));
        $des['extra_act'] = $extra_act;


        //Career section
        $interview = addslashes(trim($_POST['interview']));
        if($interview == '0'){$error = true; $text .= 'Select interview<br/>';}
        else{$des['interview'] = $interview ;}

        $work_level = addslashes(trim($_POST['work_level']));
        if($work_level == ''){$error = true; $text .= 'Years of Working Experience is missingBangla<br/>';}
        else{$des['work_level'] = $work_level;}
		
		$eng_read = addslashes(trim($_POST['eng_read']));
		if($eng_read == '0'){$error = true; $text .= 'English read skill is missing<br/>';}
        else{$des['eng_read'] = $eng_read ;}
        
        $eng_write = addslashes(trim($_POST['eng_write']));
		if($eng_write == '0'){$error = true; $text .= 'English write skill is missing<br/>';}
        else{$des['eng_write'] = $eng_write ;}
		
		$eng_speak = addslashes(trim($_POST['eng_speak']));
		if($eng_speak == '0'){$error = true; $text .= 'English speak skill is missing<br/>';}
        else{$des['eng_speak'] = $eng_speak ;}
        
        $ban_read = addslashes(trim($_POST['ban_read']));
		if($ban_read == '0'){$error = true; $text .= 'Bangla read skill is missing<br/>';}
        else{$des['ban_read'] = $ban_read ;}
        
        $ban_write = addslashes(trim($_POST['ban_write']));
		if($ban_write == '0'){$error = true; $text .= 'Bangla write skill is missing<br/>';}
        else{$des['ban_write'] = $ban_write ;}
		
		$ban_speak = addslashes(trim($_POST['ban_speak']));
		if($ban_speak == '0'){$error = true; $text .= 'Bangla speak skill is missing<br/>';}
        else{$des['ban_speak'] = $ban_speak ;}
        
        if(!empty($_POST['lan_other']))
        {
			$lan_other = addslashes(trim($_POST['lan_other']));
			$oth_read = addslashes(trim($_POST['oth_read']));
			if($oth_read == '0'){$error = true; $text .= $lan_other .' read skill is missing<br/>';}
			else{$des['oth_read'] = $oth_read ;}
			
			$oth_write = addslashes(trim($_POST['oth_write']));
			if($oth_write == '0'){$error = true; $text .= $lan_other.' write skill is missing<br/>';}
			else{$des['oth_write'] = $oth_write ;}
			
			$oth_speak = addslashes(trim($_POST['oth_speak']));
			if($oth_speak == '0'){$error = true; $text .= $lan_other.' speak skill is missing<br/>';}
			else{$des['oth_speak'] = $oth_speak ;}
	    }
	    
	    $appfor	= addslashes(trim($_POST['appfor']));
	    if($appfor == '0'){$error = true; $text .= 'Specify Apply criteria<br/>';}
		else{$des['appfor'] = $appfor ;}
		 //birthday
        $jday       = addslashes(trim($_POST['jday']));
        $jmon       = addslashes(trim($_POST['jmon']));
        $jyear      = addslashes(trim($_POST['jyear']));
        if($jday == '' || $jmon == '' || $jyear == ''){$error = true; $text .= 'Please choose ready to join date<br/>';}
        else{$jdate= $jday.'-'.$jmon.'-'.$jyear; $des['jdate'] = $jdate;}
        
	    $reason = addslashes(trim($_POST['reason']));
        
		


        //$error = true
        
        
        //Education
        $education = $_REQUEST['education'];
        //var_dump($education[0]);
        //var_dump($education[1]);
        //var_dump($education[2]);
        $des['education1'] = $education[0] ;
        $des['education2'] = $education[1] ;
        $des['education3'] = $education[2] ;

        $institute = $_REQUEST['institute'];
        $des['institute1'] = $institute[0] ;
        $des['institute2'] = $institute[1] ;
        $des['institute3'] = $institute[2] ;       

        
        $board = $_REQUEST['board'];
        $des['board1'] = $board[0] ;
        $des['board2'] = $board[1] ;
        $des['board3'] = $board[2] ;
        
        $division = $_REQUEST['division'];
        $des['division1'] = $division[0] ;
        $des['division2'] = $division[1] ;
        $des['division3'] = $division[2] ;
        
        $pyear = $_REQUEST['pyear'];
        $des['pyear1'] = $pyear[0];
        $des['pyear2'] = $pyear[1] ;
        $des['pyear3'] = $pyear[2] ;
        
        $country = $_REQUEST['country'];
        $des['country1'] = $country[0] ;
        $des['country2'] = $country[1] ;
        $des['country3'] = $country[2] ;
        
        $sgroup = $_REQUEST['sgroup'];
        $des['sgroup1'] = $sgroup[0];
        $des['sgroup2'] = $sgroup[1];
        $des['sgroup3'] = $sgroup[2];
        
        //Computer Literacy
        $computer		= $_REQUEST['computer'];
        //var_dump($computer);
        $des['computer'] = $computer ;
        $cother			= $_REQUEST['cother'];
        
        //Participated Training Programs
        $ttitle = $_REQUEST['ttitle']; // array
        $des['ttitle1'] = $ttitle[0];
        $des['ttitle2'] = $ttitle[1] ;
        $des['ttitle3'] = $ttitle[2] ;
        
        $tdf	  = $_REQUEST['tdf'];	
        $tmf	  = $_REQUEST['tmf'];  
        $tyf	  = $_REQUEST['tyf'];  
        $des['t1fdate'] = $tdf[0].'-'.$tmf[0].'-'.$tyf[0]; 
        $des['t2fdate'] = $tdf[1].'-'.$tmf[1].'-'.$tyf[1]; 
        $des['t3fdate'] = $tdf[2].'-'.$tmf[2].'-'.$tyf[2]; 
        
        
        $tdt	  = $_REQUEST['tdt'];	
        $tmt	  = $_REQUEST['tmt'];  
        $tyt	  = $_REQUEST['tyt'];  
        $des['t1tdate'] = $tdt[0].'-'.$tmt[0].'-'.$tyt[0]; 
        $des['t2tdate'] = $tdt[1].'-'.$tmt[1].'-'.$tyt[1]; 
        $des['t3tdate'] = $tdt[2].'-'.$tmt[2].'-'.$tyt[2]; 
        
        $tinstitute =  $_REQUEST['tinstitute'];	
        $des['tinstitute1'] = $tinstitute[0];
        $des['tinstitute2'] = $tinstitute[1];
        $des['tinstitute3'] = $tinstitute[2];
        
        $tcity =  $_REQUEST['tcity'];	
        $des['tcity1'] = $tcity[0];
        $des['tcity2'] = $tcity[1];
        $des['tcity3'] = $tcity[2];
        
        $tcountry = $_REQUEST['tcountry'];	
        $des['tcountry1'] = $tcountry[0];
        $des['tcountry2'] = $tcountry[1];
        $des['tcountry3'] = $tcountry[2];
        
        //Work Experience
        $expart  = $_REQUEST['expart'];	
        $des['expart1'] = $expart[0];
        $des['expart2'] = $expart[1];
        $des['expart3'] = $expart[2];
        
        $wname  = $_REQUEST['wname'];	
        $des['wname1'] = $wname[0];
        $des['wname2'] = $wname[1];
        $des['wname3'] = $wname[2];
        
        $wposition  = $_REQUEST['wposition'];	
        $des['wposition1'] = $wposition[0];
        $des['wposition2'] = $wposition[1];
        $des['wposition3'] = $wposition[2];
        
        $wfm			=  $_REQUEST['wfm'];
        $wfy			=  $_REQUEST['wfy'];
        $des['sfrom1'] = $wfm[0].'-'.$wfy[0];
        $des['sfrom2'] = $wfm[1].'-'.$wfy[1];
        $des['sfrom3'] = $wfm[2].'-'.$wfy[2];
        
        
        $wtm				=  $_REQUEST['wtm'];
        $wty				=  $_REQUEST['wty'];
        $des['tfrom1'] = $wtm[0].'-'.$wty[0];
        $des['tfrom2'] = $wtm[1].'-'.$wty[1];
        $des['tfrom3'] = $wtm[2].'-'.$wty[2];
        
        $wstart_sal    = $_REQUEST['wstart_sal'];
        $des['wstart_sal1'] = $wstart_sal[0] ;
        $des['wstart_sal2'] = $wstart_sal[1] ;
        $des['wstart_sal3'] = $wstart_sal[2] ;
        
        $wfinal_sal    = $_REQUEST['wfinal_sal'];
        $des['wfinal_sal1'] = $wfinal_sal[0] ;
        $des['wfinal_sal2'] = $wfinal_sal[1] ;
        $des['wfinal_sal3'] = $wfinal_sal[2] ;
        
        $wstart_salb    = $_REQUEST['wstart_salb'];
        $des['wstart_salb1'] = $wstart_salb[0] ;
        $des['wstart_salb2'] = $wstart_salb[1] ;
        $des['wstart_salb3'] = $wstart_salb[2] ;
        
        $wfinal_salb    = $_REQUEST['wfinal_salb'];
        $des['wfinal_salb1'] = $wfinal_salb[0] ;
        $des['wfinal_salb2'] = $wfinal_salb[1] ;
        $des['wfinal_salb3'] = $wfinal_salb[2] ;
        
        $waddress    = $_REQUEST['waddress'];
        $des['waddress1'] = $waddress[0] ;
        $des['waddress2'] = $waddress[1] ;
        $des['waddress3'] = $waddress[2] ;
        
        $wphone    = $_REQUEST['wphone'];
        $des['wphone1'] = $wphone[0] ;
        $des['wphone2'] = $wphone[1] ;
        $des['wphone3'] = $wphone[2] ;
        
        $wperson    = $_REQUEST['wperson'];
        $des['wperson1'] = $wperson[0] ;
        $des['wperson2'] = $wperson[1] ;
        $des['wperson3'] = $wperson[2] ;
        
        $wemail    = $_REQUEST['wemail'];
        $des['wemail1'] = $wemail[0] ;
        $des['wemail2'] = $wemail[1] ;
        $des['wemail3'] = $wemail[2] ;
        
        $wsupervisor    = $_REQUEST['wsupervisor'];
        $des['wsupervisor1'] = $wsupervisor[0] ;
        $des['wsupervisor2'] = $wsupervisor[1] ;
        $des['wsupervisor3'] = $wsupervisor[2] ;
        
        $wreason_leave    = $_REQUEST['wreason_leave'];
        $des['wreason_leave1'] = $wreason_leave[0] ;
        $des['wreason_leave2'] = $wreason_leave[1] ;
        $des['wreason_leave3'] = $wreason_leave[2] ;
        
        $wmajor    = $_REQUEST['wmajor'];
        $des['wmajor1'] = $wmajor[0] ;
        $des['wmajor2'] = $wmajor[1] ;
        $des['wmajor3'] = $wmajor[2] ;        
        
        $wachivement    = $_REQUEST['wachivement'];
        $des['wachivement1'] = $wachivement[0] ;
        $des['wachivement2'] = $wachivement[1] ;
        $des['wachivement3'] = $wachivement[2] ;                                             
        
        //Reference
        $rname 		= $_REQUEST['rname'];
        $des['rname1'] = $rname[0] ;
        $des['rname2'] = $rname[1] ;
        $des['rname3'] = $rname[2] ;  
        
        $rposition	= $_REQUEST['rposition'];
        $des['rposition1'] = $rposition[0] ;
        $des['rposition2'] = $rposition[1] ;
        $des['rposition3'] = $rposition[2] ;  
        
        $raddress	= $_REQUEST['raddress'];
        $des['raddress1'] = $raddress[0] ;
        $des['raddress2'] = $raddress[1] ;
        $des['raddress3'] = $raddress[2] ;  
        
        $rphone		= $_REQUEST['rphone'];
        $des['rphone1'] = $rphone[0] ;
        $des['rphone2'] = $rphone[1] ;
        $des['rphone3'] = $rphone[2] ;  
        
        //Special Skills
        $skills 	= $_REQUEST['skills'];
        $agree	= $_REQUEST['agree'];
        if($agree != 'Y'){ $error = true; $text .= 'Did you forgot to agree with condition statement ?';}
        
        //ca
        $jobs  = array();		
        $jobr  = array();
        if(!empty($_REQUEST['simple-joblist'])){
			$jobr  = $_REQUEST['simple-joblist'];
		}
        $jobother	= addslashes(trim($_REQUEST['jobother']));
        if(sizeof($jobr ) == 0 && $jobother == ''){$error = true; $text .= 'Please select at least one position<br/>';}
        else{
			if($jobother != ''){$jobs = $jobr; $jobs [] = $jobother;}
			$jobs   = serialize($jobs );
		}
		//var_dump($jobr);
		/*
		if( array_key_exists( 'simple-joblist', $_REQUEST ) ) {
			$joblist = explode( ',', $_REQUEST['simple-joblist'] );
			$jobs = array_merge( $jobs, $joblist );
					$jobs   = serialize($jobs);
		}
		*/
		
		 // file processing
		 $photograph = $_POST['photograph'];
		 if($photograph == ''){$error = true; $text .= 'Please attach your photograph';}
		 
		  if(!empty($_FILES["userfile"]) && $_FILES['userfile']['error'] == 0)
			{
				$fname = basename($_FILES['userfile']['name']);
				$ext = strtolower(substr($fname, strrpos($fname, '.') + 1));
				$ftype = $_FILES['userfile']['type'];				
				if($ext  == "jpg" && $ftype == "image/jpeg")
				{
					$maxfilesize = 1024*500; //500 KB
					if($_FILES['userfile']['size'] < $maxfilesize ){ // 
						$randname = rand(5, 15);
						$fname = str_replace(' ', '_', $fname);
						//$fname = preg_replace('/[^A-Za-z0-9\-._\/]/', '', $fname);						
						$fname = substr($fname,0,10).date("dmY").time().'.'.$ext;
						$uploadpath = get_option('simplejob_uploadpath');
						$path = $uploadpath.'/simplejob/'.$fname;
						//$newname = WP_CONTENT_URL.'/simplejob/'.substr($fname,0,10).date("d-m-Y").'-'.time().'.'.$ext;
						if ((move_uploaded_file($_FILES['userfile']['tmp_name'],$path))) {
								//$filename = $path;
								$photograph = $fname;
								$des['photograph'] = $photograph;
							} else {
							   $error = true; 							  
								$text .= "Error occurred during uploading file !";	
							}
					}
					else
					{
						$error = true; 							
						$text .= "Uploaded file is too large in size. please try to make it less than".$maxfilesize." KB";								
					}
					
				}
				else
				{
					$error = true; 						
					$text .= "Please select jpg file or your uploaded file is not valid jpg file !<br/>";							
				}
			}
			else
			{
				$error = true; 						
				 $text .= "Did you forget to attach a photo? !<br/>";							
			
			}		
		
		
        
        if(!$error){
            $des   = serialize($des);
            $sql = "INSERT INTO $wpdb->simplejobapp VALUES (0,'$fullname','$jobs','$email','$des', 0, '',NOW())";
            //var_dump($sql);
            $addapp = $wpdb->query($sql);
            if(!$addapp) {
                    $text = '<font color="red">'.sprintf(__('Error In Adding app \'%s\'' , 'simplejob'), $full_name).'</font>';
                    $error = true;
            } else {
                    $appid = intval($wpdb->insert_id);
                    $text = '<font color="green">'.sprintf(__('Application  (ID: %s) Added Successfully', 'simplejob'), $appid).' </font>';
                    $text .= '<p>Hello, '.$fullname.', thank you for applying. We will review your application and get back to you.</p.';
                   //$text .= 'Position(s) applied for:';
                    $blogname = get_option('blogname');        
                    $adminmail = get_option('simplejob_adminmail');      								
				    $mail_From = "From: $blogname <". $adminmail.">";
				    $mail_Subject = get_option('simplejob_mailsubject');					
					$search = array('$fullname','$blogname');					
					$replace = array($fullname,	$blogname);									
					$mail_Body = str_replace($search, $replace, get_option('simplejob_mailbody'));				
					@wp_mail($email, $mail_Subject, $mail_Body, $mail_From);  //send mail to applicant
					
					$mail_Subject_admin = 'New application alert';
					$app_viewlink = get_option('siteurl').'/wp-admin/admin.php?page=simplejob/applications.php&mode=view&id='.$appid;
					$mail_Body_admin = $fullname. ' has submitted an application. Application id:'.$appid.' <br/>View the application from admin panel '.$app_viewlink;
					@wp_mail( $adminmail, $mail_Subject_admin, $mail_Body, $mail_From);  //send mail alert to admin
             }      

        }//error

    }
    else
    {
		$jobr = array();
		if(!empty($_REQUEST['simple-joblist'])){
			$jobr = $_REQUEST['simple-joblist'];
		}
        $jobsingle = intval($_REQUEST['jobid']);
        if($jobsingle != 0 ){$jobr[] = $jobsingle;}
        //var_dump($jobr);
    }

    ?>
    <?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.stripslashes($text).'</p></div>'; } ?>
    <?php if(empty($_REQUEST['cmdSubmit']) || $error == true): ?>
    <form method="post" action="" enctype="multipart/form-data" id="appForm" name="appForm">

        <?php
        if(!empty ($_REQUEST['jobid'])){
            echo '<input type="hidden" name="jobid" value="'.intval($_REQUEST['jobid']). '" />';
        }
        if( array_key_exists( 'simple-joblist', $_REQUEST ) && empty($_REQUEST['cmdSubmit']) )
            echo '<input type="hidden" name="simple-joblist" value="' . implode( ',', $_REQUEST['simple-joblist'] ) . '" />';
        else
            echo '<input type="hidden" name="simple-joblist" value="'.$_REQUEST['simple-joblist'].'" />';
        ?>
        <h4 class="form_sectiontitle">Personal</h4>
        <table width="792" cellspacing="0" cellpadding="0" border="0" align="center">
            <tbody>
            <tr>
              <td width="80" height="20">Full Name </td>
              <td width="295" height="20"><input value="<?php echo $fullname; ?>" type="text" name="fullname" class="text" id="fullname" size="30" />
                <span class="mandetory">*</span></td>
              <td width="123" height="20">Father's Name </td>
              <td width="286" height="20"><input value="<?php echo $fathername; ?>" type="text" name="fathername" class="text" id="fathername" size="30">
                <span class="mandetory">*</span></td>
            </tr>
            <tr>
              <td height="20">Surname</td>
              <td height="20"><input value="<?php echo $surname; ?>" type="text" name="surname" class="text" id="surname" size="30">
                <span class="mandetory">*</span></td>
              <td width="123" height="20">Mother's Name</td>
              <td height="20"><input value="<?php echo $mname; ?>" type="text" name="mname" class="text" id="mname" size="30">
                <span class="mandetory">*</span></td>
            </tr>
            </tbody>
        </table>
        <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
            <tbody>
            <tr>
              <td height="20">Gender/Sex</td>
              <td height="20" width="295">
		<select name="sex" class="text" id="sex">
                  <option <?php selected('0', $sex); ?> value="0">select</option>
                  <option <?php selected('Male', $sex); ?> value="Male">Male</option>
                  <option <?php selected('Female', $sex); ?> value="Female">Female</option>
                </select>
                <span class="mandetory">*</span></td>
              <td width="123" height="20"></td>
              <td height="20"></td>
            </tr>
            <tr>
              <td height="20">Date of Birth </td>
              <td height="20">
                <?php date_day_helper($bday, $name='bday', $id ='bday');?>
                <?php date_month_helper($bmon, $name='bmon',$id='bmon');  ?>
                <?php date_year_helper($byear, $name='byear',$id='byear');  ?>                                              <span class="mandetory">*</span>
              </td>
              <td width="123" height="20">Place of Birth </td>
              <td height="20"><select name="pbirth" class="text" id="pbirth" >
                        <option <?php selected('0', $pbirth); ?> value="0">select</option>
                        <option <?php selected('Dhaka', $pbirth); ?> value="Dhaka">Dhaka</option>
                        <option <?php selected('Rajshahi', $pbirth); ?> value="Rajshahi">Rajshahi</option>
                        <option <?php selected('Chittagong', $pbirth); ?> value="Chittagong">Chittagong</option>
                        <option <?php selected('Khulna', $pbirth); ?>  value="Khulna">Khulna</option>
                        <option <?php selected('Barishal', $pbirth); ?> value="Barishal">Barishal</option>
                        <option <?php selected('Sylhet', $pbirth); ?> value="Sylhet">Sylhet</option>
                        <option <?php selected('Other', $pbirth); ?> value="Other">Other</option>
                            </select>
                <input type="text" name="pbirtht" class="text" id="pbirtht" size="20" value="<?php  echo $pbirtht; ?>" >
                <span class="mandetory">*</span></td>
            </tr>
            <tr>
              <td height="20">Nationality</td>
              <td height="20"><select name="nationality" class="text" id="nationality" >
                <option  <?php selected('0', $nationality); ?> value="0">select</option>
                <option <?php selected('Bangladeshi', $nationality); ?> value="Bangladeshi">Bangladeshi</option>
                <option <?php selected('Other', $nationality); ?> value="Other">Other</option>
              </select>
                <input type="text" name="nationalityt" class="text" id="nationalityt" value="<?php  echo $nationalityt; ?>" size="25" >
                <span class="mandetory">*</span></td>
              <td width="123" height="20"></td>
              <td height="20">
              </td>
            </tr>
            </tbody>
        </table>
        <table width="792" cellspacing="0" cellpadding="0" border="0" align="center">
            <tbody>
            <tr>
              <td height="20" width="80">Marital Status</td>
              <td height="20">
                <select name="marital" class="text" id="marital">
                  <option <?php selected('0', $marital); ?> value="0">select</option>
                  <option <?php selected('Unmarried', $marital); ?> value="Unmarried">Unmarried</option>
                  <option <?php selected('Married', $marital); ?> value="Married">Married</option>
                  <option <?php selected('Widow', $marital); ?> value="Widow">Widow</option>
                  <option <?php selected('Divorced', $marital); ?> value="Divorced">Divorced</option>
                </select>
                <span class="mandetory">*</span></td>
              <td width="123" height="20"></td>
              <td height="20"></td>
            </tr>
            <tr>
              <td valign="middle" height="20" width="80">Mobile No. </td>
              <td valign="middle" height="20" width="295"><input type="text" name="mobile" class="text" value="<?php echo $mobile; ?>" id="mobile" size="30">
                <span class="mandetory">*</span></td>
              <td width="123" valign="middle" height="20">&nbsp;</td>
              <td valign="middle" height="20">&nbsp;</td>
            </tr>
            <tr>
              <td valign="top" height="20">Present Address </td>
              <td valign="top" height="20"><textarea name="present" cols="30" rows="3" class="textArea" id="present"><?php echo $present; ?></textarea>
                <span class="mandetory">*</span></td>
              <td width="123" valign="top" height="20">Permanent Address </td>
              <td height="20"><textarea name="permanent" cols="30" rows="3" class="textArea" id="permanent"><?php echo $permanent; ?></textarea>
                <span class="mandetory">*</span></td>
            </tr>
            <tr>
              <td height="20">Present Phone </td>
              <td height="20"><input type="text" name="present_phone" class="text" value="<?php echo $present_phone; ?>" id="present_phone" size="30">
                <span class="mandetory">*</span></td>
              <td width="123" height="20">Permanent Phone </td>
              <td height="20"><input type="text" name="permanent_phone" class="text"  value="<?php echo $permanent_phone; ?>" id="permanent_phone" size="30">
                <span class="mandetory">*</span></td>
            </tr>

            <tr>
              <td height="20">E-mail Address </td>
              <td height="20"><input value="<?php echo $email; ?>" type="text" name="email" class="text" id="email" size="30" /><span class="mandetory">*</span></td>
              <td width="123" height="20">&nbsp;</td>
              <td height="20">&nbsp;</td>
            </tr>
            <tr>
              <td height="20">Photograph</td>
              <td height="20">
					<input type="hidden" name="photograph" value="<?php echo $photograph; ?>" />					
					<input type="file" name="userfile" class="text" id="userfile" size="28">              
					<span class="mandetory">*</span><br/>
					<?php if($photograph != ''){ echo '<a href="'.WP_CONTENT_URL.'/simplejob/'.$photograph.'">View uploaded file</a>';} ?>									
			  </td>																                
              <td height="20" colspan="2" class="mandetory">[Browse for Your photograph. Photograph Must be in JPG format. Photo Size Must be within WxH:500x600pixels]</td>
            </tr>
            <tr>
              <td valign="top" height="20">Interests /Hobbies</td>
              <td height="20" colspan="3"><textarea name="hobby" cols="50" rows="3" class="textArea" id="hobby"><?php echo $hobby; ?></textarea></td>
              </tr>
            <tr>
              <td valign="top" height="20">Extra Curricular Activities</td>
              <td height="20" colspan="3"><textarea name="extra_act" cols="50" rows="3" class="textArea" id="extra_act"><?php echo $extra_act; ?></textarea></td>
              </tr>
          </tbody>
        </table>
        <h4 class="form_sectiontitle">Career</h4>
        <table width="792" cellspacing="0" cellpadding="0" border="0" align="center">        
        <tr>
          <td height="20" colspan="4"><table width="98%" cellspacing="0" cellpadding="0" border="0" align="center">
           <tr>
              <td width="128" height="20">Position Applying for<br> <span class="mandetory">* [Atleast one] </span></td>
              <td height="20" colspan="3"><table width="100%" cellspacing="0" cellpadding="0" border="0">
              <?php 
					$jobdb = $wpdb->get_results("SELECT * FROM $wpdb->simplejob WHERE published=1 "); 
					if($jobdb){
						$i = 1;
						echo '<tr>';
                        foreach($jobdb as $job) {
							$selected = '';
						   if(in_array($job->jobid,$jobr)){ $selected = 'checked="chcked"';}
						   echo '
						  <td width="3%" height="20"><input  '.$selected.' type="checkbox" value="'.$job->jobid.'" id="simple-joblist[]"  name="simple-joblist[]"></td>
						  <td width="31%" height="20">'.$job->title.'</td>';              						    
						  if($i %3 == 0){ echo '</tr><tr>';}
						  $i++;
						}//end foreach
						echo '</tr>';
						echo '<tr><td colspan="6">Other<input type="text" name="jobother" value="'.$jobother.'"/></td></tr>';
					}
					else{ echo '<tr><td colspan="6">Sorry there is no position open now !</td></tr>'; }            
                ?>
              </table>                  
              </td>
            </tr>
            <tr>
              <td height="20" colspan="4"><table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                  <td width="36%" height="20">Have you previously interviewed with Panigram Resort? </td>
                  <td width="16%"><select id="interview" class="text" name="interview">
                      <option <?php selected('0', $interview); ?> value="0">select</option>
                      <option <?php selected('Yes',$interview); ?> value="Yes">Yes</option>
                      <option <?php selected('No', $interview); ?> value="No">No</option>
                  </select>
                    <span class="mandetory">*</span></td>
                  <td width="23%" height="20" align="left">Years of Working Experience </td>
                  <td width="25%" height="20"><input type="text" maxlength="2" size="5" id="work_level" class="text" value="<?php echo $work_level; ?>" name="work_level">
                    <span class="mandetory">*</span></td>
                </tr>
                <tr>
                  <td height="20">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td height="20" align="left" class="mandetory" colspan="2">[0-2 years = Entry Level, 2-6 years = Mid Level, <br>
                     Over 8 years or more = Senior Level ] </td>
                  </tr>
              </table>
              </td>
            </tr>
            <tr>
              <td height="20" colspan="4">                  
                </td>
              </tr>
            <tr>
              <td height="20">Language Proficiency <span class="mandetory">*</span></td>
              <td height="20" colspan="3"><table width="100%" cellspacing="0" cellpadding="0" border="0">
                  <tr>
                    <td width="25%" height="20" ><strong>Language</strong></td>
                    <td width="25%" height="20"  align="center"><strong>Read</strong></td>
                    <td width="25%" height="20"  align="center"><strong>Write</strong></td>
                    <td width="25%" height="20"  align="center"><strong>Speak</strong></td>
                  </tr>
                  <tr>
                    <td height="25%" ><strong>English</strong></td>
                    <td  height="20"  align="center"><select id="eng_read" class="text" name="eng_read">
                        <option <?php selected('0', $eng_read); ?> value="0">select</option>
                        <option <?php selected('Excellent', $eng_read); ?> value="Excellent">Excellent</option>
                        <option <?php selected('Good', $eng_read); ?> value="Good">Good</option>
                        <option <?php selected('Poor', $eng_read); ?> value="Poor">Poor</option>
                    </select></td>
                    <td  height="20"  align="center"><select id="eng_write" class="text" name="eng_write">
                        <option <?php selected('0', $eng_write); ?> value="0">select</option>
                        <option <?php selected('Excellent', $eng_write); ?> value="Excellent">Excellent</option>
                        <option <?php selected('Good', $eng_write); ?> value="Good">Good</option>
                        <option <?php selected('Poor', $eng_write); ?> value="Poor">Poor</option>
                    </select></td>
                    <td  height="20"  align="center"><select id="eng_speak" class="text" name="eng_speak">
                        <option <?php selected('0', $eng_speak); ?> value="0">select</option>
                        <option <?php selected('Excellent', $eng_speak); ?> value="Excellent">Excellent</option>
                        <option <?php selected('Good', $eng_speak); ?> value="Good">Good</option>
                        <option <?php selected('Poor', $eng_speak); ?> value="Poor">Poor</option>
                    </select></td>
                  </tr>
                  <tr>
                    <td height="25%" ><strong>Bangla</strong></td>
                    <td  height="20"  align="center"><select id="ban_read" class="text" name="ban_read">
                         <option <?php selected('0', $ban_read); ?> value="0">select</option>
                        <option <?php selected('Excellent', $ban_read); ?> value="Excellent">Excellent</option>
                        <option <?php selected('Good', $ban_read); ?> value="Good">Good</option>
                        <option <?php selected('Poor', $ban_read); ?> value="Poor">Poor</option>
                    </select></td>
                    <td  height="20"  align="center"><select id="ban_write" class="text" name="ban_write">
                        <option <?php selected('0', $ban_write); ?> value="0">select</option>
                        <option <?php selected('Excellent', $ban_write); ?> value="Excellent">Excellent</option>
                        <option <?php selected('Good', $ban_write); ?> value="Good">Good</option>
                        <option <?php selected('Poor', $ban_write); ?> value="Poor">Poor</option>
                    </select></td>
                    <td  height="20"  align="center"><select id="ban_speak" class="text" name="ban_speak">
                        <option <?php selected('0', $ban_speak); ?> value="0">select</option>
                        <option <?php selected('Excellent', $ban_speak); ?> value="Excellent">Excellent</option>
                        <option <?php selected('Good', $ban_speak); ?> value="Good">Good</option>
                        <option <?php selected('Poor', $ban_speak); ?> value="Poor">Poor</option>
                    </select></td>
                  </tr>
                  <tr>
                    <td height="25%" >Other
                      <input type="text" size="20" id="lan_other" value="<?php echo $lan_other; ?>" class="text" name="lan_other"></td>
                    <td  height="20"  align="center"><select id="oth_read" class="text" name="oth_read">
                        <option <?php selected('0', $oth_read); ?> value="0">select</option>
                        <option <?php selected('Excellent', $oth_read); ?> value="Excellent">Excellent</option>
                        <option <?php selected('Good', $oth_read); ?> value="Good">Good</option>
                        <option <?php selected('Poor', $oth_read); ?> value="Poor">Poor</option>
                    </select></td>
                    <td  height="20"  align="center"><select id="oth_write" class="text" name="oth_write">
                        <option <?php selected('0', $oth_write); ?> value="0">select</option>
                        <option <?php selected('Excellent', $oth_write); ?> value="Excellent">Excellent</option>
                        <option <?php selected('Good', $oth_write); ?> value="Good">Good</option>
                        <option <?php selected('Poor', $oth_write); ?> value="Poor">Poor</option>
                    </select></td>
                    <td  height="20"  align="center"><select id="oth_speak" class="text" name="oth_speak">
                        <option <?php selected('0', $oth_speak); ?> value="0">select</option>
                        <option <?php selected('Excellent', $oth_speak); ?> value="Excellent">Excellent</option>
                        <option <?php selected('Good', $oth_speak); ?> value="Good">Good</option>
                        <option <?php selected('Poor', $oth_speak); ?> value="Poor">Poor</option>
                    </select></td>
                  </tr>
              </table></td>
            </tr>
            <tr>
              <td height="20">Applying for <span class="mandetory">*</span></td>
              <td width="269" height="20">
			  <select id="appfor" class="text" name="appfor">
                <option value="0" <?php selected('0', $appfor); ?>>Select</option>
                <option <?php selected('Internship', $appfor); ?> value="Internship">Internship</option>
                <option <?php selected('Full Time', $appfor); ?> value="Full Time">Full Time</option>
                <option <?php selected('Part Time', $appfor); ?> value="Part Time">Part Time</option>
                <option <?php selected('Contract Job', $appfor); ?> value="Contract Job">Contract Job</option>
              </select>
              </td>
              <td width="139" height="20" align="right">Ready to Join&nbsp; </td>
              <td width="200" height="20">
                <?php date_day_helper($jday, $name='jday', $id ='select4');?>
                <?php date_month_helper($jmon, $name='jmon',$id='select5');  ?>
                <?php date_year_helper($jyear, $name='jyear',$id='select6');  ?>
                <span class="mandetory">*</span></td>
            </tr>
            <tr>
              <td valign="top" height="20">Your Main Reason for Applying to Panigram Resort</td>
              <td valign="top" height="20" colspan="3"><textarea id="reason" class="textArea" rows="4" cols="50" name="reason"><?php echo $reason; ?></textarea></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td height="20" colspan="4">&nbsp;</td>
        </tr>
        </table>
        <h4 class="form_sectiontitle">Education</h4>
        <table width="792" cellspacing="0" cellpadding="0" border="0" align="center">        
        <tr>
          <td height="20" colspan="4"><table width="100%" cellspacing="0" cellpadding="2" border="0">
            <tr>
              <td>
                <table width="97%" align="center">
                      
                      <tr>
                        <td>Please List the Recent Educational Degrees in a Chronological Order (starting from the most recent one)</td>
                      </tr>
                </table>

                <table width="97%" cellspacing="0" cellpadding="0" border="0" align="center">
                  <tr>
                    <td height="20"  colspan="4"><strong class="title">Academic Qualification 1</strong> <span class="mandetory">* Most Recent </span></td>
                    </tr>
                  <tr>
                    <td width="16%" height="20" >Type of Degree </td>
                    <td width="39%" height="20" ><input  maxlength="255" size="30" id="education[]" class="text" value="<?php echo $education[0]; ?>" name="education[]"></td>
                    <td width="14%" height="20" >Year of Degree </td>
                    <td width="31%" height="20" >                        
                        <?php date_year_helper($pyear[0], $name='pyear[]',$id='select7');  ?>
                    </td>
                  </tr>
                  <tr>
                    <td height="20" >Name of Institute </td>
                    <td height="20" ><input  maxlength="255" size="30" id="institute[]" class="text" value="<?php echo $institute[0]; ?>" name="institute[]"></td>
                    <td height="20" >Country</td>
                    <td height="20" ><input  maxlength="100" size="35" id="country[]" class="text" value="<?php echo $country[0]; ?>" name="country[]"></td>
                  </tr>
                  <tr>
                    <td height="20" >Board/University</td>
                    <td height="20" ><input  maxlength="250" size="30" id="board[]" class="text" value="<?php echo $board[0]; ?>" name="board[]"></td>
                    <td height="20" >Group/Subject </td>
                    <td height="20" ><input  maxlength="200" size="35" id="sgroup[]" class="text" value="<?php echo $sgroup[0]; ?>" name="sgroup[]"></td>
                  </tr>
                  <tr>
                    <td height="20" >Division/Class/CGPA</td>
                    <td height="20" ><input  maxlength="200" size="30" id="division[]" class="text" value="<?php echo $division[0]; ?>" name="division[]"></td>
                    <td height="20" >&nbsp;</td>
                    <td height="20" >&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="20">&nbsp;</td>
                    <td height="20">&nbsp;</td>
                    <td height="20">&nbsp;</td>
                    <td height="20">&nbsp;</td>
                  </tr>
                </table>
				<table width="97%" cellspacing="0" cellpadding="0" border="0" align="center">
                  <tr>
                    <td height="20"  colspan="4"><strong class="title">Academic Qualification 2 </strong> </td>
                  </tr>
                  <tr>
                    <td width="16%" height="20" >Type of Degree </td>
                    <td width="39%" height="20" ><input  maxlength="255" size="30" id="education[]" class="text" value="<?php echo $education[1]; ?>" name="education[]"></td>
                    <td width="14%" height="20" >Year of Degree </td>
                    <td width="31%" height="20" >
                      <?php date_year_helper($pyear[1], $name='pyear[]',$id='select9');  ?>              
                    </td>
                  </tr>
                  <tr>
                    <td height="20" >Name of Institute </td>
                    <td height="20" ><input  maxlength="255" size="30" id="institute[]" class="text" value="<?php echo $institute[1]; ?>" name="institute[]"></td>
                    <td height="20" >Country</td>
                    <td height="20" ><input  maxlength="100" size="35" id="country[]" class="text" value="<?php echo $country[1]; ?>"  name="country[]"></td>
                  </tr>
                  <tr>
                    <td height="20" >Board/University</td>
                    <td height="20" ><input  maxlength="250" size="30" id="board[]" class="text" value="<?php echo $board[1]; ?>" name="board[]"></td>
                    <td height="20" >Group/Subject </td>
                    <td height="20" ><input  maxlength="200" size="35" id="sgroup[]" class="text " value="<?php echo $sgroup[1]; ?>"  name="sgroup[]"></td>
                  </tr>
                  <tr>
                    <td height="20" >Division/Class/CGPA</td>
                    <td height="20" ><input  maxlength="200" size="30" id="division[]2" class="text"  value="<?php echo $division[1]; ?>" name="division[]"></td>
                    <td height="20" >&nbsp;</td>
                    <td height="20" >&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="20">&nbsp;</td>
                    <td height="20">&nbsp;</td>
                    <td height="20">&nbsp;</td>
                    <td height="20">&nbsp;</td>
                  </tr>
                </table>
				<table width="97%" cellspacing="0" cellpadding="0" border="0" align="center">
                  <tr>
                    <td height="20"  colspan="4"><strong class="title">Academic Qualification 3 </strong> </td>
                  </tr>
                  <tr>
                    <td width="16%" height="20" >Type of Degree </td>
                    <td width="39%" height="20" ><input value="" maxlength="255" size="30" id="education[]" class="text" value="<?php echo $eduation[2]; ?>" name="education[]"></td>
                    <td width="14%" height="20" >Year of Degree </td>
                    <td width="31%" height="20" >
                         <?php date_year_helper($pyear[2], $name='pyear[]',$id='select10');  ?>                     
                    </td>
                  </tr>
                  <tr>
                    <td height="20" >Name of Institute </td>
                    <td height="20" ><input  maxlength="255" size="30" id="institute[]" class="text" value="<?php echo $institute[2]; ?>" name="institute[]"></td>
                    <td height="20" >Country</td>
                    <td height="20" ><input  maxlength="100" size="35" id="country[]" class="text" value="<?php echo $country[2]; ?>"  name="country[]"></td>
                  </tr>
                  <tr>
                    <td height="20" >Board/University</td>
                    <td height="20" ><input  maxlength="250" size="30" id="board[]" class="text" value="<?php echo $board[2]; ?>" name="board[]"></td>
                    <td height="20" >Group/Subject </td>
                    <td height="20" ><input  maxlength="200" size="35" id="sgroup[]" class="text" value="<?php echo $sgroup[2]; ?>" name="sgroup[]"></td>
                  </tr>
                  <tr>
                    <td height="20" >Division/Class/CGPA</td>
                    <td height="20" ><input  maxlength="200" size="30" id="division[]" class="text" value="<?php echo $division[2]; ?>" name="division[]"></td>
                    <td height="20" >&nbsp;</td>
                    <td height="20" >&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="20">&nbsp;</td>
                    <td height="20">&nbsp;</td>
                    <td height="20">&nbsp;</td>
                    <td height="20">&nbsp;</td>
                  </tr>
                </table>

              </td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td width="85" height="20">&nbsp;</td>
          <td height="20" colspan="2"> </td>
          <td width="268" height="20">&nbsp;</td>
        </tr>
        <tr>
          <td height="20">&nbsp;</td>
          <td height="20" colspan="3">&nbsp;</td>
        </tr>
        </table>
        <h4 class="form_sectiontitle">Computer Literacy</h4>
        <table width="792" cellspacing="0" cellpadding="0" border="0" align="center">        
        <tr>
          <td valign="top" height="20" colspan="4"><table  cellspacing="0" cellpadding="0" border="0">
            <tr>
              <td width="94" valign="top" height="20"><p><strong>
                <input type="hidden" name="lit" value="0" id="lit">
              </strong>(you can select multiple)<span class="mandetory"> *</span> </p>                </td>
              <td width="682" height="20" colspan="3"><table width="100%" cellspacing="0" cellpadding="0" border="0">
                  <tr>
                    <td width="4%" height="20"><input type="checkbox"  name="computer[]" <?php checked('Windows', $computer[0]); ?> value="Windows" id="computer[]"></td>
                    <td width="22%" height="20">Windows</td>
                    <td width="3%" height="20"><input type="checkbox" name="computer[]" <?php checked('MS Word', $computer[1]); ?> value="MS Word" id="computer[]"></td>
                    <td width="24%" height="20">MS Word </td>
                    <td width="4%" height="20"><input type="checkbox" name="computer[]" <?php checked('MS Excel', $computer[2]); ?> value="MS Excel" id="computer[]"></td>
                    <td width="20%" height="20">MS Excel </td>
                    <td width="4%" height="20"><input type="checkbox" name="computer[]" <?php checked('MS Power Point', $computer[3]); ?>  value="MS Power Point" id="computer[]"></td>
                    <td width="19%" height="20">MS Power Point </td>
                  </tr>
                  <tr>
                    <td height="20"><input type="checkbox" name="computer[]" <?php checked('Networking', $computer[4]); ?> value="Networking" id="computer[]"></td>
                    <td height="20">Networking</td>
                    <td height="20"><input type="checkbox" name="computer[]" <?php checked('Database Management', $computer[5]); ?> value="Database Management" id="computer[]"></td>
                    <td height="20">Database Management</td>
                    <td height="20"><input type="checkbox" name="computer[]" <?php checked('System Analyst', $computer[6]); ?> value="System Analyst" id="computer[]"></td>
                    <td height="20">System Analysis</td>
                    <td height="20"><input type="checkbox" name="computer[]" <?php checked('E-Mail/Browsing', $computer[7]); ?>  value="E-Mail/Browsing" id="computer[]"></td>
                    <td height="20">E-Mail/Browsing</td>
                  </tr>
                  <tr>
                    <td height="20"><input type="checkbox" name="computer[]" <?php checked('Programming Language', $computer[8]); ?> value="Programming Language" id="computer[]"></td>
                    <td height="20">Programming Language</td>
                    <td height="20"><input type="checkbox" name="computer[]" <?php checked('Any', $computer[9]); ?> value="Any" id="computer[]"></td>
                    <td height="20">Any Special IT qualification</td>
                    <td height="20" colspan="4"><strong>
                      <input size="55" id="cother" class="text" value="<?php echo $cother; ?>" name="cother">
                    </strong></td>
                    </tr>
                  <tr>
                    <td height="20" ><input type="checkbox" name="computer[]" <?php checked('No Computer Literacy', $computer[10]); ?> value="No Computer Literacy" id="computer[]"></td>
                    <td height="20" >No Computer Literacy</td>
                    <td height="20">&nbsp;</td>
                    <td height="20">&nbsp;</td>
                    <td height="20">&nbsp;</td>
                    <td height="20">&nbsp;</td>
                    <td height="20">&nbsp;</td>
                    <td height="20">&nbsp;</td>
                  </tr>
              </table></td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td height="20">&nbsp;</td>
          <td width="327" height="20">&nbsp;</td>
          <td width="85" height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
        </tr>
        </table>
        <h4 class="form_sectiontitle">Participated Training Programs</h4>
        <table width="792" cellspacing="0" cellpadding="0" border="0" align="center">    
        <tr align="center">
          <td height="20" colspan="4"><table width="97%" cellspacing="0" cellpadding="0">

            <tr>
              <td height="20" >&nbsp;</td>
              <td height="20" >&nbsp;</td>
              <td width="197" height="20"  align="left"><strong>Training 1</strong></td>
              <td width="198" height="20"  align="left"><strong>Training 2</strong></td>
              <td width="198" height="20"  align="left"><strong>Training 3</strong></td>
            </tr>
            <tr>
              <td height="20" >Training Title</td>
              <td height="20" >&nbsp;</td>
              <td width="197" height="20"  align="left"><input maxlength="255" size="30" id="ttitle[]" class="text" value="<?php echo $ttitle[0] ?>" name="ttitle[]">              </td>
              <td width="198" height="20"  align="left"><input maxlength="255" size="30" id="ttitle[]" class="text" value="<?php echo $ttitle[1] ?>" name="ttitle[]">              </td>
              <td width="198" height="20"  align="left"><input maxlength="255" size="30" id="ttitle[]" class="text" value="<?php echo $ttitle[2] ?>" name="ttitle[]">              </td>
            </tr>
            <tr>
              <td width="128" height="20" ><br>
                Training Period<br>  (write only in numbers)<strong><br>
                </strong></td>
              <td width="34" height="20"  align="right"><br>
                From&nbsp;<br>
                <br>
                To&nbsp; </td>
              <td width="197" height="20" ><table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
                  
                    <tr align="middle">
                      <td align="left">Day</td>
                      <td align="left">Month</td>
                      <td align="left">Year</td>
                    </tr>
                    <tr align="middle">
                      <td align="left">
                           <?php date_day_helper($tdf[0], $name='tdf[]', $id ='tdf1');?>
                      </td>
                      <td align="left">
                          <?php date_month_helper($tmf[0], $name='tmf[]',$id='tmf1');  ?>
                      </td>
                      <td align="left">
                            <?php date_year_helper($tyf[0], $name='tyf[]',$id='tyf1');  ?>
                       </td>
                    </tr>
                    <tr align="middle">
                      <td align="left">
                           <?php date_day_helper($tdt[0], $name='tdt[]', $id ='tdt1');?>
                      </td>
                      <td align="left">
                          <?php date_month_helper($tmt[0], $name='tmt[]',$id='tmt1');  ?>
                      </td>
                      <td align="left">
                            <?php date_year_helper($tyt[0], $name='tyt[]',$id='tyt1');  ?>
                       </td>
                    </tr>
                  
                </table>
                  <strong></strong></td>
              <td width="198" height="20" ><table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
                  
                    <tr align="middle">
                      <td align="left">Day</td>
                      <td align="left">Month</td>
                      <td align="left">Year</td>
                    </tr>
                    <tr align="middle">
                      <td align="left">
                           <?php date_day_helper($tdf[1], $name='tdf[]', $id ='tdf2');?>
                      </td>
                      <td align="left">
                          <?php date_month_helper($tmf[1], $name='tmf[]',$id='tmf2');  ?>
                      </td>
                      <td align="left">
                            <?php date_year_helper($tyf[1], $name='tyf[]',$id='tyf2');  ?>
                       </td>
                    </tr>
                    <tr align="middle">
                      <td align="left">
                           <?php date_day_helper($tdt[1], $name='tdt[]', $id ='tdt2');?>
                      </td>
                      <td align="left">
                          <?php date_month_helper($tmt[1], $name='tmt[]',$id='tmt2');  ?>
                      </td>
                      <td align="left">
                            <?php date_year_helper($tyt[1], $name='tyt[]',$id='tyt2');  ?>
                       </td>
                    </tr>
                  
                </table>
                  <strong></strong></td>
              <td width="198" height="20" ><table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
                  
                    <tr align="middle">
                      <td align="left">Day</td>
                      <td align="left">Month</td>
                      <td align="left">Year</td>
                    </tr>
                                        <tr align="middle">
                      <td align="left">
                           <?php date_day_helper($tdf[2], $name='tdf[]', $id ='tdf3');?>
                      </td>
                      <td align="left">
                          <?php date_month_helper($tmf[2], $name='tmf[]',$id='tmf3');  ?>
                      </td>
                      <td align="left">
                            <?php date_year_helper($tyf[2], $name='tyf[]',$id='tyf3');  ?>
                       </td>
                    </tr>
                    <tr align="middle">
                      <td align="left">
                           <?php date_day_helper($tdt[2], $name='tdt[]', $id ='tdt3');?>
                      </td>
                      <td align="left">
                          <?php date_month_helper($tmt[2], $name='tmt[]',$id='tmt3');  ?>
                      </td>
                      <td align="left">
                            <?php date_year_helper($tyt[2], $name='tyt[]',$id='tyt3');  ?>
                       </td>
                    </tr>
                  
              </table></td>
            </tr>
            <tr>
              <td height="20" >Name of Institute </td>
              <td height="20" >&nbsp;</td>
              <td width="197" height="20" ><input maxlength="255" size="30" id="tinstitute[]" value="<?php  echo $tinstitute[0]; ?>" class="text" name="tinstitute[]">              </td>
              <td width="198" height="20" ><input maxlength="255" size="30" id="tinstitute[]" value="<?php  echo $tinstitute[1]; ?>" class="text" name="tinstitute[]">              </td>
              <td width="198" height="20" ><input maxlength="255" size="30" id="tinstitute[]" value="<?php  echo $tinstitute[2]; ?>" class="text" name="tinstitute[]">              </td>
            </tr>
            <tr>
              <td height="20" >City</td>
              <td height="20" >&nbsp;</td>
              <td width="197" height="20" ><input maxlength="100" size="30" id="tcity[]" value="<?php  echo $tcity[0]; ?>" class="text" name="tcity[]">              </td>
              <td width="198" height="20" ><input maxlength="100" size="30" id="tcity[]" value="<?php  echo $tcity[1]; ?>"  class="text" name="tcity[]">              </td>
              <td width="198" height="20" ><input maxlength="100" size="30" id="tcity[]" value="<?php  echo $tcity[2]; ?>"  class="text" name="tcity[]">              </td>
            </tr>
            <tr>
              <td height="20" >Country</td>
              <td height="20" >&nbsp;</td>
              <td width="197" height="20" ><input maxlength="100" size="30" id="tcountry[]" value="<?php  echo $tcountry[0]; ?>" class="text" name="tcountry[]">              </td>
              <td width="198" height="20" ><input maxlength="100" size="30" id="tcountry[]" value="<?php  echo $tcountry[1]; ?>" class="text" name="tcountry[]">              </td>
              <td width="198" height="20" ><input maxlength="100" size="30" id="tcountry[]" value="<?php  echo $tcountry[2]; ?>" class="text" name="tcountry[]">              </td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
        </tr>
        </table>
        <h4 class="form_sectiontitle">Work Experience</h4>
        <table width="792" cellspacing="0" cellpadding="0" border="0" align="center">
        <tr>
          <td height="20"  align="center" colspan="4">
             <strong>Starting with your present post, list in reverse order every employment you have had</strong>. Use a seperate block for each post. Include also service in the armed forces and note any period during which you were not gainfully employed. Give both gross and net salaries per annum for your present post.
          </td>
        </tr>
        <tr>
          <td height="20" colspan="3">&nbsp;&nbsp;PRESENT POST (LAST POST, IF NOT PRESENTLY IN EMPLOYMENT)

            <input type="hidden" value="3" id="txtPECounter" name="txtPECounter"></td>
          <td height="20" align="right">&nbsp;</td>
        </tr>
        <tr>
          <td height="20" colspan="4">
                <table width="97%" cellspacing="0" cellpadding="2" border="0" align="center">
                    <tr align="left">
                      <td height="54"  colspan="4"><span class="style15">(Fill up the Most Recent Experience First then Previous One.)</span> <br>
                          <span class="title"><strong face="Verdana">Work Experience 1</strong></span><font face="Verdana"><span class="style3"><br>
                                            </span></font>
                          <table width="22%" cellspacing="0" cellpadding="0" border="0">
                            <tr bgcolor="#006666">
                              <td><img width="1" height="1" src="dot00002.htm"></td>
                            </tr>
                          </table>
                        Please select the particular area of Experience from the list for this <strong>particular</strong> Work Experinece<b><font face="Verdana">
                          <?php expart_helper($expart[0], $name='expart[]',$id='expart[]');  ?>
                          
                        </font></b></td>
                    </tr>
                    <tr>
                      <td width="20%" >Employer Company Name<font face="Verdana" color="#333333" size="1">&nbsp; </font></td>
                      <td width="31%" ><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="200" size="35" id="wname[]" class="text" value="<?php echo $wname[0]; ?>" name="wname[]">
                      </font></b></td>
                      <td width="11%" >Position Title </td>
                      <td width="38%" ><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="150" size="35" id="wposition[]" class="text" value="<?php echo $wposition[0]; ?>" name="wposition[]">
                      </font></b></td>
                    </tr>
                    <tr>
                      <td width="20%" >Served From</td>
                      <td width="31%" ><font face="Verdana">
                        Month   <?php date_month_helper($wfm[0], $name='wfm[]',$id='wfm[]');  ?>
                        Year    <?php date_year_helper($wfy[0], $name='wfy[]',$id='wfy[]');  ?>
                      </font><font size="1"><b><font face="Verdana"> </font></b></font></td>
                      <td width="11%" >Served Till</td>
                      <td width="38%" >
                          Month    <?php date_month_helper($wtm[0], $name='wtm[]',$id='wtm[]');  ?>
                          Year     <?php date_year_helper($wty[0], $name='wty[]',$id='wty[]');  ?>
                      </td>
                    </tr>
                    <tr>
                      <td >Salary per month </td>
                      <td ><table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                          <td width="24%">Starting</td>
                          <td width="31%"><b><font face="Verdana" color="#000000" size="1">
                            <input maxlength="10" size="8" id="wstart_sal[]" class="text" value="<?php echo $wstart_sal[0]; ?>" name="wstart_sal[]">
                          </font></b></td>
                          <td width="14%">Final</td>
                          <td width="31%"><b><font face="Verdana" color="#000000" size="1">
                            <input maxlength="10" size="8" id="wfinal_sal[]" class="text" value="<?php echo $wfinal_sal[0]; ?>" name="wfinal_sal[]">
                          </font></b></td>
                        </tr>
                      </table></td>
                      <td >&nbsp;</td>
                      <td ><em>(</em>
                          <input type="checkbox" value="Y" id="tillnow[]" name="tillnow[]">
                        Presently working <em>)</em> <span id="alert1" class="style15"><em><br>
                        (For current experience, select</em></span><span class="style15"><em> )</em></span></td>
                    </tr>
                    <tr>
                      <td >Annual Bonus</td>
                      <td ><table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tbody><tr>
                          <td width="24%">Starting</td>
                          <td width="31%"><b><font face="Verdana" color="#000000" size="1">
                            <input name="wstart_salb[]" class="text" value="<?php echo $wstart_salb[0]; ?>" id="wstart_salb[]" size="8" maxlength="10">
                          </font></b></td>
                          <td width="14%">Final</td>
                          <td width="31%"><b><font face="Verdana" color="#000000" size="1">
                            <input name="wfinal_salb[]" class="text" value="<?php echo $wfinal_salb[0]; ?>" id="wfinal_salb[]" size="8" maxlength="10">
                          </font></b></td>
                        </tr>
                      </tbody></table></td>
                      <td >&nbsp;</td>
                      <td ></td>
                    </tr>
                    <tr>
                      <td width="20%" >Employer Address</td>
                      <td width="31%" ><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="255" size="35" id="waddress[]" class="text" value="<?php echo $waddress[0]; ?>" name="waddress[]">
                      </font></b></td>
                      <td width="11%" >Phone</td>
                      <td width="38%" ><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="100" id="wphone[]" class="text" value="<?php echo $wphone[0]; ?>" name="wphone[]">
                      </font></b></td>
                    </tr>
                    <tr>
                      <td width="20%" >Contact Person</td>
                      <td width="31%" ><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="120" size="35" id="wperson[]" class="text" value="<?php echo $wperson[0]; ?>" name="wperson[]">
                      </font></b></td>
                      <td width="11%" >E-Mail</td>
                      <td width="38%" ><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="150" size="35" id="wemail[]" class="text" value="<?php echo $wemail[0]; ?>" name="wemail[]">
                      </font></b></td>
                    </tr>
                    <tr>
                      <td valign="top" >Name of Supervisor </td>
                      <td  colspan="3"><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="255" size="50" id="wsupervisor[]" class="text" value="<?php echo $wsupervisor[0]; ?>" name="wsupervisor[]">
                      </font></b></td>
                    </tr>
                    <tr>
                      <td valign="top" >Reason for Leaving  </td>
                      <td  colspan="3"><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="255" size="50" id="wreason_leave[]" class="text" value="<?php echo $wreason_leave[0]; ?>" name="wreason_leave[]">
                      </font></b></td>
                    </tr>
                    <tr>
                      <td width="20%" valign="top" >Major Resposibilities</td>
                      <td  colspan="3"><b><font face="Verdana" color="#000000" size="1">
                        <textarea  id="wmajor[]" class="textArea" rows="4" cols="50" name="wmajor[]"><?php echo $wmajor[0]; ?></textarea>
                      </font></b></td>
                    </tr>
                    <tr>
                      <td width="20%" valign="top" >Achievement/Contribution</td>
                      <td  colspan="3"><b><font face="Verdana" color="#000000" size="1">
                        <textarea id="wachivement[]" class="textArea" rows="4" cols="50" name="wachivement[]"><?php echo $wachivement[0]; ?></textarea>
                      </font></b></td>
                    </tr>
                    <tr>
                      <td valign="top">&nbsp;</td>
                      <td colspan="3">&nbsp;</td>
                    </tr>
            </table>
		  
            <table width="97%" cellspacing="0" cellpadding="2" border="0" align="center">
                    <tr align="left">
                      <td height="54"  colspan="4"><span class="style15">(Fill up the Most Recent Experience First then Previous One.)</span> <br>
                          <span class="title"><strong face="Verdana">Work Experience 2</strong></span><font face="Verdana"><span class="style3"><br>
                                            </span></font>
                          <table width="22%" cellspacing="0" cellpadding="0" border="0">
                            <tr bgcolor="#006666">
                              <td><img width="1" height="1" src="dot00002.htm"></td>
                            </tr>
                          </table>
                        Please select the particular area of Experience from the list for this <strong>particular</strong> Work Experinece<b><font face="Verdana">
                          <?php expart_helper($expart[1], $name='expart[]',$id='expart[]');  ?>
                          
                        </font></b></td>
                    </tr>
                    <tr>
                      <td width="20%" >Employer Company Name<font face="Verdana" color="#333333" size="1">&nbsp; </font></td>
                      <td width="31%" ><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="200" size="35" id="wname[]" class="text" value="<?php echo $wname[1]; ?>" name="wname[]">
                      </font></b></td>
                      <td width="11%" >Position Title </td>
                      <td width="38%" ><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="150" size="35" id="wposition[]" class="text" value="<?php echo $wposition[1]; ?>" name="wposition[]">
                      </font></b></td>
                    </tr>
                    <tr>
                      <td width="20%" >Served From</td>
                      <td width="31%" ><font face="Verdana">
                        Month   <?php date_month_helper($wfm[1], $name='wfm[]',$id='wfm[]');  ?>
                        Year    <?php date_year_helper($wfy[1], $name='wfy[]',$id='wfy[]');  ?>
                      </font><font size="1"><b><font face="Verdana"> </font></b></font></td>
                      <td width="11%" >Served Till</td>
                      <td width="38%" >
                          Month    <?php date_month_helper($wtm[1], $name='wtm[]',$id='wtm[]');  ?>
                          Year     <?php date_year_helper($wty[1], $name='wty[]',$id='wty[]');  ?>
                      </td>
                    </tr>
                    <tr>
                      <td >Salary per month </td>
                      <td ><table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                          <td width="24%">Starting</td>
                          <td width="31%"><b><font face="Verdana" color="#000000" size="1">
                            <input maxlength="10" size="8" id="wstart_sal[]" class="text" value="<?php echo $wstart_sal[1]; ?>" name="wstart_sal[]">
                          </font></b></td>
                          <td width="14%">Final</td>
                          <td width="31%"><b><font face="Verdana" color="#000000" size="1">
                            <input maxlength="10" size="8" id="wfinal_sal[]" class="text" value="<?php echo $wfinal_sal[1]; ?>" name="wfinal_sal[]">
                          </font></b></td>
                        </tr>
                      </table></td>
                      <td >&nbsp;</td>
                      <td ></td>
                    </tr>
                    <tr>
                      <td >Annual Bonus</td>
                      <td ><table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tbody><tr>
                          <td width="24%">Starting</td>
                          <td width="31%"><b><font face="Verdana" color="#000000" size="1">
                            <input name="wstart_salb[]" class="text" value="<?php echo $wstart_salb[1]; ?>" id="wstart_salb[]" size="8" maxlength="10">
                          </font></b></td>
                          <td width="14%">Final</td>
                          <td width="31%"><b><font face="Verdana" color="#000000" size="1">
                            <input name="wfinal_salb[]" class="text" value="<?php echo $wfinal_salb[1]; ?>" id="wfinal_salb[]" size="8" maxlength="10">
                          </font></b></td>
                        </tr>
                      </tbody></table></td>
                      <td >&nbsp;</td>
                      <td ></td>
                    </tr>
                    <tr>
                      <td width="20%" >Employer Address</td>
                      <td width="31%" ><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="255" size="35" id="waddress[]" class="text" value="<?php echo $waddress[1]; ?>" name="waddress[]">
                      </font></b></td>
                      <td width="11%" >Phone</td>
                      <td width="38%" ><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="100" id="wphone[]" class="text" value="<?php echo $wphone[1]; ?>" name="wphone[]">
                      </font></b></td>
                    </tr>
                    <tr>
                      <td width="20%" >Contact Person</td>
                      <td width="31%" ><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="120" size="35" id="wperson[]" class="text" value="<?php echo $wperson[1]; ?>" name="wperson[]">
                      </font></b></td>
                      <td width="11%" >E-Mail</td>
                      <td width="38%" ><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="150" size="35" id="wemail[]" class="text" value="<?php echo $wemail[1]; ?>" name="wemail[]">
                      </font></b></td>
                    </tr>
                    <tr>
                      <td valign="top" >Name of Supervisor </td>
                      <td  colspan="3"><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="255" size="50" id="wsupervisor[]" class="text" value="<?php echo $wsupervisor[1]; ?>" name="wsupervisor[]">
                      </font></b></td>
                    </tr>
                    <tr>
                      <td valign="top" >Reason for Leaving  </td>
                      <td  colspan="3"><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="255" size="50" id="wreason_leave[]" class="text" value="<?php echo $wreason_leave[1]; ?>" name="wreason_leave[]">
                      </font></b></td>
                    </tr>
                    <tr>
                      <td width="20%" valign="top" >Major Resposibilities</td>
                      <td  colspan="3"><b><font face="Verdana" color="#000000" size="1">
                        <textarea  id="wmajor[]" class="textArea" rows="4" cols="50" name="wmajor[]"><?php echo $wmajor[1]; ?></textarea>
                      </font></b></td>
                    </tr>
                    <tr>
                      <td width="20%" valign="top" >Achievement/Contribution</td>
                      <td  colspan="3"><b><font face="Verdana" color="#000000" size="1">
                        <textarea id="wachivement[]" class="textArea" rows="4" cols="50" name="wachivement[]"><?php echo $wachivement[1]; ?></textarea>
                      </font></b></td>
                    </tr>
                    <tr>
                      <td valign="top">&nbsp;</td>
                      <td colspan="3">&nbsp;</td>
                    </tr>
            </table>
            <table width="97%" cellspacing="0" cellpadding="2" border="0" align="center">
                    <tr align="left">
                      <td height="54"  colspan="4"><span class="style15">(Fill up the Most Recent Experience First then Previous One.)</span> <br>
                          <span class="title"><strong face="Verdana">Work Experience 2</strong></span><font face="Verdana"><span class="style3"><br>
                                            </span></font>
                          <table width="22%" cellspacing="0" cellpadding="0" border="0">
                            <tr bgcolor="#006666">
                              <td><img width="1" height="1" src="dot00002.htm"></td>
                            </tr>
                          </table>
                        Please select the particular area of Experience from the list for this <strong>particular</strong> Work Experinece<b><font face="Verdana">
                          <?php expart_helper($expart[2], $name='expart[]',$id='expart[]');  ?>
                          
                        </font></b></td>
                    </tr>
                    <tr>
                      <td width="20%" >Employer Company Name<font face="Verdana" color="#333333" size="1">&nbsp; </font></td>
                      <td width="31%" ><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="200" size="35" id="wname[]" class="text" value="<?php echo $wname[2]; ?>" name="wname[]">
                      </font></b></td>
                      <td width="11%" >Position Title </td>
                      <td width="38%" ><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="150" size="35" id="wposition[]" class="text" value="<?php echo $wposition[2]; ?>" name="wposition[]">
                      </font></b></td>
                    </tr>
                    <tr>
                      <td width="20%" >Served From</td>
                      <td width="31%" ><font face="Verdana">
                        Month   <?php date_month_helper($wfm[2], $name='wfm[]',$id='wfm[]');  ?>
                        Year    <?php date_year_helper($wfy[2], $name='wfy[]',$id='wfy[]');  ?>
                      </font><font size="1"><b><font face="Verdana"> </font></b></font></td>
                      <td width="11%" >Served Till</td>
                      <td width="38%" >
                          Month    <?php date_month_helper($wtm[2], $name='wtm[]',$id='wtm[]');  ?>
                          Year     <?php date_year_helper($wty[2], $name='wty[]',$id='wty[]');  ?>
                      </td>
                    </tr>
                    <tr>
                      <td >Salary per month </td>
                      <td ><table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                          <td width="24%">Starting</td>
                          <td width="31%"><b><font face="Verdana" color="#000000" size="1">
                            <input maxlength="10" size="8" id="wstart_sal[]" class="text" value="<?php echo $wstart_sal[2]; ?>" name="wstart_sal[]">
                          </font></b></td>
                          <td width="14%">Final</td>
                          <td width="31%"><b><font face="Verdana" color="#000000" size="1">
                            <input maxlength="10" size="8" id="wfinal_sal[]" class="text" value="<?php echo $wfinal_sal[2]; ?>" name="wfinal_sal[]">
                          </font></b></td>
                        </tr>
                      </table></td>
                      <td >&nbsp;</td>
                      <td ></td>
                    </tr>
                    <tr>
                      <td >Annual Bonus</td>
                      <td ><table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tbody><tr>
                          <td width="24%">Starting</td>
                          <td width="31%"><b><font face="Verdana" color="#000000" size="1">
                            <input name="wstart_salb[]" class="text" value="<?php echo $wstart_salb[2]; ?>" id="wstart_salb[]" size="8" maxlength="10">
                          </font></b></td>
                          <td width="14%">Final</td>
                          <td width="31%"><b><font face="Verdana" color="#000000" size="1">
                            <input name="wfinal_salb[]" class="text" value="<?php echo $wfinal_salb[2]; ?>" id="wfinal_salb[]" size="8" maxlength="10">
                          </font></b></td>
                        </tr>
                      </tbody></table></td>
                      <td >&nbsp;</td>
                      <td ></td>
                    </tr>
                    <tr>
                      <td width="20%" >Employer Address</td>
                      <td width="31%" ><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="255" size="35" id="waddress[]" class="text" value="<?php echo $waddress[2]; ?>" name="waddress[]">
                      </font></b></td>
                      <td width="11%" >Phone</td>
                      <td width="38%" ><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="100" id="wphone[]" class="text" value="<?php echo $wphone[2]; ?>" name="wphone[]">
                      </font></b></td>
                    </tr>
                    <tr>
                      <td width="20%" >Contact Person</td>
                      <td width="31%" ><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="120" size="35" id="wperson[]" class="text" value="<?php echo $wperson[2]; ?>" name="wperson[]">
                      </font></b></td>
                      <td width="11%" >E-Mail</td>
                      <td width="38%" ><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="150" size="35" id="wemail[]" class="text" value="<?php echo $wemail[2]; ?>" name="wemail[]">
                      </font></b></td>
                    </tr>
                    <tr>
                      <td valign="top" >Name of Supervisor </td>
                      <td  colspan="3"><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="255" size="50" id="wsupervisor[]" class="text" value="<?php echo $wsupervisor[2]; ?>" name="wsupervisor[]">
                      </font></b></td>
                    </tr>
                    <tr>
                      <td valign="top" >Reason for Leaving  </td>
                      <td  colspan="3"><b><font face="Verdana" color="#000000" size="1">
                        <input maxlength="255" size="50" id="wreason_leave[]" class="text" value="<?php echo $wreason_leave[2]; ?>" name="wreason_leave[]">
                      </font></b></td>
                    </tr>
                    <tr>
                      <td width="20%" valign="top" >Major Resposibilities</td>
                      <td  colspan="3"><b><font face="Verdana" color="#000000" size="1">
                        <textarea  id="wmajor[]" class="textArea" rows="4" cols="50" name="wmajor[]"><?php echo $wmajor[2]; ?></textarea>
                      </font></b></td>
                    </tr>
                    <tr>
                      <td width="20%" valign="top" >Achievement/Contribution</td>
                      <td  colspan="3"><b><font face="Verdana" color="#000000" size="1">
                        <textarea id="wachivement[]" class="textArea" rows="4" cols="50" name="wachivement[]"><?php echo $wachivement[2]; ?></textarea>
                      </font></b></td>
                    </tr>
                    <tr>
                      <td valign="top">&nbsp;</td>
                      <td colspan="3">&nbsp;</td>
                    </tr>
            </table>

          </td>
          </tr>
        <tr>
          <td height="20">&nbsp;</td>
          <td height="20" colspan="2">
          </td>
          <td height="20">&nbsp;</td>
        </tr>
        <tr>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
        </tr>
        </table>
        <h4 class="form_sectiontitle">Reference</h4>
        <table width="792" cellspacing="0" cellpadding="0" border="0" align="center">
        <tr>
          <td height="20"  class="title" colspan="4">&nbsp;&nbsp;R E F E R E N C E</td>
        </tr>
        <tr>
          <td height="20" colspan="4">
		  <table width="100%" cellspacing="2" cellpadding="3" border="0" align="center">
            <tr bgcolor="#e7eae7" align="center">
              <td  class="style14" colspan="2"><strong>Reference 1</strong></td>
              <td  class="style14" colspan="2"><strong>Reference 2 </strong></td>
              <td  class="style14" colspan="2"><strong>Reference 3 </strong></td>
            </tr>
            <tr>
              <td width="9%">Name </td>
              <td width="24%"><input size="25" id="rname[]" class="text" value="<?php echo $rname[0]; ?>" name="rname[]"></td>
              <td width="9%">Name </td>
              <td width="24%"><input size="25" id="rname[]" class="text" value="<?php echo $rname[1]; ?>" name="rname[]"></td>
              <td width="9%">Name </td>
              <td width="24%"><input size="25" id="rname[]" class="text" value="<?php echo $rname[2]; ?>" name="rname[]"></td>
            </tr>
            <tr>
              <td width="9%">Position</td>
              <td width="24%"><input size="25" id="rposition[]" class="text" value="<?php echo $rposition[0]; ?>" name="rposition[]">              </td>
              <td width="9%">Position</td>
              <td width="24%"><input size="25" id="rposition[]" class="text" value="<?php echo $rposition[1]; ?>" name="rposition[]">              </td>
              <td width="9%">Position</td>
              <td width="24%"><input size="25" id="rposition[]" class="text" value="<?php echo $rposition[2]; ?>" name="rposition[]">              </td>
            </tr>
            <tr>
              <td width="9%" valign="top">Full Address</td>
              <td width="24%" valign="top"><textarea id="raddress[]" class="textArea" rows="3" cols="25"  name="raddress[]"><?php echo $raddress[0]; ?></textarea>              </td>
              <td width="9%" valign="top">Full Address</td>
              <td width="24%" valign="top"><textarea id="textarea" class="textArea" rows="3" cols="25" name="raddress[]"><?php echo $raddress[1]; ?></textarea></td>
              <td width="9%" valign="top">Full Address</td>
              <td width="24%"><textarea id="textarea2" class="textArea" rows="3" cols="25" name="raddress[]"><?php echo $raddress[2]; ?></textarea></td>
            </tr>
            <tr>
              <td width="9%" height="2">Phone</td>
              <td width="24%" height="2"><input size="25" id="rphone[]" class="text" value="<?php echo $rphone[0]; ?>" name="rphone[]">              </td>
              <td width="9%" height="2">Phone</td>
              <td width="24%"><input size="25" id="rphone[]" class="text" value="<?php echo $rphone[1]; ?>"  name="rphone[]">              </td>
              <td width="9%" height="2">Phone</td>
              <td width="24%"><input size="25" id="rphone[]" class="text" value="<?php echo $rphone[2]; ?>" name="rphone[]">              </td>
            </tr>
            <tr>
              <td height="2" colspan="6">&nbsp;</td>
            </tr>
          </table>		  </td>
          </tr>
        <tr>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
        </tr>
        </table>
         <h4 class="form_sectiontitle">Special Skills</h4>
        <table width="792" cellspacing="0" cellpadding="0" border="0" align="center">
        <tr>
          <td height="20" colspan="4">
            <table width="100%" border="0" align="center">
                <tr>
                  <td valign="top" align="left">List some special Skills you have that can add to your acceptability for the desired Job Position</td>
                  <td valign="top"><textarea id="skills" class="textArea" rows="4" cols="50" name="skills"><?php echo $skills; ?></textarea></td>
                </tr>
              </table>
          </td>
        </tr>
        <tr>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top" height="20" align="center" class="mandetory" colspan="4">
              <input type="checkbox" value="Y" id="agree" name="agree">
              <label > Yes/No , I agree &amp; certify that the  statements made by me in answer to the foregoing questions are true, complete  and correct to the best of my knowledge and belief.&nbsp; I understand that any misrepresentation or  material omission made on a Personal History form or other document requested  by the Organization renders a staff member of the Panigram Resort to  termination or dismissal.</label></td>
          </tr>
        <tr>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
        </tr>
        <tr>
          <td height="30" align="center" colspan="4"><input type="submit" value="Submit" id="cmdSubmit" class="button"  name="cmdSubmit">
&nbsp;&nbsp;
          </td>
          </tr>
        <tr>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
        </tr>
        <tr>
          <td height="20" align="center" class="mandetory" colspan="4">* The Fields are mandatory, must have to fill the fields </td>
          </tr>
        <tr>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
        </tr>
      </table>
    </form>
    <?php endif; ?>
    <?php
}//end function simplejob_showjobs_frontend_form
?>
