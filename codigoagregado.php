<HTML>
	<HEAD>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="STYLESHEET" type="text/css" href="stylesheet.css"> 
		<TITLE>Busqueda de libro</TITLE>
	</HEAD>
	<BODY>
		<H1>Categorizacion de libros</H1>
		<?php
		
		echo "<form action='' method='post'>";
			echo "<p class = 'dos' > Autor(es): ".$_POST['autor2']."</p>";
			echo "<p class = 'dos' >  Título: ".htmlspecialchars($_POST['titulo2'])."</p>";
			echo "<p class = 'dos' >  Editorial: ".$_POST['editorial']."</p>";
    		echo "<p class = 'dos' >  Fecha de publicación: ".$_POST['fechapublicaicon'] ."</p>";
			//echo "<p class = 'dos' >  Código: <imput type = 'text' name = 'codigo' value = ".$codigo."/></p><br>";
			echo "<p class = 'dos' >  Código: ".$_POST['codigo2']."</p>";
		?>
</BODY>
</HTML> 



