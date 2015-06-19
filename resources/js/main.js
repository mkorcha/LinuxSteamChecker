document.getElementById("form").onsubmit = function(e) {
	e.preventDefault();

	var http = new XMLHttpRequest();
	http.open("GET", "check.php");

	http.onreadystatechange = function() {
		if(http.readyState == 4 && http.status == 200) {
			var json = JSON.parse(http.responseText);

			document.getElementById("count").innerHTML = json.count;
			document.getElementById("percentage").innerHTML = json.percentage;

			var result = document.getElementById("result");
			var table  = result.getElementsByTagName("table")[0];

			document.getElementById("result").style.display = "block";
		}
	}

	http.send();
}