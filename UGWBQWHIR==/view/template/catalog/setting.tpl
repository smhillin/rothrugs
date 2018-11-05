<?php  echo $header; ?><?php echo $column_left; ?>
<div id="content">
   <div id="cssmenu">
    <ul>
    <?php foreach ($links as $link) { ?>
    <li><a class="top" href="<?php echo $link['href']; ?>"><?php echo $link['text']; ?></a></li>
    <?php } ?>
  </ul>
  </div>
     <?php if (isset($success)) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
   <div class="grsnippet">
      <div class="page-header">
        <h1><i class="fa fa-gear"></i> <?php echo $heading_title; ?></h1>
        <div class="container-fluid">
          <div class="pull-right">
                <a onclick="$('#myModal').modal('show');" data-toggle="tooltip" title="Help Guide" class="btn btn-primary"><i class="fa fa-life-ring"></i> How to use?</a> 
          </div>
        </div>
      </div>
      <div class="container-fluid">
      <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> Seo setting for your store</h3>
      </div>
      <div class="panel-body">
          <form action="<?php echo $action; ?>" id="form" method="post" enctype="multipart/form-data" class="form-horizontal"> 
              <div id="tab-company">
                    <div class="container-fluid">
                      <div class="pull-right">
                             <button onclick="$('#form').submit();" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                         </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-4 control-label" for="input-sort-order"><span data-toggle="tooltip" title="<?php echo $help_self_generate; ?>"><?php echo $text_self_generate; ?></span></label>
                        <div class="col-sm-6">
                          <div class="onoffswitch">
                              <input type="checkbox" name="nerdherd_self_generate" class="onoffswitch-checkbox nerdherd_self_generate" id="myonoffswitch2"  <?php if($nerdherd_self_generate) echo "checked"; ?> >
                              <label class="onoffswitch-label" for="myonoffswitch2">
                                  <span class="onoffswitch-inner"></span>
                                  <span class="onoffswitch-switch"></span>
                              </label>
                          </div>
                        </div>

                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="input-sort-order"><span data-toggle="tooltip" title="<?php echo $help_direct_links; ?>"><?php echo $text_direct_links; ?></span></label>
                        <div class="col-sm-6">
                          <div class="onoffswitch">
                              <input type="checkbox" name="nerdherd_direct_links" class="onoffswitch-checkbox nerdherd_direct_links" id="myonoffswitch3"  <?php if($nerdherd_direct_links) echo "checked"; ?> >
                              <label class="onoffswitch-label" for="myonoffswitch3">
                                  <span class="onoffswitch-inner"></span>
                                  <span class="onoffswitch-switch"></span>
                              </label>
                          </div>
                        </div>

                    </div>
                    <div class="clear"></div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="input-sort-order"><span data-toggle="tooltip" title="<?php echo $help_multi_lang; ?>"><?php echo $text_multi_lang; ?></span></label>
                        <div class="col-sm-6">
                           <div class="onoffswitch">
                        
                            <input type="checkbox" name="nerdherd_breadcrumblink" class="onoffswitch-checkbox nerdherd_breadcrumblink" id="myonoffswitch1" <?php if($nerdherd_breadcrumblink) echo "checked"; ?> >

                            <label class="onoffswitch-label" for="myonoffswitch1">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                          </div>
                        </div>
                    </div><br>
                </div>
              </form>
          </div>
  </div>
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
               <?php echo $text_about; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
  <script type="text/javascript">
$('#content #cssmenu ul li:nth-child(8)').addClass('active'); 
</script>
<?php echo $footer; ?>