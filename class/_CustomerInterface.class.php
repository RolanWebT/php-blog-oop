<?php
#*******************************************************************************************#


				#*****************************************#
				#********** CUSTOMER INTERFACE ***********#
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


				interface CustomerInterface {
								
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
					
					public function getCusID():NULL|int;
					public function setCusID(string|int $value):void;
				
					public function getCusFirstName():NULL|string;
					public function setCusFirstName(string $value):void;
					
					public function getCusLastName():NULL|string;
					public function setCusLastName(string $value):void;
					
					public function getCusEmail():NULL|string;
					public function setCusEmail(string $value):void;
					
					public function getCusBirthdate():NULL|string;
					public function setCusBirthdate(string $value):void;
					
					public function getCusCity():NULL|string;
					public function setCusCity(string $value):void;
					
		
					#********** VIRTUAL ATTRIBUTES **********#
					public function getFullName():string;
					
					
					#***********************************************************#
					

					#******************************#
					#********** METHODEN **********#
					#******************************#
					
					public function saveToDb(PDO $PDO);
					
					//public function fetchFromDb(PDO $PDO);
					
					public static function fetchAllFromDb(PDO $PDO);
					
					//public function updateToDb(PDO $PDO);
					
					public function deleteFromDb(PDO $PDO);
					
					public function checkIfEmailExists(PDO $PDO);				
					
					
					#***********************************************************#
					
				}
				
				
#*******************************************************************************************#
?>