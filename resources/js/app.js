function ajaxPostCall(url, dataArray, requestType) {	
	alert(requestType);
	$.ajax({
        type: requestType,
        url: url,
		data: { 'dataArray' : 'dataArray' },
        success: function(data) {
			finishedCall(data);
        },
        error: function(jQXHR, textStatus, errorThrown) {
            alert('Sorry, something went wrong. Please try again.' + jQXHR.status + " " + textStatus + " " + errorThrown);
        }
    });
}

function ajaxPostCallWithIdentifierXD(url, dataArray, ID) {	
	$.ajax({
        type: "POST",
        url: url,
		data: { 'dataArray' : dataArray },
        success: function(data) {
			finishedCallWithID(data, ID);
        },
        error: function(jQXHR, textStatus, errorThrown) {
            alert('Sorry, something went wrong. Please try again.' + jQXHR.status + " " + textStatus + " " + errorThrown);
			finishedCallWithError(errorThrown, ID);
        }
    });
}
