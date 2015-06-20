function create_tile(id, name, hash) {
	var tile_template = "\
		<div class=\"tile\" style=\"background-image: url('http://media.steampowered.com/steamcommunity/public/images/apps/{appid}/{hash}.jpg')\"> \
			<div class=\"overlay\">{name}</div> \
		</div>";

	return tile_template.replace("\{appid\}", id).replace("\{hash\}", hash).replace("\{name\}", name);
}

function create_description(count, percentage) {
	var template = "\
		You have <strong>{count}</strong> games which run natively on Linux, which accounts for<br /> approximately <strong>{percentage}</strong>% of your library.";

	return template.replace("\{count\}", count).replace("\{percentage\}", percentage);
}

document.getElementById("form").onsubmit = function(e) {
	e.preventDefault();

	var http = new XMLHttpRequest();
	http.open("GET", "check.php?url="+ document.getElementById("profile").value);

	http.onreadystatechange = function() {
		if(http.readyState == 4 && http.status == 200) {
			var json   = JSON.parse(http.responseText);
			var result = document.getElementById("result");
			
			result.innerHTML = create_description(json.count, json.percentage);

			table = document.createElement("table");

			result.appendChild(table);

			for(var i = 0; i < json.count; i += 4) {
				var row = table.insertRow();

				row.insertCell(0).innerHTML = create_tile(json.games[i].id, json.games[i].name, json.games[i].hash);

				for(var j = 1; j < 4; j++) {
					if(json.count > i + j) {
						row.insertCell(j).innerHTML = create_tile(json.games[i + j].id, json.games[i + j].name, json.games[i + j].hash)
					}
				}
			}

			document.getElementById("result").style.display = "block";
		}
	}

	http.send();
}
