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

$base_name_setting = plugin_basename('simplejob/setting.php');
$base_page_setting = 'admin.php?page='.$base_name_setting;


$simplejob_currency_codes = array(
    "Taka(BDT)" => "Bangladeshi Taka(৳)",
    "EUR" => "Euros (€)",
    "USD" => "U.S. Dollars ($)",
    "AUD" => "Australian Dollars (A $)",
    "CAD" => "Canadian Dollars (C $)",
    "GBP" => "Pounds Sterling (£)",
    "JPY" => "Yen (¥)",
    "NZD" => "New Zealand Dollar ($)",
    "CHF" => "Swiss Franc",
    "HKD" => "Hong Kong Dollar ($)",
    "SGD" => "Singapore Dollar ($)",
    "SEK" => "Swedish Krona",
    "DKK" => "Danish Krone",
    "PLN" => "Polish Zloty",
    "NOK" => "Norwegian Krone",
    "HUF" => "Hungarian Forint",
    "CZK" => "Czech Koruna",
    "ILS" => "Israeli Shekel",
    "MXN" => "Mexican Peso",
    "BRL" => "Brazilian Real (only for Brazilian users)",
    "MYR" => "Malaysian Ringgits (only for Malaysian users)",
    "PHP" => "Philippine Pesos",
    "TWD" => "Taiwan New Dollars",
    "THB" => "Thai Baht");

### If Form Is Submitted
if($_POST['Submit']) {		
	$simplejob_joblistpage  =  intval($_POST['simplejob_joblistpage']);
	$simplejob_jobapplypage =  intval($_POST['simplejob_jobapplypage']);
	$simplejob_pagelimit    =  intval($_POST['simplejob_pagelimit']);
    $simplejob_uploadpath    = trim($_POST['simplejob_uploadpath']);
    $simplejob_currency     =  trim($_POST['simplejob_currency']);
    $simplejob_adminmail    =  trim($_POST['simplejob_adminmail']);
    $simplejob_mailsubject  =  trim($_POST['simplejob_mailsubject']);
    $simplejob_mailbody     =  trim($_POST['simplejob_mailbody']);
    
    if($simplejob_mailbody == ''){
        $simplejob_mailbody = 'Dear $full_name,
        Thank you for applying for the following position.
        We will review your application and get back to you.

        Kindest regards
        $blogname';
    }
    
    if($simplejob_adminmail == ''){
        $simplejob_adminmail =  get_option('admin_email');
    }
    

    
    $update_simplejob_queries = array();
    $update_simplejob_text = array();

    $update_simplejob_queries[] = update_option('simplejob_joblistpage', $simplejob_joblistpage);
    $update_simplejob_queries[] = update_option('simplejob_jobapplypage', $simplejob_jobapplypage);
    $update_simplejob_queries[] = update_option('simplejob_pagelimit', $simplejob_pagelimit);
    $update_simplejob_queries[] = update_option('simplejob_uploadpath', $simplejob_uploadpath);
    $update_simplejob_queries[] = update_option('simplejob_currency', $simplejob_currency);
    $update_simplejob_queries[] = update_option('simplejob_adminmail', $simplejob_adminmail);
    $update_simplejob_queries[] = update_option('simplejob_mailsubject', $simplejob_mailsubject);	
    $update_simplejob_queries[] = update_option('simplejob_mailbody', $simplejob_mailbody);     


    $update_simplejob_text[] = __('Joblist page id', 'simplejob');
    $update_simplejob_text[] = __('Apply form page id', 'simplejob');
    $update_simplejob_text[] = __('Pagination limit', 'simplejob');
    $update_simplejob_text[] = __('Photo upload path', 'simplejob');
    $update_simplejob_text[] = __('Currency sign', 'simplejob');
    $update_simplejob_text[] = __('Mail from address', 'simplejob');
    $update_simplejob_text[] = __('Mail Subject', 'simplejob');
    $update_simplejob_text[] = __('Mail Dody', 'simplejob');

    $i=0;
    $text = '';
    foreach($update_simplejob_queries as $update_simplejob_query) {
      if($update_simplejob_query) {
       $text .= '<font color="green">'.$update_simplejob_text[$i].' '.__('Updated', 'simplejob').'</font><br />';
   }
   $i++;
}
if(empty($text)) {
  $text = '<font color="red">'.__('No simplejob Option Updated', 'simplejob').'</font>';
}
}//end post submit
?>
<div class="wrap">
    <div class="icon32" id="icon-options-general"><br></div>
    <h2><?php _e('Simple Job Setting', 'simplejob'); ?></h2>
    <?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>'; } ?>
    <form method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>">
        <p class="submit">
            <input type="submit" name="Submit" class="button" value="<?php _e('Save Changes', 'wp-downloadmanager'); ?>" />
        </p>
        <table class="form-table">
         <tr valign="top">
            <th><?php _e('Joblist page id', 'simplejob'); ?></th>
            <td><input type="text" name="simplejob_joblistpage" value="<?php echo stripslashes(get_option('simplejob_joblistpage')); ?>" size="50" dir="ltr" /></td>
        </tr>
        <tr valign="top">
            <th><?php _e('Apply form page id', 'simplejob'); ?></th>
            <td><input type="text" name="simplejob_jobapplypage" value="<?php echo stripslashes(get_option('simplejob_jobapplypage')); ?>" size="50" dir="ltr" /></td>
        </tr>
        <tr valign="top">
            <th><?php _e('Pagination limit', 'simplejob'); ?></th>
            <td><input type="text" name="simplejob_pagelimit" value="<?php echo stripslashes(get_option('simplejob_pagelimit')); ?>" size="50" dir="ltr" /></td>
        </tr>
        <tr valign="top">
            <th><?php _e('Photo upload path', 'simplejob'); ?></th>
            <td><input type="text" name="simplejob_uploadpath" value="<?php echo stripslashes(get_option('simplejob_uploadpath')); ?>" size="50" dir="ltr" /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Currency</th>
            <td>
                <?php
                echo "<select name='simplejob_currency'>";
                foreach($simplejob_currency_codes as $code => $currency)
                {
                    $selected = (get_option('simplejob_currency') == $code) ? "selected='selected'" : "";
                    echo "<option value='$code' $selected>$currency</option>";
                }
                echo "</select>";
                ?>
            </td>
        </tr>
        <?php
        echo "<tr valign='top'>";
        echo "<th scope='row'>Mail from address</th>";
        echo "<td>";
        echo "<input type='text' name='simplejob_adminmail' value='".get_option('simplejob_adminmail')."' size='75' />";
        echo "</td>";
        echo "</tr>";
        echo "<tr valign='top'>";
        echo "<th scope='row'>Subject of Mail</th>";
        echo "<td>";
        echo "<input type='text' name='simplejob_mailsubject' value='".get_option('simplejob_mailsubject')."' size='75' />";
        echo "</td>";
        echo "</tr>";

        echo "<tr valign='top'>";
        echo "<th scope='row'>Text of Mailbody<br />(variables beginning with '$' will be substituted)</th>";
        echo "<td>";
        echo "<textarea name='simplejob_mailbody' cols='50' rows='10'>";
        echo get_option('simplejob_mailbody');
        echo "</textarea>";
        echo "</td>";
        echo "</tr>";
        ?>
    </table>
    <p class="submit">
        <input type="submit" name="Submit" class="button" value="<?php _e('Save Changes', 'wp-downloadmanager'); ?>" />
    </p>
    <p>Simple Job 1.0 plugin for wordpress.  Developed by <a href="http://codeboxr.com" target="_blank">Codeboxr</a>.</p>   
</form>
</div>
