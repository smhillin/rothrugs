<div class="row">
  <div class="col-sm-12">
    <ul>
        <li>
            You can use the variables below in the tracking URL. They will be replaced at runtime as follow:
            <table>
                <tr>
                    <td><code>{tracking_code}</code> </td><td>The tracking code you enter when you update order status history.</td>
                </tr>
                <tr>
                    <td><code>{shipping_postcode}</code> </td><td>The shipping address zipcode</td>
                </tr>
                <tr>
                    <td><code>{invoice_postcode}</code> </td><td>The invoice addess zipcode</td>
                </tr>
            </table>
        </li>
        <li style="margin-top: 22px;">
            <span class="alert alert-info"> 
                To remove a tracking company, delete the company name and save.
            </span>
        </li>
    </ul>
    
    <div class="form-group">
        <label class="col-sm-2 control-label" style="margin-top:42px;">
            <button class="btn btn-info" id="add-company">Add New tracking company</button>
        </label>
        
        <div class="col-sm-10" id="companies">

            <?php foreach ($powertrack_companies as $company) {?>
            <div class="ce9af">
                <label class="my-label">
                    Company Name: 
                    <input type="text" name="powertrack_company_name[]" value="<?php echo $company['company_name']; ?>" class="form-control my-label__input" placeholder="Company Name" />
                </label>
                <label class="my-label">
                    Tracking Url: 
                    <input type="text" name="powertrack_company_url[]"  value="<?php echo $company['company_url']; ?>"  class="form-control my-label__input" placeholder="Tracking URL" />
                </label>
                
                <input type="hidden"   name="powertrack_company_id[]"   value="<?php echo $company['company_id']; ?>" />

                <!-- hook_8555c -->
                <label class="my-label">
                    Associate with Aftership courier:
                    <select name="powertrack_aftership_slugs[]" class="form-control">
                        <option value=""></option>
                        <?php foreach ($powertrack_aftership_couriers as $powertrack_aftership_courier) { ?>
                        <option value="<?php echo $powertrack_aftership_courier['slug'] ?>" <?php if(isset($company['aftership_slug']) && $company['aftership_slug'] == $powertrack_aftership_courier['slug']) echo 'selected'; ?>><?php echo $powertrack_aftership_courier['name'] ?></option>
                        <?php } ?>
                    </select>
                </label>

            </div>
            <?php } ?>
            
        </div><!-- .col-sm-10 -->
    </div><!-- .form-group -->
    
  </div>
</div>