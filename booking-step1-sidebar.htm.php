<?php

// Get theme options
global $sohohotel_data;


        // echo "<pre>";
        // print_r($_SESSION);
        // print_r($_POST);
        // echo "</pre>";

// $range = 0;
// Set max values
$sh_get_booking_max_rooms = sh_get_booking_max_rooms();
$sh_get_booking_max_guests = sh_get_booking_max_guests();
if ( !empty( $_SESSION['pageone'])){
	// echo "<pre>";
	// echo "HELLO";
	// echo "</pre>";
	$_SESSION['d_data'] = $_POST;
}
$_SESSION['pageone'] = '';
$d_data = $_SESSION['d_data'];
if ( !empty( $_SESSION['d_data']) && !empty($_SESSION['d_data']["check_in"]) ) {

$_SESSION['custombooking'] = '';
	$check_in_display = sh_display_formatted_date($d_data["check_in"]);
	$check_in_alt = $d_data["check_in"];
	$check_out_display = sh_display_formatted_date($d_data["check_out"]);
	$check_out_alt = $d_data["check_out"];
	$book_room_value = $d_data["book_room"];
	$book_room_weeks = $d_data["book_room_weeks"];
	$book_room_days = $d_data["book_room_days"];
        $lanai = $d_data['lanai_facing'];
        $ac = $d_data['ac'];
        $couch = $d_data['couchbed'];
        $bedrooms = $d_data['bedrooms'];
        $guests = $d_data['book_room_adults_1'];
		$range = $d_data['range'];

}
else {
	// Set date and room values
	if ( !empty( $_SESSION['sh_booking_data']) && !empty($_SESSION['sh_booking_data']["check_in"]) ) {
		$sh_booking_data = $_SESSION['sh_booking_data'];
		$check_in_display = sh_display_formatted_date($sh_booking_data["check_in"]);
		$check_in_alt = $sh_booking_data["check_in"];
		$check_out_display = sh_display_formatted_date($sh_booking_data["check_out"]);
		$check_out_alt = $sh_booking_data["check_out"];
		$book_room_value = $sh_booking_data["book_room"];
		$book_room_weeks = $sh_booking_data["book_room_weeks"];
		$book_room_days = $sh_booking_data["book_room_days"];
	        $lanai = $sh_booking_data['lanai_facing'];
	        $ac = $sh_booking_data['ac'];
	        $couch = $sh_booking_data['couchbed'];
	        $bedrooms = $sh_booking_data['bedrooms'];
	        $guests = $sh_booking_data['book_room_adults_1'];
			$range = $sh_booking_data['range'];

	} else {


		if ( !empty($_POST['check_in_display']) ) {

			$check_in_display = $_POST['check_in_display'];
			$check_in_alt = $_POST['check_in'];
			$check_out_display = $_POST['check_out_display'];
			$check_out_alt = $_POST['check_out'];
			$book_room_value = $_POST['book_room'];
			$book_room_weeks = $_POST["book_room_weeks"];
			$book_room_days = $_POST["book_room_days"];
	                $lanai = $_POST['lanai_facing'];
	                $ac = $_POST['ac'];
	                $couch = $_POST['couchbed'];
	                $bedrooms = $_POST['bedrooms'];
	                $guests = $_POST['book_room_adults_1'];
					$range = $_POST['range'];


		} else {

			$check_in_display = '';
			$check_in_alt = '';
			$check_out_display = '';
			$check_out_alt = '';
			$book_room_value = '';
			$lanai = '';
			$book_room_weeks = '';
			$book_room_days = '';
	                $ac = '';
	                $couch = '';
	                $bedrooms = '';
	                $guests = '';
		}

	}

}

/********/
if ( array_key_exists('range', $_POST )) {
   $range = $_POST['range'];
 }
 // $_SESSION['sh_booking_data']['range'] = $range;


function setRange() {
  if ( !empty( $_SESSION['sh_booking_data'])) {
	  $_SESSION['sh_booking_data']['range'] = $_POST['range'];
  }
  echo "this.form.submit();";
} ?>

<h4><?php esc_html_e('New Search', 'sohohotel_booking'); ?></h4>


<div class="title-block-3"></div>


<form name="bookroom" class="booking-form-data">
<!-- <button type="button" class="bookingbutton"><?php //esc_html_e('Search Availability', 'sohohotel_booking'); ?> <i class="fa fa-calendar"></i></button><br><br> -->
	<input type="radio" name="range" value="0" <?php if ($_SESSION['sh_booking_data']['range'] == 0 || $range == 0){echo "checked" ;} ?>>I know my exact dates<br>
	<input type="radio" name="range" value="1" <?php if ($_SESSION['sh_booking_data']['range'] == 1 || $range == 1){echo "checked" ;} ?>> I don't know my Exact Dates, but know approximately when I want to arrive and how long I want to stay<br><br>

	<label for="open_date_from"><?php esc_html_e('Check In (click on calendar)', 'sohohotel_booking'); ?></label>
	<div class="input-wrapper">
		<i class="fa fa-angle-down"></i>
		<input type="text" id="open_date_from" size="10" value="<?php echo $check_in_display; ?>" readonly/>
		<input type="hidden" id="check_in_alt" name="check_in" value="<?php echo $check_in_alt; ?>" />
	</div>

	<label for="open_date_to"><?php esc_html_e('Check Out (click on calendar)', 'sohohotel_booking'); ?></label>
	<div class="input-wrapper">
		<i class="fa fa-angle-down"></i>
		<input type="text" id="open_date_to" size="10" value="<?php echo $check_out_display; ?>" readonly/>
		<input type="hidden" id="check_out_alt" name="check_out" value="<?php echo $check_out_alt; ?>" />
	</div>


    <fieldset class="set-range-fieldset"><legend>Enter desired length of stay in weeks and/or days:</legend>
    <br>

    <div class="input-wrapper"  style="min-width: 20px;display: flex;flex-wrap: wrap;">
                                   <label for="book_room_weeks">Weeks</label>
                                    <div class="select-wrapper">
                                            <i class="fa fa-angle-down"></i>
                                           <select id="book_room_weeks" name="book_room_weeks">
             <?php for ($wk = 0; $wk < 5; $wk++) {
                  echo '<option value="' . $wk . '" ';
				  if ( $book_room_weeks == $wk ) { echo 'selected'; }
				  echo '>' . $wk . '</option>' ;
               } ?>
                                   </select>

                            </div>
                    </div>
    <div class="input-wrapper"  style="min-width: 20px;display: flex;flex-wrap: wrap;">
                                   <label for="book_room_days">Days</label>
                                    <div class="select-wrapper">
                                            <i class="fa fa-angle-down"></i>
                                           <select id="book_room_days" name="book_room_days">
             <?php for ($dd = 0; $dd < 31; $dd++) {
                  echo '<option value="' . $dd . '" ';
				  if ( $book_room_days == $dd ) { echo 'selected'; }
                  echo '>' . $dd . '</option>' ;
               } ?>
                                   </select>

                            </div>
                    </div>
    </fieldset>
                                        <label for="lanai_facing"><?php esc_html_e('Lanai Facing', 'sohohotel_booking'); ?></label>
                                        <div class="select-wrapper">
                                                <i class="fa fa-angle-down"></i>
                                        <select id="lanai_facing" name="lanai_facing">

                                                <?php foreach (array("Any","East","Southeast (corner)","South","Southwest (corner)") as $r) { ?>
                                                       <option value="<?php echo $r; ?>" <?php if ( $lanai == $r ) { echo 'selected'; } ?>><?php echo $r; ?></option>
                                                <?php } ?>
                                        </select>
                                        </div>


                                <label for="ac"><?php esc_html_e('A/C','sohohotel_booking'); ?></label>
                                <div class="select-wrapper">
                                        <i class="fa fa-angle-down"></i>
                                        <select id="ac" name="ac">
                                                <?php foreach (array("Any","Yes","No") as $r) { ?>
 <option value="<?php echo $r; ?>" <?php if ( $ac == $r ) { echo 'selected'; } ?>><?php echo $r; ?></option>
                                                <?php } ?>
                                        </select>
                                </div>

                                <label for="bedrooms"><?php esc_html_e('Bedrooms','sohohotel_booking'); ?></label>
                                <div class="select-wrapper">
                                        <i class="fa fa-angle-down"></i>
                                        <select id="bedrooms" name="bedrooms">
                                                <?php foreach (array("Any",1,2) as $r) { ?>
 <option value="<?php echo $r; ?>" <?php if ( $bedrooms == $r ) { echo 'selected'; } ?>><?php echo $r; ?></option>
                                                <?php } ?>
                                        </select>
                                </div>

                                <label for="couchbed"><?php esc_html_e('Couch Bed','sohohotel_booking'); ?></label>
                                <div class="select-wrapper">
                                        <i class="fa fa-angle-down"></i>
                                        <select id="couchbed" name="couchbed">                                                                                                     <?php foreach (array("Any","Yes","No") as $r) { ?>
 <option value="<?php echo $r; ?>" <?php if ( $couch == $r ) { echo 'selected'; } ?>><?php echo $r; ?></option>
                                                <?php } ?>
                                        </select>
                                </div>


                <?php foreach (range(1, 1) as $i) { ?>

                                                <label for="book_room_adults_<?php echo $i; ?>"><?php esc_html_e('Guests', 'sohohotel_booking'); ?></label>
                                                <div class="select-wrapper">
                                                        <i class="fa fa-angle-down"></i>
                                                        <select name="book_room_adults_<?php echo $i; ?>" id="book_room_adults_<?php echo $i; ?>">
                                                                <?php foreach (range(1, $sh_get_booking_max_guests) as $r) { ?>
                                                                        <option value="<?php echo $r; ?>" <?php if ( !empty( $_POST["edit_step_2"] ) ) {

                                                                                if ( $guests == $r ) { echo 'selected'; }

                                                                        } else {

                                                                                if ( $guests == $r ) { echo 'selected'; }

                                                                        } ?>><?php echo $r; ?></option>
                                                                <?php } ?>
                                                        </select>
                                                </div>


                <?php } ?>

	<input type="hidden" name="action" value="sh_booking_process_frontend_action_callback" />
	<?php echo wp_nonce_field('sh_booking_process_frontend', '_acf_nonce', true, false); ?>
	<button type="button" class="bookingbutton"><?php esc_html_e('Search Availability', 'sohohotel_booking'); ?> <i class="fa fa-calendar"></i></button>
</form>
<style>
.set-range-fieldset{
	display: none;
}
</style>
<script>
jQuery("input[name='range']").on("change",function(e){
// console.log("clicking");
	if(jQuery("input[name='range']:checked").val()==1){
		jQuery(".set-range-fieldset").show();
	}
	else{
		jQuery(".set-range-fieldset").hide();
	}
});
</script>
