<?php if (!isset($_SESSION['access_token'])):?>
<?php header("Refresh:0");?>
<?php endif;?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Justified Nav Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="https://getbootstrap.com/docs/3.3/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="https://getbootstrap.com/docs/3.3/examples/justified-nav/justified-nav.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
	.grid-item { width: 270px; }
	.grid-item--width2 { width: 400px; }
	</style>
  </head>

  <body>

    <div class="container">

      <!-- The justified navigation menu is meant for single line per list item.
           Multiple lines will require custom code not provided by Bootstrap. -->
      <div class="masthead">
        <h3 class="text-muted">Conatct App</h3>
        <nav>
          <ul class="nav nav-justified">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#">Projects</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Downloads</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contact</a></li>
          </ul>
        </nav>
      </div>

      <!-- Jumbotron -->
      <div class="jumbotron">
        <h1>My Contacts</h1>
      </div>

	<div class="container">
      <!-- Example row of columns -->
      <div class="row grid">
      <?php $contacts_resource = 'https://api.engagor.com/'.$_SESSION['me_accounts']['id'].'/inbox/contacts/?access_token='. $_SESSION['access_token'];
      $contacts_response = dataFactory($contacts_resource);
	  if (!$contacts_response) : ?>
      <div class="col-xs-12">
      <h2>Looks like the server is resting</h2>
      </div>
      <?php else:?>
	  <?php foreach($contacts_response as $contacts_response_item):?>
		  <?php 
          $user_profile_id = $contacts_response_item['id'];
          $user_profile_name = $contacts_response_item['name'];
          $user_profile_img = $contacts_response_item['socialprofiles'] ? $contacts_response_item['socialprofiles'][0]['avatar'] :"";
          $user_profile_tags = $contacts_response_item['tags'] ? $contacts_response_item['tags'] :"";
          ?>
        <div class="col-xs-12 col-md-3 col-lg-3 grid-item" align="center">
         <p class="text-danger"><img src="<?php echo $user_profile_img ;?>" width="100px" class="img-circle" /></p>
          <h4><?php echo $user_profile_name;?></h4>
          <p>
          <a class="btn btn-primary" role="button" onClick="showDetail('<?php echo $user_profile_name;?>','<?php echo $user_profile_img;?>');">View</a>
          <a class="btn btn-default" role="button" onClick="showAddTags('<?php echo $user_profile_name;?>', '<?php echo $user_profile_id;?>');">Add Tags &raquo;</a>
          <?php if($user_profile_tags):?>
          <?php echo "<br/><br/>Tags: ";?>
          <?php foreach($user_profile_tags as $user_profile_tags_item):?>
          <?php echo "<span class=\"label label-default\">".$user_profile_tags_item."</span> ";?>
          <?php endforeach;?>
          <?php endif;?>
          <hr/></p>
        </div>
        <?php endforeach;?>
        </div>
        
        <?php endif; /**/?>
        

      <!-- Site footer -->
      <footer class="footer">
        <p>&copy; 2018 Conatct App, Inc.</p>
      </footer>
      
      <!-- Modal -->
<div id="profileDetailsModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span id="modalHeader">Modal Header</span></h4>
      </div>
      <div class="modal-body">
        <p id="modalBody" align="center">Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

    </div> <!-- /container -->


	<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="https://getbootstrap.com/docs/3.3/assets/js/ie10-viewport-bug-workaround.js"></script>
     <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="https://getbootstrap.com/docs/3.3/assets/js/ie-emulation-modes-warning.js"></script>
    <script src="resources/js/app.js"></script>
   
     <script>
	function showDetail(title, img_url) {
		$("#modalHeader").text(title);
		$("#modalBody").html("<img src=\""+img_url+"\" class=\"img-circle\" width=\"200\" />");	
		showModal();	
	}
	
	function showAddTags(title, contactProfileID) {
		$("#modalHeader").text("Add New Tag to "+title);
		$("#modalBody").html("<form method=\"POST\" action=\"<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>\">"+
		"<input class=\"form-control\" name=\"tag\" placeholder=\"Your tag\" />"+
		"<br/><input type=\"hidden\" class=\"form-control\" name=\"contactID\" value=\""+contactProfileID+"\" />"+
		"<input type=\"submit\" class=\"btn btn-success\" value=\"Add new tag\" />"+
		"</form>");	
		showModal();	
	}
	
	function showModal() {
		$("#profileDetailsModal").modal();
	}
	</script>
    
    <script>
	  $('.grid').masonry({
	  // options
	  itemSelector: '.grid-item',
	  columnWidth: 0
	  });
	</script>
   
  </body>
</html>
