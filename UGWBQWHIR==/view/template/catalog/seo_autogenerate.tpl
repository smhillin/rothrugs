<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
   <div id="cssmenu">
    <ul>
    <?php foreach ($links as $link) { ?>
    <li><a class="top" href="<?php echo $link['href']; ?>"><?php echo $link['text']; ?></a></li>
    <?php } ?>
  </ul>
  </div>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
   <?php } ?>
  <?php if (isset($success)) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
      <div class="page-header">
      <h1><i class="fa fa-cogs"></i> <?php echo $heading_title; ?></h1>
      <div class="container-fluid">
        <div class="pull-right">
              <a onclick="$('#myModal').modal('show');" data-toggle="tooltip" title="Help Guide" class="btn btn-primary"><i class="fa fa-life-ring"></i> How to use?</a> 
        </div>
      </div>
    </div>
    <div class="container-fluid autogenerate">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
          <div class="page-header"><h1><i class="fa  fa-file-text"></i> Seo report for product</h1></div>
          <table class="pure-table pure-table-bordered" style="  margin: auto;">
            <thead>
            <tr>
              <th>Language</th>
              <th>Total Products Found</th>
              <th>Seo Keywords Found</th>
              <th>Meta Title Found</th>
              <th>Meta Keywords Found</th>
              <th>Meta Description Found</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($productreport as $key => $value) { ?>
               <tr>
              <td><img src="<?php echo $catalog; ?>image/flags/<?php echo $value['image']; ?>"> <?php echo $value['lname']; ?></td>
              <td><?php echo $value['count']; ?></td>
              <td <?php if($value['seok'] == $value['count']) { echo "class='g'"; } else { echo  "class='s'"; }?>><?php echo $value['seok']; ?></td>
              <td <?php if($value['metal']  == $value['count']) { echo "class='g'"; } else { echo  "class='s'"; }?>><?php echo $value['metal']; ?></td>
              <td <?php if($value['metak']  == $value['count']) { echo "class='g'"; } else { echo  "class='s'"; }?>><?php echo $value['metak']; ?></td>
              <td <?php if($value['metad']  == $value['count']) { echo "class='g'"; } else { echo  "class='s'"; }?>><?php echo $value['metad']; ?></td>
            </tr>
            <?php  } ?>
            </tbody>
          </table>
          <br><br>
          <div class="page-header"><h1><i class="fa fa-cog"></i> Product Seo Generators</h1></div>
          <table class="pure-table pure-table-bordered" style="width:100%;">
            <thead>
              <tr>
                <td class="left pattern"><?php echo $text_pattern; ?></td>
                <td class="left action"><?php echo $text_action; ?></td>
              </tr>
            </thead>  
            <tbody>
              <tr>
                <td><input type="text" id="products_url_template" name="products_url_template" value="<?php echo $products_url_template;?>" size="80" class="blueprint"><br> <div class="pattern-helper-msg"><?php echo $help_product_seo_description; ?></div></td>
                <td><div class="buttons">
                  <h4>Product Seo Keyword</h4><button type="submit" name="products_url" value="products_url" class="btn btn-primary"><span> <i class="fa fa-cogs"></i> <?php echo $generate;?></span></button></div></td>
              </tr>
              <tr class="bb2">
                <td><input type="text" id="products_title_template" name="products_title_template" value="<?php echo $products_title_template;?>" size="80" class="blueprint"><br> <div class="pattern-helper-msg"><?php echo $help_product_title; ?></div></td>
                <td><div class="buttons"><h4>Product Meta Title</h4><button type="submit" name="products_title" value="products_title" class="btn btn-primary"><span> <i class="fa fa-cogs"></i> <?php echo $generate;?></span></button></div></td>
              </tr>
              <tr>
                <td>
                  <input type="text" id="product_keywords_template" name="product_keywords_template" value="<?php echo $product_keywords_template;?>" size="80" class="blueprint"><br> <div class="pattern-helper-msg"><?php echo $help_meta_keywords; ?></div>
                </td>
                <td><div class="buttons"><h4>Product Meta keywords</h4><button type="submit" name="product_keywords" value="product_keywords" class="btn btn-primary"><span> <i class="fa fa-cogs"></i> <?php echo $generate;?></span></button></div></td>
              </tr>
              <tr>
                <td><input type="text" id="product_description_template" name="product_description_template" value="<?php echo $product_description_template;?>" size="80" class="blueprint"><br> <div class="pattern-helper-msg"><?php echo $help_product_description; ?></div></td>
                <td><div class="buttons"><h4>Product Meta Description</h4><button type="submit" name="product_description" value="product_description" class="btn btn-primary"><span> <i class="fa fa-cogs"></i> <?php echo $generate;?></span></button></div></td>
              </tr>
              <tr>
                <td><input type="text" id="product_tags_template" name="product_tags_template" value="<?php echo $product_tags_template;?>" size="80" class="blueprint"><br> <div class="pattern-helper-msg"><?php echo $help_product_tags; ?></div></td>
                <td><div class="buttons"><h4>Product Tags</h4><button type="submit" name="product_tags" value="product_tags" class="btn btn-primary"><span> <i class="fa fa-cogs"></i> <?php echo $generate;?></span></button></div></td>
              </tr>
              <tr>
                <td><input type="text" id="product_image_template" name="product_image_template" value="<?php echo $product_image_template;?>" size="80" class="blueprint"><br> <div class="pattern-helper-msg"><?php echo $help_product_image_description; ?></div></td>
                <td><div class="buttons"><h4>Product SEO Image Name</h4><button type="submit" name="product_image" value="product_image" class="btn btn-primary"><span> <i class="fa fa-cogs"></i> <?php echo $generate;?></span></button></div></td>
              </tr>
              </tbody>
           </table>
           <br><br>
          <div class="page-header"><h1><i class="fa  fa-file-text"></i> Seo Report For Category</h1></div>
         <table class="pure-table pure-table-bordered" style="  margin: auto;">
            <thead>
            <tr>
              <th>Language</th>
              <th>Total Categories</th>
              <th>Seo Keywords Found</th>
              <th>Meta Title Found</th>
              <th>Meta Keywords Found</th>
              <th>Meta Description Found</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($catreport as $key => $value) { ?>
               <tr>
              <td><img src="<?php echo $catalog; ?>image/flags/<?php echo $value['image']; ?>"> <?php echo $value['lname']; ?></td>
              <td><?php echo $value['count']; ?></td>
              <td <?php if($value['seok']  == $value['count']) { echo "class='g'"; } else { echo  "class='s'"; }?>><?php echo $value['seok']; ?></td>
              <td <?php if($value['metal']  == $value['count']) { echo "class='g'"; } else { echo  "class='s'"; }?>><?php echo $value['metal']; ?></td>
              <td <?php if($value['metak']  == $value['count']) { echo "class='g'"; } else { echo  "class='s'"; }?>><?php echo $value['metak']; ?></td>
              <td <?php if($value['metad']  == $value['count']) { echo "class='g'"; } else { echo  "class='s'"; }?>><?php echo $value['metad']; ?></td>
            </tr>
            <?php  } ?>
            </tbody>
          </table>
          <br><br>
         <div class="page-header"><h1><i class="fa  fa-cog"></i> Category Seo Generators</h1></div>
           <table class="pure-table pure-table-bordered" style="width:100%;">
            <thead>
              <tr>
                <td class="left pattern"><?php echo $text_pattern; ?></td>
                <td class="left action"><?php echo $text_action; ?></td>
              </tr>
            </thead>  
            <tbody>
              <tr>
                <td><input type="text" id="categories_url_template" name="categories_url_template" value="<?php echo $categories_url_template;?>" size="80" class="blueprint"><br> <div class="pattern-helper-msg"><?php echo $help_category_seo_description; ?></div></td>
                <td><div class="buttons"><h4>Category Seo Keyword</h4><button type="submit" name="categories_url" value="categories_url" class="btn btn-primary"><span> <i class="fa fa-cogs"></i> <?php echo $generate;?></span></button></div></td>
              </tr>
              <tr class="bb2">
                <td><input type="text" id="categories_title_template" name="categories_title_template" value="<?php echo $categories_title_template;?>" size="80" class="blueprint"><br><div class="pattern-helper-msg"><?php echo $help_category_title; ?></div></td>
                <td><div class="buttons"><h4>Category Meta Title</h4><button type="submit" name="categories_title" value="categories_title" class="btn btn-primary"><span> <i class="fa fa-cogs"></i> <?php echo $generate;?></span></button></div></td>
              </tr>
              <tr class="bb2">
                <td><input type="text" id="categories_keyword_template" name="categories_keyword_template" value="<?php echo $categories_keyword_template;?>" size="80" class="blueprint"><br><div class="pattern-helper-msg"><?php echo $help_category_meta_keyword; ?></div></td>
                <td><div class="buttons"><h4>Category Meta Keywords</h4><button type="submit" name="categories_keyword" value="categories_keyword" class="btn btn-primary"><span> <i class="fa fa-cogs"></i> <?php echo $generate;?></span></button></div></td>
              </tr>
              <tr class="bb2">
                <td><input type="text" id="category_description_template" name="category_description_template" value="<?php echo $category_description_template;?>" size="80" class="blueprint"><br><div class="pattern-helper-msg"><?php echo $help_category_description; ?></div></td>
                <td><div class="buttons"><h4>Category Meta Description</h4><button type="submit" name="category_description" value="category_description" class="btn btn-primary"><span> <i class="fa fa-cogs"></i> <?php echo $generate;?></span></button></div></td>
              </tr>
           </tbody>
           </table>
           <br><br>
           <div class="page-header"><h1><i class="fa  fa-file-text"></i> Seo Report For Manufacturer</h1></div>
          <table class="pure-table pure-table-bordered" style="  margin: auto;">
            <thead>
            <tr>
              <th>Language</th>
              <th>Total Brands/Manufacturer</th>
              <th>Seo Keywords Found</th>
              <th>Meta Title Found</th>
              <th>Meta Keywords Found</th>
              <th>Meta Description Found</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($manreport as $key => $value) { ?>
               <tr>
              <td><img src="<?php echo $catalog; ?>image/flags/<?php echo $value['image']; ?>"> <?php echo $value['lname']; ?></td>
              <td><?php echo $value['count']; ?></td>
              <td <?php if($value['seok']  == $value['count']) { echo "class='g'"; } else { echo  "class='s'"; }?>><?php echo $value['seok']; ?></td>
              <td <?php if($value['metal']  == $value['count']) { echo "class='g'"; } else { echo  "class='s'"; }?>><?php echo $value['metal']; ?></td>
              <td <?php if($value['metak']  == $value['count']) { echo "class='g'"; } else { echo  "class='s'"; }?>><?php echo $value['metak']; ?></td>
              <td <?php if($value['metad']  == $value['count']) { echo "class='g'"; } else { echo  "class='s'"; }?>><?php echo $value['metad']; ?></td>
            </tr>
            <?php  } ?>
            </tbody>
          </table>
          <br><br>
          <div class="page-header"><h1><i class="fa  fa-cog"></i> Manufacturer Seo Generators</h1></div>
          <table class="pure-table pure-table-bordered" style="width:100%;">
            <thead>
              <tr>
                <td class="left pattern"><?php echo $text_pattern; ?></td>
                <td class="left action"><?php echo $text_action; ?></td>
              </tr>
            </thead>  
            <tbody>
              <tr class="bb2">
                <td><input type="text" id="manufacturers_url_template" name="manufacturers_url_template" value="<?php echo $manufacturers_url_template;?>" size="80" class="blueprint"><br><div class="pattern-helper-msg"><?php echo $help_manufacturer_seo_description; ?></div></td>
                <td><div class="buttons"><h4>Manufacturer Seo Keywords</h4><button type="submit" name="manufacturers_url" value="manufacturers_url" class="btn btn-primary"><span> <i class="fa fa-cogs"></i> <?php echo $generate;?></span></button></div></td>
              </tr>
               <tr class="bb2">
                <td><input type="text" id="manufacturer_title_template" name="manufacturer_title_template" value="<?php echo $manufacturer_title_template;?>" size="80" class="blueprint"><br><div class="pattern-helper-msg"><?php echo $help_manufacturer_title; ?></div></td>
                <td><div class="buttons"><h4>Manufacturer Meta Title</h4><button type="submit" name="manufacturer_title" value="manufacturer_title" class="btn btn-primary"><span> <i class="fa fa-cogs"></i> <?php echo $generate;?></span></button></div></td>
              </tr>
              <tr class="bb2">
                <td><input type="text" id="manufacturer_keyword_template" name="manufacturer_keyword_template" value="<?php echo $manufacturer_keyword_template;?>" size="80" class="blueprint"><br><div class="pattern-helper-msg"><?php echo $help_manufacturer_meta_keyword; ?></div></td>
                <td><div class="buttons"><h4>Manufacturer Meta Keywords</h4><button type="submit" name="manufacturer_keyword" value="manufacturer_keyword" class="btn btn-primary"><span> <i class="fa fa-cogs"></i> <?php echo $generate;?></span></button></div></td>
              </tr>
              <tr class="bb2">
                <td><input type="text" id="manufacturer_description_template" name="manufacturer_description_template" value="<?php echo $manufacturer_description_template;?>" size="80" class="blueprint"><br><div class="pattern-helper-msg"><?php echo $help_manufacturer_description; ?></div></td>
                <td><div class="buttons"><h4>Manufacturer Meta Description</h4><button type="submit" name="manufacturer_description" value="manufacturer_description" class="btn btn-primary"><span> <i class="fa fa-cogs"></i> <?php echo $generate;?></span></button></div></td>
              </tr>
            </tbody>
           </table>
           <br><br>
            <div class="page-header"><h1><i class="fa fa-file-text"></i> Your Information Pages Report</h1></div>
            <table class="pure-table pure-table-bordered" style="  margin: auto;">
            <thead>
            <tr>
              <th>Language</th>
              <th>Total Information Pages Found</th>
              <th>Seo Keywords Found</th>
              <th>Meta Title Found</th>
              <th>Meta Keywords Found</th>
              <th>Meta Description Found</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($inforeport as $key => $value) { ?>
               <tr>
              <td><img src="<?php echo $catalog; ?>image/flags/<?php echo $value['image']; ?>"> <?php echo $value['lname']; ?></td>
              <td><?php echo $value['count']; ?></td>
              <td <?php if($value['seok'] == $value['count']) { echo "class='g'"; } else { echo  "class='s'"; }?>><?php echo $value['seok']; ?></td>
              <td <?php if($value['metal']  == $value['count']) { echo "class='g'"; } else { echo  "class='s'"; }?>><?php echo $value['metal']; ?></td>
              <td <?php if($value['metak']  == $value['count']) { echo "class='g'"; } else { echo  "class='s'"; }?>><?php echo $value['metak']; ?></td>
              <td <?php if($value['metad']  == $value['count']) { echo "class='g'"; } else { echo  "class='s'"; }?>><?php echo $value['metad']; ?></td>
            </tr>
            <?php  } ?>
            </tbody>
          </table>
          <br><br>
           <div class="page-header"><h1><i class="fa  fa-cog"></i> Information Page Generators</h1></div>
            <table class="pure-table pure-table-bordered" style="width:100%;">
            <thead>
              <tr>
                <td class="left pattern"><?php echo $text_pattern; ?></td>
                <td class="left action"><?php echo $text_action; ?></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text" id="information_pages_template" name="information_pages_template" value="<?php echo $information_pages_template;?>" size="80" class="blueprint"><br><div class="pattern-helper-msg"><?php echo $help_information_seo_description; ?></div></td>
                <td><div class="buttons"><h4>Information Seo Keywords</h4><button type="submit" name="information_pages" value="information_pages" class="btn btn-primary"><span> <i class="fa fa-cogs"></i> <?php echo $generate;?></span></button></div></td>
              </tr>
 
              <tr class="bb2">
                <td><input type="text" id="information_pages_title_template" name="information_pages_title_template" value="<?php echo $information_pages_title_template;?>" size="80" class="blueprint"><br><div class="pattern-helper-msg"><?php echo $help_information_title; ?></div></td>
                <td><div class="buttons"><h4>Information Meta Title</h4><button type="submit" name="information_pages_title" value="information_pages_title"class="btn btn-primary"><span> <i class="fa fa-cogs"></i> <?php echo $generate;?></span></button></div></td>
              </tr>
              <tr class="bb2">
                <td><input type="text" id="information_keyword_template" name="information_keyword_template" value="<?php echo $information_keyword_template;?>" size="80" class="blueprint"><br><div class="pattern-helper-msg"><?php echo $help_information_meta_keyword; ?></div></td>
                <td><div class="buttons"><h4>Information Meta Keywords</h4><button type="submit" name="information_keyword" value="information_keyword" class="btn btn-primary"><span> <i class="fa fa-cogs"></i> <?php echo $generate;?></span></button></div></td>
              </tr>
              <tr class="bb2">
                <td><input type="text" id="information_description_template" name="information_description_template" value="<?php echo $information_description_template;?>" size="80" class="blueprint"><br><div class="pattern-helper-msg"><?php echo $help_information_description; ?></div></td>
                <td><div class="buttons"><h4>Information Meta Description</h4><button type="submit" name="information_description" value="information_description" class="btn btn-primary"><span> <i class="fa fa-cogs"></i> <?php echo $generate;?></span></button></div></td>
              </tr>
            </tbody>
          </table>
          <br><br>
          <div class="page-header"><h1><i class="fa fa-file-text"></i> General Pages Seo Report</h1></div>
          <table class="pure-table pure-table-bordered" style="margin: auto;">
            <thead>
            <tr>
               <th>Language</th>
              <th>Report Status</th>
            </tr>
            </thead>
            <tbody>
               <?php foreach ($genreport as $key => $value) { ?>
              <tr>
                <td><img src="<?php echo $catalog; ?>image/flags/<?php echo $value['image']; ?>"> <?php echo $value['lname']; ?></td>
              <?php if($value['count']) { ?>
                <td class="g">Seo data is already created for general page </td>
              <?php } else  { ?>
                 <td class="s">Seo data is not present for general page. Kindly create from below</td>
              <?php } ?>
            </tr>
              <?php } ?>
            </tbody>
          </table>
          <table class="pure-table pure-table-bordered" style="width:100%;">
            <thead>
              <tr>
                <td class="left pattern"><?php echo $text_pattern; ?></td>
                <td class="left action"><?php echo $text_action; ?></td>
              </tr>
            </thead>
            <tbody>
               <div class="page-header"><h1><i class="fa fa-file-text"></i> Auto Generate General Pages</h1></div>
              <tr>
                <td><?php echo $help_general_data; ?><div class="pattern-helper-msg"><?php echo $help_general1_data; ?></div></td>
                <td><div class="buttons"><h4>General Page Seo</h4><button type="submit" name="general_pages" value="general_pages" class="btn btn-primary"><span> <i class="fa fa-cogs"></i> <?php echo $generate;?></span></button></div></td>
              </tr>
            </tbody>
          </table>
        </form>
    </div>
</div>
<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">How to generate seo automatically?</h1>
            </div>
            <div class="modal-body">
               <?php echo $helpauto; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
      $(".clearSeoButton button").click(function(){
          var me = $(this);
          if (!confirm('Are you sure you want to delete this?')) {
              return false;
          } else {
            $('#seo_clear').val(me.val());
            $('#form').submit();
          }
      }); 
     $('input[name=\'config_multilang_on\']').click(function() {
      var temp =  $('input[name=\'config_multilang_on\']:checked').val();
      $.ajax({
      url: 'index.php?route=catalog/seo/multisetting&token=<?php echo $token; ?>&config_multilang_on=' + temp,
      dataType: 'json',
      success: function(data) { 
      }
    });  
     });
  });
</script>
<script type="text/javascript">
$('#content #cssmenu ul li:nth-child(1)').addClass('active'); 
</script>
<?php echo $footer; ?>