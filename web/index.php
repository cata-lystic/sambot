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

// If $q is numeric or empty, fetch a random or desired ID
if ($q == null || is_numeric($q)) {
  $rand = ($q == null) ? rand(0, $total-1) : $q;
  if ($rand > $total) {
    echo "I need to rekon more to get to #".$rand;
  } else {
    echo $data['rekon'][$rand];
  }
}

// If $q is a string, search each rekon to see if that word is in it
if ($q != null && !is_numeric($q)) {

  $results = 0;

  foreach ($data['rekon'] as $id => $val) {
    if (preg_match("/{$q}/i", $val)) {
      echo $val."\n\r";
      $results++;
    }
  }

  if ($results == 0) { echo "No rekons found related to $q"; }

}



?>
