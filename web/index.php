<?php
// Simply open the rekons.json file and fetch a random line from it
$q = $_GET['q'] ?? null; // optionally allow a specific id to be fetched
$file = "rekons.json";
$fh = fopen($file, 'r');
$fcontents = fread($fh, filesize($file));
fclose($fh);
$data = json_decode($fcontents, true);
$total = count($data['rekon']);

if ($q == "list") {
  foreach ($data['rekon'] as $id => $val) {
    echo "<p>#{$id}: $val</p>";
  }
  die();
}

$rand = ($q == null) ? rand(0, $total-1) : $q;
if ($rand > $total) {
  echo "I need to rekon more to get to #".$rand;
} else {
  echo $data['rekon'][$rand];
}
?>
