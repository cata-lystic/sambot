$(document).ready(function() {
  $("#searchSubmit").hide() // hide search button
})

// Make jQuery :contains case-insensitive
$.expr[":"].contains = $.expr.createPseudo(function(arg) {
  return function( elem ) {
      return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
  };
});

$(document).on("keyup", "#searchbox", function(e) {
  /*
    let search = $(this).val()
    console.log("Search: "+search)
    $("p.rekon").hide()
    $("p.rekon:contains('"+search+"')").show()
  */
    search()
})

function search() {
  let searchQuery = $("#searchbox").val()
  let limit = $("#searchLimit").val()
  let shuffle = ($("#searchShuffle").prop("checked")) ? 1 : 0 
  let showID = ($("#searchShowID").prop("checked")) ? 1 : 0
  let platform = "api"
  $.ajax({
    type: "GET",
    url: "index.php?q="+searchQuery+"&limit="+limit+"&shuffle="+shuffle+"&showID="+showID+"&platform="+platform+"&breaks=1",
    cache: false
  }).done(function (data, textStatus, errorThrown) {
    $("#content").html(data)
  })
}