<?php 
//require '../app/config/dbConfig.ini';
//require '../app/dbconf/domainConfig.ini';


/* function dbConn(){
//Parse INI to DBConf
$file = '../app/config/dbConfig.ini';

$config = parse_ini_file($file);

$host = $config['hostname'];
$user = $config['username'];
$pass = $config['password'];
$dbname = $config['database'];
$port = $config['port'];


    
//DB Connect
global $conn;
//$conn = new mysqli("$host", "$user", "$pass", "$dbname", "$port");
$conn = new mysqli($config['hostname'], $config['username'], $config['password'], $config['database'], $config['port']);
//Erstelle Datenbank, wenn nicht vorhanden
/* $sql = "CREATE DATABASE $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Keine vorhandene Datenbak gefunden. Folgende Datenbank wurde nun erstellt: " . $dbname;
} */
/* //Prüfe Verbindung
if ($conn->connect_error) {
    die("Verbindung mit Datenbank fehlgeschlagen: " . $conn->connect_error);
}
}
 */ 

require_once 'dbConnect.php';

//Loop 
$dbDir = '../app/config/';

$dirConf = scandir($dbDir);
foreach ($dirConf as $dirConfs) {
    tableCreate();
} 

print_r($dirConf);

//Erstellen der Tabelle anhand der .ini-Datei
function tableCreate() {

    $db = Database::getInstance(); //Singleton-Instanz holen
    $conn = $db->getConnection(); //Verbindung holen

    $dbfile = '../app/dbconf/domainConfig.ini';
    $dbconf = parse_ini_file($dbfile, true);

    print_r($dbconf['table']['name']);

    // Tabellenname und Spalten auslesen
    $tableName = $dbconf['table']['name'];
    $columns = $dbconf['columns'];

    // SQL-Query für die Tabellenstruktur erstellen
    $sql = "CREATE TABLE IF NOT EXISTS `$tableName` (";
    $columnDefinitions = [];

    foreach ($columns as $columnName => $columnType) {
        $columnDefinitions[] = "`$columnName` $columnType";
    }

    $sql .= implode(", ", $columnDefinitions);
    $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";


    // Tabelle erstellen
    if ($conn->query($sql) === TRUE) {
        echo "Tabelle `$tableName` erfolgreich erstellt.";
    } else {
        echo "Fehler beim Erstellen der Tabelle: " . $conn->error;
    }

}

/* $conn = new mysqli("$host", "$user", "$pass", "$dbname", "$port");
$sql = "INSERT INTO domains (name, url) VALUES ('ddev', 'test.ddev.site/')";
if ($conn->multi_query($sql) === TRUE) {
    echo "New records created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
$conn->close(); */



//tableRead

$db = Database::getInstance(); //Singleton-Instanz holen
$conn = $db->getConnection(); //Verbindung holen

$tableName = 'domains';
// SQL-Abfrage zur Auswahl aller Daten aus der Tabelle
$sql = "SELECT * FROM `$tableName`";
$result = $conn->query($sql);

// HTML-Struktur für die Anzeige
echo "<h2>Tabelle: $tableName</h2>";

if ($result->num_rows > 0) {
    echo "<div class='dbtable'>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr>";

    // Spaltennamen anzeigen
    while ($fieldinfo = $result->fetch_field()) {
        echo "<th>" . htmlspecialchars($fieldinfo->name) . "</th>";
    }
    
    echo "</tr>";

    // Zeilen aus der Tabelle ausgeben
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . htmlspecialchars($value) . "</td>";
        }
        echo "</tr>";
    }
    
    echo "</table></div>";
    
} else {
    echo "Keine Daten in der Tabelle gefunden.";
}



