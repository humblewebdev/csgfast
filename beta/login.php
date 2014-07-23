<?php
/*
* Last updated: 1/9/2014
* Implemented remember me functionality
*/
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>CSG FAST - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/stylesheet.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- Le fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/apple-touch-icon-144-precomposed.html">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/apple-touch-icon-114-precomposed.html">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/apple-touch-icon-72-precomposed.html">
                    <link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-57-precomposed.html">
                                   <link rel="shortcut icon" href="img/favicon.png">
  </head>

  <body class="dark-login">
    <div class="alert alert-error" id="browserWarning" style="display: none;"><i class="icon-warning-sign"></i>&nbsp;<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>Browser is not Supported! Use of this site within this outdated browser is not supported!</div>
    <div style="top: 20px; left: 20px; right: 20px; z-index: 99999 !important; position: absolute;" id="notify_msg"></div>
    <div class="login-container">
	    
        <div class="login-header bordered">
			<img src="img/farmers_login_img.png"/>
        </div>
		
        <form id="login_form" name="login_form" enctype="multipart/form-data">
            <div class="login-field">
                <label for="username">Email <strong>or</strong> Agent Code</label>
				<div class="field">
						<!-- Attempt to add in remember me functionality --> 
					<input type="text" name="username" class="required" id="username"
					<?php 
						
						if(isset($_SESSION['remember'])){ 
							echo "value='".$_SESSION['remember']."'"; 
						}else{ 
							echo "placeholder='your@email.com or 1a-2b-3c'"; 
						}
					?> > <!-- end of input type --> 

					<i class="icon-user"></i>
				</div>
            </div>
            <div class="login-field">
                <label for="password">Password</label>
				<div class="field">
					<input type="password" name="pwd" id="password" class="required" placeholder="Password">
					<i class="icon-lock"></i>
				</div>
            </div>
            <div class="login-button clearfix" id="login-button">
                <label class="checkbox pull-left">
                    <input type="checkbox" class="uniform" name="remember" checked> Remember me <!-- added in default value checked -->
                </label>
                <div class="pull-right btn btn-large green" id="doSignIn">SIGN IN <i class="icon-arrow-right"></i></div>
            </div>
			
			<hr></hr>
            <div class="forgot-password">
			    <a href="reg_wizard.php" class="pull-right btn btn-large blue">Register</a>
                <a href="#forgot-pw" role="button" data-toggle="modal" class="pull-left btn btn-large blue">Forgot password?</a>
            </div>	
        </form>
		
    </div>

<!----------------------------  FORGOT PASSWORD RESET POPUP ----------------------------------------------->	
	
    <div id="forgot-pw" class="modal hide fade" tabindex="-1" data-width="760">
        
		<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
            <h3>Forgot your password?</h3>
        </div>
        <div class="modal-body">
            <div class="row-fluid">
                <div class="span12">
                    <div class="form_row">
					<form id="pwd_reset_form" name="login_form" enctype="multipart/form-data">
                        <label class="field_name">Email address</label>
                        <div class="field">
                            <div class="row-fluid">
                                <div class="span8">
                                    <input type="text" class="required span12" id="email_reset" name="email_reset" placeholder="example@domain.com">
                                </div>
                                <div class="span4 reset_button">
                                    <a href="#" class="btn btn-block blue" id="doPwdReset">Reset password</a>
                                </div>
                            </div>
                        </div>
					</form>
                    </div>
                </div>
            </div>
        </div>
		<div id="notify_msg_modal" style="padding: 15px;"></div>
    </div>

<!-- END OF FORGOT PASSWORD RESET POPUP -->	
	
<!--PASSWORD UPDATE POPUP -->	
	<div id="reset-pw" class="modal hide fade" tabindex="-1" data-width="760"  data-backdrop="static">
        
		<div class="modal-header">
            <h3><i class="icon-lock"></i>&nbsp;Password Update</h3>
        </div>
        <div class="modal-body">
            <div class="row-fluid">
                <div class="span12">
                    <div class="form_row">
					<form id="pwd_update_form" name="login_form" enctype="multipart/form-data">
                        <label class="field_name">Email</label>
                        <div class="field">
                            <div class="row-fluid">
                                <input type="text" style="display: none !important;" class="span12 required" name="email_confirm" id="email_confirm">
								<div id="email_confirm_text"></div>
                            </div>
                        </div>
                        <br>
						<label class="field_name">New Password</label>
                        <div class="field">
						    
							<div class="row-fluid">
								<div class="span8">
										<input type="password" id="newpwd" class="span12" name="pwd_confirm">
								</div>
							</div>
						
                        </div>
						<br>
						<label class="field_name">Confirm</label>
                        <div class="field">
						    
							<div class="row-fluid">
								<div class="span8">
										<input type="password" id="conpwd" class="span12" name="pwd_confirm2">
								</div>
								<div class="span4 update_button">
										<a href="#" class="btn btn-block blue" id="doPwdUpdate">Update & Login</a>
								</div>
							</div>
						
                        </div>
					</form>
                    </div>
                </div>
            </div>
        </div>
		<div id="notify_msg_modal2" style="padding: 15px;"></div>
    </div>
	
<!---------------------------- END OF PASSWORD UPDATE POPUP ---------------------------------------------->	

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/jquery-ui-1.10.3.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/library/jquery.collapsible.min.js"></script>
    <script src="js/library/jquery.mCustomScrollbar.min.js"></script>
    <script src="js/library/jquery.mousewheel.min.js"></script>
    <script src="js/library/jquery.uniform.min.js"></script>
    <script src="js/library/jquery.sparkline.min.js"></script>
    <script src="js/library/chosen.jquery.min.js"></script>
    <script src="js/library/jquery.easytabs.js"></script>
    <script src="js/library/flot/excanvas.min.js"></script>
    <script src="js/library/flot/jquery.flot.js"></script>
    <script src="js/library/flot/jquery.flot.pie.js"></script>
    <script src="js/library/flot/jquery.flot.selection.js"></script>
    <script src="js/library/flot/jquery.flot.resize.js"></script>
    <script src="js/library/flot/jquery.flot.orderBars.js"></script>
	<script src="js/library/jquery.autosize-min.js"></script>
    <script src="js/library/charCount.js"></script>
    <script src="js/library/jquery.minicolors.js"></script>
    <script src="js/library/jquery.tagsinput.js"></script>
    <script src="js/library/footable/footable.js"></script>
    <script src="js/library/footable/data-generator.js"></script>
    <script src="js/library/jquery.inputmask.bundle.js"></script>
	<script src="js/library/jquery.validate.js"></script>
	<script src="js/login_form_ajax_submit.js"></script>
    <script src="js/library/jquery.backstretch.min.js"></script>
	<script src="js/library/jquery.reject.js"></script>
	
    <script>
        jQuery(document).ready(function($) {
            $('.uniform').uniform();
        });
    </script>

  </body>
</html>
