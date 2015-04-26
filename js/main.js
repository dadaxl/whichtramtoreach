var tramRows = $('table.trams > tbody > tr'), recommended = $('#recommended div');

depData = [];
tramRows.each(function() {
	depData.push($(this).find('table.departures span.reachable').eq(0).text());
});

diffVal = eval(depData.join('-'));

if (diffVal >= 0) {
	recommended.html(tramRows.eq(1).find('> td').eq(0).text());
} else {
	recommended.html(tramRows.eq(0).find('> td').eq(0).text());	
}
