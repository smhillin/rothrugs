<?php echo $header; ?><?php echo $content_top; ?>
<div class="container kates-page" id="content">
    <div class="container"><h1>Kate's Picks</h1></div>
    <section class="katestop_sec">
        <div class="container">
            <div class="kates_list_main">
                <div class="kates_list_item clearfix">
                    <?php echo $description; ?>
                </div>
                <?php if(!empty($top_category)): ?>
                <div class="kates_list_item clearfix">
                    <div class="kates_item_img">
                        <img src="<?php echo $top_category['image']; ?>">         
                    </div>
                    <div class="kates_item_cntnt">
                        <div class="itemContnt_center">
                            <h4><?php echo $top_category['name']; ?></h4>
                            <p><?php echo $top_category['description']; ?></p>
                            <a href="<?php echo $top_category['href']; ?>" class="btn_commen">Learn More</a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <!--<div class="row">
                <?php if(!empty($top_category)): ?>
                <div class=" col-md-6">
                    <h2><a href="<?php echo $top_category['href']; ?>"><?php echo $top_category['name']; ?></a></h2>
                    <a href="<?php echo $top_category['href']; ?>"><img src="<?php echo $top_category['image']; ?>" /></a>
                    <?php echo $top_category['description']; ?> <a class="more" href="<?php echo $top_category['href']; ?>">Learn More</a>
                </div>
                <?php endif; ?>
                <div class="kate col-md-6">
                    <?php echo $description; ?>
                </div>
            </div>-->
        </div>
    </section>
    <section class="other_rugs">
        <div class="container">
            <div class="katesProduct_main">
                <?php $count = 1; foreach($categories as $cat){ ?>
                <?php  if ($count%2 == 1){ ?>
                <div class="katesProduct_row clearfix">
                <?php } ?>
                    <div class="katesProduct_item clearfix">
                        <div class="katesProduct_img">
                            <a href="<?php echo $cat['href']; ?>">
                                <img src="<?php echo $cat['thumb']; ?>">
                            </a>
                        </div>
                        <div class="katesProduct_cntnt">
                            <div class="katesPro_cntnt_innr">
                                <h4><a href="<?php echo $cat['href']; ?>"><?php echo $cat['name']; ?></a></h4>
                                <p><?php echo $cat['limit_description']; ?></p>
                                <a href="<?php echo $cat['href']; ?>" class="btn_commen">Learn More</a>
                            </div>
                        </div>
                    </div>
                <?php  if ($count%2 == 0){ ?>
                </div>
                <?php } $count++; ?>
                <?php } ?>
            </div>
            
        </div>
        <div class="kitesView_allPro text-center">
            <a href="javascript:viewMoreK()" class="btn_commen">View all Kate's picks</a>
        </div>
        <!--<?php 
        $count = 1;
        foreach($categories as $cat){
        if ($count%3 == 1)
        {  
        echo "<div class='row'>";
        }

        ?>
        <div class="col-sm-4 rug-cate">
            <a href="<?php echo $cat['href']; ?>"><img src="<?php echo $cat['thumb']; ?>" /></a>

            <div class="rug_ex">
                <h3><a href="<?php echo $cat['href']; ?>"><?php echo $cat['name']; ?></a></h3>

                <div><?php echo $cat['limit_description']; ?> <a class="more" href="<?php echo $cat['href']; ?>">Learn More</a></div>
            </div>
        </div>
        <?php 
        if ($count%3 == 0)
        {
        echo "</div>";
        }
        $count++;
        }
        if ($count%3 != 1) echo "</div>"; 
        ?>-->
        <div class="text-center" id="pagination"><?php echo $pagination; ?></div>
    </section><!-- .other_rugs -->
    <?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>

<script>
   function viewMoreK()
   {
       $.ajax({
           url : '<?php echo $base_url."index.php?route=product/category/topKatePick"; ?>',
           type: 'post',
           success : function(data)
           {
               $(".kitesView_allPro").hide();
               data = JSON.parse(data);
                var l = $('.katesProduct_main div.katesProduct_row:last .katesProduct_item').length;
                var h = 0;
                if(l==1)
                {
                    html = '<div class="katesProduct_item clearfix">';
                    html+= '<div class="katesProduct_img"><a href="'+data[0].language_id+'"><img src="'+data[0].thumb+'"></a></div>'
                    html+= '<div class="katesProduct_cntnt"><div class="katesPro_cntnt_innr">'
                    html+= '<h4><a href="'+data[0].href+'">'+data[0].name+'</a></h4>'
                    html+= '<p>'+data[0].limit_description+'</p>'
                    html+= '<a href="'+data[0].href+'" class="btn_commen">Learn More</a>'
                    html+='</div></div></div>';
                    $(".katesProduct_main div.katesProduct_row:last").append(html);
                    h = 1;
                }
                var count = 1; var content = '';
                $(data).each(function(i,datas){
                    if(i>=h)
                    {
                        if (count%2 == 1){  content+= '<div class="katesProduct_row clearfix">'; }
                            content+= '<div class="katesProduct_item clearfix">';
                            content+= '<div class="katesProduct_img"><a href="'+datas.language_id+'"><img src="'+datas.thumb+'"></a></div>'
                            content+= '<div class="katesProduct_cntnt"><div class="katesPro_cntnt_innr">'
                            content+= '<h4><a href="'+datas.href+'">'+datas.name+'</a></h4>'
                            content+= '<p>'+datas.limit_description+'</p>'
                            content+= '<a href="'+datas.href+'" class="btn_commen">Learn More</a>'
                            content+='</div></div></div>';
                        if (count%2 == 0){  content+= '</div>'; }
                        count++;
                    }
                });
                $(".katesProduct_main").append(content);
           }
       })
   }
</script>
