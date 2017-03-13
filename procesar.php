<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 


<?php
	header('Content-Type: text/html; charset=ISO-8859-1');
	$host= $_POST['host'];
	$query=	htmlspecialchars($_POST['titulo']);
	$num_hosts = count($host);

    	      	echo 'You searched for ' . htmlspecialchars($query) . '<br />';
				echo 'En el servidor ' . htmlspecialchars($host) . '<br />';
				echo 'numero de servidores  ' .$num_hosts. '<br />';
				echo '@attr 1=4 ' .'"'. $query .'"'. '<br />';
    	   //  	for ($i = 0; $i < $num_hosts; $i++) //numero de colecciones donde se realizo la busqueda
		///		{
					echo $host."aasdsafasf";
        			$id = yaz_connect($host); //realizar la conexion
		        	yaz_syntax($id, "xml"); //especifica el formato que se prefiere obtener --xml
		        	yaz_range($id, 1, 10);//especifica el numero minimo y maximo de registros que se quiere obtener
	 				//yaz_search($id[$i], "rpn", '@attr 1=7 "9971506653"');  /// hace la busqueda, se puede hacer con base al autor(), isbn(7), titulo(4),etc -bib-1    				 						//yaz_search($id[$i], "rpn", '@attr 1=4 ' .'"'. $query .'"');// query = Computer Recognition and Human Production of Handwriting
					yaz_search($id, "rpn", '@attr 1=4 ' .'"'. $query .'"'); 
    			//}
    			yaz_wait(); // tiempo de espera para obtener los registros
    			for ($i = 0; $i < $num_hosts; $i++)
 				{
        			echo '<hr />' . $host . ':';
        			$error = yaz_error($id);
        			if (!empty($error))
					{	
            			echo "Error: $error";
        			}
			 		else {
            			$hits = yaz_hits($id); //numero de resultados obtenidos por host
            			echo "Result Count $hits"; 
        			}
        			echo '<dl>';
        			for ($p = 1; $p <= $hits; $p++) //recorrer los registros
					{
						$pos = 0;
						
		            	$rec = yaz_record($id, $p, "xml"); //obtner en un formato especifo el registro en una posicion --siguiendo marc21
            		    if (empty($rec)) continue; /// en caso que el registro este vacio pasar al siguiente
            		    echo "<dt><b>$p</b></dt><dd>"; 
						//echo $rec; //muesra todo el contenido de la consulta, sin respetar salto de linea
            			//echo nl2br($rec); // muestra e contenido del registro respetando salto de linea
						//////mostrar el contenido con base en el valor del atributo tag 
						//http://php.net/simplexmlelement.attributes   
						//http://stackoverflow.com/questions/1256796/how-to-get-the-value-of-an-attribute-from-xml-file-in-php, 				
						try{						
							$xml = new SimpleXMLElement($rec);
											echo $xml;	
							foreach($xml->datafield as $source){
									$pos++;
									
									$tag = $source->attributes()->tag;							
								    if((string)$tag != '050' && (string)$tag != '245')
										continue;
									echo $tag." \n ";
									$pos2 = 0;
									foreach($source->subfield as $source2){
										$pos2++;
										$code = $source2->attributes()->code;
									    echo $source2;
										//foreach($source2->attributes() as  $a => $b) esto es para que se vea elnombre y el valor del atributo
										
									///$xml3=simplexml_load_file("books.xml") or die("Error: Cannot create object");
									
										
										
											//echo $a,'="',$b<br>";	
										if((string)$code=='a')
										{
											echo "esto es lo nuevo   ";
											//$xml2=$xml->asXML();
											echo $xml->datafield[$pos+1]['tag']. "<br>";
											echo $xml->datafield[$pos+1]->subfield['code'];
											echo "  dentro del if vale: ".$pos."  ";
											//////////$xml->datafield->subfield = '--  -- -- ashdlkhasdfklalsdflkad -- -- --';							
											//$itemsList = $xml->xpath('//datafield[@tag = "050"]');
											
											$xml->datafield[$pos-1]->subfield[$pos2-1]=' espero que funcione';
												
											$xml2=$xml->asXML();
											
											/*foreach($gdNodes->phoneNumber as $key => $phone)
											{
    														$xml->subfield[$source2] = '1234567';
											}*/
											
								/*			$resultado = $xml->xpath('datafield[@tag="050"]/subfield/@code');
											while(list( , $nodo) = each($resultado)) {
   												 echo '/a/b/c: ',$nodo,"\n";
											}
											
											foreach($xml->xpath('/record/datafield') as $t)
											{ 
												echo " listo ";
											    echo $t;
											}
										
											$itemlist2 = $itemList[0]->subfield;
											echo " el valor es: ".$itemList2[0]." -- ";
											//$source2 = ' hay que cambiar el valor';
								*/						
										}
  // $itemsList[0]->age->attributes()->years = 26;  -http://stackoverflow.com/questions/11103445/access-and-update-attribute-value-in-xml-using-xpath-and-php
									}					
									echo "<br>";
							}
			
							echo nl2br($xml2);
						}
						catch(Exception $e)
						{
							echo "error".$e;
						}
					}
				}
				yaz_close($id);
					echo "</dd>";
  	        
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

