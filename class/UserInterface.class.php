<?php
#*******************************************************************************************#


				#*****************************************#
				#********** USER INTERFACE ***********#
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


				interface UserInterface {
								
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
					
					public function getUserID():NULL|int;
					public function setUserID(string|int $value):void;
				
					public function getUserFirstName():NULL|string;
					public function setUserFirstName(string $value):void;
					
					public function getUserLastName():NULL|string;
					public function setUserLastName(string $value):void;
					
					public function getUserEmail():NULL|string;
					public function setUserEmail(string $value):void;
					
					public function getUserPassword():NULL|string;
					public function setUserPassword(string $value):void;
					
					public function getUserCity():NULL|string;
					public function setUserCity(NULL|string $value):void;
					
		
					#********** VIRTUAL ATTRIBUTES **********#
					public function getFullName():string;
					
					
					#***********************************************************#
					

					#******************************#
					#********** METHODEN **********#
					#******************************#
					
					
					public function fetchFromDb(PDO $PDO):bool ;
								
					public function checkIfEmailExists(PDO $PDO):int ;				
					
					
					#***********************************************************#
					
				}
				
				
#*******************************************************************************************#
?>