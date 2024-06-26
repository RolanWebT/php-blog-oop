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
				#********** CLASS CATEGORY **********#
				#********************************#

				/*
					Die Klasse ist quasi der Bauplan/die Vorlage für alle Objekte, die aus ihr erstellt werden.
					Sie gibt die Eigenschaften/Attribute eines späteren Objekts vor (Variablen) sowie 
					die "Fähigkeiten" (Methoden/Funktionen), über die das spätere Objekt besitzt.

					Jedes Objekt einer Klasse ist nach dem gleichen Schema aufgebaut (gleiche Eigenschaften und Methoden), 
					besitzt aber i.d.R. unterschiedliche Attributswerte.
				*/

				
#*******************************************************************************************#


				class Category implements CategoryInterface {
					
					#*******************************#
					#********** ATTRIBUTE **********#
					#*******************************#
					
					/* 
						Innerhalb der Klassendefinition müssen Attribute nicht zwingend initialisiert werden.
						Ein Weglassen der Initialisierung bewirkt das gleiche, wie eine Initialisierung mit NULL.
					*/
					private $catID;
					private $catLabel;
					
					
					#***********************************************************#
					
					
					#*********************************#
					#********** CONSTRUCTOR **********#
					#*********************************#
					
					/*
						Der Constructor ist eine besondere Methode in einer Klasse, 
						die automatisch aufgerufen wird, wenn ein neues Objekt dieser 
						Klasse erstellt wird. Der Zweck des Constructors besteht darin, 
						dem Objekt einen initialen Zustand zu geben und die notwendigen 
						Vorbereitungen für seine Verwendung zu treffen.
						
						Soll ein Objekt beim Erstellen bereits mit Attributwerten versehen werden,
						muss ein eigener Constructor geschrieben werden. Dieser nimmt die 
						Startwerte in Form von Parametern entgegen (genau wie bei Funktionen) 
						und ruft bei Verwendung des Getter & Setter Design Patterns seinerseits 
						die entsprechenden SETTER auf, die die Werte anschließend in die 
						jeweiligen Objektattribute schreiben.
					*/
					public function __construct(  $catID=NULL, $catLabel=NULL	)
					{
if(DEBUG_CC)		echo "<p class='debug class'>🛠 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "()  (<i>" . basename(__FILE__) . "</i>)</p>\n";						
						
						
						// Setter nur aufrufen, wenn der jeweilige Parameter keinen Leerstring und nicht NULL enthält
						if( $catID 					!== '' 	AND $catID 					!== NULL )		$this->setCatID($catID);
						if( $catLabel 				!== '' 	AND $catLabel 				!== NULL )		$this->setCatLabel($catLabel);
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
				
					#********** CATEGORY ID **********#
					public function getCatID():NULL|int {
						return $this->catID;
					}
					public function setCatID(int|string $value):void {
						
						#********** VALIDATE DATA FORMAT **********#
						if( filter_var($value, FILTER_VALIDATE_INT) === false ) {
							// Fehlerfall (nicht erlaubter Datentyp)
if(DEBUG_C)				echo "<p class='debug class err'><b>Line " . __LINE__ .  "</b> | " . __METHOD__ . "(): Der Wert muss inhaltlich einem Integer entsprechen! (<i>" . basename(__FILE__) . "</i>)</p>\n";
							
						} else {
							// Erfolgsfall
							// Datentyp umwandeln
							$this->catID = intval($value);
						}
					}
					
					
					#********** CATEGORY LABEL **********#
					public function getCatLabel():NULL|string {
						return $this->catLabel;
					}
					public function setCatLabel(string $value):void {
						$this->catLabel = sanitizeString($value);
					}
					
									
					#***********************************************************#
					

					#******************************#
					#********** METHODEN **********#
					#******************************#

					
					#********** CHECK IF CATEGORY EXISTS **********#
					/**
					*
					*	CHECKS IF NEW CATEGORY EXISTS IN DB
					*
					*	@param	PDO $PDO		DB-Connection object
					*
					*	@return	int			return count of columns
					*
					*/
					public function checkIfExists(PDO $PDO):int {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
						
						// Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen:
						$sql 		= 'SELECT COUNT(catLabel) FROM category WHERE catLabel = ?';
						
						$params 	= array( $this->getCatLabel() );
						
						// Schritt 3 DB: Prepared Statements:
						try {
							// SQL-Statement vorbereiten
							$PDOStatement = $PDO->prepare($sql);
							
							// Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
							$PDOStatement->execute($params);						
							
						} catch(PDOException $error) {
if(DEBUG)				echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
							$dbError = 'Fehler beim Zugriff auf die Datenbank!';
						}
						
						$count = $PDOStatement->fetchColumn();
if(DEBUG_V)			echo "<p class='debug value'>Line <b>" . __LINE__ . "</b>: \$count: $count <i>(" . basename(__FILE__) . ")</i></p>";
												
						if( $count === 1 ) {
							// Fehlerfall
							echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Kategorie <b>'{$this->getCatLabel()}'</b> existiert bereits! <i>(" . basename(__FILE__) . ")</i></p>";
							$errorCatLabel = 'Es existiert bereits eine Kategorie mit diesem Namen!';
								
						
						} else {
							// Erfolgsfall
if(DEBUG)				echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Neue Kategorie <b>{$this->getCatLabel()}</b> wird gespeichert... <i>(" . basename(__FILE__) . ")</i></p>";	


						}
						return $count;
					}
					#***********************************************************#
					
					
					#********** SAVES NEW CATEGORY TO DB **********#
					/**
					*
					*	SAVES NEW CATEGORY TO DB
					*	WRITES LAST INSERT ID INTO CATEGORY-OBJECT
					*
					*	@param	PDO $PDO		DB-Connection object
					*
					*	@return	BOOLEAN		true if writing was successful, else false
					*
					*/
					public function saveToDB(PDO $PDO):bool {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
						
						// Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen:
						$sql 		= 'INSERT INTO category
										(catLabel)
										VALUES
										(?)';
								
						$params 	= array( $this->getCatLabel() );
						
						
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
							Bei schreibenden Operationen (INSERT/UPDATE/DELETE):
							Schreiberfolg prüfen anhand der Anzahl der betroffenen Datensätze (number of affected rows).
							Diese werden über die PDOStatement-Methode rowCount() ausgelesen.
							Der Rückgabewert von rowCount() ist ein Integer; wurden keine Daten verändert, wird 0 zurückgeliefert.
						*/
						$rowCount = $PDOStatement->rowCount();
if(DEBUG_C)			echo "<p class='debug class value'><b>Line " . __LINE__ . "</b>: \$rowCount: $rowCount <i>(" . basename(__FILE__) . ")</i></p>\n";
						
						if( $rowCount === 0 ) {
							// Fehlerfall
							return false;
															
						} else {
							// Erfolgsfall
							$this->setCatID( $PDO->lastInsertId() );
							return true;
						}
					}
					
					
										
					#***********************************************************#
					
					#********** FETCH ALL CATEGORIES DATA FROM DB **********#
					/**
					*
					*	FETCH ALL CATEGORIES DATA FROM DB AND RETURNS ARRAY WITH BLOGBJECTS
					*
					*	@param	PDO $PDO		DB-Connection object
					*
					*	@return	ARRAY			An array containing all Categories as Categoryobjects
					*
					*/
					
					public static function fetchAllFromDB(PDO $PDO):array {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
						
						$sql 		= 'SELECT * FROM category';				
						$params 	= NULL;
						
						try {
							// Schritt 2 DB: SQL-Statement vorbereiten
							$PDOStatement = $PDO->prepare($sql);
							
							// Schritt 3 DB: SQL-Statement ausführen und ggf. Platzhalter füllen
							$PDOStatement->execute($params);						
							
						} catch(PDOException $error) {
if(DEBUG)			echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
						$dbError = 'Fehler beim Zugriff auf die Datenbank!';
						}
				
							$category = new Category();
							$allCategories = array ();
							
							
							while( $row = $PDOStatement->fetch(PDO::FETCH_ASSOC) ) {
								
								$category->setCatID( $row['catID'] );
								$category->setCatLabel( $row['catLabel'] );
								$allCategories[] = $category;
								$category = new Category();
							}
						return $allCategories;
/*
if(DEBUG_C)				echo "<pre class='debug class value'><b>Line " . __LINE__ . "</b>: \$allCategories[ 'userID' ] <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_C)				print_r($allCategories);					
if(DEBUG_C)				echo "</pre>";
*/
			
					}
					#***********************************************************#
					

					
				}
				
				
#*******************************************************************************************#