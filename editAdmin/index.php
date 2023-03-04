<?php
	if(isset($_COOKIE['email']) && isset($_COOKIE['password'])){
		if($_COOKIE['role'] == "owner"){
			$connect = new mysqli('localhost','root','','pps_impex') or die('Connection Failed: '.$connect->connect_error); 
			$stmt = $connect->prepare("select name,role from user where role = ? || role = ?");
			$value1 = "user";
			$value2 = "admin";
			$stmt->bind_param("ss",$value1,$value2);
			$stmt->execute();
			$stmt_result = $stmt->get_result();
			while($row = $stmt_result->fetch_assoc()){
				$data[] = $row;
			}
			if(!empty($data)){
				$adminRole= [];
				$userRole = [];
				for($i = 0; $i < count($data); $i++) {
					if($data[$i]['role']=="admin"){
						array_push($adminRole,$data[$i]['name']);
					} else {
						array_push($userRole,$data[$i]['name']);
					}
				}
				$stmt->close();
				$connect->close();
			}
		}
		elseif($_COOKIE['role'] == "admin"){
			header('Location: ../admin_home.php');
		}
		else{
			header('Location: ../index.php');
		}
	}
	else{
		header('Location: ../index.php');
	}
?>
<!doctype html>
<html lang="en">
  <head>
  	<title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" href="css/style.css" >
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<title>PPS IMPEX Edit-Admin</title>
    <link rel="icon" type="icon" href="jkjk.ico">
	</head>
	<body style="background-color: #FFFFF0;">
		<div id="xyz" style="display:none;">
			<?php
			for($i = 0; $i < count($adminRole); $i++) {
				echo "<div id='admin$i'>$adminRole[$i]</div>";
			}
			
			for($i = 0; $i < count($userRole); $i++) {
				echo "<div id='user$i'>$userRole[$i]</div>";
			}
			?>
		</div>
		<section class="ftco-section">
        <form action="new.php" method="post">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-md-6 text-center mb-5">
						<h2 class="heading-section"><?php if(isset($_GET['set'])){echo 'Updated Admin';}
						else{echo "Edit Admin";}?></h2>
					</div>
					
				</div>
				<div class="row justify-content-center">
					<div class="col-md-5 d-flex justify-content-center align-items-center">
						<div class="dropdown-container">
						<div class="dropdown-button noselect w-100">
						<div class="dropdown-label">Add or Remove Admin</div>
						<div class="dropdown-quantity">(<span class="quantity">Any</span>)</div>
						<i class="fa fa-chevron-down"></i>
						</div>
						<div class="dropdown-list" style="display: none;">

						<input type="search" placeholder="Search" class="dropdown-search"/>
						<ul></ul>
						</div>
						</div>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col-md-6 text-center mb-5 mt-4 ">
					
							<input type="submit" class="btn btn-primary" name="btn1" value="submit">
					</div>
				</div>
			</div>
        </form>
		</section>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/3.5.0/lodash.min.js"></script>
  <script>
	(function($) {

		"use strict";


		// Events
		$('.dropdown-container')
		.on('click', '.dropdown-button', function() {
			$(this).siblings('.dropdown-list').toggle();
		})
		.on('input', '.dropdown-search', function() {
			var target = $(this);
			var dropdownList = target.closest('.dropdown-list');
			var search = target.val().toLowerCase();

			if (!search) {
				dropdownList.find('li').show();
				return false;
			}

			dropdownList.find('li').each(function() {
				var text = $(this).text().toLowerCase();
				var match = text.indexOf(search) > -1;
				$(this).toggle(match);
			});
		})
		.on('change', '[type="checkbox"]', function() {
			var container = $(this).closest('.dropdown-container');
			var numChecked = container. find('[type="checkbox"]:checked').length;
			container.find('.quantity').text(numChecked || 'Any');
		});
		// JSON of States for demo purposes
		var admin = [];
		var users = [];

		for(var i =0; i< parseInt("<?php echo count($userRole); ?>"); i++) {
			var mystr = document.getElementById("user"+i).innerHTML+"";
			admin.push({name: mystr}); 
		}

	
		for(var i =0; i< parseInt("<?php echo count($adminRole); ?>"); i++) {
			var mystr = document.getElementById("admin"+i).innerHTML+"";
			users.push({name: mystr}); 
		}
		
		// <li> template
		var stateTemplate = _.template(
		'<li>' +
			'<label class="checkbox-wrap"><input class="cbox" name="<%= name %>" type="checkbox" checked> <span for="<%= name %>"><%= capName %></span> <span class="checkmark"></span></label>' +
			// '<label for="<%= abbreviation %>"><%= capName %></label>' +
		'</li>'
		);

		var stateTemplates = _.template(
		'<li>' +
			'<label class="checkbox-wrap"><input class="cbox" name="<%= name %>" type="checkbox"> <span for="<%= name %>"><%= capName %></span> <span class="checkmark"></span></label>' +
			// '<label for="<%= abbreviation %>"><%= capName %></label>' +
		'</li>'
		);

		_.each(users, function(s) {
		s.capName = _.startCase(s.name.toLowerCase());
		$('ul').append(stateTemplate(s));
		});

		_.each(admin, function(s) {
		s.capName = _.startCase(s.name.toLowerCase());
		$('ul').append(stateTemplates(s));
		});

		
		
		})(jQuery);

  </script>
  	</body>
</html>