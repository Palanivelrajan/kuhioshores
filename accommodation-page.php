<?php

function accommodation_shortcode( $atts, $content = null ) {

	extract( shortcode_atts( array(
		'rooms_per_page' => '10',
		'hotel_category' => '',
		'columns' => '1',
		'order' => '',
		'style' => ''
	), $atts ) );

	global $post;
	global $wp_query;

	ob_start();

        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $arr = array();
        $arr22 = array();
		$arr_acc1 = array();
		$participating_condos = array();
        // Display From Category

        $args = array(
           'post_type' => 'accommodation',
           'posts_per_page' => '9999',
        );

        $wp_query = new WP_Query( $args );
        if ($wp_query->have_posts()) :
          while($wp_query->have_posts()) :
             $wp_query->the_post();

             // Get accommodation data
             $accommodation_meta = json_decode( get_post_meta($post->ID,'_accommodation_meta',TRUE), true );
             $accommodation_meta_room_excerpt = get_post_meta($post->ID,'_accommodation_room_excerpt_meta',TRUE);
             $pro_bedroom = $accommodation_meta['bedrooms'];
             $pro_lanai_Facing = $accommodation_meta['Lanai_Facing'];
             $pro_ac = $accommodation_meta['ac'];
             $pro_Couch_Bed = $accommodation_meta['Couch_Bed'];
			 $participate = $accommodation_meta['participate_condo_in_the_website'];

			 $photo = get_field('participate_condo_in_the_website');
             $titleww = get_the_title($post->ID );

//              echo "<pre>";
// 			 if( get_field('participate_condo_in_the_website') ):
//     <?php the_field('participate_condo_in_the_website');
// 	<?php the_title();
// <?php endif;
//          echo "</pre>";


			 array_push($arr_acc1,array('roomno' => $titleww, 'bedroom_count' => $pro_bedroom, 'facing' => $pro_lanai_Facing, 'ac' => $pro_ac, 'couch_bed' => $pro_Couch_Bed));

			 if( get_field('participate_condo_in_the_website') ){
				 array_push($participating_condos,$titleww);
				// array_push($participating_condos,array('roomno' => $titleww, 'bedroom_count' => $pro_bedroom, 'facing' => $pro_lanai_Facing, 'ac' => $pro_ac, 'couch_bed' => $pro_Couch_Bed));
			 }

             $final_array = array ($titleww, $pro_bedroom, $pro_lanai_Facing);
             array_push($arr22,$final_array);
             array_push($arr,$titleww);

          endwhile;

        endif;
        wp_reset_query();

		// print_r($participating_condos);

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
		usort($arr_acc1,"cmp0");
		foreach ($arr_acc1 as $row) {
		  $id= $row[roomno];
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

	   ?>
	   <div class="sh-wrapper" style="">
		   <div class="block-wrapper" style="background-color: rgba(0,0,0,.75);">
			  <div class="block-floating-frame1">
				  <div class="east-block">


		              <div class="block block01 east">

		                  <?php
		                  usort($b1,"cmp");

		                  foreach ($b1 as $value){
							  if (in_array($value[0],$participating_condos)){
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
		                  usort($b2,"cmp");

		                  foreach ($b2 as $value){
							  if (in_array($value[0],$participating_condos)){
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
		                  usort($b3,"cmp");

		                  foreach ($b3 as $value){
							  if (in_array($value[0],$participating_condos)){
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
		                  usort($b4,"cmp");

		                  foreach ($b4 as $value){
							  if (in_array($value[0],$participating_condos)){
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
		                  usort($b5,"cmp");

		                  foreach ($b5 as $value){
							  if (in_array($value[0],$participating_condos)){
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
		                  usort($b6,"cmp");

		                  foreach ($b6 as $value){
							  if (in_array($value[0],$participating_condos)){
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
		                  usort($b7,"cmp");

		                  foreach ($b7 as $value){
							  if (in_array($value[0],$participating_condos)){
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
		                  usort($b8,"cmp");

		                  foreach ($b8 as $value){

							  if (in_array($value[0],$participating_condos)){
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
		                  usort($b9,"cmp");

		                  foreach ($b9 as $value){
							  if (in_array($value[0],$participating_condos)){
							  echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
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
		                  usort($b19,"cmp");

		                  foreach ($b19 as $value){
							  if (in_array($value[0],$participating_condos)){
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
		                  usort($b18,"cmp");

		                  foreach ($b18 as $value){
							  if (in_array($value[0],$participating_condos)){
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
		                  usort($b17,"cmp");

		                  foreach ($b17 as $value){
							  if (in_array($value[0],$participating_condos)){
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
		                  usort($b16,"cmp");

		                  foreach ($b16 as $value){
							  if (in_array($value[0],$participating_condos)){
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
		                  usort($b15,"cmp");

		                  foreach ($b15 as $value){
							  if (in_array($value[0],$participating_condos)){
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
		                  usort($b14,"cmp");

		                  foreach ($b14 as $value){
							  if (in_array($value[0],$participating_condos)){
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
		                  usort($b13,"cmp");

		                  foreach ($b13 as $value){
							  if (in_array($value[0],$participating_condos)){
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
		                  usort($b12,"cmp");

		                  foreach ($b12 as $value){
							  if (in_array($value[0],$participating_condos)){
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
		                  usort($b11,"cmp");

		                  foreach ($b11 as $value){
							  if (in_array($value[0],$participating_condos)){
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
		                  usort($b10,"cmp");

		                  foreach ($b10 as $value){
							  if (in_array($value[0],$participating_condos)){
							  echo '<a target="_blank" class="buttoncust available" href="'. home_url().'/accommodation/'. $value[0].'" >'.$value[0].'</a>';
						  	}
						  	else {
						  		echo '<a target="_blank" class="buttoncust disabled" href="javascript:void(0);" >'.$value[0].'</a>';
						  	}
		                  }
		                  ?>
		              </div>


		          </div>
		          <div class="corner-block">

		              <div class="block block20 southeast">

		                  <?php
		                  usort($bsoutheast,"cmp");

		                  foreach ($bsoutheast as $value){
							  if (in_array($value[0],$participating_condos)){
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
		                  usort($bsouthwest,"cmp");

		                  foreach ($bsouthwest as $value){
							  if (in_array($value[0],$participating_condos)){
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
		  </div>
		  <div class="loadedblocks-xs">

		          <div class="south-block clearfix">

		              <div class="block block19 south">

		                  <?php

		                  foreach ($b19 as $value){

							  if (in_array($value[0],$participating_condos)){
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

							  if (in_array($value[0],$participating_condos)){
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

							  if (in_array($value[0],$participating_condos)){
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

							  if (in_array($value[0],$participating_condos)){
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

							  if (in_array($value[0],$participating_condos)){
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

							  if (in_array($value[0],$participating_condos)){
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

							  if (in_array($value[0],$participating_condos)){
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

							  if (in_array($value[0],$participating_condos)){
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

							  if (in_array($value[0],$participating_condos)){
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

							  if (in_array($value[0],$participating_condos)){
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

		              <div class="responsive-eastface clearfix">
		                  <div class="east-block">
		                      <div class="block block block01 east">

		                          <?php


		                          foreach ($b1 as $value){

									  if (in_array($value[0],$participating_condos)){
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

									  if (in_array($value[0],$participating_condos)){
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

									  if (in_array($value[0],$participating_condos)){
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

									  if (in_array($value[0],$participating_condos)){
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

									  if (in_array($value[0],$participating_condos)){
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

									  if (in_array($value[0],$participating_condos)){
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

									  if (in_array($value[0],$participating_condos)){
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


									  if (in_array($value[0],$participating_condos)){
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

									  if (in_array($value[0],$participating_condos)){
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

									  if (in_array($value[0],$participating_condos)){
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
									if (in_array($value[0],$participating_condos)){
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


		  </div>
	  </div>
<?php
/* this section should loop on east/south, 1/2 bed, 400, 300, 200, 100 */
        $oneeast = array(); $twoeast = array(); $onesouth = array(); $twosouth = array();
        foreach ($arr22 as $row) {
          $id= $row[0];
          $room = $row[1];
          $facing = $row[2];
          if (( $room == "1" ) && (( $facing == "East" ) or ( $facing == "Southeast_corner" ))) {
            array_push($oneeast,$id);
          }
          if (( $room == "2" ) && ( $facing == "East" )) {
            array_push($twoeast,$id);
          }
          if (( $room == "1" ) && (( $facing == "South" ) or ( $facing == "Southeast_corner" ) or ($facing == "Southwest_corner")) ) {
            array_push($onesouth,$id);
          }
          if (( $room == "2" ) && (( $facing == "South" ) or ( $facing == "Southeast_corner" ) or ($facing == "Southwest_corner")) ) {
            array_push($twosouth,$id);
          }
        }
        $oneeast11 = array(); $oneeast22 = array(); $oneeast33 = array(); $oneeast44 = array();
        foreach ($oneeast as $resoneeast11) {
          $east11 =  intval($resoneeast11);
          if ($east11 < 130) {
            array_push($oneeast11,$east11);
          }
          if (($east11 < 230) && ($east11 > 200)) {
            array_push($oneeast22,$east11);
          }
          if (($east11 < 330) && ($east11 > 300)) {
            array_push($oneeast33,$east11);
          }
          if (($east11 < 430) && ($east11 > 400)) {
            array_push($oneeast44,$east11);
          }
       }
       $twoeast11 = array(); $twoeast22 = array(); $twoeast33 = array(); $twoeast44 = array();
       foreach ($twoeast as $resoneeast22) {
          $east22 =  intval($resoneeast22);
          if ($east22 < 130) {
            array_push($twoeast11,$east22);
          }
          if (($east22 < 230) && ($east22 > 200)) {
            array_push($twoeast22,$east22);
          }
          if (($east22 < 330) && ($east22 > 300)) {
            array_push($twoeast33,$east22);
          }
          if (($east22 < 430) && ($east22 > 400)) {
            array_push($twoeast44,$east22);
          }
       }
       $arrlength1 = count($oneeast11);
       $arrlength2 = count($oneeast22);
       $arrlength3 = count($oneeast33);
       $arrlength4 = count($oneeast44);
       $arrlength5 = count($twoeast11);
       $arrlength6 = count($twoeast22);
       $arrlength7 = count($twoeast33);
       $arrlength8 = count($twoeast44);
       $oneeast_total = $arrlength1 + $arrlength2 + $arrlength3 + $arrlength4;
       $twoeast_total = $arrlength5 + $arrlength6 + $arrlength7 + $arrlength8;
       $east_total = $oneeast_total + $twoeast_total;
       echo '<div  style="background-color: #0d2444;display: none;align-items: center;justify-content: center;width: 100%;height: 100%;margin: 5% 0%;flex-wrap: wrap;color: white;">';
       if ($east_total > 0) {
         echo '<div class="cust_col_rm_06"><fieldset style="margin: 8px;border: 1px solid silver;padding: 8px;border-radius: 4px;"><legend>East Bay/Ocean</legend>';
         if ($oneeast_total > 0) {
           echo '<div class="cust_cols_03" style="min-width: 300px;display: flex;width: 50%;"><fieldset style="margin: 8px;border: 1px solid silver;padding: 8px;border-radius: 4px;"><legend>One Bedroom</legend><div class="cust_rom_det_inner"><div class="rm_fl_sec">';
           sort($oneeast44);
           for($a = 0; $a < $arrlength4; $a++) { $oneeast444[] =  $oneeast44[$a]; }
           foreach ($oneeast444 as $resoneeast114) { ?>
              <a target="_blank" class="buttoncust" href="<?php echo home_url()."/accommodation/". $resoneeast114; ?>" style="display
:inline-block !important;"; ><?= $resoneeast114 ?></a>
    <?php  }  ?>
           </div>
           <div class="rm_fl_sec">
    <?php
           sort($oneeast33);
           for($z = 0; $z < $arrlength3; $z++) { $oneeast333[] =  $oneeast33[$z]; }
           foreach ($oneeast333 as $resoneeast113) { ?>
             <a target="_blank" class="buttoncust" href="<?php echo home_url()."/accommodation/". $resoneeast113; ?>" style="display:
inline-block !important;"; ><?= $resoneeast113 ?></a>
     <?php  }  ?>
           </div>
           <div class="rm_fl_sec">
      <?php
           sort($oneeast22);
           for($y = 0; $y < $arrlength2; $y++) { $oneeast222[] =  $oneeast22[$y]; }
           foreach ($oneeast222 as $resoneeast112) { ?>
             <a target="_blank" class="buttoncust" href="<?php echo home_url()."/accommodation/". $resoneeast112; ?>" style="display:
inline-block !important;"; ><?= $resoneeast112 ?></a>
      <?php  }  ?>
           </div>
           <div class="rm_fl_sec">
      <?php
           sort($oneeast11);
           for ($x = 0; $x < $arrlength1; $x++) { $oneeast111[] =  $oneeast11[$x]; }
           foreach ($oneeast111 as $resoneeast111) { ?>
              <a target="_blank" class="buttoncust" href="<?php echo home_url()."/accommodation/". $resoneeast111; ?>" style="display:inline-block !important;"; ><?= $resoneeast111 ?></a>
    <?php  }  ?>
           </div>
           </div>
           </fieldset>
           </div>
    <?php
         }
         if ($twoeast_total > 0) {
           echo '<div class="cust_cols_03" style="min-width: 300px;display: flex;width: 50%;"><fieldset style="margin: 8px;border: 1px solid silver;padding: 8px;border-radius: 4px;"><legend>Two Bedroom:</legend><div class="cust_rom_det_inner"><div class="rm_fl_sec">';
            if ($arrlength8 > 0) {
            sort($twoeast44);
            for($e = 0; $e < $arrlength8; $e++) { $twoeast444[] =  $twoeast44[$e]; }
            foreach ($twoeast444 as $restwoeast224) {   ?>
               <a target="_blank" class="buttoncust" href="<?php echo home_url()."/accommodation/". $restwoeast224; ?>" style="display:inline-block !important;"; ><?= $restwoeast224 ?></a>
     <?php  }  ?>
            </div>
            <div class="rm_fl_sec">
     <?php
            if ($arrlength7 > 0) {
              sort($twoeast33);
              for($d = 0; $d < $arrlength7; $d++) { $twoeast333[] =  $twoeast33[$d]; }
              foreach ($twoeast333 as $restwoeast223) {   ?>
               <a target="_blank" class="buttoncust" href="<?php echo home_url()."/accommodation/". $restwoeast223; ?>" style="displa
y:inline-block !important;"; ><?= $restwoeast223 ?></a>
     <?php  }}  ?>
           </div>
           <div class="rm_fl_sec">
    <?php
           if ($arrlength6 > 0) {
             sort($twoeast22);
             for($c = 0; $c < $arrlength6; $c++) { $twoeast222[] =  $twoeast22[$c]; }
             foreach ($twoeast222 as $restwoeast222) {   ?>
              <a target="_blank" class="buttoncust" href="<?php echo home_url()."/accommodation/". $restwoeast222; ?>" style="display:inline-block !important;"; ><?= $restwoeast222 ?></a>
    <?php  }}  ?>
            </div>
            <div class="rm_fl_sec">
     <?php
           if ($arrlength5 > 0) {
           sort($twoeast11);
           for($b = 0; $b < $arrlength5; $b++) { $twoeast111[] =  $twoeast11[$b]; }
           foreach ($twoeast111 as $restwoeast221) {   ?>
              <a target="_blank" class="buttoncust" href="<?php echo home_url()."/accommodation/". $restwoeast221; ?>" style="display:inline-block !important;"; ><?= $restwoeast221 ?></a>
    <?php  }}  ?>
            </div>
            </div>
            </fieldset>
            </div>
  <?php  }}  ?>
         </fieldset>
         </div>
  <?php
       }
       $onesouth11 = array(); $onesouth22 = array(); $onesouth33 = array(); $onesouth44 = array();
       foreach ($onesouth as $resonesouth11) {
         $south11 =  intval($resonesouth11);
         if ($south11 < 130) {
           array_push($onesouth11,$south11);
         }
         if (($south11 < 230) && ($south11 > 200)) {
           array_push($onesouth22,$south11);
         }
         if (($south11 < 330) && ($south11 > 300)) {
           array_push($onesouth33,$south11);
         }
         if (($south11 < 430) && ($south11 > 400)) {
           array_push($onesouth44,$south11);
         }
       }
       $twosouth11 = array(); $twosouth22 = array(); $twosouth33 = array(); $twosouth44 = array();
       foreach ($twosouth as $restwosouth11) {
         $south22 =  intval($restwosouth11);
         if ($south22 < 130) {
           array_push($twosouth11,$south22);
         }
         if (($south22 < 230) && ($south22 > 200)) {
           array_push($twosouth22,$south22);
         }
         if (($south22 < 330) && ($south22 > 300)) {
           array_push($twosouth33,$south22);
         }
         if (($south22 < 430) && ($south22 > 400)) {
           array_push($twosouth44,$south22);
         }
       }
       $arrlength9 = count($onesouth11);
       $arrlength10 = count($onesouth22);
       $arrlength11 = count($onesouth33);
       $arrlength12 = count($onesouth44);
       $arrlength13 = count($twosouth11);
       $arrlength14 = count($twosouth22);
       $arrlength15 = count($twosouth33);
       $arrlength16 = count($twosouth44);

       $onesouth_total = $arrlength9 + $arrlength10 + $arrlength11 + $arrlength12;
       $twosouth_total = $arrlength13 + $arrlength14 + $arrlength15 + $arrlength16;
       $south_total = $onesouth_total + $twosouth_total;
       if ($south_total > 0) {
         echo '<div class="cust_col_rm_06"><fieldset style="margin: 8px;border: 1px solid silver;padding: 8px;border-radius: 4px;"><legend>South Ocean</legend>';
         if ($onesouth_total > 0) {
           echo '<div class="cust_cols_03" style="min-width: 300px;display: flex;width: 50%;"><fieldset style="margin: 8px;border: 1px solid silver;padding: 8px;border-radius: 4px;"><legend>One Bedroom</legend><div class="cust_rom_det_inner"><div class="rm_fl_sec">';
           sort($onesouth44);
           for($i = 0; $i < $arrlength12; $i++) { $onesouth444[] =  $onesouth44[$i]; }
           foreach ($onesouth444 as $resonesouth114) { ?>
              <a target="_blank" class="buttoncust" href="<?php echo home_url()."/accommodation/". $resonesouth114; ?>" style="display:inline-block !important;"; ><?= $resonesouth114 ?></a>
    <?php  }  ?>
           </div>
           <div class="rm_fl_sec">
    <?php
           sort($onesouth33);
           for($h = 0; $h < $arrlength11; $h++) { $onesouth333[] =  $onesouth33[$h]; }
           foreach ($onesouth333 as $resonesouth113) { ?>
              <a target="_blank" class="buttoncust" href="<?php echo home_url()."/accommodation/". $resonesouth113; ?>" style="display:inline-block !important;"; ><?= $resonesouth113 ?></a>
    <?php  }  ?>
           </div>
           <div class="rm_fl_sec">
    <?php
           sort($onesouth22);
           for($g = 0; $g < $arrlength10; $g++) { $onesouth222[] =  $onesouth22[$g]; }
           foreach ($onesouth222 as $resonesouth112) { ?>
              <a target="_blank" class="buttoncust" href="<?php echo home_url()."/accommodation/". $resonesouth112; ?>" style="display:inline-block !important;"; ><?= $resonesouth112 ?></a>
    <?php  }  ?>
           </div>
           <div class="rm_fl_sec">
    <?php
           sort($onesouth11);
           for($f = 0; $f < $arrlength9; $f++) { $onesouth111[] =  $onesouth11[$f]; }
           foreach ($onesouth111 as $resonesouth111) { ?>
             <a target="_blank" class="buttoncust" href="<?php echo home_url()."/accommodation/". $resonesouth111; ?>" style="display:inline-block !important;"; ><?= $resonesouth111 ?></a>
    <?php  }  ?>
           </div>
           </div>
           </fieldset>
           </div>
   <?php
         }
         if ($twosouth_total > 0) {
           echo '<div class="cust_cols_03" style="min-width: 300px;display: flex;width: 50%;"><fieldset style="margin: 8px;border: 1px solid silver;padding: 8px;border-radius: 4px;"><legend>Two Bedroom:</legend><div class="cust_rom_det_inner"><div class="rm_fl_sec">';
           if ($arrlength16 > 0) {
           sort($twosouth44);
           for($m = 0; $m < $arrlength16; $m++) { $twosouth444[] =  $twosouth44[$m]; }
           foreach ($twosouth444 as $restwosouth224) { ?>
              <a target="_blank" class="buttoncust" href="<?php echo home_url()."/accommodation/". $restwosouth224; ?>" style="display:inline-block !important;"; ><?= $restwosouth224 ?></a>
    <?php  }}  ?>
           </div>
           <div class="rm_fl_sec">
    <?php
           if ($arrlength15 > 0) {
           sort($twosouth33);
           for($l = 0; $l < $arrlength15; $l++) { $twosouth333[] =  $twosouth33[$l]; }
           foreach ($twosouth333 as $restwosouth223) { ?>
              <a target="_blank" class="buttoncust" href="<?php echo home_url()."/accommodation/". $restwosouth223; ?>" style="display:inline-block !important;"; ><?= $restwosouth223 ?></a>
    <?php  }}  ?>
           </div>
           <div class="rm_fl_sec">
    <?php
           if ($arrlength14 > 0) {
           sort($twosouth22);
           for($k = 0; $k < $arrlength14; $k++) { $twosouth222[] =  $twosouth22[$j]; }
           foreach ($twosouth222 as $restwosouth222) { ?>
              <a target="_blank" class="buttoncust" href="<?php echo home_url()."/accommodation/". $restwosouth222; ?>" style="display:inline-block !important;"; ><?= $restwosouth222 ?></a>
    <?php  }}  ?>
           </div>
           <div class="rm_fl_sec">
    <?php
           sort($twosouth11);
           for($j = 0; $j < $arrlength13; $j++) { $twosouth111[] =  $twosouth11[$j]; }
           foreach ($twosouth111 as $restwosouth221) { ?>
              <a target="_blank" class="buttoncust" href="<?php echo home_url()."/accommodation/". $restwosouth221; ?>" style="display:inline-block !important;"; ><?= $restwosouth221 ?></a>
    <?php  }  ?>
           </div>
           </div>
           </fieldset>
           </div>
<?php  }  ?>

       </fieldset>
       </div>
       </div>


<style>
.sh-wrapper{
	background-color: #0d2444;display: flex;align-items: center;justify-content: center;width: 100%;height: 100%;margin: 5% 0%;flex-wrap: wrap;color: white;background: url('wp-content/uploads/2017/10/header-ks.jpg') no-repeat fixed center top;background-size: 100%;
}
.popover {
    background: white;
    padding: 5px;
    color: black;
    z-index: 2;
    border-radius: 3px;
}
.popover-body {
    color: black;
    background: white;
    padding: 5px;
}
.buttoncust{
    color:gray;
    text-align:center;
}
.block-wrapper {
    position: relative;
    width: 100%;
    height: 1000px;
}

.east-block .block{
    float: right !important;
    float: right !important;
    height: 126px;
    position: relative;
    display: flex !important;
    flex-direction: column;
    justify-content: flex-end;
    padding-bottom: 30px;
    background: url('wp-content/uploads/2019/12/eastblock.png');
    border: none;
    background-repeat: no-repeat;
    background-size: contain;
}
.east-block .block > a:nth-child(2) {
    margin-left: -5px;
}

.east-block .block > a:nth-child(3) {
    margin-left: -10px;
}

.east-block .block > a:nth-child(4) {
    margin-left: -20px;
}

.block.south a:nth-child(2) {
    position: relative;
    top: 5px;
}
.block.south a:nth-child(3) {
    position: relative;
    top: 10px;
}
.block.south a:nth-child(4) {
    position: relative;
    top: 15px;
}
.buttoncust {
    display: block;
    width: 28px;
    background-color: gray;
    padding: 2px;
    text-align: center;
    border-radius: 7px;
    color: white;
    font-weight: bold;
    margin: 1px;
    color: #fff;
    text-decoration: none;
    font-size: 10px;
    text-align: center;
}
.buttoncust:hover{
    color: #fff;
    text-decoration: none;
    background-color: blue;
}
.buttoncust.available{
    background-color: green;
}
.buttoncust.not-available{
    background-color: red;
}
.block{
    border: 1px solid #a13741;
    background-color: #e8cbd0;
}
.block.south a {
    display: inline-block;
    transform: rotate(-90deg);
    width: 24px;
	height: 13px;
}

.east-block .block.block09 {
    background: url('wp-content/uploads/2019/12/block09.png');
    border: none;
    margin-top: -24px;
    background-repeat: no-repeat;
    background-size: contain;
}
.south-block .block a { margin: -2px;
    padding: 1px; }
    .south-block .block.block10 {
        background: url('wp-content/uploads/2019/12/block10.png');
        border: none;
        background-repeat: no-repeat;
        background-size: contain;
        min-width: 112px;
    }
    .south-block .block.block11 {
        background: url('wp-content/uploads/2019/12/block11.png');
        border: none;
        background-repeat: no-repeat;
        background-size: contain;
        min-width: 112px;
    }
    .south-block .block.block12 {
        background: url('wp-content/uploads/2019/12/block12.png');
        border: none;
        background-repeat: no-repeat;
        background-size: contain;
        min-width: 112px;
    }
    .south-block .block.block13 {
        background: url('wp-content/uploads/2019/12/block13.png');
        border: none;
        background-repeat: no-repeat;
        background-size: contain;
        min-width: 112px;
    }
    .south-block .block.block14 {
        background: url('wp-content/uploads/2019/12/block14.png');
        border: none;
        background-repeat: no-repeat;
        background-size: contain;
        min-width: 112px;
    }
    .south-block .block.block15 {
        background: url('wp-content/uploads/2019/12/block15.png');
        border: none;
        background-repeat: no-repeat;
        background-size: contain;
        min-width: 112px;
    }
    .south-block .block.block16 {
        background: url('wp-content/uploads/2019/12/block16.png');
        border: none;
        background-repeat: no-repeat;
        background-size: contain;
        min-width: 112px;
    }
    .south-block .block.block17 {
        background: url('wp-content/uploads/2019/12/block17.png');
        border: none;
        background-repeat: no-repeat;
        background-size: contain;
        min-width: 112px;
    }
    .south-block .block.block18 {
        background: url('wp-content/uploads/2019/12/block18.png');
        border: none;
        background-repeat: no-repeat;
        background-size: contain;
        min-width: 112px;
    }
    .south-block .block.block19 {
        background: url('wp-content/uploads/2019/12/block19.png');
        border: none;
        background-repeat: no-repeat;
        background-size: contain;
        min-width: 112px;
    }
    .corner-block {
        background-image: url('wp-content/uploads/2019/12/floor.png');
        background-repeat: no-repeat;
        background-size: contain;
        min-height: 132px;
    }
    .corner-block .block {
        border: none !important;
        background: none;
    }
    .corner-block .block.block20 {
        margin-top: 45px;
    }

    .corner-block .block.block21 {
        margin-top: 20px;
    }
	@media screen and (min-width:320px) and (max-width: 400px) {

        .loadedblocks-xs>div{
            position: relative;
        }
        .loadedblocks-xs>div:before {
            content: "Site Plan";
            position: absolute;
            right: 0px !important;
            top: 40% !important;
            font-family: 'Playfair Display', serif;
            transform: rotate(90deg);
            font-size: 42px;
        }
    }
    @media screen and (max-width: 767px) {
		.block-wrapper{
			display: none;
		}
        .loadedblocks-xs>div{
            position: relative;
        }

        .loadedblocks-xs .south-block{
            margin-bottom: 30px;
            padding: 30px;
            width: 100%;
            background-color: rgba(0,0,0,.5);
        }
        .south-block .block {
            padding: 15px 20px;
            height: 50px;
            display: flex;
            align-items: flex-start;
        }
        .loadedblocks-xs .south-block{
            margin-bottom: 30px;
            padding: 30px;
            width: 100%;
            background-color: rgba(0,0,0,.5);
        }
        .responsive-eastface {
            display: flex;
            flex-direction: row;
            padding: 30px;
            width: calc(100% - 60px);
            /* background-color: rgba(0,0,0,.5); */
        }
		.east-block .eastwing {
	    display: block;
	    text-transform: uppercase;
	    color: #dadada;
	    font-size: 20px;
	    padding-top: 50px;
	    padding-left: 80px;
	    background: url(wp-content/uploads/2019/12/arrow2.png) no-repeat;
	    background-position: 60px 5px;
	}
	.crossline {
	    content: '';
	    background-image: url(wp-content/uploads/2019/12/kuhioline.png);
		width: 50px;
height: 50px;
position: absolute;
display: block;
top: 90px;
left: 174px;
background-size: contain;
transform: rotate(50deg);
background-repeat: no-repeat;
	}
	.crossline2 {
	    content: '';
	    background-image: url(wp-content/uploads/2019/12/kuhioline.png);
		width: 60px;
height: 50px;
position: absolute;
display: block;
top: 206px;
transform: rotate(0deg);
left: 174px;
background-size: contain;
	}
        .east-block {
            display: flex;
            flex-direction: column-reverse;
            align-items: flex-start;
        }
        .east-block .block.block09{
            margin-top: 0;
        }
        .east-block .block {
            position: relative;
            float: right !important;
            background: none;
            height: 126px;
            display: flex !important;
            flex-direction: row-reverse;
            justify-content: flex-end;
            align-items: flex-end;
            border: none;
            height: 66px;
            padding: 0px 16px;
            width: 125px;

        }
        .east-block .block:before {
            content: '';
            background: url(wp-content/uploads/2019/12/eastblock.png);
            background-repeat: no-repeat;
            background-size: contain;
            width: 150px;
            height: 145px !important;
            position: absolute;
            z-index: 0;
            transform: rotate(90deg);
            top: 0;
            left: 0;
        }
		.east-block .block.block09 {
		    margin-top: 0;
		    background: none;
		}
		.east-block .block.block09:before {
		    background: none;
		    background-image: url(wp-content/uploads/2019/12/block09.png);
		    background-size: contain;
		    background-repeat: no-repeat;
		}
        a.buttoncust {
            transform: rotate(90deg);
            height: 13px;
            margin-left: 0 !important;
        }
        .east-block .block > a:nth-child(2) {
            margin-bottom: 10px !important;
        }
        .east-block .block > a:nth-child(3) {
            margin-bottom: 15px;
        }
        .east-block .block > a:nth-child(4) {
            margin-bottom: 20px;
        }
		.corner-block {
			background: none;
    display: block;
    position: relative;
    justify-content: flex-start;
    height: 150px;
    position: absolute;
    top: 95px;
    left: 180px;
		}
        .corner-block:before {
            background-image: url('wp-content/uploads/2019/12/floor.png');
            background-repeat: no-repeat;
            background-size: contain;
            min-height: 70px;
            content: '';
            width: 136px;
            height: 132px;
            position: absolute;
            left: 0;
            top: 5px;
            right: 0;
            transform: rotate(90deg);
            bottom: 0;
            margin-left: -20px;
        }
        .corner-block .block.block20 {
            margin-top: 20px;
            margin-left: 40px;
        }

		.corner-block .block.block21 {
		    margin-top: 60px;
		    margin-left: 60px;
		}
		.loadedblocks-xs .east-block:before {
		    /* word-break: break-word; */
		    content: "EAST WING";
		    position: absolute;
		    right: 65px;
		    color: #dadada;
		    top: 50%;
		    font-family: 'Playfair Display', serif;
		    font-size: 30px;
		    width: 37%;
		    text-align: center;
		}
		.loadedblocks-xs {
		    display: block;
		   width: 100%;
		}
		.sh-wrapper{
			background-color: #0d2444;
			display: flex;
			align-items: center;
			justify-content: center;
			width: 100%;
			background: none;
			height: 100%;
			margin: 5% 0%;
			background: url(wp-content/uploads/2019/12/mobilebg.png) no-repeat;
			flex-wrap: wrap;
			color: white;
			background-color: transparent;
			background-size: 100% 100%;
			}

.loadedblocks-xs .south-block {
    margin-bottom: 30px;
    padding: 30px;
    width: calc(100% - 60px)
    /* background: transparent; */

}
.loadedblocks-xs .south-block:before {
    /* word-break: break-word; */
    content: "SOUTH WING";
    position: absolute;
    right: 0;
    color: #dadada;
    top: 50%;
    font-family: 'Playfair Display', serif;
    font-size: 30px;
    width: 37%;
    text-align: center;
}
.south-block .southwing {
    display: block;
    text-transform: uppercase;
    color: #dadada;
    font-size: 20px;
    padding-top: 50px;
    padding-left: 80px;
    background: url(wp-content/uploads/2019/12/arrow.png) no-repeat !important;
    background-position: 60px 5px;
}
.more-info {
    font-size: 14px;
    position: absolute;
    bottom: 30%;
    width: 50%;
    color: #dadada;
    right: -30px;
}
.loadedblocks-xs>div {
    background: rgba(0,0,0,.5);
}
}
@media screen and (min-width: 767px) {
	a.buttoncust {
    width: 24px;
    height: 13px;
    padding: 2px !important;
}
    .loadedblocks-xs{
        display: none;
    }
    .loadedblocks-xs {
        display: none;
    }
	.block-wrapper{
		display: block;
	}
    .main-frame {

        width: 100%;
    }
    .block-floating-frame {
        /* background: url('../wp-content/uploads/2019/11/bare-sitemap.jpeg') no-repeat; */
        /* background-size: contain; */
        /* background-color: #fff; */
        width: 100%;
        float: right;
        z-index: 1;
        height: 100%;
        position: relative;
    }
    .loadedblocks {
        width: 100%;
        height: 1430px;
        position: relative;
    }
    .loadedblocks:before {
        content: "Site Plan";
        position: absolute;
        right: 100px;
        font-family: 'Playfair Display', serif;
        top: 100px;
        font-size: 48px;
        text-shadow: black;
    }
    .loadedblocks .block-floating-frame {
        width: 100%;
    }
    .east-block {
        position: absolute;
        bottom: 100px;
        z-index: 2;
        left: 100px;
    }
    .loadedblocks .east-block{
        left: 0px;
    }
    .east-block .block {
        display: inline-block;
        width: 70px;
        padding-left: 30px;
        min-height: 95px;
        float: left;
        padding-right: 5px;

    }
    .south-block {
        position: absolute;
        left: 100px;
        bottom: 200px;
    }
    .loadedblocks .south-block{
        left: 20px;
    }
    .south-block .block {
        padding-top: 5px;
        padding-bottom: 32px;
        padding-left: 15px;
    }
    .corner-block {
        position: absolute;
        left: 220px;
        bottom: 200px;
        /* width: 70px; */
    }
    .loadedblocks .corner-block{
        left: 140px;
    }
    .corner-block .block {
        display: inline-block;
        padding-top: 12px;
        padding-left: 40px;
        height: 50px;
        float: left;
        padding-right: 40px;
    }
}
@media screen and (max-width: 990px) and (min-width: 768px){
    .main-frame{
        height: 940px;
    }
    .loadedblocks{
        height: 860px;
    }

		.east-block {
		    position: absolute;
		    bottom: 40px;
		    z-index: 2;
		    left: 40px;
		}


		.east-block .block {
		    display: inline-block;
		    width: 37px;
		    padding-left: 30px;
		    height: 80px;
		    float: left;
		    padding-right: 5px;
		}
		.south-block {
		    position: absolute;
		    left: 60px;
		    bottom: 185px;
		}
		.south-block .block {
		    padding-top: 15px;
		    padding-bottom: 32px;
		    padding-left: 15px;
		}
		.corner-block {
		    position: absolute;
			left: 170px;
			bottom: 130px;

		    /* width: 70px; */
		}
		.corner-block .block {
	    display: inline-block;
	    padding-top: 12px;
	    padding-left: 20px;
	    height: 50px;
	    float: left;
	    padding-right: 20px;
	}
    .corner-block .block{
        height: auto;
        padding:20px;
    }
    .corner-block .block.block20 {
        margin-top: 20px;
    }
    .corner-block .block.block21{
        margin-top: 0;
    }
	.corner-block:after {
        content: '';
        background-image: url(wp-content/uploads/2019/12/kuhioline.png);
        width: 60px;
        height: 50px;
        position: relative;
        display: block;
        bottom: -75px;
        left: -30px;
        background-size: contain;
    }
}
@media screen and (min-width: 991px) {
    .booking-side-wrapper {
        width: calc(100% - 30px);
        margin: 0 auto;
    }
    .booking-main-wrapper {
        width: calc(100% - 30px);
        margin: 0 auto 30px auto;
        float: none;
        background: transparent !important;
    }
    .booking-main{
        background-size: 100%;
        min-height: 930px;
    }
    .main-frame {
        position: absolute;
        top: 325px;
        /* background: green; */
        height: 930px;
        width: 100%;
    }
    .block-floating-frame {
        width: 100%;
        float: right;
        z-index: 1;
        height: 100%;
        position: relative;
        padding:40px 55px;
    }
    .loadedblocks {
        width: 100%;
        height: 800px;
        position: relative;
    }
    .loadedblocks .block-floating-frame {
        width: 100%;
    }


	.east-block {
	    position: absolute;
	    bottom: 40px;
	    z-index: 2;
	    left: 40px;
	}


	.east-block .block {
	    display: inline-block;
	    width: 37px;
	    padding-left: 30px;
	    height: 80px;
	    float: left;
	    padding-right: 5px;
	}
	.south-block {
	    position: absolute;
	    left: 60px;
	    bottom: 185px;
	}
	.south-block .block {
	    padding-top: 15px;
	    padding-bottom: 32px;
	    padding-left: 15px;
	}
	.corner-block {
	    position: absolute;
		left: 170px;
		bottom: 130px;

	    /* width: 70px; */
	}
	.corner-block .block {
    display: inline-block;
    padding-top: 12px;
    padding-left: 20px;
    height: 50px;
    float: left;
    padding-right: 20px;
}
    .east-block .block.block09:after {
        display: none;
    }
	.corner-block:after {
        content: '';
        background-image: url(wp-content/uploads/2019/12/kuhioline.png);
        width: 60px;
        height: 50px;
        position: relative;
        display: block;
        bottom: -75px;
        left: -30px;
        background-size: contain;
    }

}
@media screen and (min-width: 1024px) {
	.east-block .block {
	    display: inline-block;
	    width: 31px;
	    padding-left: 30px;
	    height: 90px;
	    min-height: 65px;
	    float: left;
	    padding-right: 5px;
	}
.loadedblocks .east-block{
    left: 0px;
}
.east-block {
    position: absolute;
    bottom: 40px;
    z-index: 2;
    left: 40px;
}


.east-block .block {
    display: inline-block;
    width: 37px;
    padding-left: 30px;
    min-height: 95px;
    float: left;
    padding-right: 5px;
}
.south-block {
    position: absolute;
    left: 60px;
    bottom: 185px;
}
.south-block .block {
    padding-top: 15px;
    padding-bottom: 32px;
    padding-left: 15px;
}
.corner-block {
    position: absolute;
    left: 170px;
    bottom: 135px;
    /* width: 70px; */
}
.corner-block .block {
    display: inline-block;
    padding-top: 12px;
    padding-left: 20px;
    height: 50px;
    float: left;
    padding-right: 20px;
}
.corner-block:after {
    content: '';
    background-image: url(wp-content/uploads/2019/12/kuhioline.png);
    width: 60px;
    height: 50px;
    position: relative;
    display: block;
    bottom: -75px;
    left: -30px;
    background-size: contain;
}
}
@media screen and (min-width: 1115px) {
    .east-block {
        position: absolute;
        bottom: 40px;
        z-index: 2;
        left: 40px;
    }
	.east-block .block {
	    display: inline-block;
	    width: 40px;
	    padding-left: 30px;
	    height: 100px;
	    float: left;
	    padding-right: 5px;
	}
    .south-block {
        position: absolute;
        left: 60px;
        bottom: 194px;
    }
    .south-block .block {
        padding-top: 20px;
        padding-bottom: 40px;
    }
	.corner-block {
	    position: absolute;
	    left: 200px;
	    bottom: 150px;
	    /* width: 70px; */
	}

    .corner-block .block {
        display: inline-block;
        padding-top: 12px;
        padding-left: 20px;
        height: 50px;
        float: left;
        padding-right: 20px;
    }
	.corner-block:after {
        content: '';
        background-image: url(wp-content/uploads/2019/12/kuhioline.png);
        width: 60px;
        height: 50px;
        position: relative;
        display: block;
        bottom: -75px;
        left: -30px;
        background-size: contain;
    }

}
.south-block .block.block17 {
    margin-left: 25px;
}

.south-block .block.block18 {
    margin-left: 25px;
}

.south-block .block.block19 {
    margin-left: 50px;
}
.south-block .block.block13,
.south-block .block.block14{
    margin-left: -15px;
}
.south-block .southwing {
    display: none;
}
@media screen and (max-width: 668px) {
    .booking-page-wrapper {
        background: url('wp-content/uploads/2019/12/mobilebg.png') no-repeat;
        background-size: 100% 100%;
    }
    .south-block .southwing {
        display: block;
        text-transform: uppercase;
        color: #dadada;
        font-size: 20px;
        padding-top: 50px;
        padding-left: 80px;
        background: url(../wp-content/uploads/2019/12/arrow.png) no-repeat;
        background-position: 60px 5px;
    }
}
.buttoncust{
	font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
	text-align: center !important;
}
.loadedblocks .buttoncust,.loadedblocks .buttoncust:hover,
.loadedblocks-xs .buttoncust,.loadedblocks-xs .buttoncust:hover {
    background-color: gray;
    cursor: default;
	pointer-events: none;
}
.loadedblocks .buttoncust.available, .loadedblocks-xs .buttoncust.available {
    background-color: green;
	cursor: pointer;
	pointer-events: all !important;
}
.buttoncust.disabled{
	background-color: gray;
	cursor: default;
	pointer-events: none;
	color:#fff;
}
</style>
<?php
     }
		return ob_get_clean();

}
add_shortcode( 'accommodation', 'accommodation_shortcode' );

?>
