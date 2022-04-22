<?php
if ($_SERVER['REQUEST_METHOD']=="GET") {
    $rsid = ($_GET['rsid']);
}   
error_reporting(E_ALL);

require_once(realpath(dirname(__FILE__) . '/SQLiteConnection.php'));
 // use SQLiteConnection; 
$db = new SQLite3('../db/scz_asoc.db');
#$result = $db->query('Select * FROM snps;');
#var_dump($result->fetchArray());

$statement = $db->prepare('SELECT Chrom, position FROM snps WHERE rsid=:rsid;');
$statement->bindValue(':rsid',$rsid,SQLITE3_TEXT);
$result = $statement->execute();
$row = $result->fetchArray();
$low_value = $row[1]-500;
$high_value = $row[1]+500;
  
echo '<script src="http://localhost:40080/bower_components/webcomponentsjs/webcomponents-loader.js" ></script>';
echo '<link rel="import" href="http://localhost:40080/components/chart-controller/chart-controller.html">';
echo "<chart-controller ref=hg38 num-of-subs=1 group-id-list='genes' default-track-id-list= 'GA_20_lines' title-text='Allele Specific Open Chromatin Browser' coordinate = $row[0]:$low_value-$high_value > </chart-controller>";
?>