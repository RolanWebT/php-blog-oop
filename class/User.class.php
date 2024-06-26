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



				#********************************#
				#********** CLASS USER **********#
				#********************************#

				/*
					Die Klasse ist quasi der Bauplan/die Vorlage für alle Objekte, die aus ihr erstellt werden.
					Sie gibt die Eigenschaften/Attribute eines späteren Objekts vor (Variablen) sowie 
					die "Fähigkeiten" (Methoden/Funktionen), über die das spätere Objekt besitzt.

					Jedes Objekt einer Klasse ist nach dem gleichen Schema aufgebaut (gleiche Eigenschaften und Methoden), 
					besitzt aber i.d.R. unterschiedliche Attributswerte.
				*/

				
#*******************************************************************************************#


				class User implements UserInterface {
					
					#*******************************#
					#********** ATTRIBUTE **********#
					#*******************************#
					
					/* 
						Innerhalb der Klassendefinition müssen Attribute nicht zwingend initialisiert werden.
						Ein Weglassen der Initialisierung bewirkt das gleiche, wie eine Initialisierung mit NULL.
					*/
					private $userID;
					private $userFirstName;
					private $userLastName;
					private $userEmail;
					private $userCity;
					private $userPassword;
					

					
					#***********************************************************#
					
					
					#*********************************#
					#********** CONSTRUCTOR **********#
					#*********************************#
					
					public function __construct(  $userFirstName=NULL, $userLastName=NULL, $userEmail=NULL,
															$userCity=NULL, $userPassword=NULL,
															$userID=NULL	)
					{
if(DEBUG_CC)		echo "<p class='debug class'>🛠 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "()  (<i>" . basename(__FILE__) . "</i>)</p>\n";						
						
						
						// Setter nur aufrufen, wenn der jeweilige Parameter keinen Leerstring und nicht NULL enthält
						if( $userFirstName 			!== '' 	AND $userFirstName 			!== NULL )		$this->setUserFirstName($userFirstName);
						if( $userLastName 			!== '' 	AND $userLastName 			!== NULL )		$this->setUserLastName($userLastName);
						if( $userEmail 				!== '' 	AND $userEmail 				!== NULL )		$this->setUserEmail($userEmail);
						if( $userPassword 			!== '' 	AND $userPassword 			!== NULL )		$this->setUserPassword($userPassword);
						if( $userCity 					!== '' 	AND $userCity 					!== NULL )		$this->setUserCity($userCity);
						if( $userID 					!== '' 	AND $userID 					!== NULL )		$this->setUserID($userID);
/*						
if(DEBUG_CC)		echo "<pre class='debug class value'><b>Line " . __LINE__ .  "</b> | " . __METHOD__ . "(): <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_CC)		print_r($this);					
if(DEBUG_CC)		echo "</pre>";	
*/					
					}
					
					
					#********** DESTRUCTOR **********#
					/*
						Der Destructor ist eine magische Methode und wird automatisch aufgerufen,
						sobald ein Objekt mittels unset() gelöscht wird, oder sobald das Skript beendet ist.
						Der Destructor gibt den vom gelöschten Objekt belegten Speicherplatz wieder frei.
					*/
					public function __destruct() {
if(DEBUG_CC)		echo "<p class='debug class'>☠️  <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "()  (<i>" . basename(__FILE__) . "</i>)</p>\n";						
					}
					
					
					#***********************************************************#

					
					#*************************************#
					#********** GETTER & SETTER **********#
					#*************************************#
				
					#********** USER ID **********#
					public function getUserID():NULL|int {
						return $this->userID;
					}
					public function setUserID(int|string $value):void {
						
						#********** VALIDATE DATA FORMAT **********#
						if( filter_var($value, FILTER_VALIDATE_INT) === false ) {
							// Fehlerfall (nicht erlaubter Datentyp)
if(DEBUG_C)				echo "<p class='debug class err'><b>Line " . __LINE__ .  "</b> | " . __METHOD__ . "(): Der Wert muss inhaltlich einem Integer entsprechen! (<i>" . basename(__FILE__) . "</i>)</p>\n";
							
						} else {
							// Erfolgsfall
							// Datentyp umwandeln
							$this->userID = intval($value);
						}
					}
					
					
					#********** USER FIRST NAME **********#
					public function getUserFirstName():NULL|string {
						return $this->userFirstName;
					}
					public function setUserFirstName(string $value):void {
						$this->userFirstName = sanitizeString($value);
					}
					
					
					#********** USER LAST NAME **********#
					public function getUserLastName():NULL|string {
						return $this->userLastName;
					}
					public function setUserLastName(string $value):void {
						$this->userLastName = sanitizeString($value);
					}
					
					#********** USER EMAIL **********#
					public function getUserEmail():NULL|string {
						return $this->userEmail;
					}
					public function setUserEmail(string $value):void {
						$this->userEmail = sanitizeString($value);
					}
					
					
					#********** USER PASSWORD **********#
					public function getUserPassword():NULL|string {
						return $this->userPassword;
					}
					public function setUserPassword(string $value):void {
						$this->userPassword = $value;
					}
					
					
					#********** USER CITY **********#
					public function getUserCity():NULL|string {
						return $this->userCity;
					}
					public function setUserCity(NULL|string $value):void {
						$this->userCity = sanitizeString($value);
					}

					
					#********** VIRTUAL ATTRIBUTES **********#
					public function getFullName():string {
						
						return $this->getUserFirstName() . " " . $this->getUserLastName();
					}			
					
					#***********************************************************#
					

					#******************************#
					#********** METHODEN **********#
					#******************************#

										
					#***********************************************************#
					
					
					#********** CHECK IF EMAIL IS ALREADY REGISTERED **********#
					/**
					*
					*	CHECK IF USER EMAIL IS ALREADY IN DB BY CHECKING COUNT NUMBER 
					*
					*	@param	PDO $PDO		DB-Connection object
					*
					*	@return	int			0 if not exists, 1 if exists
					*
					*/
					public function checkIfEmailExists(PDO $PDO):int {
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
						$sql 		= 'SELECT COUNT(userEmail) FROM user
										WHERE userEmail = ?
										AND NOT userID <=> ?';
						
						$params 	= array( $this->getUserEmail(),
												$this->getUserID() );
						
						
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
					
					
					#********** FETCH USER DATA FROM DB **********#
					/**
					*
					*	FETCH USER DATA DEPENDS ON HIS EMAIL FROM DB 
					*
					*	@param	PDO $PDO		DB-Connection object
					*
					*	@return	BOOL			true if return User-Object was successful, else false 
					*
					*/
					public function fetchFromDB(PDO $PDO):bool {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
						
						
						// 2. SQL-Statement und Placeholder-Array erstellen
						$sql 		= 'SELECT * FROM user
										WHERE userEmail 	= ?';
						
						$params 	= array( $this->getUserEmail() );
						
						
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
						$userData = $PDOStatement->fetch(PDO::FETCH_ASSOC);
						
						/*
							In $userData ist nur dann ein Datensatz enthalten, wenn die EmailAdresse aus dem Formular 
							mit einer Emailadresse aus der DB übereinstimmt.						
							Wenn ein passender Datensatz gefunden wurde, liefert $PDOStatement->fetch() an dieser
							Stelle ein eindimensionales Array mit den ausgelesenen Datenfeldwerten zurück.
							Wenn KEIN passender Datensatz gefunden wurde, enthält $row an dieser Stelle false.
						*/
/*						
if(DEBUG_C)			echo "<pre class='debug class value'><b>Line " . __LINE__ . "</b>: \$userData <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_C)			print_r($userData);					
if(DEBUG_C)			echo "</pre>";
*/						
						// Wurde ein Datensatz geliefert?
						if( $userData === false ) {
							// Fehlerfall
							return false;
							
						} else {
							// Erfolgsfall
							
							#********** WRITE USER DATA VALUES INTO CALLING OBJECTS **********#
							#*** USER OBJECT'S VALUES ***#
							if( $userData['userID'] 					!== '' 	AND $userData['userID'] 					!== NULL )		$this->setUserID( $userData['userID'] );
							if( $userData['userEmail'] 				!== '' 	AND $userData['userEmail'] 				!== NULL )		$this->setUserEmail( $userData['userEmail'] );
							if( $userData['userFirstName'] 			!== '' 	AND $userData['userFirstName'] 			!== NULL )		$this->setUserFirstName( $userData['userFirstName'] );
							if( $userData['userLastName'] 			!== '' 	AND $userData['userLastName'] 			!== NULL )		$this->setUserLastName( $userData['userLastName'] );
							if( $userData['userPassword']				!== '' 	AND $userData['userPassword'] 			!== NULL )		$this->setUserPassword( $userData['userPassword'] );
							if( $userData['userCity'] 					!== '' 	AND $userData['userCity'] 					!== NULL )		$this->setUserCity( $userData['userCity'] );

							
if(DEBUG_C)				echo "<pre class='debug class value'><b>Line " . __LINE__ . "</b>: \$this <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_C)				print_r($this);					
if(DEBUG_C)				echo "</pre>";							
							
							return true;							
						}
	
					}
					
					
					#***********************************************************#
					
					
					
				}
				
				
#*******************************************************************************************#