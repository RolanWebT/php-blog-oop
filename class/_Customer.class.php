<?php
#*******************************************************************************************#
				
				
				#******************************************#
				#********** ENABLE STRICT TYPING **********#
				#******************************************#
				
				/*
					Erklärung zu 'strict types' in Projekt '01a_klassen_und_instanzen'
				*/
				declare(strict_types=1);
				
				
#*******************************************************************************************#



				#************************************#
				#********** CLASS CUSTOMER **********#
				#************************************#


				
#*******************************************************************************************#


				class Customer implements CustomerInterface {
					
					#*******************************#
					#********** ATTRIBUTE **********#
					#*******************************#

					private $cusID;
					private $cusFirstName;
					private $cusLastName;
					private $cusEmail;
					private $cusBirthdate;
					private $cusCity;
				
					
					#***********************************************************#
					
					
					#*********************************#
					#********** CONSTRUCTOR **********#
					#*********************************#

					public function __construct( 	$cusFirstName	=NULL, $cusLastName	=NULL,
															$cusEmail		=NULL, $cusBirthdate	=NULL,
															$cusCity			=NULL, $cusID			=NULL	)
					{
if(DEBUG_CC)		echo "<p class='debug class'>🛠 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "()  (<i>" . basename(__FILE__) . "</i>)</p>\n";						
						

						
						// Setter nur aufrufen, wenn der jeweilige Parameter keinen Leerstring und nicht NULL enthält
						if( $cusFirstName 	!== '' 	AND $cusFirstName 		!== NULL )		$this->setCusFirstName($cusFirstName);
						if( $cusLastName 		!== '' 	AND $cusLastName 			!== NULL )		$this->setCusLastName($cusLastName);
						if( $cusEmail 			!== '' 	AND $cusEmail 				!== NULL )		$this->setCusEmail($cusEmail);
						if( $cusCity 			!== '' 	AND $cusCity 				!== NULL )		$this->setCusCity($cusCity);
						if( $cusBirthdate 	!== '' 	AND $cusBirthdate 		!== NULL )		$this->setCusBirthdate($cusBirthdate);
						if( $cusID 				!== '' 	AND $cusID 					!== NULL )		$this->setCusID($cusID);
					
/*						
if(DEBUG_CC)		echo "<pre class='debug class value'><b>Line " . __LINE__ .  "</b> | " . __METHOD__ . "(): <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_CC)		print_r($this);					
if(DEBUG_CC)		echo "</pre>";	
*/	
					}
					
					
					#********** DESTRUCTOR **********#

					public function __destruct() {
if(DEBUG_CC)		echo "<p class='debug class'>☠️  <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "()  (<i>" . basename(__FILE__) . "</i>)</p>\n";						
					}
					
					
					#***********************************************************#

					
					#*************************************#
					#********** GETTER & SETTER **********#
					#*************************************#
				
					#********** CUSTOMER ID **********#
					public function getCusID():NULL|int {
						return $this->cusID;
					}
					public function setCusID(int|string $value):void {
						
						#********** VALIDATE DATA FORMAT **********#
						if( filter_var($value, FILTER_VALIDATE_INT) === false ) {
							// Fehlerfall (nicht erlaubter Datentyp)
if(DEBUG_C)				echo "<p class='debug class err'><b>Line " . __LINE__ .  "</b> | " . __METHOD__ . "(): Der Wert muss inhaltlich einem Integer entsprechen! (<i>" . basename(__FILE__) . "</i>)</p>\n";
							
						} else {
							// Erfolgsfall
							// Datentyp umwandeln
							$this->cusID = intval($value);
						}
					}
					
					
					#********** CUSTOMER FIRST NAME **********#
					public function getCusFirstName():NULL|string {
						return $this->cusFirstName;
					}
					public function setCusFirstName(string $value):void {
						$this->cusFirstName = sanitizeString($value);
					}
					
					
					#********** CUSTOMER LAST NAME **********#
					public function getCusLastName():NULL|string {
						return $this->cusLastName;
					}
					public function setCusLastName(string $value):void {
						$this->cusLastName = sanitizeString($value);
					}
					
					
					#********** CUSTOMER EMAIL **********#
					public function getCusEmail():NULL|string {
						return $this->cusEmail;
					}
					public function setCusEmail(string $value):void {
						$this->cusEmail = $value;
					}
					
					
					#********** CUSTOMER BIRTHDATE **********#
					public function getCusBirthdate():NULL|string {
						return $this->cusBirthdate;
					}
					public function setCusBirthdate(string $value):void {
						$this->cusBirthdate = sanitizeString($value);
					}
					
					#********** CUSTOMER CITY **********#
					public function getCusCity():NULL|string {
						return $this->cusCity;
					}
					public function setCusCity(string $value):void {
						$this->cusCity = sanitizeString($value);
					}

					
					#********** VIRTUAL ATTRIBUTES **********#
					public function getFullName():string {
						return $this->getCusFirstName() . " " . $this->getCusLastName();
 					}				
					
					#***********************************************************#
					

					#******************************#
					#********** METHODEN **********#
					#******************************#
					
					
					#********** CHECK IF EMAIL IS ALREADY REGISTERED ******** DONE **#
					public function checkIfEmailExists(PDO $PDO) {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
						
						
						// Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen:
						/*
							<=>: NULL-Safe-Vergleichsoperator in MySQL "NOT usr_id <=> ? (usr_id != ?)"
							für internalPage: 
							Damit aber auch die Registrierungsseite nach wie vor funktioniert, über den 
							normalen Vergleichsoperator in SQL jedoch keine Prüfung gegen NULL möglich ist,
							muss hier der sog. NULL-safe Vergleichsoperator <=> benutzt werden, der Vergleiche
							gegen NULL ermöglicht.
							Da der NULL-safe Vergleichsoperator keine Verneinung kennt (!=), muss auf = 
							geprüft und der gesamte Ausdruck mittels NOT negiert werden.
						*/
						$sql 		= 'SELECT COUNT(cusEmail) FROM customer
										WHERE cusEmail = ?
										AND NOT cusID <=> ?';
						
						$params 	= array( $this->getCusEmail(),
												$this->getCusID() );
						
						
						// Schritt 3 DB: Prepared Statements:
						try {
							// Prepare: SQL-Statement vorbereiten
							$PDOStatement = $PDO->prepare($sql);
							
							// Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
							$PDOStatement->execute($params);
							
						} catch(PDOException $error) {
if(DEBUG_C) 			echo "<p class='debug class db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
							$dbError = 'Fehler beim Zugriff auf die Datenbank!';
						}
						
						
						// Schritt 4 DB: Datenbankoperation auswerten und DB-Verbindung schließen
						/*
							Bei SELECT COUNT(): Rückgabewert von COUNT() über $PDOStatement->fetchColumn() auslesen
						*/
						$count = $PDOStatement->fetchColumn();
if(DEBUG_C)			echo "<p class='debug class value'><b>Line " . __LINE__ . "</b>: \$count: $count <i>(" . basename(__FILE__) . ")</i></p>\n";
						
						return $count;
					}
					
										
					#***********************************************************#
					
					
					#********** SAVE NEW USER DATA INTO DB ******** DONE **#
					public function saveToDB(PDO $PDO) {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
						
						// Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen:
						$sql 		= 'INSERT INTO customer
										(cusFirstName, cusLastName, cusEmail, cusBirthdate, cusCity)
										VALUES
										(?, ?, ?, ?, ?)';
								
						$params 	= array( $this->getCusFirstName(),
												$this->getCusLastName(),
												$this->getCusEmail(),
												$this->getCusBirthdate(),
												$this->getCusCity() );
						
						
						// Schritt 3 DB: Prepared Statements:
						try {
							// Prepare: SQL-Statement vorbereiten
							$PDOStatement = $PDO->prepare($sql);
							
							// Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
							$PDOStatement->execute($params);
							
						} catch(PDOException $error) {
if(DEBUG_C) 			echo "<p class='debug class db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
							$dbError = 'Fehler beim Zugriff auf die Datenbank!';
						}
						
						
						// Schritt 4 DB: Datenbankoperation auswerten und DB-Verbindung schließen
						
						$rowCount = $PDOStatement->rowCount();
if(DEBUG_C)			echo "<p class='debug class value'><b>Line " . __LINE__ . "</b>: \$rowCount: $rowCount <i>(" . basename(__FILE__) . ")</i></p>\n";
						
						return $rowCount;
					}
					
										
					#***********************************************************#
					
					
					#********** FETCH ALL CUSTOMERS DATA FROM DB ******** DONE **#
					public static function fetchAllFromDb(PDO $PDO) {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
						
						// 2. SQL-Statement und Placeholder-Array erstellen
						$sql 		= 'SELECT * FROM customer';
						
						$params 	= array( );
						
						
						// 3. Prepared Statements
						try {
							// Prepare: SQL-Statement vorbereiten
							$PDOStatement = $PDO->prepare($sql);
							
							// Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
							$PDOStatement->execute($params);
							
						} catch(PDOException $error) {
if(DEBUG_C) 			echo "<p class='debug class db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
							$dbError = 'Fehler beim Zugriff auf die Datenbank!';
						}
						
						$customersData = $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
/*
if(DEBUG_CC)		echo "<pre class='debug class value'><b>Line " . __LINE__ .  "</b> | " . __METHOD__ . "(): <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_CC)		print_r($customersData);					
if(DEBUG_CC)		echo "</pre>";	
*/					
						if( $customersData === false ) {
							// Fehlerfall
if(DEBUG)				echo "<p class='debug class err'><b>Line " . __LINE__ . "</b>: FEHLER: fetchAll ist nicht erfolgreich! <i>(" . basename(__FILE__) . ")</i></p>\n";				
						
						} else {
							// Erfolgsfall
							
							#********** WRITE CUSTOMER DATA VALUES INTO ARRAY OF OBJECTS**********#
							#*** CUSTOMER OBJECT'S VALUES ***#
							
							$customerObject = new Customer();
							$customersObjectsArray = array();
								
							foreach($customersData AS $cusData) {
								
								if( $cusData['cusID'] 			!== '' 	AND $cusData['cusID'] 			!== NULL )		$customerObject->setCusID( $cusData['cusID'] );
								if( $cusData['cusFirstName'] 	!== '' 	AND $cusData['cusFirstName'] 	!== NULL )		$customerObject->setCusFirstName( $cusData['cusFirstName'] );
								if( $cusData['cusLastName'] 	!== '' 	AND $cusData['cusLastName'] 	!== NULL )		$customerObject->setCusLastName( $cusData['cusLastName'] );
								if( $cusData['cusEmail'] 		!== '' 	AND $cusData['cusEmail'] 		!== NULL )		$customerObject->setCusEmail( $cusData['cusEmail'] );
								if( $cusData['cusBirthdate'] 	!== '' 	AND $cusData['cusBirthdate'] 	!== NULL )		$customerObject->setCusBirthdate( $cusData['cusBirthdate'] );
								if( $cusData['cusCity'] 		!== '' 	AND $cusData['cusCity'] 		!== NULL )		$customerObject->setCusCity( $cusData['cusCity'] );
				

								array_push( $customersObjectsArray, $customerObject );
								$customerObject = new Customer();
							}
						return $customersObjectsArray;	
					
						}
					}
					
					
					#***********************************************************#
					
					
					public function fetchFromDB(PDO $PDO) {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
						
						
						// 2. SQL-Statement und Placeholder-Array erstellen
						$sql 		= 'SELECT * FROM customer
										WHERE	cusID	= ?';
						
						$params 	= array( $this->getCusID() );
						
						
						// 3. Prepared Statements
						try {
							// Prepare: SQL-Statement vorbereiten
							$PDOStatement = $PDO->prepare($sql);
							
							// Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
							$PDOStatement->execute($params);
							
						} catch(PDOException $error) {
if(DEBUG_C) 			echo "<p class='debug class db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
							$dbError = 'Fehler beim Zugriff auf die Datenbank!';
						}
						
						
						// Schritt 4 DB: Datenbankoperation auswerten und DB-Verbindung schließen
						/*
							Bei lesenden Operationen wie SELECT und SELECT COUNT:
							Abholen der Datensätze bzw. auslesen des Ergebnisses
						*/
						$cusData = $PDOStatement->fetch(PDO::FETCH_ASSOC);
						
						/*
							In $userData ist nur dann ein Datensatz enthalten, wenn die EmailAdresse aus dem Formular 
							mit einer Emailadresse aus der DB übereinstimmt.						
							Wenn ein passender Datensatz gefunden wurde, liefert $PDOStatement->fetch() an dieser
							Stelle ein eindimensionales Array mit den ausgelesenen Datenfeldwerten zurück.
							Wenn KEIN passender Datensatz gefunden wurde, enthält $row an dieser Stelle false.
						*/
/*						
if(DEBUG_C)			echo "<pre class='debug class value'><b>Line " . __LINE__ . "</b>: \$cusData <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_C)			print_r($cusData);					
if(DEBUG_C)			echo "</pre>";
*/						
						// Wurde ein Datensatz geliefert?
						if( $cusData === false ) {
							// Fehlerfall
							return false;
							
						} else {
							// Erfolgsfall
							// Create Customer Object with the givven ID
							$customerObject = new Customer( cusID:$this->getCusID() );
/*
if(DEBUG_C)			echo "<pre class='debug class value'><b>Line " . __LINE__ . "</b>: \$cusData <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_C)			print_r($customerObject);					
if(DEBUG_C)			echo "</pre>";
*/						
							#********** WRITE CUSTOMER DATA VALUES INTO CALLING OBJECTS **********#
							#*** USER OBJECT'S VALUES ***#
							if( $cusData['cusID'] 			!== '' 	AND $cusData['cusID'] 			!== NULL )		$customerObject->setCusID( $this->getCusID() );
							if( $cusData['cusFirstName'] 	!== '' 	AND $cusData['cusFirstName'] 	!== NULL )		$customerObject->setCusFirstName( $cusData['cusFirstName'] );
							if( $cusData['cusLastName'] 	!== '' 	AND $cusData['cusLastName'] 	!== NULL )		$customerObject->setCusLastName( $cusData['cusLastName'] );
							if( $cusData['cusEmail'] 		!== '' 	AND $cusData['cusEmail'] 		!== NULL )		$customerObject->setCusEmail( $cusData['cusEmail'] );
							if( $cusData['cusBirthdate'] 	!== '' 	AND $cusData['cusBirthdate'] 	!== NULL )		$customerObject->setCusBirthdate( $cusData['cusBirthdate'] );
							if( $cusData['cusCity'] 		!== '' 	AND $cusData['cusCity'] 		!== NULL )		$customerObject->setCusCity( $cusData['cusCity'] );
						
/*
if(DEBUG_C)				echo "<pre class='debug class value'><b>Line " . __LINE__ . "</b>: \$this <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_C)				print_r($customerObject);					
if(DEBUG_C)				echo "</pre>";							
*/
							
							return $customerObject;							
						}
	
					}
					
					
					#***********************************************************#
					
					
					public function updateToDB(PDO $PDO) {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
						
						// Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen:
						$sql 		= 'UPDATE customer
										SET
										cusFirstName 	= ?,
										cusLastName 	= ?,
										cusEmail 		= ?,
										cusBirthdate 	= ?,
										cusCity 			= ?
										WHERE cusID	= ?';
						
						$params 	= array( $this->getCusFirstName(),
												$this->getCusLastName(),
												$this->getCusEmail(),
												$this->getCusBirthdate(),
												$this->getCusCity(),
												$this->getCusID()
													);
						
						// Schritt 3 DB: Prepared Statements:
						try {
							// Prepare: SQL-Statement vorbereiten
							$PDOStatement = $PDO->prepare($sql);
							
							// Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
							$PDOStatement->execute($params);
							
						} catch(PDOException $error) {
if(DEBUG_C)				echo "<p class='debug class db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
							$dbError = 'Fehler beim Zugriff auf die Datenbank!';
						}					
						
						// Schritt 4 DB: Datenbankoperation auswerten und DB-Verbindung schließen
						/*
							Bei schreibenden Operationen (INSERT/UPDATE/DELETE):
							Schreiberfolg prüfen anhand der Anzahl der betroffenen Datensätze
						*/
						$rowCount = $PDOStatement->rowCount();
if(DEBUG_C)			echo "<p class='debug class value'><b>Line " . __LINE__ . "</b>: \$rowCount: $rowCount <i>(" . basename(__FILE__) . ")</i></p>\n";
				
						return $rowCount;
					}
					
					
					#***********************************************************#
					
					#********** DELETE CUSTOMER DATA FROM DB ******** !! **#
					public function deleteFromDB(PDO $PDO) {
						// Try to make Confirm Popup
						/*
						echo "<script>window.confirm('Möchten Sie den Kunden mit der ID: wirklich löschen?'); \n";
						echo "if( confirm('yes delete') ) { ";
						*/
if(DEBUG_C)				echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
							
							// Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen:
							$sql 		= 'delete FROM customer
											WHERE cusID = ?';
									
							$params 	= array( $this->getCusID() );
							
							
							// Schritt 3 DB: Prepared Statements:
							try {
								// Prepare: SQL-Statement vorbereiten
								$PDOStatement = $PDO->prepare($sql);
								
								// Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
								$PDOStatement->execute($params);
								
							} catch(PDOException $error) {
if(DEBUG_C) 				echo "<p class='debug class db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
								$dbError = 'Fehler beim Zugriff auf die Datenbank!';
							}
							
							
							// Schritt 4 DB: Datenbankoperation auswerten und DB-Verbindung schließen
							
							$rowCount = $PDOStatement->rowCount();
if(DEBUG_C)				echo "<p class='debug class value'><b>Line " . __LINE__ . "</b>: \$rowCount: $rowCount <i>(" . basename(__FILE__) . ")</i></p>\n";
							
							return $rowCount;
							
						/*	
						echo "} else { } ";
						
						echo " </script>";
						*/
					}
					
										
					#***********************************************************#	
					
				}
				
				
#*******************************************************************************************#