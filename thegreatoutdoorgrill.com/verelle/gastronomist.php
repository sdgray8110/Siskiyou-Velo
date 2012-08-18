<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>

		<title>
			Verelle Stuck is a gastronomist 
		</title>
		
		<meta name="keywords" content =", , , " />
		<meta name="discription" content=" " />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		
		<link rel="stylesheet" type="text/css" href="default.css" />
		
		<script type="text/javascript" src="js/jquery.js"></script>

		<style type="text/css">
		body	{background-image: url('images/background_oystergray_small.png'); background-repeat:repeat;}
		
		</style>				
	</head>

	<body>
		<div id="gastronomistpage">
			<div class="home">
			<a href="index.php"><img src="images/verelle_lovesfood.gif" border="none" alt="verelle stuck food"/></a>
			</div>
			<ul class="loves">
				<li><a href="design.php"><img src="images/navigation_design.gif" border="none" alt="verelle loves design"/></a></li>
				<li><a href="gastronomist.php"><img src="images/navigation_food.gif" border="none" alt="verelle loves food"/></a></li>
				<li><a href="penpal.php"><img src="images/navigation_contact.gif" border="none" alt="verelle loves you"/></a></li>
				<li><a href="http://verelle.blogspot.com/" onclick="window.open(this.href,'newwin'); return false;"><img src="images/navigation_blog.gif" border="none" alt="verlle loves blogging"/></a></li>
			</ul>
			<?php include("recipe.php"); ?>
			<div class="food">
				<img src="images/recipe_template.gif"  alt="recipe name">
			</div>
			<ul class="arrows">	
				<li><a href="recipe_image.php"><img src="images/arrow1.gif" border="none" alt="recipe image(s)"/></a></li>
			</ul>
		</div>
	</body>	
</html>