<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
   <div id="cssmenu">
    <ul>
    <?php foreach ($links as $link) { ?>
    <li><a class="top" href="<?php echo $link['href']; ?>"><?php echo $link['text']; ?></a></li>
    <?php } ?>
  </ul>
  </div>

    <div class="page-header">
      <h1><i class="fa fa-times"></i> <?php echo $heading_title; ?></h1>
      <div class="container-fluid">
        <div class="pull-right">
              <a onclick="$('#myModal').modal('show');" data-toggle="tooltip" title="Help Guide" class="btn btn-primary"><i class="fa fa-life-ring"></i> How to use?</a> 
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Clear seo data for complete store</h3>
      </div>
      <div class="panel-body">

            <div class="othersetting clearseo table-responsive">
            <div class="helper-msg">
             Each seo data can be clear individually as per requirement.
            </div>
                   
                   <legend>Clear Products Seo</legend>
                   <table class="form">
                        <tbody>
                               <tr>
                                <td colspan="2"><button class="productseokeyword">Clear Product Seo Keywords</button></td>
                              </tr>
                              <tr>
                                <td colspan="2"><button class="producttitle">Clear Product Meta Title</button></td>
                              </tr>
                              <tr>
                                <td colspan="2"><button class="productmetakeyword">Clear Product Meta Keywords</button></td>
                              </tr>
                               <tr>
                                <td colspan="2"><button class="productmetadescription">Clear Product Meta Description</button></td>
                              </tr>
                               <tr>
                                <td colspan="2"><button class="producttags">Clear Product Tags</button></td>
                              </tr>
                        </tbody>
                    </table>
                  
            <br><br>
                  
                   <legend>Clear Category Seo</legend>
                    <table class="form">
                            <tbody>
                               <tr>
                                <td colspan="2"><button class="categoryseokeyword">Clear Category Seo Keywords</button></td>
                              </tr>
                               <tr>
                                <td colspan="2"><button class="categorytitle">Clear Category Title</button></td>
                              </tr>
                              <tr>
                                <td colspan="2"><button class="categorymetakeyword">Clear Category Meta Keywords</button></td>
                              </tr>
                               <tr>
                                <td colspan="2"><button class="categorymetadescription">Clear Category Meta Description</button></td>
                              </tr>
                        </tbody>
                        </table>
                      <br><br>
                       <legend>Clear Manufacturer Seo</legend>
                      <table class="form">
                      <tbody>
                              <tr>
                                <td colspan="2"><button class="manufacturerseokeyword">Clear Manufacturer Seo Keywords</button></td>
                              </tr>
                               <tr>
                                <td colspan="2"><button class="manufacturertitle">Clear Manufacturer Title</button></td>
                              </tr>
                              <tr>
                                <td colspan="2"><button class="manufacturermetakeyword">Clear Manufacturer Meta Keywords</button></td>
                              </tr>
                               <tr>
                                <td colspan="2"><button class="manufacturermetadescription">Clear Manufacturer Meta Description</button></td>
                              </tr>
                        </tbody>
                    </table>
                <br><br>
                 
                   <legend>Clear Information Seo</legend>
                      <table class="form">
                            <tbody>
                              <tr>
                                <td colspan="2"><button class="informationseokeyword">Clear Information Seo Keywords</button></td>
                              </tr>
                               <tr>
                                <td colspan="2"><button class="informationtitle">Clear Information Title</button></td>
                              </tr>
                              <tr>
                                <td colspan="2"><button class="informationmetakeyword">Clear Information Meta Keywords</button></td>
                              </tr>
                               <tr>
                                <td colspan="2"><button class="informationmetadescription">Clear Information Meta Description</button></td>
                              </tr>
                        </tbody>
                      </table>
                      <br><br>
                       <legend>Clear General Page Seo</legend>
                      <table class="form">
                            <tbody>
                              <tr>
                                <td colspan="2"><button class="clearGeneral">Clear General Seo Keywords</button></td>
                              </tr>
                        </tbody>
                      </table>
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
                <h1 class="modal-title">How to use clear seo tool?</h1>
            </div>
            <div class="modal-body">
               <?php echo $help; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(".clearseo button").addClass("red");
$('.clearseo button').click(function(){
  $(".alert").remove();
   if (confirm('Are you sure you want to delete this?')) {
      $(this).removeClass("red");
      var name = $(this).attr("class");
      $.ajax({
          url: 'index.php?route=catalog/clearseo/deletedata&token=<?php echo $token; ?>&name=' +  name,
          dataType: 'json',
          success: function(data) {
              $('.'+name).addClass("greens");
              $('.page-header').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> Seo data has been cleared. Go to auto generate page for generating again.</div>')
          }
        });
  } 
});
</script>
<script type="text/javascript">
$('#content #cssmenu ul li:nth-child(6)').addClass('active'); 
</script>
<?php echo $footer; ?>