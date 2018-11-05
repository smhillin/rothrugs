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
      <h1><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h1>
      <div class="container-fluid">
        <div class="pull-right">
             <a href="<?php echo $clear_all; ?>" class="btn btn-primary"><i class="fa fa-eraser"></i> Clear All</a>
             <a href="<?php echo $cancel; ?>" class="btn btn-primary"><i class="fa fa-reply"></i> Back</a>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> Check your broken links below</h3>
      </div>
      <div class="panel-body">
        <div class="helper-msg">
          Redirect Table consist of all Url's that has failed on the store.<br>
          It is automatically updated as an when some url fails over the store.<br>
        </div>
        <table class="pure-table pure-table-bordered" style="width:100%;">
          <thead>
            <tr>
              <td class="center"><?php if ($sort == 'date') { ?>
                <a href="<?php echo $sort_date; ?>" title="Date Of Failure" class="<?php echo strtolower($order); ?>"><?php echo $date; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date; ?>" title="Date Of Failure"><?php echo $date; ?></a>
                <?php } ?></td>
              <td class="center"><?php if ($sort == 'failed_url') { ?>
                <a href="<?php echo $sort_failed_url; ?>" title="Failed Url In Store Front" class="<?php echo strtolower($order); ?>"><?php echo $failed_url; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_failed_url; ?>" title="Failed Url In Store Front"><?php echo $failed_url; ?></a>
                <?php } ?></td>
              <td class="center"><?php if ($sort == 'count') { ?>
                <a href="<?php echo $sort_count; ?>" title="Rate Of Failure" class="<?php echo strtolower($order); ?>"><?php echo $count; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_count; ?>" title="Rate Of Failure"><?php echo $count; ?></a>
                <?php } ?>
              </td>
              <td class="center" title="Assign Redirection To Failed Links">
                <?php echo $create_redirect; ?>
              </td>
            </tr>
          </thead>
          <tbody>
            <?php if ($redirectlist) { ?>
            <?php foreach ($redirectlist as $redirect) { ?>
            <form action="<?php echo $redirect['action']; ?>" method="post" enctype="multipart/form-data" id="form<?php echo $redirect['index']; ?>">
            <tr>
              <td class="center"><?php echo $redirect['date']; ?></td>
              <td class="center" style="width:60%;height:30px;"><textarea disabled style="width:95%" name="fromTable"/><?php echo $redirect['failedUrl']; ?></textarea></td>
              <td class="center failcount"><?php echo $redirect['count'] ?></td>
              <td class="center"><a onclick="$(this).parent().parent().find('textarea').attr('disabled', false);$('#form<?php echo $redirect['index'] ?>').submit()" class="btn btn-warning" ><?php echo $insert_redirect; ?></a></td>
            </tr>
          </form>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          
          </tbody>
        </table>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
    </div>
</div>
<script>
 $(".delete").click(function(){
      if (!confirm('Are you sure you want to delete this?')) {
        return false;
      } else {
        return true;
      }
  }); 
</script>
<script type="text/javascript">
$('#content #cssmenu ul li:nth-child(7)').addClass('active'); 
</script>
<?php echo $footer; ?>