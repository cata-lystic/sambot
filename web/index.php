<?php
// Get possible queries
$q = $_GET['q'] ?? null; // optionally allow a specific id to be fetched
$limit = $_GET['limit'] ?? 3; // amount of search results to return
$shuffle = $_GET['shuffle'] ?? 1; // whether to shuffle search results
$platform = $_GET['platform'] ?? "web"; // anything besides "web" will have /r/n instead of <br /> between rekons
$domain = "https://sambot.frwd.app"; // i'm lazy

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
  echo "Full list of rekons can be found at {$domain}?q=list";
  die();
}

// If $q is numeric or empty, fetch a random or desired ID
if ($q == null || is_numeric($q)) {
  $rand = ($q == null) ? rand(0, $total-1) : $q;
  if ($rand > $total) {
    echo "I need to rekon more to get to #".$rand;
  } else {
    echo "`".$data['rekon'][$rand]."`";
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
    if ($shuffle == 1) shuffle($matches);
    foreach($matches as $id => $val) {
      if ($id > $limit-1) break; // stop after the $limit
      if ($id > 0) echo ($platform == "web") ? "<br />" : "\n\r";
      echo "`".$val."`";
    }
  }

}

// Show the API info box to the web users
if ($platform == "web") {

  echo "
  <div id='info' style='position: fixed; right: 5%; bottom: 5%; background: #dddddd; padding: 3px 10px;'>
    <h1>API</h1>
    <p>Random rekon<br />
    <a href='{$domain}'>{$domain}</a></p>

    <p>Rekon list<br />
    <a href='{$domain}?q=list'>{$domain}?q=list</a></p>

    <p>Link to rekon list<br />
    <a href='{$domain}?q=list&platform=discord'>{$domain}?q=list&platform=discord</a></p>

    <p>Rekon by ID #<br />
    <a href='{$domain}?q=25'>{$domain}?q=25</a> (ID #25)</p>

    <p>Rekon by word search<br />
    <a href='{$domain}?q=multiple word search&limit=3'>{$domain}?q=multiple words&limit=3&shuffle=1</a></p>
  </div>";

}


?>
