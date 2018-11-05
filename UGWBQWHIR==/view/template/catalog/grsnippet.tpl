<?php  echo $header; ?><?php echo $column_left; ?>
<div id="content">
   <div id="cssmenu">
    <ul>
    <?php foreach ($links as $link) { ?>
    <li><a class="top" href="<?php echo $link['href']; ?>"><?php echo $link['text']; ?></a></li>
    <?php } ?>
  </ul>
  </div>
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
       <a onclick="$('#myModal').modal('show');" data-toggle="tooltip" title="Help Guide" class="btn btn-primary"><i class="fa fa-life-ring"></i> How to use?</a> </div>
      <h1><i class="fa fa-heart"></i> <?php echo $heading_title; ?></h1>
    </div>
  </div>

  <?php if (isset($success)) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
      
  <div class="container-fluid">
   <div class="grsnippet">
      <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-check-circle"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">

        <div class="container-fluid">
          <div id="tabs" class="htabs"><a href="#tab-company"><?php echo $tab_company; ?></a><a href="#tab-achieve"><?php echo $tab_achieve; ?></a><a href="#tab-verify"><?php echo $tab_verify; ?></a></div>

          <form action="<?php echo $action; ?>" id="form" method="post" enctype="multipart/form-data" class="form-horizontal"> 
            <div id="tab-company">
                  <div class="container-fluid">
                    <div class="pull-right">
                        <button onclick="$('#form').submit();" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
                  <br>
                  <fieldset>
                   <legend><img src="view/image/seo/googleplusicon.jpg"> Google Snippet</legend>
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_google_status; ?></label>
                      <div class="col-sm-10" style="display: inline-flex;">
                        <select name="grsnippet_config_gr_status" class="form-control">
                          <?php if ($grsnippet_config_gr_status) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                            <option value="1"  ><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php }?>
                        </select> 
                      </div>
                    </div>
                  </fieldset>
                  <br><br>
                  <fieldset>
                   <legend><img src="view/image/seo/facebookicon.png"> Facebook Snippet</legend>
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_facebook_status; ?></label>
                      <div class="col-sm-10" style="display: inline-flex;">
                        <select name="grsnippet_config_facebook_status" class="form-control">
                          <?php if ($grsnippet_config_facebook_status) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                            <option value="1"  ><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php }?>
                        </select> 
                      </div>
                    </div>
                    </fieldset>
                    <br><br>
                  <fieldset>
                   <legend><img src="view/image/seo/twittericon.png"> Twitter Snippet</legend>
                   <div class="form-group">
                      <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_twitter_status; ?></label>
                      <div class="col-sm-10" style="display: inline-flex;">
                        <select name="grsnippet_config_twitter_status" class="form-control">
                          <?php if ($grsnippet_config_twitter_status) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                            <option value="1"  ><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php }?>
                        </select> 
                      </div>
                    </div>
                    </fieldset>
                    <br><br>
                    <fieldset>
                   <legend><img src="view/image/seo/pinterest.png"> Pinterest Snippet</legend>
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_pinterest_status; ?></label>
                      <div class="col-sm-10" style="display: inline-flex;">
                        <select name="grsnippet_config_pinterest_status" class="form-control">
                          <?php if ($grsnippet_config_pinterest_status) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                            <option value="1"  ><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php }?>
                        </select> 
                      </div>
                    </div>
                     <div class="container-fluid">
                        <div class="pull-right">
                             <button onclick="$('#form').submit();" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                         </div>
                     </div>
                      </fieldset>
          </div>
        <div id="tab-achieve">
          <br><br>
          <fieldset>
            <legend>How your google search results will be seen?</legend>
             <table class="form">
                    <tr>
                      <td class="title"><img src="view/image/seo/nerdherd seo result.png" alt="google achievement" title="what you will achieve in google"></td>
                      <td align="left"> <div class="helper-msg">Product rating and review</div><vr><div class="helper-msg">Google Plus Page Link</div></td>
                    </tr>
             </table>
              </fieldset>
             <br><br>
             <fieldset>
            <legend>How your tweets would like?</legend>
             <table class="form">
                    <tr>
                      <td class="title"><img src="view/image/seo/twitter rich snippet.png" alt="Twitter achievement" title="What you will achieve when someoone tweets"></td>
                      <td align="left"> <div class="helper-msg">Details Info about product</div><vr><div class="helper-msg">Also a new look on smart phone</div></td>
                    </tr>
             </table>
              </fieldset>
              <br><br>
              <fieldset>
            <legend>How your facebook share would look like?</legend>
             <table class="form">
                    <tr>
                      <td class="title"><img src="view/image/seo/Facebook share.png" alt="Facebook achievement" title="What you will achieve when someoone shared product link"></td>
                      <td align="left"> <div class="helper-msg">Share post will also show image</div><vr><div class="helper-msg">Link to web site and actual link</div></td>
                    </tr>
             </table>
              </fieldset>
             <br><br>
            <fieldset>
            <legend>How your pins would look like?</legend>
              <table class="form">
                    <tr>
                      <td class="title"><img src="view/image/seo/pinterest_snippet.jpg" alt="Pinterest achievement" title="What you will achieve when someoone pins your product"></td>
                      <td align="left"> <div class="helper-msg">Get all details of product on pinterest</div><vr><div class="helper-msg">Stock,price details and product link are also pinned</div></td>
                    </tr>
             </table>
              </fieldset>
          </div>
          <div id="tab-verify">
            <div class="generate-helper-msg">
              <?php echo $verify_help; ?>
            </div>
            <fieldset>
            <legend>How to test google rich snippet?</legend>
             <table class="form">
                    <tr>
                      <td class="title"><img src="view/image/seo/googleplusicon.jpg" alt="Google testing" title="what you will achieve in google"></td>
                      <td align="left">
                        <div class="helper-msg">1) Google rich snippet status should be enabled</div>
                        <div class="helper-msg">2) Sign into your google account.</div>
                        <div class="helper-msg">3) Click this link <a href="https://developers.google.com/structured-data/testing-tool/" target="_blank">Google Verification Tool</a></div><vr>
                        <div class="helper-msg">4) Click Fetch url button and enter any of your product link and click validate.</div></td>
                    </tr>
             </table>
           </fieldset>
           <fieldset>
            <legend>How to test twiiter rich snippet?</legend>
             <table class="form">
                    <tr>
                      <td class="title"><img src="view/image/seo/twittericon.png" alt="Twitter Testing" title="what you will achieve in twitter"></td>
                      <td align="left">
                        <div class="helper-msg">1) Twitter rich snippet status should be enabled</div>
                        <div class="helper-msg">2) Sign into your twitter account.</div>
                        <div class="helper-msg">3) Click this link <a href="https://cards-dev.twitter.com/validator" target="_blank">Twitter Verification Tool</a></div><vr>
                        <div class="helper-msg">4) Enter any of your product link and click preview card.</div>
                      </td>
                    </tr>
             </table>
            </fieldset>
            <fieldset>
            <legend>How to test Facebook rich snippet?</legend>
             <table class="form">
                    <tr>
                      <td class="title"><img src="view/image/seo/facebookicon.png" alt="Facebook achievement" title="what you will achieve on facebook"></td>
                      <td align="left">
                        <div class="helper-msg">1) Facebook rich snippet status should be enabled</div>
                        <div class="helper-msg">2) Sign into your facebook account.</div>
                        <div class="helper-msg">3) Click this link <a href="https://developers.facebook.com/tools/debug" target="_blank">Facebook Verification Tool</a></div><vr>
                        <div class="helper-msg">4) Enter any of your product link and click debug.</div>
                      </td>
                    </tr>
             </table>
            </fieldset>
            <fieldset>
            <legend>How to test Pinterest rich snippet?</legend>
             <table class="form">
                    <tr>
                      <td class="title"><img src="view/image/seo/pinterest.png" alt="Pinterest achievement" title="what you will achieve on pinterest"></td>
                      <td align="left">
                        <div class="helper-msg">1) Pinterest rich snippet status should be enabled</div>
                        <div class="helper-msg">2) Sign into your pinterest account.</div>
                        <div class="helper-msg">3) Click this link <a href="https://developers.pinterest.com/rich_pins/validator" target="_blank">Pinterest Verification Tool</a></div><vr>
                        <div class="helper-msg">4) Enter any of your product link and click Validate.</div>
                        <div class="helper-msg">5) Click Apply and select html tags and again click apply.</div>
                      </td>
                    </tr>
             </table>
            </fieldset>
          </div>
      </form>
  </div>
</div>
</div>
<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">How to use rich snippet?</h1>
            </div>
            <div class="modal-body">
               <?php echo $text_about; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
  <script type="text/javascript">
$('#content #cssmenu ul li:nth-child(4)').addClass('active'); 

$.fn.tabs = function() {
  var selector = this;
  
  this.each(function() {
    var obj = $(this); 
    
    $(obj.attr('href')).hide();
    
    $(obj).click(function() {
      $(selector).removeClass('selected');
      
      $(selector).each(function(i, element) {
        $($(element).attr('href')).hide();
      });
      
      $(this).addClass('selected');
      
      $($(this).attr('href')).show();
      
      return false;
    });
  });

  $(this).show();
  
  $(this).first().click();
};
</script>
<script type="text/javascript">
function helpguide() {
    $('#popup').bPopup({
                 speed: 400,
            transition: 'slideIn',
      transitionClose: 'slideBack',
                   
                    modalColor: 'black',
                    onClose: function() { } 
                  });
  }

</script>
  <script type="text/javascript">
  $('#tabs a').tabs(); 
  </script>
<?php echo $footer; ?>