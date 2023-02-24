<?php
// Show the source code if requested
if (isset($_GET['code'])) {
  highlight_file("index.php");
  die();
}

// Get possible queries
$q = $_GET['q'] ?? null; // allow a specific ID to be fetched
$limit = $_GET['limit'] ?? 3; // amount of search results to return
$shuffle = $_GET['shuffle'] ?? 1; // shuffle search results
$showID = $_GET['showID'] ?? 0; // show unique ID before each rekon
$platform = $_GET['platform'] ?? "web"; // anything besides "web" will be plain text mode
$quotes = $_GET['quotes'] ?? ""; // no quotes by default
$breaks = $_GET['breaks'] ?? 1; // prefer <br /> over /n/r
$js = $_GET['js'] ?? 1; // web javascript features enabled by default
if ($platform == "discord") {
  $quotes = "`"; // force Discord rekons to be in a quote box
  $limit = ($limit > 5) ? 5 : $limit; // Discord limit can't go past 5
}
$domain = "https://sambot.frwd.app"; // i'm lazy

// Get file contents of rekons.json
$file = "rekons.json";
$fh = fopen($file, 'r');
$fcontents = fread($fh, filesize($file));
fclose($fh);
$data = json_decode($fcontents, true);
$total = count($data['rekon']); // total rekons

if ($platform == "web") echo "<div id='content'>"; // create the content div for web for javascript search

// ?q=list creates an entire list of rekons and then quits
if ($q == "list" && $platform == "web") {
  foreach ($data['rekon'] as $id => $val) {
    echo "<p class='rekon'>#{$id}: $val</p>";
  }

// ?q=list for a non-web platform just shows a link to the list page
} else if ($q == "list" && $platform != "web") {
  echo "Full list of rekons can be found at {$domain}?q=list";

// If $q is numeric or empty, fetch a random or desired ID
} else if ($q == null || is_numeric($q)) {
  $rand = ($q == null) ? rand(0, $total-1) : $q;
  if ($rand > $total) {
    echo "I need to rekon more to get to #".$rand;
  } else {
    $thisID = ($showID == 1) ? "#".$rand.": " : null;
    echo $thisID."{$quotes}".$data['rekon'][$rand]."{$quotes}";
  }

// If $q is a string, search each rekon to see if that word is in it
} else if ($q != null && !is_numeric($q)) {

  $matches = [];

  foreach ($data['rekon'] as $id => $val) {
    if (preg_match("/{$q}/i", $val)) {
      $matches[$id] = $val;
    }
  }


  if (count($matches) == 0) {
    echo "No rekons found related to `$q`";
  } else {
    if ($shuffle == 1) $matches = shuffle_assoc($matches);
    $results = 0;
    foreach($matches as $ids => $vals) {
      if ($results > $limit-1) break; // stop after the $limit
      if ($results > 0) echo ($platform == "web" || $breaks == 1) ? "<br />" : "\n\r"; // different line breaks per platform
      $thisID = ($showID == 1) ? "#".$ids.": " : null;
      echo "{$thisID}{$quotes}{$vals}{$quotes}";
      $results++;
    }
  }

}

if ($platform == "web") echo "</div>"; // end content div for web

// Show the API info box to the web users
if ($platform == "web") {

  echo "
  <div id='info' style='position: fixed; right: 2%; bottom: 4%; background: #cccccc; padding: 3px 10px;'>
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

    <p>Other flags (with defaults)<br />
    &js=1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Javascript on web page<br />
    &showID=0&nbsp;&nbsp;&nbsp;&nbsp;Show ID of rekon<br />
    &amp;quotes=&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mark to put around each rekon (\", ', `, etc)</p>
  </div>";

  // Search Bar for Web
  if ($platform == "web") {
    $shuffleChecked = ($shuffle == 1) ? "checked" : null;
    $showIDChecked = ($showID == 1) ? "check" : null;
    echo "
    <div id='search' style='position: fixed; top: 4%; right: 2%;'>
      <form method='get' action='index.php'>
        <input type='text' id='searchbox' name='q' placeholder='Search...' value='{$q}' /> Limit: <input type='number' id='searchLimit' name='limit' value='{$limit}' size='3'> Shuffle: <input type='checkbox' id='searchShuffle' name='shuffle' value='1' {$shuffleChecked}> Show ID: <input type='checkbox' id='searchShowID' name='showID' {$showIDChecked} /> Quotes: <input type='text' id='searchQuotes' name='quotes' value='{$quotes}' size='3'> <input id='searchSubmit' type='submit' value='Search' />
        <input type='hidden' id='searchJS' name='js' value='0' />
      </form>
    </div>
    ";
  }

  if ($js == 1) {
    echo "
    <script src='jquery-3.6.3.min.js'></script>
    <script src='sambot.js'></script>";
  }

}

// Functions

// Shuffle associated array
function shuffle_assoc($arr) {
  $keys = array_keys($arr);

  shuffle($keys);

  foreach($keys as $key) {
    $new[$key] = $arr[$key];
  }

  $arr = $new;
  return $arr;
}
?>