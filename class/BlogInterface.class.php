<?php
#*******************************************************************************************#


				#*****************************************#
				#********** BLOG INTERFACE ***********#
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


				interface BlogInterface {
								
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
					
					public function getBlogID():NULL|int;
					public function setBlogID(string|int $value):void;
				
					public function getBlogHeadline():NULL|string;
					public function setBlogHeadline(string $value):void;
					
					public function getBlogImagePath():NULL|string;
					public function setBlogImagePath(NULL|string $value):void;
					
					public function getBlogImageAlignment():NULL|string;
					public function setBlogImageAlignment(NULL|string $value):void;
					
					public function getBlogContent():NULL|string;
					public function setBlogContent(string $value):void;
					
					public function getBlogDate():NULL|string;
					public function setBlogDate(string $value):void;
					
					public function getCategory():Category;
					public function setCategory(Category $value):void;
					
					public function getUser():User;
					public function setUser(User $value):void;
					
		
					#********** VIRTUAL ATTRIBUTES **********#

					
					
					#***********************************************************#
					

					#******************************#
					#********** METHODEN **********#
					#******************************#
					
					public function saveToDb(PDO $PDO):bool ;
					
					public static function fetchAllFromDB(PDO $PDO):array ;
					
					public static function fetchFromDBByCategory(PDO $PDO, string $catID):array ;
									

					#***********************************************************#
					
				}
				
				
#*******************************************************************************************#
?>