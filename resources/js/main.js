document.getElementById("form").onsubmit = function(e) {
	e.preventDefault();

	var http = new XMLHttpRequest();
	http.open("GET", "check.php");

	http.onreadystatechange = function() {
		if(http.readyState == 4 && http.status == 200) {
			var json = JSON.parse(http.responseText);

			console.log(json);

			document.getElementById("count").innerHTML = json.count;
			document.getElementById("percentage").innerHTML = json.percentage;

			var result = document.getElementById("result");

			document.getElementById("result").style.display = "block";
		}
	}

	http.send();
}
