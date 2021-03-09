<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">

		<!--Definition de la base du site-->
		<base href="/">

		<?php

        use basics\Session;

        if (isset($head) && !empty($head))
			{
				echo $head;
			}
			else
			{
				echo "<title>#Title</title>";
			}
		?>

		<link rel="stylesheet" type="text/css" href="res/styles/style.css<?php echo "?i=".uniqid(); ?>">
        <link rel="stylesheet" type="text/css" href="res/styles/libs/jquery-ui.min.css">
        <script src="https://kit.fontawesome.com/6064821b21.js"></script> <!--Pour Font Awesome-->
		<script src="res/scripts/libs/jquery.js"></script>
        <script src="res/scripts/libs/jquery-keyframes.js"></script>
        <!--<script src="res/scripts/libs/rellax.js"></script>-->
        <!--<script src="res/scripts/libs/responsiveMap.js"></script>-->
        <script src="res/scripts/libs/jquery-ui.min.js"></script>
        <script src="res/scripts/modules/ajax.js"></script>
        <script src="res/scripts/modules/forms.js"></script>
        <script src="res/scripts/libs/utils.js"></script>
	</head>
	<body>
		<main id="main">
			<div id="errors">
                <?php
                $errors = Session::getAlerts()["errors"];
                foreach ($errors as $error)
                {
                    echo "<div class='alert alert-error'>$error</div><br/>";
                }
                ?>
			</div>
			<div id="success">
                <?php
                $success = Session::getAlerts()["success"];
                foreach ($success as $succes)
                {
                    echo "<div class='alert alert-success'>$succes</div><br/>";
                }
                Session::clearAlerts();
                ?>
			</div>
            <?php
            	echo $content;
            ?>
		</main>
	</body>

    <!-- Fading out when click on alerts -->
    <script>
        $(".alert").click(function (ev) {
            $(ev.target).fadeOut(400, function () {
                $(ev.target).remove();
            })
        });
    </script>
</html>
