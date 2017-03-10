<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
<?php
	header('Content-Type: text/html; charset=ISO-8859-1');
	$host=$_REQUEST[host];
	$query=$_REQUEST[query];
	$num_hosts = count($host);
	if (empty($query) || count($host) == 0) 
	{
	  echo '<form method="get">
    		<input type="checkbox"
    		name="host[]" value="bagel.indexdata.dk/gils" />
        	GILS test
    		<input type="checkbox"
    		name="host[]" value="localhost:9999/Default" />
        		local test
    		<input type="checkbox" checked="checked"
    		name="host[]" value="z3950.loc.gov:7090/voyager" />
        		Library of Congress
    		<br />
    		RPN Query:
   		 <input type="text" size="30" name="query" />
    		<input type="submit" name="action" value="Search" />
    		</form>
    		';        
	} 
	else {
    	      	echo 'You searched for ' . htmlspecialchars($query) . '<br />';
    	     	for ($i = 0; $i < $num_hosts; $i++) //numero de colecciones donde se realizo la busqueda
				{
        			$id[] = yaz_connect($host[$i]); //realizar la conexion
		        	yaz_syntax($id[$i], "xml"); //especifica el formato que se prefiere obtener --xml
		        	yaz_range($id[$i], 1, 10);//especifica el numero minimo y maximo de registros que se quiere obtener
	 				yaz_search($id[$i], "rpn", '@attr 1=7 "9971506653"');  /// hace la busqueda, se puede hacer con base al autor(), isbn(7), titulo(4),etc -bib-1    				 	
					//yaz_search($id[$i], "rpn", '@attr 1=4 ' .'"'. $query .'"');// query = Computer Recognition and Human Production of Handwriting
    			}
    			yaz_wait(); // tiempo de espera para obtener los registros
    			for ($i = 0; $i < $num_hosts; $i++)
 				{
        			echo '<hr />' . $host[$i] . ':';
        			$error = yaz_error($id[$i]);
        			if (!empty($error))
					{	
            			echo "Error: $error";
        			}
			 		else {
            			$hits = yaz_hits($id[$i]); //numero de resultados obtenidos por host
            			echo "Result Count $hits"; 
        			}
        			echo '<dl>';
        			for ($p = 1; $p <= $hits; $p++) //recorrer los registros
					{
		            	$rec = yaz_record($id[$i], $p, "xml"); //obtner en un formato especifo el registro en una posicion --siguiendo marc21
            		    if (empty($rec)) continue; /// en caso que el registro este vacio pasar al siguiente
            		    echo "<dt><b>$p</b></dt><dd>"; 
						//echo $rec; //muesra todo el contenido de la consulta, sin respetar salto de linea
            			//echo nl2br($rec); // muestra e contenido del registro respetando salto de linea
						//////mostrar el contenido con base en el valor del atributo tag 
						//http://php.net/simplexmlelement.attributes   
						//http://stackoverflow.com/questions/1256796/how-to-get-the-value-of-an-attribute-from-xml-file-in-php, 
						
						try{								
							$xml = new SimpleXMLElement($rec);
							$xml2 = new SimpleXMLElement($rec);
							//$source = $xml->datafield->attributes()->tag;
							foreach($xml->datafield as $source){
									//$casa = $source->attributes()->tag;
									$tag = $source->attributes()->tag;
									
								    if((string)$tag != '050' && (string)$tag != '245')
										continue;
									//echo $tag." \n ";
									//echo $source->subfield;
									foreach($source->subfield as $source2){
										$code = $source2->attributes()->code;
										//$source2 = $source2->attributes()->;
								   		//echo $code." \n ";
										//$source2 = '50';
										$xml2->datafield = '550500';
										$xml2->asXML($xml2);
										echo $source2;
										}
								
									echo "<br>";	
							}
							
								foreach($xml2->datafield as $source){
									//$casa = $source->attributes()->tag;
									$tag = $source->attributes()->tag;
									
								//    if((string)$tag != '050' && (string)$tag != '245')
								//		continue;
									//echo $tag." \n ";
									//echo $source->subfield;
									foreach($source->subfield as $source2){
										$code = $source2->attributes()->code;
										//$source2 = $source2->attributes()->;
								   		//echo $code." \n ";
										//$source2 = '50';
										//$xml2->$source2 = '50';
										//$xml2->asXML($xml);
										echo $source2;
										}
								
									echo "<br>";	
							}
							
							//$xml->saveXML();
						}
						catch(Exception $e)
						{
							echo "error".$e;
						}
					}
				}
					echo "</dd>";
  	        }
        		echo '</dl>';
    	
?>

<?php
/*$z = yaz_connect("z3950.loc.gov:7090/voyager");
yaz_syntax($z, 'opac');
yaz_search($z, 'rpn', '@attr 1=4 "Computer Recognition and Human Production of Handwriting"');
yaz_wait();
$hits = yaz_hits($z);
yaz_range($z, 1, $hits);
yaz_present($z);
for($i = 1; $i <= $hits; $i++)
       my_display(yaz_record($z, $i, 'xml'));
yaz_close($z);

function my_display($s)
{
  $lines = explode("\n", trim($s));
    var_dump($lines);
}*/
?>

