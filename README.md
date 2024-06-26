# php-blog-oop

Ein Blog-System besteht auf zwei PHP-Seiten (index.php und dashboard.php).

# index.php

Die Indexseite ist diejenige Seite, die jeder Besucher des Blogs zu sehen bekommt. Hier
werden die vorhandenen Blogeinträge angezeigt, sowie ein Navigationselement, über das
man die Blogbeiträge nach Kategorien filtern kann.

Die Startseite index.php ist diejenige Seite, die jeder Besucher des Blogs zu sehen
bekommt. Hier werden die vorhandenen Blogeinträge angezeigt, sowie ein
Navigationselement, über das man die Blogbeiträge nach Kategorien filtern kann.
Im Kopf der Seite index.php gibt es ein Login-Formular, über das sich bestehende
Redakteure im Backend ( dashboard.php ) anmelden können. Nach erfolgreichem Login soll
der Redakteur direkt auf die Seite dashboard.php umgeleitet werden.
Ist ein Redakteur bereits eingeloggt, soll er statt des Login-Formulars einen Link auf die Seite
dashboard.php sowie einen Logout-Link vorfinden.

# dashboard.php

Auf der Dashboardseite kann der Redakteur entweder neue Kategorien oder neue
Blogbeiträge anlegen.

Das Backend ist zugriffgeschützt und darf nur von eingeloggten Redakteuren aufgerufen
werden.
Das Backend besteht primär aus zwei Formularen: Eines zum Anlegen einer neuen Kategorie,
das andere zum Anlegen eines neuen Blogbeitrags.
Auf der dashboard.php kann der Redakteur also entweder neue Kategorien anlegen, oder
neue Blogbeiträge und diese einer bestehenden Kategorie zuweisen.
Optional kann der Redakteur zu einem Blogbeitrag ein Bild hochladen und auswählen, ob
dieses später links oder rechts vom Inhaltstext angezeigt wird.
Im Kopf der dashboard.php soll der Redakteur zwei Links vorfinden: Einen Links zur Seite
index.php und einen Logout-Link.
