<?php
#*******************************************************************************************#
				
				
				#******************************************#
				#********** ENABLE STRICT TYPING **********#
				#******************************************#
				
				/*
					Erklärung zu 'strict types' in Projekt '01a_klassen_und_instanzen'
				*/
				declare(strict_types=1);
				
#***************************************************************************************#


				#****************************************#
				#********** PAGE CONFIGURATION **********#
				#****************************************#
				
				require_once('./include/config.inc.php');
				require_once('./include/db.inc.php');
				require_once('./include/form.inc.php');
				include_once('./include/dateTime.inc.php');

				
				#********** INCLUDE CLASSES **********#
				
				require_once('./class/UserInterface.class.php');
				require_once('./class/User.class.php');
				require_once('./class/CategoryInterface.class.php');
				require_once('./class/Category.class.php');
				require_once('./class/BlogInterface.class.php');
				require_once('./class/Blog.class.php');

				
#****************************************************************************************************#


				#**************************************#
				#********** OUTPUT BUFFERING **********#
				#**************************************#
				
				/*
					Output Buffering erstellt auf dem Server einen Speicherbereich, in dem Frontend-Ausgaben 
					gespeichert (und nicht sofort im Frontend ausgegeben) werden, bis der Buffer-Inhalt
					explizit gesendet werden soll.
					
					Hat man beispielsweise Probleme mit der Fehlermeldung
					"Warning: Cannot modify header information - headers already sent by 
					(output started at /some/file.php:12) in /some/file.php on line 23",
					hilft ein Buffering des Header-Versands. Hiermit wird der Header solange nicht gesendet, bis das PHP-Skript
					eine explizite Anweisung dazu findet, bspw. ob_end_flush() ODER automatisch am Ende des Skripts.
					
					Diese Funktion ob_start() aktiviert die Ausgabepufferung. Während die Ausgabepufferung aktiv ist, 
					werden Skriptausgaben (inklusive der Headerinformationen) nicht direkt an den Client 
					weitergegeben, sondern in einem internen Puffer gesammelt.
				*/
				if( ob_start() === false ) {
					// Fehlerfall
if(DEBUG)		echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER beim Starten des Output Bufferings! <i>(" . basename(__FILE__) . ")</i></p>\r\n";				
					
				} else {
					// Erfolgsfall
if(DEBUG)		echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Output Buffering erfolgreich gestartet. <i>(" . basename(__FILE__) . ")</i></p>\r\n";									
				}


#***********************************************************************************************************************#
					
			
				#************************************#
				#********** VALIDATE LOGIN **********#
				#************************************#
				
				/*
					Für die Fortsetzung der Session muss hier der gleiche Name ausgewählt werden,
					wie beim Login-Vorgang, damit die Seite weiß, welches Cookie sie vom Client auslesen soll
				*/
				session_name("wwwblogprojectde");
				
				
				
				#********** START/CONTINUE SESSION **********#
				if( session_start() === false ) {
					// Fehlerfall
if(DEBUG)		echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER beim Starten der Session! <i>(" . basename(__FILE__) . ")</i></p>\n";				
									
				} else {
					// Erfolgsfall
if(DEBUG)		echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Session <i>'blogOOP'</i> erfolgreich gestartet. <i>(" . basename(__FILE__) . ")</i></p>\n";				

				
if(DEBUG)		echo "<pre class='debugAuth value'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG)		print_r($_SESSION);					
if(DEBUG)		echo "</pre>";

					
					#*******************************************#
					#********** CHECK FOR VALID LOGIN **********#
					#*******************************************#

					
					#********** A) NO VALID LOGIN **********#				
					if( isset($_SESSION['ID']) === false OR $_SESSION['IPAddress'] !== $_SERVER['REMOTE_ADDR'] ) {
						// Fehlerfall | User ist nicht eingeloggt
if(DEBUG)			echo "<p class='debug auth err'><b>Line " . __LINE__ . "</b>: User ist nicht eingeloggt. <i>(" . basename(__FILE__) . ")</i></p>\n";				

						session_destroy();
						
						$loggedIn = false;


					#********** B) VALID LOGIN **********#
					} else {
						// Erfolgsfall | User ist eingeloggt
if(DEBUG)		echo "<p class='debug auth ok'><b>Line " . __LINE__ . "</b>: User ist eingeloggt. <i>(" . basename(__FILE__) . ")</i></p>\n";				
					
						session_regenerate_id(true);
												
						$loggedIn = true;
						
					} // CHECK FOR VALID LOGIN END
					
				} // VALIDATE LOGIN END


#***************************************************************************************#


				#******************************************#
				#********** INITIALIZE VARIABLES **********#
				#******************************************#
				
				$blog						= new Blog();
				$category 				= new Category();
				
				$loginError 			= NULL;
				$categoryFilterID		= NULL;
				
				
				
#****************************************************************************************************#


				#****************************************#
				#********** PROCESS FORM LOGIN **********#
				#****************************************#				
						
				// Schritt 1 FORM: Prüfen, ob Formular abgeschickt wurde
				if( isset($_POST['formLogin']) === true ) {
if(DEBUG)		echo "<p class='debug'>🧻 Line <b>" . __LINE__ . "</b>: Formular 'Login' wurde abgeschickt... <i>(" . basename(__FILE__) . ")</i></p>";	


					// Schritt 2 FORM: Werte auslesen, entschärfen, DEBUG-Ausgabe
if(DEBUG)		echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Werte werden ausgelesen und entschärft... <i>(" . basename(__FILE__) . ")</i></p>\n";
					
										
					#********** GENERATE NEW USER OBJECT **********#
					//$userFirstName=NULL, $userLastName=NULL, $userEmail=NULL,
					//$userCity=NULL, $userPassword=NULL,
					//$userID=NULL
					$user = new User( userEmail:$_POST['f1'] );				
					
					#********** GENERATE HELPER VARIABLES **********#
					$password = sanitizeString( $_POST['f2'] );
if(DEBUG_V)		echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$password: $password <i>(" . basename(__FILE__) . ")</i></p>\n";
					

					// Schritt 3 FORM: ggf. Werte validieren
if(DEBUG)		echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Feldwerte werden validiert... <i>(" . basename(__FILE__) . ")</i></p>\n";
					/*
						[x] Validieren der Formularwerte (Feldprüfungen)
						[ ] Vorbelegung der Formularfelder für den Fehlerfall 
						[ ] Abschließende Prüfung, ob das Formular insgesamt fehlerfrei ist
					*/
					$errorUserEmail	= validateEmail( $user->getUserEmail() );
					$errorPassword		= validateInputString($password, minLength:4);
					
					
					#********** FINAL FORM VALIDATION **********#					
					if( $errorUserEmail !== NULL OR $errorPassword !== NULL ) {
						// Fehlerfall
if(DEBUG)			echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Formular enthält noch Fehler! <i>(" . basename(__FILE__) . ")</i></p>";						
						$loginError = 'Login Email oder Password falsch!';
						
					} else {
						// Erfolgsfall
if(DEBUG)			echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Das Formular ist formal fehlerfrei. <i>(" . basename(__FILE__) . ")</i></p>";						
									
						// Schritt 4 FORM: Daten weiterverarbeiten
if(DEBUG)			echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Daten werden weiterverarbeitet... <i>(" . basename(__FILE__) . ")</i></p>\n";



						#***********************************#
						#********** DB OPERATIONS **********#
						#***********************************#
						
						// Schritt 1 DB: DB-Verbindung herstellen
						$PDO = dbConnect('blog_oop');
						
						#********** FETCH USER DATA FROM DB BY EMAIL **********#	
if(DEBUG)			echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Lese Userdaten aus DB aus... <i>(" . basename(__FILE__) . ")</i></p>\n";
						
						
						$userExists = $user->fetchFromDB($PDO);

if(DEBUG_V)			echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$user <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_V)			print_r($user);					
if(DEBUG_V)			echo "</pre>";	
						
						
						#********** CLOSE DB CONNECTION **********#
if(DEBUG_DB)		echo "<p class='debug db'><b>Line " . __LINE__ . "</b>: DB-Verbindung geschlossen. <i>(" . basename(__FILE__) . ")</i></p>\n";
						unset($PDO);

						#********** 1. VERIFY LOGIN EMAIL **********#
if(DEBUG)			echo "<p class='debug'><b>Line " . __LINE__ . "</b>: Validiere Email-Adresse... <i>(" . basename(__FILE__) . ")</i></p>\n";
												
						/*
							In $userData ist nur dann ein Datensatz enthalten, wenn die EmailAdresse aus dem Formular 
							mit einer Emailadresse aus der DB übereinstimmt.						
							Wenn ein passender Datensatz gefunden wurde, liefert $PDOStatement->fetch() an dieser
							Stelle ein eindimensionales Array mit den ausgelesenen Datenfeldwerten zurück.
							Wenn KEIN passender Datensatz gefunden wurde, enthält $row an dieser Stelle false.
						*/
						if( $userExists === false ) {
							// Fehlerfall
if(DEBUG)				echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Die Email-Adresse: '{$user->getUserEmail()}' wurde nicht in der DB gefunden! <i>(" . basename(__FILE__) . ")</i></p>\n";				
							
							// NEUTRALE Fehlermeldung an den User
							$loginError = 'Login Email oder Password falsch!';
							
						} else {
							// Erfolgsfall
if(DEBUG)				echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Die Email-Adresse '{$user->getUserEmail()}' wurde in der DB gefunden. <i>(" . basename(__FILE__) . ")</i></p>\n";				
							
							
							#********** 2. VERIFY PASSWORD **********#
							/*
								Die Funktion password_verify() vergleicht einen String mit einem mittels
								password_hash() verschlüsseltem Passwort. Die Rückgabewerte sind true oder false.
							*/
							if( password_verify($password, $user->getUserPassword()) === false ) {
								// Fehlerfall
if(DEBUG)					echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Das Passwort aus dem Formular stimmt NICHT mit dem Passwort aus der DB überein! <i>(" . basename(__FILE__) . ")</i></p>\n";				
								
								// NEUTRALE Fehlermeldung an den User
								$loginError = 'Login Email oder Password falsch!';
							
							} else {
								// Erfolgsfall
if(DEBUG)					echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Passwort stimmt mit DB überein. LOGIN OK. <i>(" . basename(__FILE__) . ")</i></p>\n";				
								

								#********** 3. PROCESS LOGIN **********#
if(DEBUG)					echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Login wird durchgeführt... <i>(" . basename(__FILE__) . ")</i></p>\n";
								
													
								#********** START SESSION **********#
								if( session_start() === false ) {
									// Fehlerfall
if(DEBUG)						echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER beim Starten der Session! <i>(" . basename(__FILE__) . ")</i></p>\n";				
									
									$loginError = 'Der Loginvorgang konnte nicht durchgeführt werden!<br>
														Bitte überprüfen Sie die Sicherheitseinstellungen Ihres Browsers und 
														aktivieren Sie die Annahme von Cookies für diese Seite.';
									
								} else {
									// Erfolgsfall
if(DEBUG)						echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Session erfolgreich gestartet. <i>(" . basename(__FILE__) . ")</i></p>\n";				
									
									
									#********** SAVE USER DATA INTO SESSION FILE **********#
if(DEBUG)						echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Schreibe Userdaten in Session... <i>(" . basename(__FILE__) . ")</i></p>";

									$_SESSION['IPAddress']			= $_SERVER['REMOTE_ADDR'];
									$_SESSION['ID']					= $user->getUserID();
									/*
										Um auf der Dashboard-Seite eine DB-Operation zu sparen,
										können die zusätzlichen Userdaten wie Vorname und Nachname
										ebenfalls in die Session geschrieben werden.
									*/
									$_SESSION['userFirstName']		= $user->getUserFirstName();
									$_SESSION['userLastName']		= $user->getUserLastName();
									

if(DEBUG_V)						echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$_SESSION <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_V)						print_r($_SESSION);					
if(DEBUG_V)						echo "</pre>";
									
							
									// User ist eingeloggt
if(DEBUG)						echo "<p class='debug auth ok'><b>Line " . __LINE__ . "</b>: User: <i>{$_SESSION['userFirstName']} {$_SESSION['userLastName']} </i> ist eingeloggt. <i>(" . basename(__FILE__) . ")</i></p>\n";				
					
									$loggedIn = true;
									#********** REDIRECT TO DASHBOARD PAGE **********#
									header('LOCATION: dashboard.php');
									

								} // 3. PROCESS LOGIN END
								
							} // 2. VERIFY PASSWORD END
							
						} // 1. VERIFY LOGIN EMAIL END
						
					} // FINAL FORM VALIDATION END
					
				}  // PROCESS FORM LOGIN END
				

#***************************************************************************************#

			
				#********************************************#
				#********** PROCESS URL PARAMETERS **********#
				#********************************************#
				
				// Schritt 1 URL: Prüfen, ob Parameter übergeben wurde
				if( isset($_GET['action']) === true ) {
if(DEBUG)		echo "<p class='debug'>🧻 <b>Line " . __LINE__ . "</b>: URL-Parameter 'action' wurde übergeben. <i>(" . basename(__FILE__) . ")</i></p>\n";										
			
					// Schritt 2 URL: Werte auslesen, entschärfen, DEBUG-Ausgabe
if(DEBUG)		echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Werte werden ausgelesen und entschärft... <i>(" . basename(__FILE__) . ")</i></p>\n";
					$action = sanitizeString($_GET['action']);
if(DEBUG_V)		echo "<p class='debug value'>Line <b>" . __LINE__ . "</b>: \$action: $action <i>(" . basename(__FILE__) . ")</i></p>";
		
					// Schritt 3 URL: ggf. Verzweigung
							
							
					#********** LOGOUT **********#					
					if( $_GET['action'] === 'logout' ) {
if(DEBUG)			echo "<p class='debug'>📑 Line <b>" . __LINE__ . "</b>: 'Logout' wird durchgeführt... <i>(" . basename(__FILE__) . ")</i></p>";	
						
						session_destroy();
						header("Location: index.php");
						exit();
						
						
					#********** FILTER BY CATEGORY **********#					
					} elseif( $action === 'filterByCategory' ) {
if(DEBUG)			echo "<p class='debug'>📑 Line <b>" . __LINE__ . "</b>: Kategoriefilter aktiv... <i>(" . basename(__FILE__) . ")</i></p>";				
						
						
						#********** FETCH SECOND URL PARAMETER **********#
						if( isset($_GET['catID']) === true ) {
							
							// use $categoryFilterID as flag
							$categoryFilterID = sanitizeString($_GET['catID']);
if(DEBUG_V)				echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$categoryFilterID: $categoryFilterID <i>(" . basename(__FILE__) . ")</i></p>\n";			
							
						
						}

					} // BRANCHING END
					
				} // PROCESS URL PARAMETERS END
			
			
#***************************************************************************************#


				#************************************************#
				#********** FETCH BLOG ENTRIES FORM DB **********#
				#************************************************#				
				
				
				// Schritt 1 DB: DB-Verbindung herstellen
				/*
					Das PDO-Objekt sollte immer außerhalb der DB-Methoden
					erzeugt und dann an diese übergeben werden, da ansonsten
					keine Methodenübergreifenden Transactions funktionieren
					würden.
				*/
				$PDO = dbConnect('blog_oop');
				
if(DEBUG)	echo "<p class='debug'>🧻 <b>Line " . __LINE__ . "</b>: Fetching Blogs data from database... <i>(" . basename(__FILE__) . ")</i></p>\r\n";				
				
				#*** TESTING CATEGORY ID STRING OR INT ****# 
				//$allBlogObjectsArray = $blog->fetchFromDBByCategory($PDO, '3');
				//$allBlogObjectsArray = $blog->fetchFromDBByCategory($PDO, 3);
				$allBlogObjectsArray = $blog->fetchAllFromDB($PDO);
/*								
if(DEBUG_V)	echo "<pre class='debug value'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_V)	print_r($allBlogObjectsArray);					
if(DEBUG_V)	echo "</pre>";
*/			
				// DB-Verbindung schließen
if(DEBUG_DB)echo "<p class='debug db'><b>Line " . __LINE__ . "</b>: DB-Verbindung geschlossen. <i>(" . basename(__FILE__) . ")</i></p>\n";
				unset($PDO);
				
				
				
#***************************************************************************************#


				#******************************************************#
				#******* FETCH BLOG ENTRIES BY CATEGORY FORM DB *******#
				#******************************************************#				

				// Schritt 1 DB: DB-Verbindung herstellen
				/*
					Das PDO-Objekt sollte immer außerhalb der DB-Methoden
					erzeugt und dann an diese übergeben werden, da ansonsten
					keine Methodenübergreifenden Transactions funktionieren
					würden.
				*/
				$PDO = dbConnect('blog_oop');
				

				#********** FILTER BLOG ENTRIES BY CATEGORY ID **********#					
				if( $categoryFilterID !== NULL ) {
if(DEBUG)		echo "<p class='debug'>📑 Line <b>" . __LINE__ . "</b>: Filtere Blog-Einträge nach Kategorie-ID$categoryFilterID... <i>(" . basename(__FILE__) . ")</i></p>";
			

				
					
					$allBlogObjectsArray = $blog->fetchFromDBByCategory($PDO, $categoryFilterID);
/*								
if(DEBUG_V)	echo "<pre class='debug value'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_V)	print_r($allBlogObjectsArray);					
if(DEBUG_V)	echo "</pre>";
*/			
				// DB-Verbindung schließen
if(DEBUG_DB)echo "<p class='debug db'><b>Line " . __LINE__ . "</b>: DB-Verbindung geschlossen. <i>(" . basename(__FILE__) . ")</i></p>\n";
				unset($PDO);
								
				}


			
#***************************************************************************************#


				#**********************************************#
				#********** FETCH CATEGORIES FROM DB **********#
				#**********************************************#
if(DEBUG)	echo "<p class='debug'>📑 Line <b>" . __LINE__ . "</b>: Lade Kategorien aus DB... <i>(" . basename(__FILE__) . ")</i></p>";	
				
				// Schritt 1 DB: DB-Verbindung herstellen
				$PDO = dbConnect('blog_oop');

				$allCategories = $category->fetchAllFromDB($PDO);

				// DB-Verbindung schließen
if(DEBUG_DB)echo "<p class='debug db'><b>Line " . __LINE__ . "</b>: DB-Verbindung geschlossen. <i>(" . basename(__FILE__) . ")</i></p>\n";
				unset($PDO);
/*
if(DEBUG_V)	echo "<pre class='debug value'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_V)	print_r($allCategories);					
if(DEBUG_V)	echo "</pre>";
*/
			
#***************************************************************************************#
?>

<!doctype html>

<html>

	<head>
		<meta charset="utf-8">
		<title>PHP-Projekt Blog</title>
		<link rel="stylesheet" href="./css/main.css">
		<link rel="stylesheet" href="./css/debug.css">
	</head>

	<body>
		
		<!-- ---------- PAGE HEADER START ---------- -->
		<header class="fright">
			
			<?php if( $loggedIn === false ): ?>
				<?php if($loginError): ?>
				<p class="error"><b><?= $loginError ?></b></p>
				<?php endif ?>
				
				<!-- -------- Login Form START -------- -->
				<form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
					<input type="hidden" name="formLogin">
					<input type="text" name="f1" placeholder="Email">
					<input type="password" name="f2" placeholder="Password">
					<input type="submit" value="Login">
				</form>
				<!-- -------- Login Form END -------- -->
				
			<?php else: ?>
				<!-- -------- PAGE LINKS START -------- -->
				<p class='author'>Eingelogd Als: <?=$_SESSION['userFirstName'] . " " . $_SESSION['userLastName'] ?></p>
				<a href='dashboard.php'>zum Dashboard >></a><br>
				<a href="?action=logout"><< Logout</a>
				<!-- -------- PAGE LINKS END -------- -->
			<?php endif ?>
		
		</header>
		
		<div class="clearer"></div>
				
		<br>
		<hr>
		<br>		
		<!-- ---------- PAGE HEADER END ---------- -->
		
		
		
		<h1>PHP-Projekt Blog</h1>
		<p><a href='index.php'>:: Alle Einträge anzeigen ::</a></p>
		
		
		<!-- ---------- TEST BLOG START ---------- -->
<!--		
			<h3>Leere & Named Parameter Objekte: User::, Category::, Blog::</h3>
			<?php
			
				//$newUser = new User(userLastName:'NewUser');
				//$newCategory = new Category(catLabel:'games');
				//$newBlog = new Blog();
			?>
		
			<h3>Eingefülte Objekte: User::, Category::, Blog::</h3>
			<?php
				//$userFirstName=NULL, $userLastName=NULL, $userEmail=NULL,
				//$userCity=NULL, $userPassword=NULL,
				//$userID=NULL
				
				//$newUser1 = new User('Rolan', 'Tayarah', 'rolan@mail.com', 'Magdeburg', '1234', 001);
				
				// $catID=NULL, $catLabel=NULL
				
				//$newCategory1 = new Category(001, 'Lifstyle');
				
				//$user = new User(), $category = new Category,
				//$blogHeadline=NULL, $blogImagePath=NULL,
				//$blogImageAlignment=NULL, $blogContent=NULL,
				//$blogDate=NULL, $blogID=NULL
				
				//$newBlog1 = new Blog($newUser1, $newCategory1, 'First Article', '/uploads/img/01.png', 'right', 'Content hier Long Text', '05.10.2023', 001);
			?>
-->
		<!-- ----------- TEST BLOG END ----------- -->	
		
		
		<!-- ---------- BLOG ENTRIES START ---------- -->		
		<main class="blogs fleft">
			<?php if( $allBlogObjectsArray != true ): ?>
				<p class="info">Noch keine Blogeinträge vorhanden.</p>
			
			<?php else: ?>
			<p><?= $category->getCatID() ?></p>
				<?php foreach( $allBlogObjectsArray AS $blogItemObject ): ?>
			
					<?php $dateTimeArray = isoToEuDateTime( $blogItemObject->getBlogDate() ) ?>
					
					<article class='blogEntry'>
			
						<a name='entry<?= $blogItemObject->getBlogID() ?>'></a>
						<p class='fright'><a href='?action=filterByCategory&catID=<?= $blogItemObject->getCategory()->getCatID() ?>'>Kategorie: <?= $blogItemObject->getCategory()->getCatLabel() ?></a></p>
						<h2 class='_clearer'><?= $blogItemObject->getBlogHeadline() ?></h2>

						<p class='author'><?= $blogItemObject->getUser()->getUserFirstName() ?> <?= $blogItemObject->getUser()->getUserLastName() ?> (<?= $blogItemObject->getUser()->getUserCity() ?>) schrieb am <?= $dateTimeArray['date'] ?> um <?= $dateTimeArray['time'] ?> Uhr:</p>
						
						<p class='blogContent'>
						
							<?php if($blogItemObject->getBlogImagePath()): ?>
								<img class='<?= $blogItemObject->getBlogImageAlignment() ?>' src='<?= $blogItemObject->getBlogImagePath() ?>' alt='' title=''>
							<?php endif ?>
							
							<?= nl2br( $blogItemObject->getBlogContent() ) ?>	
						</p>
						
						<div class='clearer'></div>
						
						<br>
						<hr>
						
					</article>
					
				<?php endforeach ?>
			<?php endif ?>
			
		</main>		
		<!-- ---------- BLOG ENTRIES END ---------- -->
		
		
		
		<!-- ---------- CATEGORY FILTER LINKS START ---------- -->		
		<nav class="categories fright">

			<?php if( $allCategories === false ): ?>
				<p class="info">Noch keine Kategorien vorhanden.</p>
			
			<?php else: ?>
			
				<?php foreach( $allCategories AS $categorySingleObject ): ?>
					<p><a href="?action=filterByCategory&catID=<?= $categorySingleObject->getCatID()?>" <?php if( $categorySingleObject->getCatID() == $categoryFilterID ) echo 'class="active"' ?>><?= $categorySingleObject->getCatLabel() ?></a></p>
				<?php endforeach ?>

			<?php endif ?>
		</nav>

		<div class="clearer"></div>
		<!-- ---------- CATEGORY FILTER LINKS END ---------- -->
		
	</body>

</html>
