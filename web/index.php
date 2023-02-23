<?php
// Get possible queries
$q = $_GET['q'] ?? null; // optionally allow a specific id to be fetched
$platform = $_GET['platform'] ?? "web"; // anything besides "web" will have /r/n instead of <br /> between rekons

// Get file contents of rekons.json
$file = "rekons.json";
$fh = fopen($file, 'r');
$fcontents = fread($fh, filesize($file));
fclose($fh);
$data = json_decode($fcontents, true);
$total = count($data['rekon']); // total rekons

// ?q=list creates an entire list of rekons and then quits
if ($q == "list" && $platform == "web") {
  foreach ($data['rekon'] as $id => $val) {
    echo "<p>#{$id}: $val</p>";
  }
  die();
} else if ($q == "list" && $platform != "web") {
  echo "Full list of rekons can be found at https://sambot.frwd.app?q=list";
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

  $matches = [];

  foreach ($data['rekon'] as $id => $val) {
    if (preg_match("/{$q}/i", $val)) {
      $matches[] = $val;
    }
  }

  if (count($matches) == 0) {
    echo "No rekons found related to $q";
  } else {
    shuffle($matches);
    foreach($matches as $id => $val) {
      if ($id > 0) echo ($platform == "web") ? "<br />" : "\n\r";
      echo $val;
      if ($id > 1) break;
    }
  }

}



?>
