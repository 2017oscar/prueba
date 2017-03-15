<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 


<?php
	header('Content-Type: text/html; charset=UTF-8');
	$host= "z3950.loc.gov:7090/voyager";
	//$query=	htmlspecialchars($_POST['titulo']);
	//$query=	"Biologia";
	$num_hosts = count($host);
	//echo $query;
	//echo '@attr 1='.$val.'"'. $query .'"';
	$id = yaz_connect($host); //realizar la conexion
	yaz_syntax($id, "xml"); //especifica el formato que se prefiere obtener --xml
	yaz_range($id, 1, 10);//especifica el numero minimo y maximo de registros que se quiere obtener
	//yaz_search($id[$i], "rpn", '@attr 1=7 "9971506653"');  /// hace la busqueda, se puede hacer con base al autor(), isbn(7), titulo(4),etc -bib-1    				 	 //yaz_search($id[$i], "rpn", '@attr 1=4 ' .'"'. $query .'"');// query = Computer Recognition and Human Production of Handwriting
	if(!empty($_POST['titulo']))
	{
		$query=	htmlspecialchars($_POST['titulo']);
		$val = 4;
		//echo '@attr 1='.$val.' "'. $query .'"';
	}
	else if (!empty($_POST['isbn']))
	{
		$query=	htmlspecialchars($_POST['isbn']);
		$val = 7;
		//echo '@attr 1='.$val.' "'. $query .'"';
	}
	yaz_search($id, "rpn", '@attr 1='.$val.' "'. $query .'"'); 
    yaz_wait(); // tiempo de espera para obtener los registros
    
	$error = yaz_error($id);
	$titulo = "";
	$autor="no definido";
	$codigo = "sin definir";
	$distribuidior = "";
    $fpublicacion = "";
		if (!empty($error))
		echo "Error: $error";
    else 
	{
    	$hits = yaz_hits($id); //numero de resultados obtenidos por host
       // echo "Result Count $hits"; 
        echo '<dl>';
	
        for ($p = 1; $p <= $hits; $p++) //recorrer los registros
		{
			$pos = 0;
			$rec = yaz_record($id, $p, "xml"); //obtner en un formato especifo el registro en una posicion --siguiendo marc21
            if (empty($rec)) 
				continue; /// en caso que el registro este vacio pasar al siguiente
            //echo "<dt><b>$p</b></dt><dd>"; 
			//echo $rec; //muesra todo el contenido de la consulta, sin respetar salto de linea
            //echo nl2br($rec); // muestra e contenido del registro respetando salto de linea
			//////mostrar el contenido con base en el valor del atributo tag 
			//http://php.net/simplexmlelement.attributes   
			//http://stackoverflow.com/questions/1256796/how-to-get-the-value-of-an-attribute-from-xml-file-in-php, 				
			try
			{						
				$xml = new SimpleXMLElement($rec);
				echo $xml;	
				foreach($xml->datafield as $source)
				{
					$pos++;
					$tag = $source->attributes()->tag;							
				
					if((string)$tag != '050' && (string)$tag != '245' && (string)$tag != '260')
						continue;
					//echo $tag."<br>"; //muestra el valor del atributo tag
					$pos2 = 0;
					
					foreach($source->subfield as $source2)
					{
						$pos2++;
						$code = $source2->attributes()->code;
						//echo $code."<br>"; //muestra el valor del atributo codigo
						//echo $source2."<br>"; //muestra el valor 
						
						if((string)$tag == '050')
						{
							if((string)$code == 'a')
								$codigo= $source2;
							if((string)$code == 'b')
								$codigo = $codigo.$source2;
						}
						
						if((string)$tag == '245')
						{
							
							if((string)$code == 'a')
								$titulo= $source2;
							if((string)$code == 'b')
								$titulo = $titulo.$source2;
							if((string)$code == 'c')
								$autor= $source2;
						}
						
						if((string)$tag == '260')
						{
							if((string)$code == 'b')
								$distribuidior = $source2;
							if((string)$code == 'c')
								$fpublicacion= $source2;
						}
						
					
						/*if((string)$code=='a') ////esto es para cambiar el valor en xml
							
						{
							//echo $xml->datafield[$pos+1]['tag']. "<br>"; //muestra el valor que tiene el atributo tag de la categoria datafield
							//echo $xml->datafield[$pos+1]->subfield['code'];//muestra el valor que tiene el atributo code en la subcategoria subfield
							//echo "  dentro del if vale: ".$pos."  ";
							$xml->datafield[$pos-1]->subfield[$pos2-1]=' espero que funcione'; //cambia el valor de un subcategoria de xml
							$xml2=$xml->asXML(); //se guarda el xml con las modificaciones
						}*/
 						 // $itemsList[0]->age->attributes()->years = 26;  // cambiar el valor de un atributo-http://stackoverflow.com/questions/11103445/access-and-update-attribute-value-in-xml-using-xpath-and-php
					}					
					//echo "<br>";
				}
				//echo nl2br($xml2); //mostrar el xml
			}
			catch(Exception $e)
			{
				echo "error".$e;
			}
		$autor = preg_replace("/editors, /","", $autor) ;
		$titulo = preg_replace('[/]',"", $titulo) ;	
		$distribuidior = preg_replace('/,/',"", $distribuidior) ;	
		//$fpublicacion = preg_replace(".","", $fpublicacion) ;	
			//echo preg_replace($patrones, $sustituciones, $cadena);
			
		echo"<HEAD>";
			echo "<link rel='STYLESHEET' type='text/css' href='stylesheet.css'> ";
		echo"<TITLE>Categorizacion de libros</TITLE>";
		echo"</HEAD>";
		echo"<BODY>";
		echo"<H1>Asignacion de codigo</H1>";
			
		echo "<form action='codigoagregado.php' method='post'>";
			echo "<p class = 'dos' > Autor(es): ".$autor."</p>";
			echo "<p class = 'dos' >  Título: ".$titulo."</p>";
			echo "<p class = 'dos' >  Editorial: ".$distribuidior."</p>";
    		echo "<p class = 'dos' >  Fecha de publicación: ".$fpublicacion ."</p>";
			//echo "<p class = 'dos' >  Código: <imput type = 'text' name = 'codigo' value = ".$codigo."/></p><br>";
			echo "<p class = 'dos' >  Código: <input type='text' name ='codigo2' value= '".$codigo."'>";
			echo " <input type='hidden' name ='autor2' value= '".$autor."'>";
			echo " <input type='hidden' name ='titulo2' value= '".$titulo."'>";
			echo " <input type='hidden' name ='editorial' value= '".$distribuidior."'>";
			echo " <input type='hidden' name ='fechapublicaicon' value= '".$fpublicacion."'>";
			echo "<p><input type='submit' value='agregar' /></p>";
		echo"</BODY>";

			
	/*	$titulo = "";
		$autor="no definido";
		$codigo = "sin definir";
		$distribuidior = "";
    	$fpublicacion = "";*/
		}
	}
	yaz_close($id);
	echo "</dd>";

  	echo '</dl>';



?>
