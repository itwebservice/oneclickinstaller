<div class="owl-carousel">
  <?php
  $sq_img = mysql_query("select * from custom_package_images where package_id='$package_id'");
    while ($row_img = mysql_fetch_assoc($sq_img)){
      $url = $row_img['image_url'];
      $pos = strstr($url,'uploads');
      if ($pos != false)   {
          $newUrl = preg_replace('/(\/+)/','/',$row_img['image_url']); 
          $newUrl1 = BASE_URL.str_replace('../', '', $newUrl);
      }
      else{
          $newUrl1 =  $row_img['image_url']; 
      }
        
       ?>
  	<div class="item">
         <img src="<?php echo $newUrl1; ?>" id="image<?php echo $count; ?>" class="img-responsive">
    </div> 

    <?php
   }
  
  ?>
  </div> 
 
 
 
</div>               
<script type="text/javascript">
    $('.owl-carousel').owlCarousel({
        loop:true,
        margin:10,
        nav:false,
        pagination:false,
        autoPlay:true,
        singleItem:true,
        navigation:true,
        navigationText: ["<i class='fa fa-angle-left' aria-hidden='true'></i>", "<i class='fa fa-angle-right' aria-hidden='true'></i>"],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:3
            },
            1000:{
                items:5
            }
        }
    })
 
 
</script>  