<div class="tab-product" style="    margin-top: 11px;">
          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
              <a href="#description" aria-controls="description" role="tab" data-toggle="tab">Product Description</a>
            </li>
            <li role="presentation">
              <a href="#reviews" aria-controls="reviews" role="tab" data-toggle="tab">Customer Reviews</a>
            </li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="description">
              <?php echo $description; ?>
            </div>
            <div role="tabpanel" class="tab-pane " id="reviews">
              <div class="pull-left">
                  <a href="#" id="write-review" class="btn btn-grey">Write a review</a>
              </div>
              <div class="box-show-review">
                <p style="font-size: 17px">
                    <strong>The product name</strong><br>
                    Overall rating
                </p>
                <div class="over-review">
                    <div class="out-review">
                        <h3>4.9</h3>
                        <span>Out of 5.0</span>
                    </div>
                    <ul class="num-review">
                        <li>
                            <label>Quality</label>
                            <div class="star-review">
                                <span class="pull-star"></span>
                                <span class="pull-star"></span>
                                <span class="pull-star"></span>
                                <span class="pull-star"></span>
                                <span></span>
                            </div>
                        </li>
                        <li>
                            <label>Color</label>
                            <div class="star-review">
                                <span class="pull-star"></span>
                                <span class="pull-star"></span>
                                <span class="pull-star"></span>
                                <span class="pull-star"></span>
                                <span class="pull-star"></span>
                            </div>
                        </li>
                        <li>
                            <label>Value</label>
                            <div class="star-review">
                                <span class="pull-star"></span>
                                <span class="pull-star"></span>
                                <span class="pull-star"></span>
                                <span class="pull-star"></span>
                                <span class="pull-star"></span>
                            </div>
                        </li>
                    </ul>
                </div>
              </div>
                <div class="clear"></div>

                <div class="form-comment">
                  <div id="review"></div>
                  <div id="form-write-review" style="display:none;">
                    <form class="form-horizontal">
                      <h4>Product Review</h4>
                      <div class="row form-group">
                          <label class="col-sm-4"><span class="require">*</span>Review title :</label>
                          <div class="col-sm-8">
                              <input type="text" name="title" id="title" class="form-control" placeholder="Example:  Best rug ever!">
                          </div>
                      </div>

                      <div class="row form-group">
                          <label class="col-sm-4">
                              <span class="require">*</span>Quality rating<br>
                              <span class="require">*</span>Color rating<br>
                              <span class="require">*</span>Value rating</label>
                          <div class="col-sm-8">

                              <div class="rate-comment">
                                <div class="rate-form">
                                  <div class="rate-input">
                                    <input class="star v-quality" type="radio" name="rating-1" value="1"/>
                                    <input class="star v-quality" type="radio" name="rating-1" value="2"/>
                                    <input class="star v-quality" type="radio" name="rating-1" value="3"/>
                                    <input class="star v-quality" type="radio" name="rating-1" value="4"/>
                                    <input class="star v-quality" type="radio" name="rating-1" value="5"/>
                                  </div>
                                  <div class="rate-input ">
                                    <input class="star v-color" type="radio" name="rating-2" value="1"/>
                                    <input class="star v-color" type="radio" name="rating-2" value="2"/>
                                    <input class="star v-color" type="radio" name="rating-2" value="3"/>
                                    <input class="star v-color" type="radio" name="rating-2" value="4"/>
                                    <input class="star v-color" type="radio" name="rating-2" value="5"/>
                                  </div>
                                  <div class="rate-input ">
                                    <input class="star v-value" type="radio" name="rating-3" value="1"/>
                                    <input class="star v-value" type="radio" name="rating-3" value="2"/>
                                    <input class="star v-value" type="radio" name="rating-3" value="3"/>
                                    <input class="star v-value" type="radio" name="rating-3" value="4"/>
                                    <input class="star v-value" type="radio" name="rating-3" value="5"/>
                                  </div>
                                  <input type="hidden" name="quality" id="v-quality" value="">
                                  <input type="hidden" name="color" id="v-color" value="">
                                  <input type="hidden" name="value" id="v-value" value="">
                                </div>
                                  <div class="check-rate">
                                      Would you recommend this product?<br>
                                      <label class="radio-inline">
                                          <input type="radio"> Yes
                                      </label>
                                      <label class="radio-inline">
                                          <input type="radio"> No
                                      </label>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <h4>Your Info</h4>
                      <div class="row form-group">
                          <label class="col-sm-4">
                              <span class="require">*</span>Review Summary :<br>
                              <span class="text-small">Product features and performance</span>
                          </label>
                          <div class="col-sm-8">
                              <textarea rows="5" name="summary" id="summary" class="form-control" placeholder="Example: the texture of the rug is soft and plusy as well as strong and durable. I often find myself laying on the floor!"></textarea>
                          </div>
                      </div>

                      <div class="row form-group">
                          <label class="col-sm-4"><span class="require">*</span>Your Nickname :</label>
                          <div class="col-sm-8">
                              <input type="text" name="nickname" id="nickname" class="form-control" placeholder="Please do not use your own name use letters and numbers only">
                          </div>
                      </div>

                      <div class="row form-group">
                          <label class="col-sm-4">Your Location :</label>
                          <div class="col-sm-8">
                              <input type="text" name="location" id="location" class="form-control" placeholder="Austin, TX">
                          </div>
                      </div>
                      <div class="row form-group">
                        <div class="pull-right" style="margin-right: 7px;">
                          <a type="button" role="<?php echo $product_id ?>" id="button-review" data-loading-text="Submit..." class="btn btn-danger">Submit</a>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div id="show-review"></div>
                </div>
            </div>
          </div>

        </div>

        <script type="text/javascript">
  $('.v-quality').rating({
    callback: function(value, link){
      // alert(value);
      $('#v-quality').val(value);
    }
  });
  $('.v-color').rating({
    callback: function(value, link){
      // alert(value);
      $('#v-color').val(value);
    }
  });
  $('.v-value').rating({
    callback: function(value, link){
      // alert(value);
      $('#v-value').val(value);
    }
  });

</script>