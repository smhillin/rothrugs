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
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
    <div class="page-header">
      <h1><i class="fa fa-sitemap"></i> <?php echo $heading_title; ?></h1>
      <div class="container-fluid">
        <div class="pull-right">
              <a onclick="$('#myModal').modal('show');" data-toggle="tooltip" title="Help Guide" class="btn btn-primary"><i class="fa fa-life-ring"></i> How to use?</a> 
        </div>
      </div>
    </div>
    <div class="container-fluid">
        <div class="generate-helper-msg">
        <?php echo $seordata; ?>
        </div>
        <form action="<?php echo $sitemapg; ?>" method="post" enctype="multipart/form-data" id="sitemapg">

            <?php if(isset($sitemapexists)) { ?>
              <br><div style="text-align:center;"><a href="<?php echo $sitemapexists; ?>" target="_blank" class="sitemap">Sitemap is found. Click To View</a></div>
            <?php } else { ?>
             <br><div style="text-align:center;"><a class="sitemap">Sitemap Doesn't Exist. Click Generate Below</a></div>
            <?php } ?>
          <br>
           <?php if(isset($sitemapexists)) { ?>
           <div class="buttons sbutton"><a onclick="$('#sitemapg').submit();" class="button mbutton" >Update Sitemap</a></div>
           <?php } else { ?>
           <div class="buttons sbutton"><a onclick="$('#sitemapg').submit();" class="button mbutton" ><?php echo $button_generate; ?></a></div>
           <?php } ?>
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
               <?php echo $help; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
<?php echo $footer; ?>
<script type="text/javascript">
$('#content #cssmenu ul li:nth-child(5)').addClass('active'); 
</script>