<?php if ($reviews): ?>
  <ul class="list-comment">
    <?php foreach ($reviews as $key => $review): ?>
      <li>
          <div class="left-comment">

              <h3><?php echo $review['review_title'] ?></h3>
              <p class="text-small">
                  <?php echo $review['your_nickname'] ?><br>
                  <?php echo isset($review['your_localtion']) ? $review['your_localtion'] : '' ?><br>
                  RECOMMENDS THIS PRODUCT
              </p>

              <div class="over-review">
                  <div class="out-review">
                      <h3><?php echo $review['total'] ?></h3>
                      <span>Out of 5.0</span>
                  </div>
                  <ul class="num-review">
                      <li>
                          <label>Quality</label>
                          <div class="star-review">
                            <?php
                              for ($i=1; $i < 6; $i++) {
                                if ($i <= $review['quality_rating']) {
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
                                if ($i <= $review['color_rating']) {
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
                              for ($i=1; $i <= 5; $i++) {
                                if ($i <= $review['value_rating']) {
                                  echo '<span class="pull-star"></span>';
                                }else{
                                  echo '<span></span>';
                                }
                              }
                            ?>
                          </div>
                      </li>
                  </ul>
              </div>
              <div class="helpful-comment">
                <?php if (empty($logged)): ?>
                  <p> <a href="index.php?route=account/login" title=""><i class="fa fa-thumbs-o-up"></i></a> Helpful  (<?php echo $review['like'] ?>)</p>
                  <p> <a href="index.php?route=account/login" title=""><i class="fa fa-thumbs-o-down"></i></a> Not Helpful (<?php echo $review['dislike'] ?>)</p>
                <?php else: ?>
                  <p> <a href="#" class="like" role="<?php echo $review['review_id'] ?>" title=""><i class="fa fa-thumbs-o-up"></i></a> Helpful  (<span class="like_<?php echo $review['review_id'] ?>"><?php echo $review['like'] ?></span>)</p>
                  <p> <a href="#" class="dislike" role="<?php echo $review['review_id'] ?>" title=""><i class="fa fa-thumbs-o-down"></i></a> Not Helpful (<span class="dislike_<?php echo $review['review_id'] ?>"><?php echo $review['dislike'] ?></span>)</p>
                <?php endif ?>
              </div>

          </div>
          <div class="content-comment">
              <?php echo $review['review_summary'] ?>
          </div>
      </li>
    <?php endforeach ?>
  </ul>
  <div class="text-right"><?php echo $pagination; ?></div>
<?php else: ?>
  <p><?php echo $text_no_reviews; ?></p>
<?php endif ?>