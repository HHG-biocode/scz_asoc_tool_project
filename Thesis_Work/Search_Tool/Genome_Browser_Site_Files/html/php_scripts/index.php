<?php
// error reporting turned on for all errors.
error_reporting(E_ALL);
#echo json_encode(SQLite3::version());
// Defining variables from index.html
// Obtaining the cell type information that they submitted
if ($_SERVER['REQUEST_METHOD']=="GET") {
    $rsid = ($_GET['rsid']);
    $cell_type = ($_GET['cell_type']);
}
#var_dump($cell_type);
// Establishing a connection between the index PHP file and the server.
// Performing a simple search against our database
require_once(realpath(dirname(__FILE__) . '/SQLiteConnection.php'));
 // use SQLiteConnection; 
$db = new SQLite3('../db/scz_asoc.db');
#$result = $db->query('Select * FROM snps;');
#var_dump($result->fetchArray());

$i = 0;
$j=0;
$old_length = count($_GET['cell_type']);
$another_rsid = str_split($_GET['rsid'], 10);
#echo $another_rsid[0];
$another_length = count($another_rsid);
while ($i < $old_length) {
		$st = "statement$i";
		$$st = $db->prepare('SELECT * FROM snps WHERE rsid=:rsid AND cell_type=:cell_type');
		$y = $cell_type[$i];
		$title = "array$i";
		$$st->bindValue(':rsid',$rsid,SQLITE3_TEXT);
		$$st->bindValue(':cell_type', $y, SQLITE3_TEXT);
		$result = $$st->execute();
		if($result){
			$$title = $result->fetchArray(SQLITE3_ASSOC);
			#var_dump($$title);
		}
	$i++;
}
#$statement1->bindValue(':rsid',$rsid,SQLITE3_TEXT);
#$statement2->bindValue(':rsid',$rsid,SQLITE3_TEXT);
#$statement3->bindValue(':rsid',$rsid,SQLITE3_TEXT);
#$statement4->bindValue(':rsid',$rsid,SQLITE3_TEXT);
#$statement5->bindValue(':rsid',$rsid,SQLITE3_TEXT);
#$statement6->bindValue(':rsid',$rsid,SQLITE3_TEXT);
#$statement1->bindValue(':cell_type1',$cell_type[0],SQLITE3_TEXT);
#$statement2->bindValue(':cell_type2',$cell_type[1],SQLITE3_TEXT);
#$statement3->bindValue(':cell_type3',$cell_type[2],SQLITE3_TEXT);
#$statement4->bindValue(':cell_type4',$cell_type[3],SQLITE3_TEXT);
#$statement5->bindValue(':cell_type5',$cell_type[4],SQLITE3_TEXT);
#$statement6->bindValue(':cell_type6',$cell_type[5],SQLITE3_TEXT);

#$result1 = $statement1->execute();
#$result2 = $statement2->execute();
#$result3 = $statement3->execute();
#$result4 = $statement4->execute();
#$result5 = $statement5->execute();
#$result6 = $statement6->execute();
#$array1 = $result1->fetchArray(SQLITE3_ASSOC);
#$array2 = $result2->fetchArray(SQLITE3_ASSOC);
#$array3 = $result3->fetchArray(SQLITE3_ASSOC);
#$array4 = $result4->fetchArray(SQLITE3_ASSOC);
#$array5 = $result5->fetchArray(SQLITE3_ASSOC);
#$array6 = $result6->fetchArray(SQLITE3_ASSOC);
#var_dump($array1);
#var_dump($array2);
// establishing the output page, jazzing up results and providing external links (hopefully helpful)
echo "<link rel='stylesheet' href = '../main.css' type = 'text/css'>";
echo "<h1> Allele Specific Open Chromatin Database Request </h1>";
echo "<nav class = 'snp_return'>
	<ul>
		<li class = 'snp_return'> <a href = '../browser.html'> Genome Browser </a> </li> 
		<li class = 'snp_return'> <a href = '../index.html'>  Home Page </a> </li>
	</ul>
</Nav>";

// translating two-letter cell type acronym to the full cell type name 
if ($cell_type == 'DN') {
	$cell_name = "Dopaminergic Neurons";
}
if ($cell_type == 'MG') {
	$cell_name = "Microglia";
}
if ($cell_type == 'IPSC'){
	$cell_name = 'Induced Pluripotent Stem Cells';
}
if ($cell_type == 'NPC'){
	$cell_name = 'Neural Progenitor Cell';
}
if ($cell_type == 'GA') {
	$cell_name = 'GABAergic Neurons';
}
if ($cell_type =='GN') {
	$cell_name = 'Glutamatergic Neurons';
}

$i = 0;
$functional_celltype = [];
$org1 = [];
$org2 = [];
$org3 = [];
$org4 = [];
$org5 = [];
$org6 = [];
$functional_results=[];
#echo $old_length,$another_length;
while($i < $old_length) {
		$title = "array$i";
		if($$title){
			array_push($functional_celltype, $$title['cell_type']);
			array_push($functional_results, $$title);
			}
		$i++;
}
#var_dump($result);

$length = count($functional_celltype);
#echo $length;
if($functional_celltype) {
	echo "<p> This SNP is located in SCZ ASoC in ";
	$j = 0;
	while($j < $length) {
		$y = $functional_celltype[$j];
		if($j != $length-1 and $length != 1 and $length !=2) {
			echo "$y, ";
			$j++;
		}
		elseif($j == $length-1 and $length == 2) {
			echo "and $y";
			$j++;
		}
		elseif($j == $length-1 and $length != 1) {
			echo "and $y! </p>";
			$j++;
		}
		elseif($j == 0 and $length ==1) {
			echo "$y!</p>";
			$j++;
		}
		elseif($j == 0 and $length ==2){
			echo "$y ";
			$j++;
		}
	}	
}
else {
	echo "<p> This SNP is not located in SCZ ASoC in the cell types queried. Try again! </p>";
}
echo "<style>
table,tr,td,th {
  width: 100%;
  border: 1px solid;
}
</style>";
echo "<table>";
	echo "<tr>";
		echo "<th> Cell Type </th>";
		echo "<th> Rsid </th>";
		echo "<th> Chromosome </th> ";
		echo "<th> Position </th> ";
		echo "<th> Reference Allele </th>";
		echo "<th> Alternative Allele </th>";
		echo "<th> Number of Reference Allele Calls </th>";
		echo "<th> Number of Alternative Allele Calls </th>";
		echo "<th> Total Number of Calls </th>";
		echo "<th> -log10(FDR) </th> ";
	echo "</tr>";

		$lengthy = count($functional_results);
		$i = 0;
		while($i < $lengthy) {
			echo "<tr>";
			$e = $functional_results[$i];
			echo "<td> $e[cell_type] </td>";
			echo "<td> $e[rsid] </td>";
			echo "<td> $e[Chrom] </td>";
			echo "<td> $e[position] </td>";
			echo "<td> $e[ref_allele] </td>";
			echo "<td> $e[alt_allele] </td>";
			echo "<td> $e[ref_n] </td>";
			echo "<td> $e[alt_n] </td>";
			echo "<td> $e[depth] </td>";
			echo "<td> $e[logfdr] </td>";
			echo "</tr>";
			$i++;
		}
echo "</table>";
	echo "<p> For more information, check out the following resources: <br>
	<a href = ' https://www.ncbi.nlm.nih.gov/snp/$rsid?vertical_tab=true'> NCBI Entry on $rsid </a> </p>" ;
	echo "<a href = ' https://genome.ucsc.edu/cgi-bin/hgTracks?hgsid=1135239051_LNQe9K5A2FfaeN4hg6rROmIfLMGt&org=Human&db=hg38&position=$rsid&pix=1105'> UCSC Genome Browser Search </a>
	</p>";
	echo "<a href = 'https://www.disgenet.org/browser/2/1/0/$rsid/'> DISGENET Entry on $rsid </a>";
	echo "<style> form {
		text-align: center;
		background-color: white-smoke; 
		button-color: white-smoke;
	}";
	echo "</style>";
	echo '<form action = "php_search.php" method = "get">';
	echo "<label for ='rsid'></label>";
	echo "<input type = 'submit' value = '$rsid' id = rsid name = rsid>";
?>