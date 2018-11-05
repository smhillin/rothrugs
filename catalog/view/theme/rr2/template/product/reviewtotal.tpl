<?php if ($total): ?>
  <div class="out-review">
    <h3><?php echo $total['total']  ?></h3>
    <span>Out of 5.0</span>
  </div>
  <ul class="num-review">
      <li>
          <label>Quality</label>
          <div class="star-review">
            <?php
              for ($i=1; $i < 6; $i++) {
                if ($i <= $total['quality']) {
                  echo '<span class="pull-star"></span>';
                }else{
                  echo '<span></span>';
                }
              }
             ?>
          </div>
      </li>
      <li>
          <label>Color</label>
          <div class="star-review">
            <?php
              for ($i=1; $i < 6; $i++) {
                if ($i <= $total['color']) {
                  echo '<span class="pull-star"></span>';
                }else{
                  echo '<span></span>';
                }
              }
             ?>
          </div>
      </li>
      <li>
          <label>Value</label>
          <div class="star-review">
              <?php
              for ($i=1; $i < 6; $i++) {
                if ($i <= $total['value']) {
                  echo '<span class="pull-star"></span>';
                }else{
                  echo '<span></span>';
                }
              }
             ?>
          </div>
      </li>
  </ul>
  <div class="text-small">This rating is based on (<?php echo $total['review'] ?> reviews)</div>
<?php endif ?>
