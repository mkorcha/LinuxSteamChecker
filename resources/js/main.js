function create_tile(id, name, hash) {
	var tile_template = "\
		<div class=\"tile\" style=\"background-image: url('http://media.steampowered.com/steamcommunity/public/images/apps/{appid}/{hash}.jpg')\"> \
			<div class=\"overlay\">{name}</div> \
		</div>";

	return tile_template.replace("\{appid\}", id).replace("\{hash\}", hash).replace("\{name\}", name);
}

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

			if(typeof table === "Node") {
				result.removeChild(table);
			}

			table = document.createElement("table");

			result.appendChild(table);

			for(var i = 0; i < json.count; i+=4) {
				console.log(i);

				var row = table.insertRow();

				row.insertCell(0).innerHTML = create_tile(json.games[i].id, json.games[i].name, json.games[i].hash);

				if(json.count > i + 1) {
					row.insertCell(1).innerHTML = create_tile(json.games[i + 1].id, json.games[i + 1].name, json.games[i + 1].hash);
				}

				if(json.count > i + 2) {
					row.insertCell(2).innerHTML = create_tile(json.games[i + 2].id, json.games[i + 2].name, json.games[i + 2].hash);
				}

				if(json.count > i + 3) {
					row.insertCell(3).innerHTML = create_tile(json.games[i + 3].id, json.games[i + 3].name, json.games[i + 3].hash);
				}
			}

			document.getElementById("result").style.display = "block";

			console.log(document.getElementsByTagName("table")[0].getElementsByTagName("tbody")[0].children.length);
		}
	}

	http.send();
}
