<?php 
require 'config/config.php';
include("includes/classes/Message.php");
include("includes/classes/User.php");   //not import Post and User in header but in profile and requests

// if user not logged in, go to register.php 
if(isset($_SESSION['username'])) {
	$userLoggedIn = $_SESSION['username'];
	$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
	$user = mysqli_fetch_array($user_details_query);
}
else {
	header("Location: register.php");
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Welcome to Swirlfeed</title>

	<!--Javascript-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
	<script type="text/javascript" src="assets/js/bootstrap.js"></script>
	<script type="text/javascript" src="assets/js/bootbox.min.js"></script>
	<script type="text/javascript" src="assets/js/demo.js"></script>
	<script type="text/javascript" src="assets/js/jquery.jcrop.js"></script>
	<script type="text/javascript" src="assets/js/jcrop_bits.js"></script>

	<!--CSS-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="assets/css/jquery.Jcrop.css">
</head>
<body>

	<div class="top_bar">

		<div class="logo">
			<a href="index.php">Swirlfeed</a>
		</div>

		<nav>
			<?php
				//Unread messages 
				$messages = new Message($con, $userLoggedIn);
				$num_messages = $messages->getUnreadNumber();


			?>

			<a href="#">
				<?php echo $user['first_name']; ?>
			</a>
			<a href="index.php">
				<i class="fa fa-home fa-lg"></i>
			</a>
			<a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'message')">
				<i class="fa fa-envelope fa-lg"></i>
				<?php
				echo '<span class="notification_badge" id="unread_message">' . $num_messages . '</span>';
				?>
			</a>
			<a href="#">
				<i class="fa fa-bell-o fa-lg"></i>
			</a>
			<a href="requests.php">
				<i class="fa fa-users fa-lg"></i>
			</a>
			<a href="#">
				<i class="fa fa-cog fa-lg"></i>
			</a>
			<a href="includes/handlers/logout.php">
				<i class="fa fa-sign-out fa-lg"></i>
			</a>
		</nav>

		<div class="dropdown_data_window" style="height: 0px; border: none;"></div>
		<input type="hidden" id="dropdown_data_type" value="">

	</div>

	<script>
		var userLoggedIn = '<?php echo $userLoggedIn; ?>';

		$(document).ready(function() {

			$('.dropdown_data_window').scroll(function() {  //prevent scroll whole page , replace window
				var inner_height = $('.dropdown_data_window').innerHeight(); // Div contaning data
				var scroll_top = $('.dropdown_data_window').scrollTop();
				var page = $('.dropdown_data_window').find('.nextPageDropdownData').val();
				var noMoreData = $('.dropdown_data_window').find('.noMoreDropdownData').val();

				if((scroll_top + inner_height >= $('.dropdown_data_window')[0].scrollHeight) && noMoreData == 'false'){
					
					var pageName;  //Holds name of page to send ajax
					var type = $('#dropdown_data_type').val();

					if(type == 'notification') {
						pageName = "ajax_load_notifications.php";
					}
					else if(type == 'message') {
						pageName = "ajax_load_messages.php";
					}

					var ajaxReq = $.ajax({
							url: "includes/handlers/" + pageName,
							type: "POST",
							data: "page="+ page +"&userLoggedIn=" + userLoggedIn,
							cache: false,

							success: function(response) {
								$('.dropdown_data_window').find('.nextPageDropdownData').remove(); //remove current nextPage
								$('.dropdown_data_window').find('.noMoreDropdownData').remove(); //remove current  .nextPage

								$('.dropdown_data_window').append(response);
							}
					});
				}

				return false;
			});

		});

	</script>


	<div class="wrapper">