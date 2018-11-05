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
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
     <div class="page-header">
      <h1><i class="fa fa-file-text"></i> <?php echo $heading_title; ?></h1>
      <div class="container-fluid">
        <div class="pull-right">
              <a onclick="$('#myModal').modal('show');" data-toggle="tooltip" title="Help Guide" class="btn btn-primary"><i class="fa fa-life-ring"></i> How to use?</a> 
              <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Back" class="btn btn-warning backreport"><i class="fa fa-reply"></i> Back</a> 
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="helper-msg">Click below to see if your Store Seo gets positive results.</div>
      <div class="sbutton"><a onclick="getreport();"><button class="btn btn-primary"><?php echo $create_report; ?></button></a></div>
      <div class="report">
         <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><i class="fa fa-list"></i> Current Seo Report</h3>
            </div>
             <div class="panel-body">
              <div class="well">
                <div class="row">

                    <div class="page-header"><h1><i class="fa  fa-file-text"></i> Sitemap.xml report</h1></div>
                    <table class="pure-table pure-table-bordered" style="  margin: auto;">
                      <thead>
                      <tr>
                        <th style="width: 60%;">Importance</th>
                        
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
                     
                        <td><?php echo $sitemap ?></td>
                        <td <?php if($sitemapfound) { echo "class='g'"; } else { echo  "class='s'"; }?>>
                          <?php if($sitemapfound) { ?>
                            <?php echo $sitemapt; ?>
                          <?php } else { ?>
                            <?php echo $sitemapnt; ?>
                          <?php } ?>
                        </td>
                        <td>
                           <?php if($sitemapfound) { ?>
                            <?php echo $sitemapnso; ?>
                          <?php } else { ?>
                            <?php echo $sitemapso; ?>
                          <?php } ?>
                        </td>
                        
                      </tr>
                      </tbody>
                    </table>

                    <br><br>

                    <div class="page-header"><h1><i class="fa  fa-file-text"></i> Robots.txt report</h1></div>
                    <table class="pure-table pure-table-bordered" style="  margin: auto;">
                      <thead>
                      <tr>
                        <th style="width: 60%;">Importance</th>
                        
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
                     
                        <td><?php echo $robots ?></td>
                        <td <?php if($robotsfound) { echo "class='g'"; } else { echo  "class='s'"; }?>>
                          <?php if($robotsfound) { ?>
                            <?php echo $robotst; ?>
                          <?php } else { ?>
                            <?php echo $robotsnt; ?>
                          <?php } ?>
                        </td>
                        <td>
                           <?php if($robotsfound) { ?>
                            <?php echo $robotsnso; ?>
                          <?php } else { ?>
                            <?php echo $robotsso; ?>
                          <?php } ?>
                        </td>
                        
                      </tr>
                      </tbody>
                    </table>

                    <br><br>

                      <div class="page-header"><h1><i class="fa  fa-file-text"></i> Complete keyword Report For Removing Duplicates</h1></div>
                   <table class="pure-table pure-table-bordered" style="  margin: auto;">
                      <thead>
                      <tr>
                        <th colspan="3"  <?php if(count($seokeyword['details'])) {  echo 'class="s"'; } else { echo 'class="g"';  } ?> ><h3>Total Duplicates found in seo keyword:<b> <?php echo $seokeyword['totalcount'] ?></b></h3></th>
                      </tr>
                      </thead>
                       <?php if(count($seokeyword['details'])) { ?>
                      <thead>
                      <tr>
                        <th>Links</th>
                        <th>Seo Keyword</th>
                        <th>Count</th>
                      </tr>
                      </thead>
                      <?php } ?>
                      <tbody>
                      <?php foreach ($seokeyword['details'] as $key => $value) { ?>
                         <tr>
                          <td>
                          <?php foreach ($value['links'] as $key1 => $value1) { ?>
                            <?php echo $value1; ?><br>
                         <?php } ?>
                          </td>
                        <td><?php echo $value['keyword']; ?></td>
                        <td><?php echo $value['count']; ?></td>
                      </tr>
                      <?php  } ?>
                      </tbody>
                    </table>

                    <br><br>


                   <div class="page-header"><h1><i class="fa  fa-file-text"></i> Product Report</h1></div>
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


                    <div class="page-header"><h1><i class="fa  fa-file-text"></i> Meta Title Product Report</h1></div>
                   <table class="pure-table pure-table-bordered" style="  margin: auto;">
                      <thead>
                      <tr>
                        <th colspan="2" <?php if(count($productmetatitle['details'])) {  echo 'class="s"'; } else { echo 'class="g"';  } ?>><h3>Total Duplicates Meta title found in products:<b> <?php echo $productmetatitle['totalcount'] ?></b></h3></th>
                      </tr>
                      </thead>
                       <?php if(count($productmetatitle['details'])) { ?>
                      <thead>
                      <tr>
                        <th>Meta title</th>
                        <th>Count</th>
                      </tr>
                      </thead>
                       <?php  } ?>
                      <tbody>
                      <?php foreach ($productmetatitle['details'] as $key => $value) { ?>
                         <tr>
                        <td><?php echo $value['meta_title']; ?></td>
                        <td><?php echo $value['count']; ?></td>
                      </tr>
                      <?php  } ?>
                      </tbody>
                    </table>

                    <br><br>

                     <div class="page-header"><h1><i class="fa  fa-file-text"></i> Meta Description Product Report</h1></div>
                   <table class="pure-table pure-table-bordered" style="  margin: auto;">
                      <thead>
                      <tr>
                        <th colspan="2"  <?php if(count($productmetadesc['details'])) {  echo 'class="s"'; } else { echo 'class="g"';  } ?> ><h3>Total Duplicates Meta Description found in products:<b> <?php echo $productmetadesc['totalcount'] ?></b></h3></th>
                      </tr>
                      </thead>
                       <?php if(count($productmetadesc['details'])) { ?>
                      <thead>
                      <tr>
                        <th>Meta Description</th>
                        <th>Count</th>
                      </tr>
                      </thead>
                      <?php  } ?>
                      <tbody>
                      <?php foreach ($productmetadesc['details'] as $key => $value) { ?>
                         <tr>
                        <td><?php echo $value['meta_description']; ?></td>
                        <td><?php echo $value['count']; ?></td>
                      </tr>
                      <?php  } ?>
                      </tbody>
                    </table>

                    <br><br>

                    <div class="page-header"><h1><i class="fa  fa-file-text"></i> Meta Keyword Product Report</h1></div>
                   <table class="pure-table pure-table-bordered" style="  margin: auto;">
                      <thead>
                      <tr>
                        <th colspan="2" <?php if(count($productmetakey['details'])) {  echo 'class="s"'; } else { echo 'class="g"';  } ?> ><h3>Total Duplicates Meta keyword found in products:<b> <?php echo $productmetakey['totalcount'] ?></b></h3></th>
                      </tr>
                      </thead>
                       <?php if(count($productmetakey['details'])) { ?>
                      <thead>
                      <tr>
                        <th>Meta keywords</th>
                        <th>Count</th>
                      </tr>
                      </thead>
                      <?php  } ?>
                      <tbody>
                      <?php foreach ($productmetakey['details'] as $key => $value) { ?>
                         <tr>
                        <td><?php echo $value['meta_keyword']; ?></td>
                        <td><?php echo $value['count']; ?></td>
                      </tr>
                      <?php  } ?>
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

                        <div class="page-header"><h1><i class="fa  fa-file-text"></i> Meta Title Category Report</h1></div>
                   <table class="pure-table pure-table-bordered" style="  margin: auto;">
                      <thead>
                      <tr>
                        <th colspan="2" <?php if(count($categorymetatitle['details'])) {  echo 'class="s"'; } else { echo 'class="g"';  } ?>><h3>Total Duplicates Meta title found in category:<b> <?php echo $categorymetatitle['totalcount'] ?></b></h3></th>
                      </tr>
                      </thead>
                       <?php if(count($categorymetatitle['details'])) { ?>
                      <thead>
                      <tr>
                        <th>Meta title</th>
                        <th>Count</th>
                      </tr>
                      </thead>
                       <?php  } ?>
                      <tbody>
                      <?php foreach ($categorymetatitle['details'] as $key => $value) { ?>
                         <tr>
                        <td><?php echo $value['meta_title']; ?></td>
                        <td><?php echo $value['count']; ?></td>
                      </tr>
                      <?php  } ?>
                      </tbody>
                    </table>

                    <br><br>

                     <div class="page-header"><h1><i class="fa  fa-file-text"></i> Meta Description category Report</h1></div>
                   <table class="pure-table pure-table-bordered" style="  margin: auto;">
                      <thead>
                      <tr>
                        <th colspan="2"  <?php if(count($categorymetadesc['details'])) {  echo 'class="s"'; } else { echo 'class="g"';  } ?> ><h3>Total Duplicates Meta Description found in category:<b> <?php echo $categorymetadesc['totalcount'] ?></b></h3></th>
                      </tr>
                      </thead>
                       <?php if(count($categorymetadesc['details'])) { ?>
                      <thead>
                      <tr>
                        <th>Meta Description</th>
                        <th>Count</th>
                      </tr>
                      </thead>
                      <?php  } ?>
                      <tbody>
                      <?php foreach ($categorymetadesc['details'] as $key => $value) { ?>
                         <tr>
                        <td><?php echo $value['meta_description']; ?></td>
                        <td><?php echo $value['count']; ?></td>
                      </tr>
                      <?php  } ?>
                      </tbody>
                    </table>

                    <br><br>

                    <div class="page-header"><h1><i class="fa  fa-file-text"></i> Meta Keyword Category Report</h1></div>
                   <table class="pure-table pure-table-bordered" style="  margin: auto;">
                      <thead>
                      <tr>
                        <th colspan="2" <?php if(count($categorymetakey['details'])) {  echo 'class="s"'; } else { echo 'class="g"';  } ?> ><h3>Total Duplicates Meta keyword found in category:<b> <?php echo $categorymetakey['totalcount'] ?></b></h3></th>
                      </tr>
                      </thead>
                       <?php if(count($categorymetakey['details'])) { ?>
                      <thead>
                      <tr>
                        <th>Meta keywords</th>
                        <th>Count</th>
                      </tr>
                      </thead>
                      <?php  } ?>
                      <tbody>
                      <?php foreach ($categorymetakey['details'] as $key => $value) { ?>
                         <tr>
                        <td><?php echo $value['meta_keyword']; ?></td>
                        <td><?php echo $value['count']; ?></td>
                      </tr>
                      <?php  } ?>
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

                        <div class="page-header"><h1><i class="fa  fa-file-text"></i> Meta Title Information Report</h1></div>
                   <table class="pure-table pure-table-bordered" style="  margin: auto;">
                      <thead>
                      <tr>
                        <th colspan="2" <?php if(count($informationmetatitle['details'])) {  echo 'class="s"'; } else { echo 'class="g"';  } ?>><h3>Total Duplicates Meta title found in information:<b> <?php echo $informationmetatitle['totalcount'] ?></b></h3></th>
                      </tr>
                      </thead>
                       <?php if(count($informationmetatitle['details'])) { ?>
                      <thead>
                      <tr>
                        <th>Meta title</th>
                        <th>Count</th>
                      </tr>
                      </thead>
                       <?php  } ?>
                      <tbody>
                      <?php foreach ($informationmetatitle['details'] as $key => $value) { ?>
                         <tr>
                        <td><?php echo $value['meta_title']; ?></td>
                        <td><?php echo $value['count']; ?></td>
                      </tr>
                      <?php  } ?>
                      </tbody>
                    </table>

                    <br><br>

                     <div class="page-header"><h1><i class="fa  fa-file-text"></i> Meta Description Information Report</h1></div>
                   <table class="pure-table pure-table-bordered" style="  margin: auto;">
                      <thead>
                      <tr>
                        <th colspan="2"  <?php if(count($informationmetadesc['details'])) {  echo 'class="s"'; } else { echo 'class="g"';  } ?> ><h3>Total Duplicates Meta Description found in information:<b> <?php echo $informationmetadesc['totalcount'] ?></b></h3></th>
                      </tr>
                      </thead>
                       <?php if(count($informationmetadesc['details'])) { ?>
                      <thead>
                      <tr>
                        <th>Meta Description</th>
                        <th>Count</th>
                      </tr>
                      </thead>
                      <?php  } ?>
                      <tbody>
                      <?php foreach ($informationmetadesc['details'] as $key => $value) { ?>
                         <tr>
                        <td><?php echo $value['meta_description']; ?></td>
                        <td><?php echo $value['count']; ?></td>
                      </tr>
                      <?php  } ?>
                      </tbody>
                    </table>

                    <br><br>

                    <div class="page-header"><h1><i class="fa  fa-file-text"></i> Meta Keyword Information Report</h1></div>
                   <table class="pure-table pure-table-bordered" style="  margin: auto;">
                      <thead>
                      <tr>
                        <th colspan="2" <?php if(count($informationmetakey['details'])) {  echo 'class="s"'; } else { echo 'class="g"';  } ?> ><h3>Total Duplicates Meta keyword found in information:<b> <?php echo $informationmetakey['totalcount'] ?></b></h3></th>
                      </tr>
                      </thead>
                       <?php if(count($informationmetakey['details'])) { ?>
                      <thead>
                      <tr>
                        <th>Meta keywords</th>
                        <th>Count</th>
                      </tr>
                      </thead>
                      <?php  } ?>
                      <tbody>
                      <?php foreach ($informationmetakey['details'] as $key => $value) { ?>
                         <tr>
                        <td><?php echo $value['meta_keyword']; ?></td>
                        <td><?php echo $value['count']; ?></td>
                      </tr>
                      <?php  } ?>
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


                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">How to use seo report?</h1>
            </div>
            <div class="modal-body">
              <?php echo $seordata; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div id="robots" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">robots.txt</h1>
            </div>
            <div class="modal-body">
             Sitemap: <?php echo $catalog ?>sitemap.xml<br>
             User-agent: *<br>
             Disallow: <strong>/admin/</strong><br>
             Allow: /
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
$('.report').hide();
$('.backreport').hide();
var tips = '<?php echo $sitemapt; ?>'; 

function getreport() {

    $.ajax({
    url: 'index.php?route=catalog/seoReport/getreport&token=<?php echo $token; ?>',
    dataType: 'json',
    beforeSend: function() {
    $('.sbutton').after('<img src="../catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
    $('.sbutton').hide();
    $('.helper-msg').hide();
    },
    complete: function() {
      $('.report').show(500);
      $('.loading').hide();
    },
    success: function(data) {
      console.log(data);
       var html = '';

       var s = data.sitemap;
       var r = data.robots;

       if(s){
        $('.ss').html('Tip');
        $('.sitemap .so h4').html(tips);
        $('.sitemap .d h4').html('Sitemap.xml is present');
        $('.sitemap .d').removeClass("d").addClass("g");
       }
       $('.backreport').show();
     }
  });  
}
</script>
<script type="text/javascript">
$('#content #cssmenu ul li:nth-child(3)').addClass('active'); 
</script>
<?php echo $footer; ?>