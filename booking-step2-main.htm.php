<?php

session_start();

?>
<div>
    <form name="bookroom" class="booking-form-data">

        <?php global $post;
        global $wp_query;
        global $fee;
        // echo "<pre>";
        $sh_booking_data = $_SESSION['sh_booking_data'];
        $range = $_SESSION['sh_booking_data']['range'];
        if (!empty($_SESSION['custombooking']["lanai_facing"]) or empty($sh_booking_data["lanai_facing"])) {
            $sh_booking_data = $_SESSION['custombooking'];
            $_SESSION['sh_booking_data'] = $sh_booking_data;
            //echo "exchanged";
        }
        $initial_lastdate = strtotime($_SESSION['sh_booking_data']['check_out']);
        // print_r($_SESSION);
        //echo $booked_room_ids;
        // echo "</pre>";


        $occupancy_hide = false;

        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        // Get IDs of fully booked rooms (from database and temp session)
        $fully_booked_rooms_db = sh_get_all_booked_room_ids($_SESSION['sh_booking_data']['check_in'],$_SESSION['sh_booking_data']['check_out'],true,false);

        // Get IDs of all bookings for remaining room check
        $booked_ids_array = sh_get_booked_room_ids_db($_SESSION['sh_booking_data']['check_in'],$_SESSION['sh_booking_data']['check_out'],false);


        $range = $_SESSION['sh_booking_data']['range'];
        if ($range == 1) {
            $fully_booked_rooms_db = '';
            $booked_ids_array = '';
            $modified = 0;

            $totdays = ($_SESSION['sh_booking_data']['book_room_weeks']*7)+$_SESSION['sh_booking_data']['book_room_days'];

            if ( $totdays > 35 ) {
                $totdays = 35;
                $_SESSION['sh_booking_data']['book_room_weeks'] = $totdays / 7;
                $_SESSION['sh_booking_data']['book_room_days'] = $totdays % 7;


                $modified = 1;
            }
            $constrainto = strtotime($_SESSION['sh_booking_data']['check_in'] . " +35 days");
            $lastdate = strtotime($_SESSION['sh_booking_data']['check_out']);
            if ( $lastdate > $constrainto ) {
                $lastdate = $constrainto;
                $_SESSION['sh_booking_data']['check_out'] = date('Y-m-d',$lastdate);
                $modified = 1;
            }
            $firstdate = strtotime($_SESSION['sh_booking_data']['check_in']);
            $firstplusspan = strtotime($_SESSION['sh_booking_data']['check_in'] . " + " . $totdays . " days");
            if ($firstplusspan > $lastdate) {
                $lastdate = $firstplusspan;
                $_SESSION['sh_booking_data']['check_out'] = date('Y-m-d',$lastdate);
                $modified = 1;
            }

            //error_log(date('Y-m-d',$firstdate));
            //error_log($firstdate);
            // error_log($_SESSION['sh_booking_data']['check_out']);
            //error_log($totdays);
            for ($i=$firstdate; $i <= strtotime($_SESSION['sh_booking_data']['check_out'] . " - " . $totdays . " days"); $i = strtotime(date('Y-m-d',$i) . " + 1 day")) {

                //error_log(date('Y-m-d',$i) . " to " . date('Y-m-d',strtotime(date('Y-m-d',$i) . " + " . $totdays . " days")));
                $fully_booked_rooms_db_temp = sh_get_all_booked_room_ids(date('Y-m-d',$i),date('Y-m-d',strtotime(date('Y-m-d',$i) . " + " . $totdays . " days")),true,false);


                if ($fully_booked_rooms_db == '' ) {
                    $fully_booked_rooms_db = $fully_booked_rooms_db_temp;
                } else {
                    $fully_booked_rooms_db = array_intersect($fully_booked_rooms_db,$fully_booked_rooms_db_temp);
                }

                // Get IDs of all bookings for remaining room check
                $booked_ids_array_temp = sh_get_booked_room_ids_db(date('Y-m-d',$i),date('Y-m-d',strtotime(date('Y-m-d',$i) . " + " . $totdays . " days")),false);

                if ($booked_ids_array == '' ){
                    $booked_ids_array = $booked_ids_array_temp;
                } else {
                    $booked_ids_array =  array_intersect($booked_ids_array,$booked_ids_array_temp);
                }
            }
            //error_log('fully_booked_rooms_db');
            //error_log(json_encode($fully_booked_rooms_db_temp, JSON_PRETTY_PRINT));
            //error_log('booked_ids_array_temp');
            //error_log(json_encode($booked_ids_array_temp, JSON_PRETTY_PRINT));

        } else {

            // Get IDs of fully booked rooms (from database and temp session)
            $fully_booked_rooms_db = sh_get_all_booked_room_ids($_SESSION['sh_booking_data']['check_in'],$_SESSION['sh_booking_data']['check_out'],true,false);

            // Get IDs of all bookings for remaining room check
            $booked_ids_array = sh_get_booked_room_ids_db($_SESSION['sh_booking_data']['check_in'],$_SESSION['sh_booking_data']['check_out'],false);

        }

        //error_log('Range: ' . $range);
        //error_log(json_encode($fully_booked_rooms_db, JSON_PRETTY_PRINT));
        //error_log(json_encode($booked_ids_array, JSON_PRETTY_PRINT));



        $booked_room_ids = array();
        $cal_view_rooms_list = array();





    $args = array(
        'post_type' => 'accommodation',
        'posts_per_page' => '9999',
        'paged' => $paged
        // 'post__not_in' => $fully_booked_rooms_db
    );

    $args1 = array(
        'post_type' => 'accommodation',
        'posts_per_page' => '9999',
        'paged' => $paged,
        'post__in'=> $booked_ids_array
    );

    $args2 = array(
        'post_type' => 'accommodation',
        'posts_per_page' => '9999',
        'paged' => $paged,
        'post__not_in' => $fully_booked_rooms_db
    );

        $arr = array();

        $arr22 = array();
        $participating_condos = array();

        $all_condos_only_id = array();
        $participate_condo_only_id = array();
        $participate_condo_only_id_without_any_booking = array();
        $not_participate_condo_only_id = array();

        $total_guests = $_SESSION['sh_booking_data']['book_room_adults_' . sh_get_current_room()] + $_SESSION['sh_booking_data']['room_' . sh_get_current_room()]["children"];

if($range == 0) {
        $wp_query = new WP_Query( $args );
    }
    else {
        $wp_query = new WP_Query( $args2 ); // NOT GETTING FULL BOOKED  ROOMS
    }

        $wp_query1 = new WP_Query( $args1 ); /// Booked ID query

        $wp_query2 = new WP_Query( $args );  // GETTING ALL Rooms for ranges 1

        if ($wp_query1->have_posts()) :
            while($wp_query1->have_posts()) :
                $wp_query1->the_post();

                $bookedroomno = get_the_title();

                array_push($booked_room_ids,$bookedroomno);

            endwhile;

        endif;
$arr_acc1 = array();

if($range == 1){

            if ($wp_query2->have_posts()) :
                while($wp_query2->have_posts()) :
                    $wp_query2->the_post();

                    $permalink = get_permalink();
                    $roomno = get_the_title();
                    $cal_rid = get_the_ID();

                    // Get accommodation data
                    $accommodation_meta = json_decode( get_post_meta($post->ID,'_accommodation_meta',TRUE), true );
                    $accommodation_meta_room_excerpt = get_post_meta($post->ID,'_accommodation_room_excerpt_meta',TRUE);

                    // Remaining rooms
                    $remaining_rooms = sh_get_remaining_rooms($_SESSION['sh_booking_data']['check_in'],$_SESSION['sh_booking_data']['check_out'],$post->ID,$booked_ids_array);


                    $pro_bedroom = $accommodation_meta['bedrooms'];

                    $pro_lanai_Facing = $accommodation_meta['Lanai_Facing'];

                    $pro_ac = $accommodation_meta['ac'];

                    $pro_Couch_Bed = $accommodation_meta['Couch_Bed'];


                        if($range == 0 ){$pro_icalfeed = $accommodation_meta['ical_feed'];
                        $participate = $accommodation_meta['participate_condo_in_the_website'];
                    }

                    $titleww = get_the_title($post->ID );

                    array_push($arr_acc1,array('link' => $permalink, 'roomno' => $roomno, 'bedroom_count' => $pro_bedroom, 'facing' => $pro_lanai_Facing, 'ac' => $pro_ac, 'couch_bed' => $pro_Couch_Bed, 'guest' => $accommodation_meta["maximum_occupancy"], 'desc' => $accommodation_meta_room_excerpt));
                    // array_push($cal_view_rooms_list,array('link' => $permalink, 'roomno' => $roomno,'room_type' => $cal_rid, 'bedroom_count' => $pro_bedroom, 'facing' => $pro_lanai_Facing, 'ac' => $pro_ac, 'couch_bed' => $pro_Couch_Bed, 'guest' => $accommodation_meta["maximum_occupancy"], 'desc' => $accommodation_meta_room_excerpt));


                endwhile;

            endif;
}
        // echo "<pre>";

        // print_r($booked_ids_array);
        // echo "************";
        // print_r($booked_room_ids);

        // echo "</pre>";


        if ($wp_query->have_posts()) :


            $arr_east = array();
            $arr_south = array();

            $checked_bedroom = $_SESSION['sh_booking_data']['bedrooms'];
            $checked_Facing = $_SESSION['sh_booking_data']['lanai_facing'];
            if ( $checked_Facing == "Southwest (corner)" ) {
                $checked_Facing = "Southwest_corner";
            }
            if ( $checked_Facing == "Southeast (corner)" ) {
                $checked_Facing = "Southeast_corner";
            }
            $checked_AC = $_SESSION['sh_booking_data']['ac'];
            $checked_couchbed = $_SESSION['sh_booking_data']['couchbed'];

            while($wp_query->have_posts()) :


                $wp_query->the_post();
                $permalink = get_permalink();
                $roomno = get_the_title();
                $cal_rid = get_the_ID();

                // Get accommodation data
                $accommodation_meta = json_decode( get_post_meta($post->ID,'_accommodation_meta',TRUE), true );
                $accommodation_meta_room_excerpt = get_post_meta($post->ID,'_accommodation_room_excerpt_meta',TRUE);

                // Remaining rooms
                $remaining_rooms = sh_get_remaining_rooms($_SESSION['sh_booking_data']['check_in'],$_SESSION['sh_booking_data']['check_out'],$post->ID,$booked_ids_array);


                $pro_bedroom = $accommodation_meta['bedrooms'];

                $pro_lanai_Facing = $accommodation_meta['Lanai_Facing'];

                $pro_ac = $accommodation_meta['ac'];

                $pro_Couch_Bed = $accommodation_meta['Couch_Bed'];

                $pro_icalfeed = $accommodation_meta['ical_feed'];
                $participate = $accommodation_meta['participate_condo_in_the_website'];

                $titleww = get_the_title($post->ID );

                if($range == 0 ){
                    array_push($arr_acc1,array('link' => $permalink, 'roomno' => $roomno, 'bedroom_count' => $pro_bedroom, 'facing' => $pro_lanai_Facing, 'ac' => $pro_ac, 'couch_bed' => $pro_Couch_Bed, 'guest' => $accommodation_meta["maximum_occupancy"], 'desc' => $accommodation_meta_room_excerpt));
                }
                array_push($cal_view_rooms_list,array('link' => $permalink, 'roomno' => $roomno,'room_type' => $cal_rid, 'bedroom_count' => $pro_bedroom, 'facing' => $pro_lanai_Facing, 'ac' => $pro_ac, 'couch_bed' => $pro_Couch_Bed, 'guest' => $accommodation_meta["maximum_occupancy"], 'desc' => $accommodation_meta_room_excerpt));


                if( get_field('participate_condo_in_the_website') ){
                    array_push($participating_condos,array('link' => $permalink, 'roomno' => $roomno,'room_type' => $cal_rid, 'bedroom_count' => $pro_bedroom, 'facing' => $pro_lanai_Facing, 'ac' => $pro_ac, 'couch_bed' => $pro_Couch_Bed, 'guest' => $accommodation_meta["maximum_occupancy"], 'desc' => $accommodation_meta_room_excerpt));

                }
                if( get_field('participate_condo_in_the_website') ){
                    array_push($participate_condo_only_id, $roomno);
                }

                array_push($all_condos_only_id, $roomno);
                $not_participate_condo_only_id = array_diff($all_condos_only_id, $participate_condo_only_id);


                if (( $pro_lanai_Facing == "East" ) or ( $pro_lanai_Facing == "Southeast_corner" ) or ($pro_lanai_Facing == "Southwest_corner")) {

                    array_push($arr_east,array('link' => $permalink, 'roomno' => $roomno, 'bedroom_count' => $pro_bedroom, 'facing' => $pro_lanai_Facing, 'ac' => $pro_ac, 'couch_bed' => $pro_Couch_Bed, 'guest' => $accommodation_meta["maximum_occupancy"], 'desc' => $accommodation_meta_room_excerpt));
                }

                if (( $pro_lanai_Facing == "South" )) {

                    array_push($arr_south,array('link' => $permalink, 'roomno' => $roomno, 'bedroom_count' => $pro_bedroom, 'facing' => $pro_lanai_Facing, 'ac' => $pro_ac, 'couch_bed' => $pro_Couch_Bed, 'guest' => $accommodation_meta["maximum_occupancy"], 'desc' => $accommodation_meta_room_excerpt));
                }


                if ( ($checked_bedroom == $pro_bedroom) or ( $checked_bedroom == "Any")) {

                    $bedroom_conf = true;
                }
                else {
                    $bedroom_conf = false;

                }


                /*
                echo "<pre>$pro_lanai_Facing</pre>";
                echo "<pre>$checked_Facing</pre>";
                */
                if ( ($checked_Facing == $pro_lanai_Facing) or ( $checked_Facing == "Any")) {

                    $Facing_conf = true;
                }
                else {
                    // if ( $checked_Facing == "South" && ($pro_lanai_Facing == "Southwest_corner" or $pro_lanai_Facing == "Southeast_corner")) {
                    if ( $checked_Facing == "South") {
                        $Facing_conf = true;
                    }
                    else {
                        if ( $checked_Facing == "East" && $pro_lanai_Facing == "Southeast_corner" or $pro_lanai_Facing == "Southwest_corner") {
                            $Facing_conf = true;
                        }
                        else {

                            $Facing_conf = false;

                        }}}


                        if ( stripos($checked_AC,$pro_ac) === 0 or ( $checked_AC == "Any")) {

                            $AC_conf = true;
                        }
                        else {
                            $AC_conf = false;

                        }
                        /*
                        echo "<pre>$pro_Couch_Bed</pre>";
                        echo "<pre>$checked_couchbed</pre>";
                        */
                        if ( stripos($checked_couchbed,$pro_Couch_Bed) === 0 or ( $checked_couchbed == "Any")) {

                            $couchbed_conf = true;
                        }
                        else {
                            $couchbed_conf = false;

                        }





                        // Check max occupancy
                        if (( get_field('participate_condo_in_the_website')) && ( $total_guests <= $accommodation_meta['maximum_occupancy'] ) && ($bedroom_conf ) && ($Facing_conf ) && ($AC_conf ) && ($couchbed_conf ) && !empty($pro_icalfeed)) {



                            $final_array = array ($titleww, $pro_bedroom, $pro_lanai_Facing);

                            array_push($arr22,$final_array);


                            array_push($arr,$titleww);


                        } ?>

                        <!-- BEGIN .booking-room-wrapper -->


                    <?php endwhile; ?>

                <?php else : ?>
                    <p><?php esc_html_e('No rooms are available','sohohotel_booking'); ?></p>
                <?php endif;

                wp_reset_query(); ?>

<?php

?>


                <div class="clearboth"></div>

                <input type="hidden" name="action" value="sh_booking_process_frontend_action_callback" />
                <?php echo wp_nonce_field('sh_booking_process_frontend', '_acf_nonce', true, false); ?>

                <input type="hidden" name="room_<?php echo sh_get_current_room(); ?>_selection" class="selected-room" value="" />
                <input type="hidden" name="current_room" value="<?php echo sh_get_current_room(); ?>" />
                <input type="hidden" name="edit_room_data" class="edit-room-field" value="" />
                <input type="hidden" name="edit_step_2" class="edit-step-2" value="" />

            </form>
            <?php



            $oneeast = array(); $twoeast = array(); $onesouth = array(); $twosouth = array();


            $b1 = array();
            $b2 = array();
            $b2 = array();
            $b3 = array();
            $b4 = array();
            $b5 = array();
            $b6 = array();
            $b7 = array();
            $b8 = array();
            $b9 = array();
            $b10 = array();
            $b11 = array();
            $b12 = array();
            $b13 = array();
            $b14 = array();
            $b15 = array();
            $b16 = array();
            $b17 = array();
            $b18 = array();
            $b19 = array();
            $bsoutheast= array();
            $bsouthwest = array();

            //usort
            function cmp($a, $b)
            {
                if ($a[0] == $b[0]) {
                    return 0;
                }
                return ($a[0] > $b[0]) ? -1 : 1;
            }

            function cmp0($a, $b)
            {
                if ($a[roomno] == $b[roomno]) {
                    return 0;
                }
                return ($a[roomno] < $b[roomno]) ? -1 : 1;
            }

            function mcompare($a, $b)
            {
                if ($a[roomno] == $b[roomno]) {
                    return $a[check_in] < $b[check_in] ? -1 : 1;
                } else {
                    return 0;
                }
                return $a[roomno] < $b[roomno] ? -1 : 1;

            }
            usort($arr_acc1,"cmp0");


            foreach ($arr_acc1 as $row) {
                $id = $row[roomno];
                $bedroom_count = $row[bedroom_count];
                $facing = $row[facing];
                $ac = $row[ac];
                $couch_bed = $row[couch_bed];
                $guest = $row[guest];
                $desc = $row[desc];


                /* rooms block wise classification */
                if($id <= 406){

                    if(($id % 100) == 01) {
                        array_push($b1, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 02) {
                        array_push($b2, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 03) {
                        array_push($b3, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 4) {
                        array_push($b4, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 05) {
                        array_push($b5, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 06) {
                        array_push($b6, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }

                    if(($id % 100) == 07) {
                        array_push($b7, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 8) {
                        // echo $id;
                        array_push($b8, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 9) {
                        // echo $id;
                        array_push($b9, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 10) {
                        array_push($b10, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 11) {
                        array_push($b11, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 12) {
                        array_push($b12, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 13) {
                        array_push($b13, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 14) {
                        array_push($b14, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 15) {
                        array_push($b15, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 16) {
                        array_push($b16, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 17) {
                        array_push($b17, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 18) {
                        array_push($b18, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 19) {
                        array_push($b19, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                }
                if($id == 407){
                    array_push($bsouthwest, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                }
                if($id == 408){
                    array_push($bsoutheast, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                }
                if($id > 408){
                    if(($id % 100) == 9) {
                        array_push($b10, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 10) {
                        array_push($b11, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 11) {
                        array_push($b12, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 12) {
                        array_push($b13, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 13) {
                        array_push($b14, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 14) {
                        array_push($b15, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 15) {
                        array_push($b16, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 16) {
                        array_push($b17, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 17) {
                        array_push($b18, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                    if(($id % 100) == 18) {
                        array_push($b19, array($id,$bedroom_count,$facing,$ac,$couch_bed,$guest,$desc));
                    }
                }


            }



            // Block wise rooms sorting
            usort($b1,"cmp");
            usort($b2,"cmp");
            usort($b3,"cmp");
            usort($b4,"cmp");
            usort($b5,"cmp");
            usort($b6,"cmp");
            usort($b7,"cmp");
            usort($b8,"cmp");
            usort($b9,"cmp");
            usort($b10,"cmp");
            usort($b11,"cmp");
            usort($b12,"cmp");
            usort($b13,"cmp");
            usort($b14,"cmp");
            usort($b15,"cmp");
            usort($b16,"cmp");
            usort($b17,"cmp");
            usort($b18,"cmp");
            usort($b19,"cmp");

            $mobile_eastface = FALSE;
            $mobile_southface= FALSE;
            foreach ($arr22 as $value){

                if (in_array("East", $value) || in_array("Southwest_corner", $value) || in_array("Southeast_corner", $value)){
                    $mobile_eastface = TRUE;
                }
                if (in_array("South", $value)){
                    $mobile_southface = TRUE;
                }
            }

            ?>
<?php
if($range == 0){
$no_booking_id = array_diff($participate_condo_only_id,$booked_room_ids);

if(empty($no_booking_id)) {
?>
     <script>
             jQuery(document).ready(function($) {
                 alert("No rooms available for this selected date. Please modify your search criteria");
             });
    </script>
<?php


} ?>
    <div class="loadedblocks-xs">
        <?php if ($mobile_southface){ ?>
            <div class="south-block clearfix">

                <div class="block block19 south">

                    <?php

                    foreach ($b19 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>
                <div class="block block18 south">

                    <?php

                    foreach ($b18 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>
                <div class="block block17 south">

                    <?php

                    foreach ($b17 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>

                <div class="block block16 south">

                    <?php

                    foreach ($b16 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>

                <div class="block block15 south">

                    <?php

                    foreach ($b15 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>

                <div class="block block14 south">

                    <?php

                    foreach ($b14 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>

                <div class="block block13 south">

                    <?php

                    foreach ($b13 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>

                <div class="block block12 south">

                    <?php

                    foreach ($b12 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>

                <div class="block block11 south">

                    <?php

                    foreach ($b11 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>
                <div class="block block10 south">

                    <?php

                    foreach ($b10 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>
                <div class="southwing">EAST WING</div>
                <div class="more-info">CLICK ON THE UNIT NUMBER FOR MORE INFORMATION AND BOOKING</div>
            </div>
            <?php
        }
        if ($mobile_eastface){ ?>
            <div class="responsive-eastface clearfix">
                <div class="east-block">
                    <div class="block block block01 east">

                        <?php


                        foreach ($b1 as $value){
                            if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            elseif (in_array($value[0],$no_booking_id)) {
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            else {
                                echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                            }
                        }
                        ?>
                    </div>

                    <div class="block block02 east">

                        <?php

                        foreach ($b2 as $value){
                            if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            elseif (in_array($value[0],$no_booking_id)) {
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            else {
                                echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                            }
                        }
                        ?>
                    </div>
                    <div class="block block03 east">

                        <?php

                        foreach ($b3 as $value){
                            if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            elseif (in_array($value[0],$no_booking_id)) {
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            else {
                                echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                            }
                        }
                        ?>
                    </div>
                    <div class="block block04 east">

                        <?php

                        foreach ($b4 as $value){
                            if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            elseif (in_array($value[0],$no_booking_id)) {
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            else {
                                echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                            }
                        }
                        ?>
                    </div>
                    <div class="block block05 east">

                        <?php

                        foreach ($b5 as $value){
                            if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            elseif (in_array($value[0],$no_booking_id)) {
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            else {
                                echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                            }
                        }
                        ?>
                    </div>
                    <div class="block block06 east">

                        <?php

                        foreach ($b6 as $value){
                            if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            elseif (in_array($value[0],$no_booking_id)) {
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            else {
                                echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                            }
                        }
                        ?>
                    </div>
                    <div class="block block07 east">

                        <?php

                        foreach ($b7 as $value){
                            if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            elseif (in_array($value[0],$no_booking_id)) {
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            else {
                                echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                            }
                        }

                        ?>
                    </div>
                    <div class="block block08 east">

                        <?php

                        foreach ($b8 as $value){

                            if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            elseif (in_array($value[0],$no_booking_id)) {
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            else {
                                echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                            }
                        }
                        ?>
                    </div>
                    <div class="block block09 east">

                        <?php

                        foreach ($b9 as $value){
                            if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            elseif (in_array($value[0],$no_booking_id)) {
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            else {
                                echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                            }
                        }
                        ?>
                    </div>
                    <div class="eastwing">SOUTH WING</div>
                    <div class="crossline">

                    </div>
                    <div class="crossline2">

                    </div>
                    <div class="more-info">CLICK ON THE UNIT NUMBER FOR MORE INFORMATION AND BOOKING</div>
                </div>
                <div class="corner-block ">

                    <div class="block block20 southeast">

                        <?php
                        // usort($bsoutheast,"cmp");

                        foreach ($bsoutheast as $value){
                            if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            elseif (in_array($value[0],$no_booking_id)) {
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            else {
                                echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                            }
                        }
                        ?>
                    </div>

                    <div class="block block21 southwest">

                        <?php
                        // usort($bsouthwest,"cmp");

                        foreach ($bsouthwest as $value){
                            if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            elseif (in_array($value[0],$no_booking_id)) {
                                echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                            }
                            else {
                                echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

        <?php } ?>
    </div>
    <div class="loadedblocks">
        <div class="block-floating-frame">
            <div class="east-block">
                <div class="block block block01 east">

                    <?php


                    foreach ($b1 as $value){

                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>

                <div class="block block02 east">

                    <?php

                    foreach ($b2 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>
                <div class="block block03 east">

                    <?php

                    foreach ($b3 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>
                <div class="block block04 east">

                    <?php

                    foreach ($b4 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>
                <div class="block block05 east">

                    <?php

                    foreach ($b5 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>
                <div class="block block06 east">

                    <?php

                    foreach ($b6 as $value){

                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>
                <div class="block block07 east">

                    <?php

                    foreach ($b7 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }

                    ?>
                </div>
                <div class="block block08 east">

                    <?php

                    foreach ($b8 as $value){

                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>
                <div class="block block09 east">

                    <?php

                    foreach ($b9 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="south-block">

                <div class="block block19 south">

                    <?php

                    foreach ($b19 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>
                <div class="block block18 south">

                    <?php

                    foreach ($b18 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>
                <div class="block block17 south">

                    <?php

                    foreach ($b17 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>

                <div class="block block16 south">

                    <?php

                    foreach ($b16 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>

                <div class="block block15 south">

                    <?php

                    foreach ($b15 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>

                <div class="block block14 south">

                    <?php

                    foreach ($b14 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>

                <div class="block block13 south">

                    <?php

                    foreach ($b13 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>

                <div class="block block12 south">

                    <?php

                    foreach ($b12 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>

                <div class="block block11 south">

                    <?php

                    foreach ($b11 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>
                <div class="block block10 south">

                    <?php

                    foreach ($b10 as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>

            </div>
            <div class="corner-block ">

                <div class="block block20 southeast">

                    <?php
                    // usort($bsoutheast,"cmp");

                    foreach ($bsoutheast as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>

                <div class="block block21 southwest">

                    <?php
                    // usort($bsouthwest,"cmp");

                    foreach ($bsouthwest as $value){
                        if (!in_array($value[0],$booked_room_ids) and in_array($value[0], $arr)){
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        elseif (in_array($value[0],$no_booking_id)) {
                            echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                        }
                        else {
                            echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>


            <?php
}
?>
            <?php

            // echo "<pre>";
            // // print_r($arr_east);
            // print_r($booked_room_ids);
            // print_r($arr);
            // print_r($no_booking_id);
            // echo "eeee";
            // // echo $b6[0][0];
            // foreach ($b2 as $value){
            //
            // if(!in_array($value[0], $booked_room_ids)){
            //     echo "true ";
            //     echo $value[0];
            // }
            // if(in_array($value[0], $arr)){
            //     echo "false";
            //     echo $value[0];
            //
            // }
            //
            // echo "<br>";
            // }
            // echo "</pre>";
            //


            if ($range == 1) {
                $table_start_date = strtotime($_SESSION['sh_booking_data']['check_in']);
                // $table_end_date = strtotime($_SESSION['sh_booking_data']['check_out']);
                $table_end_date = $initial_lastdate;

                $last_constrainto = strtotime($_SESSION['sh_booking_data']['check_in'] . " +35 days");
                if ( $table_end_date > $last_constrainto ) {
                    $table_end_date = strtotime($_SESSION['sh_booking_data']['check_in'] . " +35 days");
                }

                usort($cal_view_rooms_list,"cmp0");
                // for ($currentDate = $table_start_date; $currentDate <= $table_end_date;
                //                                 $currentDate += (86400)) {
                //
                // $Store = date('M d', $currentDate);
                // $array[] = $Store;
                // }
                // print_r($array);
?>

                    <?php
                    $mfirst = date('Y-m-d', $table_start_date);
                    $mlast = date('Y-m-d', $table_end_date);
                    $current_month_start_date = date('Y-m-d');
                    $prior_room_availability_array = array();
                    $room_availability_array = array();
                    $booked_ids = array();
                    $args_booking = array('post_type' => 'booking','posts_per_page' => '99999','orderby' => array(
                           'room_type'      => 'ASC',
                           'check_in' => 'ASC'
                        )
                    );
                    $wp_query_booking = new WP_Query( $args_booking );
                    $found_posts_booking = $wp_query_booking->found_posts;

                    if ($wp_query_booking->have_posts()) :
                        while($wp_query_booking->have_posts()) :

                            $wp_query_booking->the_post();
                            $booking_meta = get_post_meta( get_the_ID(), '_booking_meta', true );
                            $cal_booked_ids[] = get_the_ID();


                            $room_filter_booking_data_decoded = json_decode($booking_meta["save_rooms"], true);
                            // echo "<pre>";
                            // print_r($booking_meta);
                            // echo "</pre>";

                                foreach ($participating_condos as $row)  {

                                    foreach($room_filter_booking_data_decoded as $key => $val) {

                                        if($val[check_out] >= $mfirst){
                                            if($val[check_in] <= $mlast){

                                            if ( $row['room_type'] == $val['room_type'] ) {
                                                // echo "<pre>";
                                                // echo $row['roomno'];
                                                // echo "<br>";
                                                // // echo $row['room_type'];
                                                // // echo "<br>";
                                                // echo $val['check_in'];
                                                // echo "<br>";
                                                // echo $val['check_out'];
                                                // // echo "<br>";
                                                // // echo $booking_meta['booking_status'];
                                                // echo "</pre>";
                                                array_push($room_availability_array,array('roomno' => $row['roomno'],'room_type' => $row['room_type'],"check_in" => $val['check_in'],"check_out" => $val['check_out'],"booking_status" => $booking_meta['booking_status']));
                                                // array_push($prior_room_availability_array,array('roomno' => $row['roomno'],'room_type' => $row['room_type'],"check_in" => $val['check_in'],"check_out" => $val['check_out'],"booking_status" => $booking_meta['booking_status']));

                                            }
                                        }
                                        }
                                    }
                                }
                            endwhile;
                        endif;


                        //SORTING ASC BY ROOM NO. AND CHECK IN DATE
                        $tempArr = array();

                            foreach($room_availability_array as $key=>$val) {
                                // echo $val['roomno'];
                                $tempArr['roomno'][$key] = $val['roomno'];
                                $tempArr['check_in'][$key] = $val['check_in'];
                             }
                            // sort by number1 asc and then number2 asc
                            array_multisort($tempArr['roomno'], SORT_ASC, $tempArr['check_in'], SORT_ASC,$room_availability_array);
                            // print_r($tempArr);
                            // print_r($room_availability_array);

                        $is_required_room = false;
                        $required_rooms_to_display = array();

                        $length = count($room_availability_array);

                        if($length == 1){
                            $start_date = strtotime($room_availability_array[0][check_in]);
                            $start_date2 = strtotime($room_availability_array[0][check_out]);
                            if($start_date>$table_start_date){
                                $dates_available_before_checkin = ($start_date - $table_start_date)/60/60/24;
                            }
                            else {
                                $dates_available_before_checkin = ($table_start_date - $start_date)/60/60/24;
                            }
                            if($start_date2 > $table_end_date){
                                $dates_available_after_checkout = ($start_date2 - $table_end_date)/60/60/24;
                            }
                            else {
                                $dates_available_after_checkout = ($table_end_date - $start_date2)/60/60/24;
                            }

                            // echo "<pre>";
                            // echo $dates_available_before_checkin;
                            // echo $dates_available_after_checkout;
                            // echo "</pre>";

                            if($dates_available_before_checkin > $dates_available_after_checkout){
                                $room_availability_array[0]['dates_available_after_checkout'] = $dates_available_before_checkin;
                                if($dates_available_after_checkout >= $totdays){
                                    if(!in_array($room_availability_array[0][roomno], $required_rooms_to_display, true)){
                                        array_push($required_rooms_to_display, $room_availability_array[0][roomno]);
                                   }
                                }
                            }
                            else{
                                $room_availability_array[0]['dates_available_after_checkout'] = $dates_available_after_checkout;
                                if($dates_available_after_checkout >= $totdays){
                                    if(!in_array($room_availability_array[0][roomno], $required_rooms_to_display, true)){
                                        array_push($required_rooms_to_display, $room_availability_array[0][roomno]);
                                   }
                                }
                            }

                        }
                        else if($length == 2){
                            if($room_availability_array[0][roomno] != $room_availability_array[1][roomno]){
                                $start_date = strtotime($room_availability_array[0][check_in]);
                                $start_date2 = strtotime($room_availability_array[0][check_out]);
                                // $dates_available_before_checkin = ($start_date - $table_start_date)/60/60/24;
                                // $dates_available_after_checkout = ($start_date2 - $table_end_date)/60/60/24;

                                if($start_date>$table_start_date){
                                    $dates_available_before_checkin = ($start_date - $table_start_date)/60/60/24;
                                }
                                else {
                                    $dates_available_before_checkin = ($table_start_date - $start_date)/60/60/24;
                                }
                                if($start_date2 > $table_end_date){
                                    $dates_available_after_checkout = ($start_date2 - $table_end_date)/60/60/24;
                                }
                                else {
                                    $dates_available_after_checkout = ($table_end_date - $start_date2)/60/60/24;
                                }
                                if($dates_available_before_checkin > $dates_available_after_checkout){
                                    $room_availability_array[0]['dates_available_after_checkout'] = $dates_available_before_checkin;
                                    if($dates_available_after_checkout >= $totdays){
                                        if(!in_array($room_availability_array[0][roomno], $required_rooms_to_display, true)){
                                            array_push($required_rooms_to_display, $room_availability_array[0][roomno]);
                                       }
                                    }
                                }
                                else{
                                    $room_availability_array[0]['dates_available_after_checkout'] = $dates_available_after_checkout;
                                    if($dates_available_after_checkout >= $totdays){
                                        if(!in_array($room_availability_array[0][roomno], $required_rooms_to_display, true)){
                                            array_push($required_rooms_to_display, $room_availability_array[0][roomno]);
                                       }
                                    }
                                }
                            }
                        }
                        else {
                            for($i = 0; $i < $length - 1; ++$i) {
                                    if($room_availability_array[$i][roomno] == $room_availability_array[$i+1][roomno]){
                                        // echo "<pre>";

                                        // Declare two dates
                                        $start_date = strtotime($room_availability_array[$i][check_out]);
                                        $end_date = strtotime($room_availability_array[$i+1][check_in]);
                                        $dates_available_after_checkout = ($end_date - $start_date)/60/60/24;

                                        // Get the difference and divide into
                                        // total no. seconds 60/60/24 to get
                                        // number of days
                                        // echo "Difference between two dates: "
                                        //     . ($end_date - $start_date)/60/60/24;
                                        //         echo "</pre>";
                                        $room_availability_array[$i]['dates_available_after_checkout'] = $dates_available_after_checkout;
                                        if($dates_available_after_checkout >= $totdays){
                                            if(!in_array($room_availability_array[$i][roomno], $required_rooms_to_display, true)){
                                                array_push($required_rooms_to_display, $room_availability_array[$i][roomno]);
                                           }
                                        }

                                    }
                                    if(($room_availability_array[$i][roomno] != $room_availability_array[$i-1][roomno]) && ($room_availability_array[$i][roomno] != $room_availability_array[$i+1][roomno])){
                                        $start_date = strtotime($room_availability_array[$i][check_in]);
                                        $start_date2 = strtotime($room_availability_array[$i][check_out]);
                                        // $dates_available_before_checkin = ($start_date - $table_start_date)/60/60/24;
                                        // $dates_available_after_checkout = ($start_date2 - $table_end_date)/60/60/24;

                                        if($start_date>$table_start_date){
                                            $dates_available_before_checkin = ($start_date - $table_start_date)/60/60/24;
                                        }
                                        else {
                                            $dates_available_before_checkin = ($table_start_date - $start_date)/60/60/24;
                                        }
                                        if($start_date2 > $table_end_date){
                                            $dates_available_after_checkout = ($start_date2 - $table_end_date)/60/60/24;
                                        }
                                        else {
                                            $dates_available_after_checkout = ($table_end_date - $start_date2)/60/60/24;
                                        }

                                        if($dates_available_before_checkin > $dates_available_after_checkout){
                                            $room_availability_array[$i]['dates_available_after_checkout'] = $dates_available_before_checkin;
                                            if($dates_available_after_checkout >= $totdays){
                                                if(!in_array($room_availability_array[$i][roomno], $required_rooms_to_display, true)){
                                                    array_push($required_rooms_to_display, $room_availability_array[$i][roomno]);
                                               }
                                            }
                                        }
                                        else{
                                            $room_availability_array[$i]['dates_available_after_checkout'] = $dates_available_after_checkout;
                                            if($dates_available_after_checkout >= $totdays){
                                                if(!in_array($room_availability_array[$i][roomno], $required_rooms_to_display, true)){
                                                    array_push($required_rooms_to_display, $room_availability_array[$i][roomno]);
                                               }
                                            }
                                        }
                                    }


                            }
                        }



                        // PARTICIPATING CONDOS BUT NO BOOKING
                        $dummyarray = array();
                        if(count($room_availability_array) == 1){
                            array_push($dummyarray, $room_availability_array[0][roomno]);
                        }
                        if(count($room_availability_array)>1) {
                            for ($darr =0; $darr <= count($room_availability_array);$darr++) {

                                array_push($dummyarray, $room_availability_array[$darr][roomno]);
                            }
                        }

                        $dummyarray = array_unique($dummyarray);
                        $participate_condo_only_id_without_any_booking = array();
                        $participate_condo_only_id_without_any_booking1 = array();
                        if(count($dummyarray)>0){
                            $onearray = $participate_condo_only_id;
                            $twoarray = $dummyarray;
                            $participate_condo_only_id_without_any_booking = array_values(array_diff($participate_condo_only_id, $dummyarray));
                            // $participate_condo_only_id_without_any_booking = array_values(array_filter($participate_condo_only_id_without_any_booking));

                            foreach ($participate_condo_only_id_without_any_booking as $row)  {
                                array_push($participate_condo_only_id_without_any_booking1,array('roomno' => $row,'flag' => 0));
                            }
                                // array_push($participate_condo_only_id_without_any_booking1,array('roomno' => $participate_condo_only_id_without_any_booking[$f],'flag' => 0));

                        }
                        else {
                            $participate_condo_only_id_without_any_booking = array_values(array_diff($participate_condo_only_id, $dummyarray));
                            foreach ($participating_condos as $row)  {
                                array_push($participate_condo_only_id_without_any_booking1,array('roomno' => $row[roomno],'flag' => 0));
                            }

                        }

                        usort($participate_condo_only_id_without_any_booking1,"cmp0");

                        // echo "<pre>";
                        // print_r($required_rooms_to_display);
                        // echo "<br>";
                        // print_r($room_availability_array);
                        // print_r($participate_condo_only_id_without_any_booking);
                        // print_r($participate_condo_only_id_without_any_booking1);
                        // echo "</pre>";

                    if(empty($required_rooms_to_display) && empty($participate_condo_only_id_without_any_booking1)) {

                        ?>
                        <script>
                        jQuery(document).ready(function($) {
                            alert("No rooms available for this selected date. Please modify your search criteria");
                        });
                        </script>


                    <?php
                    }
                    else {
                        ?>
                        <div class="calendar-view-modal">

                            <table>
                                <thead>
                                    <?php
                                    echo '<tr class="sh_month_labels">';
                                    echo '<th rowspan="2">&nbsp;</th>';
                                    // echo "<th >".date('F', $table_start_date);."</th>";
                                    for ($currentDate = $table_start_date; $currentDate <= $table_end_date;
                                    $currentDate += (86400)) {

                                        $Store = date('F', $currentDate);
                                        $lmonth = date('F', ($currentDate - 86400));
                                        if($Store != $lmonth){
                                            echo "<th class='next_month_label'>".$Store."</th>";
                                        }
                                        else {
                                            echo "<th>".$Store."</th>";
                                        }
                                    }
                                    //echo '<td colspan="' . $month_2_day_count . '">' . $month_title_2 . '</td>';
                                    echo '</tr>';
                                    echo "<tr>";
                                    for ($currentDate = $table_start_date; $currentDate <= $table_end_date;
                                    $currentDate += (86400)) {

                                        $Store = date('D d', $currentDate);

                                        echo "<th>".$Store."</th>";
                                    }
                                    echo "</tr>";
                                    ?>

                                </thead>

                                    <tbody>

                                        <?php

                                            $coldate = array();
                                            $nocoldate = array();
                                            $id = '';
                                            $numItems = count($room_availability_array);
                                            $i = 0;
                                            //room Availabilty starts
                                            if(!empty($required_rooms_to_display)) {
                                            foreach ($room_availability_array as $row){
                                                if(!empty($required_rooms_to_display)) {
                                                if($id!='' && $id != $row[roomno]){
                                                    foreach ($coldate as $col => $v) {
                                                        // echo '<td class="booked">'.$v.'</td>';
                                                        if($v!=''){
                                                            echo $v;
                                                        }
                                                        else {
                                                            echo '<td class="not-booked">&nbsp;</td>';
                                                        }
                                                    }

                                                    echo "</tr>";
                                                    unset($coldate);
                                                    $coldate = array();
                                                    if(in_array($row[roomno], $required_rooms_to_display, true)){
                                                    $id= $row[roomno];

                                                    if(!empty($participate_condo_only_id_without_any_booking1)) {

                                                        foreach ($participate_condo_only_id_without_any_booking1 as &$nobooking) {

                                                            if($id > $nobooking[roomno] && $nobooking['flag']==0){
                                                                $nobooking['flag']=1;
                                                                echo '<tr><th><a target="_blank" href="'.get_home_url().'/accommodation/'.$nobooking[roomno].'">'.$nobooking[roomno].'</a></th>';
                                                                for ($currentDate = $table_start_date; $currentDate <= $table_end_date;
                                                                $currentDate += (86400)) {
                                                                    echo '<td class="not-booked day">&nbsp;</td>';

                                                                }
                                                                echo '</tr>';
                                                            }
                                                        }
                                                    }

                                                    echo '<tr><th><a target="_blank" href="'.get_home_url().'/accommodation/'.$id.'">'.$id.'</a></th>';
                                                    $last_array_element = end($required_rooms_to_display);
                                                        if($last_array_element == $id){
                                                            // if(!empty($participate_condo_only_id_without_any_booking1)) {
                                                            //
                                                            //     foreach ($participate_condo_only_id_without_any_booking1 as &$nobooking) {
                                                            //
                                                            //         if($id < $nobooking[roomno] && $nobooking['flag']==0){
                                                            //             $nobooking['flag']=1;
                                                            //             echo '<tr><th><a target="_blank" href="'.get_home_url().'/accommodation/'.$nobooking[roomno].'">'.$nobooking[roomno].'</a></th>';
                                                            //              for ($currentDate = $table_start_date; $currentDate <= $table_end_date;
                                                            //             $currentDate += (86400)) {
                                                            //                 echo '<td class="not-booked day">&nbsp;</td>';
                                                            //
                                                            //             }
                                                            //             echo '</tr>';
                                                            //         }
                                                            //
                                                            //     }
                                                            // }
                                                        }
                                                    }
                                                }
                                                if($id == ''){
                                                    if(in_array($row[roomno], $required_rooms_to_display, true)){
                                                    $id= $row[roomno];

                                                    if(!empty($participate_condo_only_id_without_any_booking1)) {

                                                        foreach ($participate_condo_only_id_without_any_booking1 as &$nobooking) {

                                                            if($id > $nobooking[roomno] && $nobooking['flag']==0){
                                                                $nobooking['flag']=1;
                                                                echo '<tr><th><a target="_blank" href="'.get_home_url().'/accommodation/'.$nobooking[roomno].'">'.$nobooking[roomno].'</a></th>';
                                                                for ($currentDate = $table_start_date; $currentDate <= $table_end_date;
                                                                $currentDate += (86400)) {
                                                                    echo '<td class="not-booked day">&nbsp;</td>';

                                                                }
                                                                echo '</tr>';
                                                            }

                                                        }
                                                    }

                                                    echo '<tr><th><a target="_blank" href="'.get_home_url().'/accommodation/'.$id.'">'.$id.'</a></th>';

                                                    }
                                                }

                                                if($id == $row[roomno]){



                                                    $first = date('Y-m-d', $table_start_date);
                                                    $last = date('Y-m-d', $table_end_date);
                                                    $cdate = $row[check_in];
                                                    $cldate = $row[check_out];

                                                    // if($first <= $cdate && $cdate <= $last) {
                                                    // echo "Yes";
                                                    // echo $first;
                                                    // echo $last;
                                                    // echo $cdate;
                                                    // }

                                                    //Interval
                                                    $interval = new DateInterval('P1D');

                                                    $realEnd = new DateTime($cldate);
                                                    $realEnd->add($interval);

                                                    $period = new DatePeriod(new DateTime($cdate), $interval, $realEnd);

                                                    // Use loop to store date into array

                                                    for ($idate = strtotime($cdate); $idate <= strtotime($cldate); $idate += (86400)) {
                                                        $index=0;
                                                        // echo "<pre>";
                                                        // echo $index;
                                                        // print_r($coldate);
                                                        // echo "</pre>";

                                                        $Store = date('d', $idate);
                                                        // $Store = $date->format('d');
                                                        // echo "<th>".$Store."</th>";


                                                        for ($currentDate = $table_start_date; $currentDate <= $table_end_date;
                                                        $currentDate += (86400)) {


                                                            $pointer = date('d', $currentDate);

                                                            if($currentDate == $idate)
                                                            {
                                                                // $coldate[$index] = $Store;


                                                                if( strtotime($cdate) == $idate ) {
                                                                    if($coldate[$index]!=''){
                                                                        $l= "last_day";
                                                                    }
                                                                    else {
                                                                        $l= "";
                                                                    }
                                                                    $coldate[$index] = '<td class="booked '.$l.' first_day">&nbsp;</td>';
                                                                    // echo '<td class="sh_first_day_' . $booking_status_class . '">&nbsp;</td>';
                                                                } elseif( strtotime($cldate) == $idate ) {
                                                                    $coldate[$index] = '<td class="booked last_day">&nbsp;</td>';
                                                                } else {
                                                                    $coldate[$index] = '<td class="booked day">&nbsp;</td>';
                                                                }
                                                                // echo '<td class="sh_booking_' . $booking_status_class . '">'.$Store.'</td>';
                                                            }
                                                            else {
                                                                // echo '<td>'.$currentDate.' --'.$idate.'</td>';
                                                                // echo '<td>&nbsp;'.$Store.'</td>';$booking_status_class
                                                                if($coldate[$index]==''){
                                                                    $coldate[$index] = '';
                                                                }
                                                            }
                                                            $index ++;

                                                        }
                                                        }


                                                    }



                                                if(++$i === $numItems) {
                                                    foreach ($coldate as $col => $v) {
                                                        // echo '<td class="booked">'.$v.'</td>';
                                                        if($v!=''){
                                                            echo $v;
                                                        }
                                                        else {
                                                            echo '<td class="not-booked">&nbsp;</td>';
                                                        }
                                                    }

                                                    echo "</tr>";
                                                    // echo "Last row";
                                                    unset($coldate);
                                                    $coldate = array();
                                                    if(!empty($participate_condo_only_id_without_any_booking)) {

                                                        foreach ($participate_condo_only_id_without_any_booking1 as &$nobooking) {

                                                            if($nobooking['flag']==0){
                                                                $nobooking['flag']=1;
                                                                echo '<tr><th><a target="_blank" href="'.get_home_url().'/accommodation/'.$nobooking[roomno].'">'.$nobooking[roomno].'</a></th>';
                                                                for ($currentDate = $table_start_date; $currentDate <= $table_end_date;
                                                                $currentDate += (86400)) {
                                                                    echo '<td class="not-booked day">&nbsp;</td>';

                                                                }
                                                                echo '</tr>';
                                                            }
                                                        }
                                                    }
                                                }
                                                }
                                                else {

                                                    if(!empty($participate_condo_only_id_without_any_booking)) {

                                                        foreach ($participate_condo_only_id_without_any_booking1 as &$nobooking) {

                                                            if($nobooking['flag']==0){
                                                                $nobooking['flag']=1;
                                                                echo '<tr><th><a target="_blank" href="'.get_home_url().'/accommodation/'.$nobooking[roomno].'">'.$nobooking[roomno].'</a></th>';
                                                                for ($currentDate = $table_start_date; $currentDate <= $table_end_date;
                                                                $currentDate += (86400)) {
                                                                    echo '<td class="not-booked day">&nbsp;</td>';

                                                                }
                                                                echo '</tr>';
                                                            }
                                                        }
                                                    }
                                                }


                                            }
                                            //room available EventDnsBase
                                        }
                                        else if(!empty($participate_condo_only_id_without_any_booking1)) {

                                            foreach ($participate_condo_only_id_without_any_booking1 as &$nobooking) {

                                                if($nobooking['flag']==0){
                                                    $nobooking['flag']=1;
                                                    echo '<tr><th><a target="_blank" href="'.get_home_url().'/accommodation/'.$nobooking[roomno].'">'.$nobooking[roomno].'</a></th>';
                                                    for ($currentDate = $table_start_date; $currentDate <= $table_end_date;
                                                    $currentDate += (86400)) {
                                                        echo '<td class="not-booked day">&nbsp;</td>';

                                                    }
                                                    echo '</tr>';
                                                }
                                            }
                                        }
                                        else{
                                            echo "<pre>";
                                            echo "No rooms available for this selected date. Please modify your search criteria";
                                            echo "</pre>";
                                        }

                                            // foreach ($coldate as $col => $v) {
                                            //     // echo '<td class="booked">'.$v.'</td>';
                                            //     if($v!=''){
                                            //         echo $v;
                                            //     }
                                            //     else {
                                            //         echo '<td class="not-booked">&nbsp;</td>';
                                            //     }
                                            // }
                                            //
                                            // echo "</tr>";
                                            // unset($coldate);
                                        // }
                                        // echo count($dddarray);
                                        ?>


                                    </tbody>
                                </table>
                        </div>
                    <?php
                    }
                    ?>
            <div class="loadedblocks-xs">
                <?php if ($mobile_southface){ ?>
                    <div class="south-block clearfix">

                        <div class="block block19 south">

                            <?php

                            foreach ($b19 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>
                        <div class="block block18 south">

                            <?php

                            foreach ($b18 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>
                        <div class="block block17 south">

                            <?php

                            foreach ($b17 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>

                        <div class="block block16 south">

                            <?php

                            foreach ($b16 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>

                        <div class="block block15 south">

                            <?php

                            foreach ($b15 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>

                        <div class="block block14 south">

                            <?php

                            foreach ($b14 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>

                        <div class="block block13 south">

                            <?php

                            foreach ($b13 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>

                        <div class="block block12 south">

                            <?php

                            foreach ($b12 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>

                        <div class="block block11 south">

                            <?php

                            foreach ($b11 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>
                        <div class="block block10 south">

                            <?php

                            foreach ($b10 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>
                        <div class="southwing">EAST WING</div>
                        <div class="more-info">CLICK ON THE UNIT NUMBER FOR MORE INFORMATION AND BOOKING</div>
                    </div>
                    <?php
                }
                if ($mobile_eastface){ ?>
                    <div class="responsive-eastface clearfix">
                        <div class="east-block">
                            <div class="block block block01 east">

                                <?php


                                foreach ($b1 as $value){
                                    if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                        echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                    }
                                    else {
                                        echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                    }
                                }
                                ?>
                            </div>

                            <div class="block block02 east">

                                <?php

                                foreach ($b2 as $value){
                                    if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                        echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                    }
                                    else {
                                        echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                    }
                                }
                                ?>
                            </div>
                            <div class="block block03 east">

                                <?php

                                foreach ($b3 as $value){
                                    if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                        echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                    }
                                    else {
                                        echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                    }
                                }
                                ?>
                            </div>
                            <div class="block block04 east">

                                <?php

                                foreach ($b4 as $value){
                                    if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                        echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                    }
                                    else {
                                        echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                    }
                                }
                                ?>
                            </div>
                            <div class="block block05 east">

                                <?php

                                foreach ($b5 as $value){
                                    if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                        echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                    }
                                    else {
                                        echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                    }
                                }
                                ?>
                            </div>
                            <div class="block block06 east">

                                <?php

                                foreach ($b6 as $value){
                                    if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                        echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                    }
                                    else {
                                        echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                    }
                                }
                                ?>
                            </div>
                            <div class="block block07 east">

                                <?php

                                foreach ($b7 as $value){
                                    if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                        echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                    }
                                    else {
                                        echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                    }
                                }

                                ?>
                            </div>
                            <div class="block block08 east">

                                <?php

                                foreach ($b8 as $value){

                                    if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                        echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                    }
                                    else {
                                        echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                    }
                                }
                                ?>
                            </div>
                            <div class="block block09 east">

                                <?php

                                foreach ($b9 as $value){
                                    if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                        echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                    }
                                    else {
                                        echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                    }
                                }
                                ?>
                            </div>
                            <div class="eastwing">SOUTH WING</div>
                            <div class="crossline">

                            </div>
                            <div class="crossline2">

                            </div>
                            <div class="more-info">CLICK ON THE UNIT NUMBER FOR MORE INFORMATION AND BOOKING</div>
                        </div>
                        <div class="corner-block ">

                            <div class="block block20 southeast">

                                <?php
                                // usort($bsoutheast,"cmp");

                                foreach ($bsoutheast as $value){
                                    if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                        echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                    }
                                    else {
                                        echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                    }
                                }
                                ?>
                            </div>

                            <div class="block block21 southwest">

                                <?php
                                // usort($bsouthwest,"cmp");

                                foreach ($bsouthwest as $value){
                                    if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                        echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                    }
                                    else {
                                        echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                <?php } ?>
            </div>
            <div class="loadedblocks">

                <div class="block-floating-frame">
                    <div class="east-block">
                        <div class="block block block01 east">

                            <?php


                            foreach ($b1 as $value){

                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>

                        <div class="block block02 east">

                            <?php

                            foreach ($b2 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    // echo $value[0];
                                    echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>
                        <div class="block block03 east">

                            <?php

                            foreach ($b3 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>
                        <div class="block block04 east">

                            <?php

                            foreach ($b4 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>
                        <div class="block block05 east">

                            <?php

                            foreach ($b5 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>
                        <div class="block block06 east">

                            <?php

                            foreach ($b6 as $value){

                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>
                        <div class="block block07 east">

                            <?php

                            foreach ($b7 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }

                            ?>
                        </div>
                        <div class="block block08 east">

                            <?php

                            foreach ($b8 as $value){

                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>
                        <div class="block block09 east">

                            <?php

                            foreach ($b9 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="south-block">

                        <div class="block block19 south">

                            <?php

                            foreach ($b19 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>
                        <div class="block block18 south">

                            <?php

                            foreach ($b18 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>
                        <div class="block block17 south">

                            <?php

                            foreach ($b17 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>

                        <div class="block block16 south">

                            <?php

                            foreach ($b16 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>

                        <div class="block block15 south">

                            <?php

                            foreach ($b15 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>

                        <div class="block block14 south">

                            <?php

                            foreach ($b14 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>

                        <div class="block block13 south">

                            <?php

                            foreach ($b13 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>

                        <div class="block block12 south">

                            <?php

                            foreach ($b12 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>

                        <div class="block block11 south">

                            <?php

                            foreach ($b11 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>
                        <div class="block block10 south">

                            <?php

                            foreach ($b10 as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>

                    </div>
                    <div class="corner-block ">

                        <div class="block block20 southeast">

                            <?php
                            // usort($bsoutheast,"cmp");

                            foreach ($bsoutheast as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>

                        <div class="block block21 southwest">

                            <?php
                            // usort($bsouthwest,"cmp");

                            foreach ($bsouthwest as $value){
                                if (in_array($value[0],$required_rooms_to_display) || in_array($value[0], $participate_condo_only_id_without_any_booking)){
                                    echo '<a target="_blank" class="buttoncust available"  data-toggle="popover" data-trigger="hover" data-content="Bedroom - '.$value[1].',AC - '.$value[3].', Couch Bed - '.$value[4].', Guest - '.$value[5].'" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
                                }
                                else {
                                    echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php

    } ?>



                <!-- Large modal -->


</div>

                <?php
                echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"><script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
                <script>(function($) {
                    $(document).ready(function() {
                        $("[data-toggle=popover]").popover();
                        $(".main-frame").remove();
                    });
                })(jQuery);</script>';
                ?>
