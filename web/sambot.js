$(document).ready(function() {
  //  alert("heyo")
})

// Make jQuery :contains case-insensitive
$.expr[":"].contains = $.expr.createPseudo(function(arg) {
  return function( elem ) {
      return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
  };
});

$(document).on("keyup", "#searchbox", function(e) {
    let search = $(this).val()
    console.log("Search: "+search)
    $("p.rekon").hide()
    $("p.rekon:contains('"+search+"')").show()
})