<?php
#*******************************************************************************************#


				#*****************************************#
				#********** CATEGORY INTERFACE ***********#
				#*****************************************#

				/*
					So wie eine Klasse quasi eine Blaupause für alle später aus ihr zu erstellenden Objekte/Instanzen
					darstellt, kann man ein Interface quasi als eine Blaupause für eine später zu erstellende Klasse
					ansehen.	Hierzu wird ein Interface definiert, das später in die entsprechene Klasse implementiert 
					wird. Der Sinn des Interfaces besteht darin, dass innerhalb des Interfaces sämtliche später 
					innerhalb der Klasse zu erstellende Methoden bereits vordeklariert werden.
					Die Klasse muss dann zwingend sämtliche im Interface deklarierten Methoden enthalten.
					
					Ein Interface darf keinerlei Attribute beinhalten.
					Die im Interface definierten Methoden müssen public sein und dürfen über keinen 
					Methodenrumpf {...} verfügen.
					An die Methode zu übergebende Parameter müssen im Interface vordefiniert sein ($value).
				*/

				
#*******************************************************************************************#


				interface CategoryInterface {
								
					/*
						Ein Interface darf keinerlei Attribute beinhalten.
					*/


					#***********************************************************#
					
									
					#*********************************#
					#********** CONSTRUCTOR **********#
					#*********************************#
					
					
					
					
					#***********************************************************#

					
					#*************************************#
					#********** GETTER & SETTER **********#
					#*************************************#
					
					public function getCatID():NULL|int;
					public function setCatID(string|int $value):void;
				
					public function getCatLabel():NULL|string;
					public function setCatLabel(string $value):void;
					
				
					#***********************************************************#
					

					#******************************#
					#********** METHODEN **********#
					#******************************#
					
					public function checkIfExists(PDO $PDO):int ;
					
					public function saveToDb(PDO $PDO):bool;
										
					public static function fetchAllFromDb(PDO $PDO):array ;
					
					
					
					#***********************************************************#
					
				}
				
				
#*******************************************************************************************#
?>