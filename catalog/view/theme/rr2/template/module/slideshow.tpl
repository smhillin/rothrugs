<section id="intro">
    <div class="section_silder">
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <?php foreach ($banners as $k=>$banner) { ?>
                    <li data-target="#myCarousel" data-slide-to="<?php echo $k; ?>" class="<?php if($k==0){ echo 'active'; }  ?>"></li>
                <?php } ?>
            </ol>

            <div class="carousel-inner">
                <?php foreach ($banners as $k=>$banner) { ?>
                    <div class="item <?php if($k==0){ echo 'active'; }?>">
                        <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" style='width:100%'/>
                    </div>
                <?php } ?>
           </div>
            <div class="carousel-caption">
                <h1>WE HAVE THE BEST RUGS</h1>
                <div class="col-md-12 text-center" style="margin-top:20px">
                    <a href="index.php?route=product/search" class="btn_red btn_df">SHOP NOW</a>
                </div>
            </div>

            <a class="left carousel-control" href="#myCarousel" data-slide="prev">
              <span class="fa fa-angle-left"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#myCarousel" data-slide="next">
              <span class="fa fa-angle-right"></span>
              <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</section>
</div>