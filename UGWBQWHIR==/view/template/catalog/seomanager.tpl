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
      <h1><i class="fa fa-random"></i> <?php echo $heading_title; ?></h1>
      <div class="container-fluid">
        <div class="pull-right">
              <a onclick="$('#myModal').modal('show');" data-toggle="tooltip" title="Help Guide" class="btn btn-primary"><i class="fa fa-life-ring"></i> How to use</a> 
            </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> Check broken links and assign it a redirect</h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $mstatus; ?></label>
            <div class="col-sm-10">
              <select name="redirectstatus" onchange="javascript:handleSelect(this)">
                  <?php if ($redirectstatus) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $text_redirect_table; ?></label>
            <div class="col-sm-10">
             <a href="<?php echo $redirect_table; ?>" class="btn btn-primary"><i class="fa fa-link"></i> Broken links on your store</a>  
            </div>
          </div>
          <div class="clear"></div>
        </div></div>
          <div class="container-fluid">
          <div class="pull-right">
                <a onclick="addRow();" data-toggle="tooltip" title="Add New Redirect" class="btn btn-primary"><i class="fa fa-plus"></i></a>
          </div>
        </div>
        <br>
       
        <table class="pure-table pure-table-bordered" style="  margin: auto;">
          <thead>
            <tr class="redirect">
               <td class="center" style="width:8%;"><?php if ($sort == 'times_used') { ?>
                <a href="<?php echo $sort_times_used; ?>" title="Number of times redirected"  class="<?php echo strtolower($order); ?>"><?php echo $times_used; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_times_used; ?>" title="Number of times redirected" ><?php echo $times_used; ?></a>
                <?php } ?>
              </td>
              <td class="center"><?php if ($sort == 'fromUrl') { ?>
                <a href="<?php echo $sort_fromUrl; ?>" title="The url which needs to be redirected" class="<?php echo strtolower($order); ?>"><?php echo $from_url; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_fromUrl; ?>" title="The url which needs to be redirected"><?php echo $from_url; ?></a>
                <?php } ?></td>
              <td class="center"><?php if ($sort == 'toUrl') { ?>
                <a href="<?php echo $sort_toUrl; ?>"  title="The target Url" class="<?php echo strtolower($order); ?>"><?php echo $to_url; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_toUrl; ?>" title="The target Url"><?php echo $to_url; ?></a>
                <?php } ?></td>
              <td class="center" title="Test Url"  style="width:8%;"><?php echo $url_test; ?></td>
              <td class="center" width="12" title="Delete"><?php echo $delete; ?></td>
              <td class="center" width="12"><?php if ($sort == 'status') { ?>
                <a href="<?php echo $sort_status; ?>" title="Enable and disable redirection individually" class="<?php echo strtolower($order); ?>"><?php echo $status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>" title="Enable and disable redirection individually"><?php echo $status; ?></a>
                <?php } ?></td>
              <td class="center" width="14" title="Save">Action</td>
            </tr>
          </thead>
          <tbody>
           <?php if($fromTable) { ?>
             <tr>
              <td>-</td>
              <td class="center"><textarea style="width:95%" name="redirectf-100"/><?php echo $fromTable; ?></textarea></td>
              <td class="center"><textarea style="width:95%" name="redirectt-100"/></textarea></td>
              <td class="center">-</td>
             <td class="center"><a class="btn btn-danger" onclick="$(this).parent().parent().remove()"><i class="fa fa-minus"></i></a></td>
             <td class="center">
                <select name="redirects-100">
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                  </select>
               </td>
             <td class="center"><a class="btn btn-primary" onclick="insert(-100)">Insert</a></td>
            </tr>
            <?php } ?>
            <?php if ($redirectlist) { ?>
            <?php foreach ($redirectlist as $redirect) { ?>
            <tr class="delete<?php echo $redirect['index']; ?>">
              <td class="center">
                <label class="resetvalue" name="resetvalue<?php echo $redirect['index']; ?>"><?php echo $redirect['times_used']; ?></label><br><br>
                <span><a onclick="reset1(<?php echo $redirect['index']; ?>)" class="btn btn-primary"><i class="fa fa-refresh"></i></a></span>
              </td>
              <td class="center"><textarea style="width:95%" name="redirectf<?php echo $redirect['index']; ?>"/><?php echo $redirect['fromUrl']; ?></textarea></td>
              <td class="center"><textarea style="width:95%" name="redirectt<?php echo $redirect['index']; ?>"/><?php echo $redirect['toUrl']; ?></textarea></td>
              <td class="center"><a href="<?php echo $redirect['fromUrl']; ?>" name="test<?php echo $redirect['index']; ?>" target="_blank" class="btn btn-primary">Test Redirection</a></td>
              <td class="center"><a class="btn btn-danger" onclick="delete1(<?php echo $redirect['index']; ?>)" class="btn btn-primary"><i class="fa fa-minus"></i></a></td>
              <td class="center"><select name="redirects<?php echo $redirect['index']; ?>">
                    <?php if($redirect['status']) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                </select>
              </td>
              <td class="center"><a class="update<?php echo $redirect['index']; ?> btn btn-primary" onclick="update(<?php echo $redirect['index']; ?>)" class="">Update</a></a></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
    </div>
  </div>
    <div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">How to use seo redirect manager?</h1>
            </div>
            <div class="modal-body">
               <?php echo $help; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
<script type="text/javascript">
newRow = -1;
  function addRow() {
    $('\
      <tr class="delete'+newRow+'">\
        <td class="center">Insert Details First</td>\
        <td class="center"><textarea style="width:95%" name="redirectf' + newRow + '"></textarea></td>\
        <td class="center"><textarea style="width:95%" name="redirectt' + newRow + '"></textarea></td>\
        <td class="center"></td>\
        <td class="center"><a onclick="$(this).parent().parent().remove()" class="btn btn-primary"><i class="fa fa-minus"></i></a></td>\
         <td class="center"><select name="redirects' + newRow + '"><option value="1" selected="selected"><?php echo $text_enabled; ?></option>\
                    <option value="0"><?php echo $text_disabled; ?></option></select>\
        </td>\
        <td class="center"><a class="insert'+newRow+' btn btn-primary " onclick="insert('+newRow+')"><i class="fa fa-save"></i></a></td>\
      </tr>\
    ').insertBefore('table.pure-table tbody tr:first');
    newRow--;
  };
</script>
<script>
function update(id) {
    var to = $('textarea[name = "redirectt'+id+'"]').val();
    var fr = $('textarea[name = "redirectf'+id+'"]').val();
    var s = $('select[name = "redirects'+id+'"]').val();
    if(to != '' && fr != '') {
      $.ajax({
        url: 'index.php?route=catalog/seomanager/update&token=<?php echo $token ?>',
        type: 'POST',
        data: { 'id': id, 'toUrl' : to,'fromUrl': fr, 'status' : s },
        success: function(data) {
              $('.update'+id+'').html('Updated').css('background-color','green');
              $('a[name="test'+id+'"]').attr("href",fr).html("Test Redirection");
        }
      });
    }
};

</script>
<script>
function insert(id) {
    var to = $('textarea[name = "redirectt'+id+'"]').val();
    var fr = $('textarea[name = "redirectf'+id+'"]').val();
    var s = $('select[name = "redirects'+id+'"]').val();
    if(to != '' && fr != '') {
      $.ajax({
        url: 'index.php?route=catalog/seomanager/inserting&token=<?php echo $token ?>',
        type: 'POST',
        data: { 'id': id, 'toUrl' : to,'fromUrl': fr, 'status' : s },
        success: function(data) {
              $('.insert'+id+'').html('Inserted').css('background-color','green');
              $('a[name="test'+id+'"]').attr("href",fr).html("Test Redirection");
        }
      });
    }  
};

</script>
<script type="text/javascript">
function handleSelect(elm) {
    $.ajax({
      url: 'index.php?route=catalog/seomanager/insert&token=<?php echo $token; ?>&value=' + elm.value,
      dataType: 'json',
      success: function(data) {
           console.log("Success");
      }
    });
};
</script>
<script>
function delete1(id) {
    $.ajax({
    url: 'index.php?route=catalog/seomanager/delete&token=<?php echo $token; ?>&id=' +  id,
    dataType: 'json',
    success: function(data) {
          $('.delete'+id+'').remove();
    }
  });
}
</script>
<script>
function reset1(id) {
  $.ajax({
    url: 'index.php?route=catalog/seomanager/reset&token=<?php echo $token; ?>&id=' +  id,
    dataType: 'json',
    success: function(data) {
           $('label[name="resetvalue'+id+'"]').html("0");
    }
  });
};
</script>
<script type="text/javascript">
$('#content #cssmenu ul li:nth-child(7)').addClass('active'); 
</script>
<?php echo $footer; ?>