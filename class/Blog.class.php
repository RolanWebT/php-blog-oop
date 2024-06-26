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
				#********** CLASS BLOG **********#
				#********************************#

				/*
					Die Klasse ist quasi der Bauplan/die Vorlage für alle Objekte, die aus ihr erstellt werden.
					Sie gibt die Eigenschaften/Attribute eines späteren Objekts vor (Variablen) sowie 
					die "Fähigkeiten" (Methoden/Funktionen), über die das spätere Objekt besitzt.

					Jedes Objekt einer Klasse ist nach dem gleichen Schema aufgebaut (gleiche Eigenschaften und Methoden), 
					besitzt aber i.d.R. unterschiedliche Attributswerte.
				*/

				
#*******************************************************************************************#


				class Blog implements BlogInterface {
					
					#*******************************#
					#********** ATTRIBUTE **********#
					#*******************************#
					
					/* 
						Innerhalb der Klassendefinition müssen Attribute nicht zwingend initialisiert werden.
						Ein Weglassen der Initialisierung bewirkt das gleiche, wie eine Initialisierung mit NULL.
					*/
					private $blogID;
					private $blogHeadline;
					private $blogImagePath;
					private $blogImageAlignment;
					private $blogContent;
					private $blogDate;
					
					// Einzubettendes Object
					private $category;
					private $user;

					
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
					public function __construct(  $user = new User(), $category = new Category,
															$blogHeadline=NULL, $blogImagePath=NULL,
															$blogImageAlignment=NULL, $blogContent=NULL,
															$blogDate=NULL, $blogID=NULL	)
					{
if(DEBUG_CC)		echo "<p class='debug class'>🛠 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "()  (<i>" . basename(__FILE__) . "</i>)</p>\n";						
						
						/*
							Entweder wird beim Constructor-Aufruf ein eingebettetes Objekt übergeben, oder es wird bei der 
							Parameterübernahme ein leeres Objekt erzeugt. In beiden Fällen wird das einzubettende Objekt
							IMMER in das einbettende Objekt geschachtelt.
						*/
						$this->setUser($user);
						$this->setCategory($category);
						
						// Setter nur aufrufen, wenn der jeweilige Parameter keinen Leerstring und nicht NULL enthält
						if( $blogHeadline 			!== '' 	AND $blogHeadline 			!== NULL )		$this->setBlogHeadline($blogHeadline);
						if( $blogImagePath 			!== '' 	AND $blogImagePath 			!== NULL )		$this->setBlogImagePath($blogImagePath);
						if( $blogImageAlignment 	!== '' 	AND $blogImageAlignment 	!== NULL )		$this->setBlogImageAlignment($blogImageAlignment);
						if( $blogContent 				!== '' 	AND $blogContent 				!== NULL )		$this->setBlogContent($blogContent);
						if( $blogDate 					!== '' 	AND $blogDate 					!== NULL )		$this->setBlogDate($blogDate);
						if( $blogID 					!== '' 	AND $blogID 					!== NULL )		$this->setBlogID($blogID);
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
				
					#********** BLOG ID **********#
					public function getBlogID():NULL|int {
						return $this->blogID;
					}
					public function setBlogID(int|string $value):void {
						
						#********** VALIDATE DATA FORMAT **********#
						if( filter_var($value, FILTER_VALIDATE_INT) === false ) {
							// Fehlerfall (nicht erlaubter Datentyp)
if(DEBUG_C)				echo "<p class='debug class err'><b>Line " . __LINE__ .  "</b> | " . __METHOD__ . "(): Der Wert muss inhaltlich einem Integer entsprechen! (<i>" . basename(__FILE__) . "</i>)</p>\n";
							
						} else {
							// Erfolgsfall
							// Datentyp umwandeln
							$this->blogID = intval($value);
						}
					}
					
					
					#********** BLOG HEAD LINE **********#
					public function getBlogHeadline():NULL|string {
						return $this->blogHeadline;
					}
					public function setBlogHeadline(string $value):void {
						$this->blogHeadline = sanitizeString($value);
					}
					
					
					#********** BLOG IMAGE PATH **********#
					public function getBlogImagePath():NULL|string {
						return $this->blogImagePath;
					}
					public function setBlogImagePath(NULL|string $value):void {
						$this->blogImagePath = sanitizeString($value);
					}
					
					
					#********** BLOG IMAGE ALIGNEMENT **********#
					public function getBlogImageAlignment():NULL|string {
						return $this->blogImageAlignment;
					}
					public function setBlogImageAlignment(NULL|string $value):void {
						$this->blogImageAlignment = $value;
					}
					
					
					#********** BLOG CONTENT **********#
					public function getBlogContent():NULL|string {
						return $this->blogContent;
					}
					public function setBlogContent(string $value):void {
						$this->blogContent = sanitizeString($value);
					}

					
					#********** BLOG DATE **********#
					public function getBlogDate():NULL|string {
						return $this->blogDate;
					}
					public function setBlogDate(string $value):void {
						$this->blogDate = sanitizeString($value);
					}
					
					
					
					#********** CATEGORY **********#
					public function getCategory():Category {
						return $this->category;
					}
					public function setCategory(Category $value) :void {
						$this->category = $value;
					}
										
					
					#********** USER **********#
					public function getUser():User {
						return $this->user;
					}
					public function setUser(User $value) :void {
						$this->user = $value;
					}
					
					#***********************************************************#
					

					#******************************#
					#********** METHODEN **********#
					#******************************#
					
					
					#********** SAVE NEW BLOG DATA INTO DB **********#
					/**
					*
					*	SAVES NEW BLOG TO DB
					*	WRITES LAST INSERT ID INTO BLOG-OBJECT
					*
					*	@param	PDO $PDO		DB-Connection object
					*
					*	@return	BOOLEAN		true if writing was successful, else false
					*
					*/
					public function saveToDB(PDO $PDO) :bool{
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
						
						
				
						// Schritt 2 DB: SQL-Statement vorbereiten
						$sql 		= 	'INSERT INTO blog (blogHeadline, blogImagePath, blogImageAlignment, blogContent, catID, userID)
										 VALUES (?, ?, ?, ?, ?, ?) ';
						
						$params 	= array( $this->getBlogHeadline(),
												$this->getBlogImagePath(),
												$this->getBlogImageAlignment(),
												$this->getBlogContent(),
												$this->getCategory()->getCatID(),
												$_SESSION['ID'] );
												
						try {
							
							$PDOStatement = $PDO->prepare($sql);
							
							// Schritt 3 DB: SQL-Statement ausführen und ggf. Platzhalter füllen
							$PDOStatement->execute($params);						
							
						} catch(PDOException $error) {
if(DEBUG)				echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
							$dbError = 'Fehler beim Zugriff auf die Datenbank!';
						}
							
						// Schritt 4 DB: Schreiberfolg prüfen
						$rowCount = $PDOStatement->rowCount();							
if(DEBUG_V)			echo "<p class='debug value'>Line <b>" . __LINE__ . "</b>: \$rowCount: $rowCount <i>(" . basename(__FILE__) . ")</i></p>";						
							
						if( $rowCount !== 1 ) {
							// Fehlerfall
if(DEBUG)				echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER beim Speichern des Blogbeitrags! <i>(" . basename(__FILE__) . ")</i></p>";
							$dbError = 'Es ist ein Fehler aufgetreten! Bitte versuchen Sie es später noch einmal.';
							return false;
							
						} else {
							// Erfolgsfall
							$newBlogID = $PDO->lastInsertId();
							
if(DEBUG)				echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Blogbeitrag erfolgreich mit der ID$newBlogID gespeichert. <i>(" . basename(__FILE__) . ")</i></p>";
							$dbSuccess = 'Der Blogbeitrag wurde erfolgreich gespeichert.';
								

							return true;
						}
									
						
					}
					
										
					#***********************************************************#
					
					
					#********** FETCH ALL BLOGS DATA FROM DB **********#
					/**
					*
					*	FETCH ALL BLOGS DATA FROM DB AND RETURNS ARRAY WITH BLOGBJECTS
					*
					*	@param	PDO $PDO		DB-Connection object
					*
					*	@return	ARRAY			An array containing all Blogs as blogobjects
					*
					*/
					public static function fetchAllFromDB(PDO $PDO):array {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
						
						$blogObjectsArray = array();

						
						// Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen:
						$sql 		= 	'SELECT * FROM blog 
										INNER JOIN user USING(userID)
										INNER JOIN category USING(catID)
										ORDER BY blogDate DESC';
								 
						// case a) No condition and therefore no placeholder needed
						$params 	= 	array();					 
						
						// Schritt 3 DB: Prepared Statements
						try {
							// Prepare: SQL-Statement vorbereiten
							$PDOStatement = $PDO->prepare($sql);
							
							// Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
							$PDOStatement->execute($params);
							
						} catch(PDOException $error) {
if(DEBUG_C) 			echo "<p class='debug class db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
						}
						
						// Schritt 4 DB: Daten weiterverarbeiten und DB-Verbindung schließen
						/*
							Bei lesenden Operationen wie SELECT und SELECT COUNT():
							Abholen der Datensätze bzw. auslesen des Ergebnisses
						*/	

						
						$blogObject = new Blog();
						$blogObjectsArray = array ();

						while( $row = $PDOStatement->fetch(PDO::FETCH_ASSOC) ) {
	
							// Je Datensatz ein Objekt der jeweiligen Klasse erstellen und in ein Array speichern
							// Variante mit Automation
							// Ohne Automation muss hier der Constructor mit allen einzelnen Werten aus dem $row-Array als Attribute aufgerufen werden

							$blogObject->getUser()->setUserID($row['userID']);
							$blogObject->getUser()->setUserFirstName($row['userFirstName']);
							$blogObject->getUser()->setUserLastName($row['userLastName']);
							$blogObject->getUser()->setUserEmail($row['userEmail']);
							$blogObject->getUser()->setUserCity($row['userCity']);
							
							$blogObject->getCategory()->setCatID($row['catID']);
							$blogObject->getCategory()->setCatLabel($row['catLabel']);
							
							$blogObject->setBlogID($row['blogID']);
							$blogObject->setBlogHeadline($row['blogHeadline']);
							$blogObject->setBlogImagePath($row['blogImagePath']);
							$blogObject->setBlogImageAlignment($row['blogImageAlignment']);
							$blogObject->setBlogContent($row['blogContent']);
							$blogObject->setBlogDate($row['blogDate']);
						
							array_push($blogObjectsArray, $blogObject);
							$blogObject = new Blog();

						}
/*						
if(DEBUG_C)				echo "<pre class='debug class value'><b>Line " . __LINE__ . "</b>: \$blogObjectsArray[ 'userID' ] <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_C)				print_r($blogObjectsArray);					
if(DEBUG_C)				echo "</pre>";	
*/
						return $blogObjectsArray;

					}					
					#************************************************************#
				
					#********** FETCH BLOGS DATA FROM DB BY CATEGORY **********#
					/**
					*
					*	FETCH BLOGS DATA FROM DB BY CATEGORY AND RETURNS ARRAY WITH BLOGBJECTS
					*
					*	@param	PDO $PDO		DB-Connection object
					*	@param	int|string $catID	Category ID Integer
					*
					*	@return	ARRAY			An array containing all Blogs as blogobjects
					*
					*/
					public static function fetchFromDBByCategory(PDO $PDO, string|int $catID):array {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
					
						
						$blogObjectsArray = array();
						
						// Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen:
						$sql 		= 	'SELECT * FROM blog 
										INNER JOIN user USING(userID)
										INNER JOIN category USING(catID)
										WHERE catID = ?
										ORDER BY blogDate DESC';
								 
						// case a) No condition and therefore no placeholder needed
						$params 	= 	array($catID);			
						
						// Schritt 3 DB: Prepared Statements
						try {
							// Prepare: SQL-Statement vorbereiten
							$PDOStatement = $PDO->prepare($sql);
							
							// Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
							$PDOStatement->execute($params);
							
						} catch(PDOException $error) {
if(DEBUG_C) 			echo "<p class='debug class db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
						}
						
						// Schritt 4 DB: Daten weiterverarbeiten und DB-Verbindung schließen
						/*
							Bei lesenden Operationen wie SELECT und SELECT COUNT():
							Abholen der Datensätze bzw. auslesen des Ergebnisses
						*/	
						$blogObject = new Blog();
						$blogObjectsArray = array ();

						while( $row = $PDOStatement->fetch(PDO::FETCH_ASSOC) ) {
							
							if( $row['catID'] === intval($catID) ) {
								
								$blogObject->getUser()->setUserID($row['userID']);
								$blogObject->getUser()->setUserFirstName($row['userFirstName']);
								$blogObject->getUser()->setUserLastName($row['userLastName']);
								$blogObject->getUser()->setUserEmail($row['userEmail']);
								$blogObject->getUser()->setUserCity($row['userCity']);
								
								$blogObject->getCategory()->setCatID($row['catID']);
								$blogObject->getCategory()->setCatLabel($row['catLabel']);
								
								$blogObject->setBlogID($row['blogID']);
								$blogObject->setBlogHeadline($row['blogHeadline']);
								$blogObject->setBlogImagePath($row['blogImagePath']);
								$blogObject->setBlogImageAlignment($row['blogImageAlignment']);
								$blogObject->setBlogContent($row['blogContent']);
								$blogObject->setBlogDate($row['blogDate']);
							
								array_push($blogObjectsArray, $blogObject);
								$blogObject = new Blog();
							}
						}

						return $blogObjectsArray;
					
				
					}
					
					#***********************************************************#
					
				}
				
				
#*******************************************************************************************#