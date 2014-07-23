<?php 
/*****************************************************
* Author: Amber Bryson
* Date: 1/19/2014
* List of fast products offered to the farmers agents
* and allows the admin to update the description.
*
*****************************************************/


/*---------------- Query the database to get the product information ----------------*/
$product_qry = "SELECT * FROM fast_products";
$product_result = $mysqli->query($product_qry);

?>

<div class="row-fluid">
	<div class="span12">
		<div class="well <?php echo $info_ui_color; ?>">
			<div class="well-header">
				<h5>CSGFast Products List</h5>
			</div>	
			<div class="well-content no-search">

				<?php while($row = $product_result->fetch_assoc()){ ?>				
				
					<div class="panel-group" id="accordion<?php echo $row['product_id']; ?>">
						<div class="accordion-group">
							<div class="accordion-heading">
							  <h4 class="panel-title">
								<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion<?php echo $row['product_id']; ?>" href="#collapse<?php echo $row['product_id']; ?>">
								  <?php echo $row['product_name'];?>
								</a>
							  </h4>
							</div>
							<div id="collapse<?php echo $row['product_id']; ?>" class="accordion-body collapse">
							  <div class="accordion-inner">
								<div class="row-fluid">
									<div class="span12">
										<div class="row-fluid">
											<form id="productlistform<?php echo $row['product_id'];?>" name="productlistform<?php echo $row['product_id'];?>" enctype="multipart/form-data">
											<div class="span6">
												<textarea class="textarea" name="product_short_desc" placeholder="<?php echo $row['product_short_desc']; ?>" style="width: 600px; height: 300px;"></textarea>
											</div>
											<div class="span6"> 
												<div class="form_row">
												<?php	$email_confirm_qry = "SELECT * FROM email_templates";
														$email_confirm_result = $mysqli->query($email_confirm_qry);
												?>
												<label class="field_name align_left"><strong>Confirmation Email</strong></label>
												<select id="econfirm<?php echo $row['product_id']; ?>" name="confirm_id" type="text" >
													<?php while($email_confirm_row = $email_confirm_result->fetch_assoc()){?>
													<option value="<?php echo $email_confirm_row['id_email_template']; ?>"  <?php if($row['confirm_id'] == $email_confirm_row['id_email_template']){echo "selected";}?>><?php echo $email_confirm_row['id_email_template']. " - " . $email_confirm_row['template_title']; ?></option>
													<?php } ?>
												</select>
												</div>
												
												<div class="form_row">
												<?php	$email_approval_qry = "SELECT * FROM email_templates";
														$email_approval_result = $mysqli->query($email_approval_qry);
												?>
												<label class="field_name align_left"><strong>Approval Email</strong></label>
												<select id="eapprove<?php echo $row['product_id']; ?>" name="approval_id" type="text" >
													<?php while($email_approval_row = $email_approval_result->fetch_assoc()){?>
													<option value="<?php echo $email_approval_row['id_email_template']; ?>" <?php if($row['approve_id'] == $email_approval_row['id_email_template']){echo "selected";}?>><?php echo $email_approval_row['id_email_template']. " - " . $email_approval_row['template_title']; ?></option>
													<?php } ?>
												</select>
												</div>
												
											</div>
											</form>
										</div>
										<br>
										<a href="#" class="btn admin_update_product btn-block" formid="productlistform<?php echo $row['product_id'];?>" prodid="<?php echo $row['product_id'];?>"><i class="icon-share"></i>Update</a>
									</div>
								</div>
							  </div>
							</div>
						</div>
					</div><!-- end of panel-group accordion -->
				<?php } ?><!-- end of product fetch whileloop -->
			</div> <!-- Well content -->
		</div> <!-- Well defined -->
	</div> <!-- span 12 -->
</div> <!-- row fluid -->

<!-------------------------------- Java Script ------------------------------------->
<script src="js/jquery-1.10.2.js"></script>