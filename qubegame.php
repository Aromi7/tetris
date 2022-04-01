<?php

	ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    ob_start();
    session_start();

    $servername = "sql6.freesqldatabase.com";
    $username = "sql6440216";
    $password = "sLj9jRX9ns";
    $dbname = "sql6440216";

    $conn = new mysqli($servername, $username, $password, $dbname);

	$info = $_SESSION['json'];

	if(isset($_POST['act']) && $_POST['act'] == "logout"){
		$_SESSION['login'] = false;
		header("location: panel.html");
	}

?>
<!DOCTYPE html>
<html>
<head>

	<style>
		#pers{ position: absolute; width: 50px; height: 50px; border: 1px solid;	top: 0px; left: 0px;}
		#controls { position:absolute; left:1000px; top:20px }
		#area div { position:absolute; background:#000; }
		#menu { position: absolute; }
		#poi-area div{ position: absolute; border-radius: 100%; border: 1px; width: 25px; height: 25px; background-color: yellow; text-align: center; padding-top: 3px; }
		div p { text-align: center; }
		#coin { top: 0px; left: 0px; }
		#enemy div { position: absolute; border-radius: 100%; border: 1px; width: 25px; height: 25px; background-color: red; }
		#m-pr div { position: absolute; border-radius: 100%; border: 1px; width: 50px; height: 50px; background-color: blue; }
		#res { position: absolute; left: 100px; }
		#life-t div { position: absolute; border-radius: 100%; border: 1px; width: 25px; height: 25px; background-color: purple; }
		#save { position: absolute; left: 200px; }
		#logout { position: absolute; left: 300px; }
	</style>

</head>
<body onkeypress="move(event.keyCode)" onload="bm()">

	<div id="menu">
		<h3>Score</h3>
		<div id="points"></div>
		
		<h3>Lives</h3>
		<div id="life"></div>
	</div>
	
	<form id="res">
		<input type="button" value="Restart" onclick="bm()">
	</form>

	<form id="save">
		<input type="button" value="Save" onclick="save()">
	</form>

	<form method="post" id="logout" onsubmit="return out();">
		<input type="hidden" name="act" value="logout">
		<input type="submit" value="LogOut">
	</form>
	
	<div id="area"></div>
	<div id="poi-area"></div>
	<div id="pers"></div>
	<div id="enemy"></div>
	<div id="m-pr"></div>
	<div id="life-t"></div>
	
	<p id="cord"></p>

	<p id="txt"></p>
	
	<script>
	
	// top, left, direction, length
	let str = "";
	let borders = [];
	let saved = true;

	<?php

			if ($_SESSION['login'] == true && !is_null($info)) {
				$borders = $info->borders;
				$x = $info->x;
				$y = $info->y;
				$coins = $info->coins;
				$key = $info->keys;
				$en = $info->enemies;
				$score = $info->score;
				$mpr = $info->megprize;
			}else{
				$borders = [[300, 400, "v", 300, "b1"], [300, 700, "h", 300, "b2"], [300, 400, "h", 300, "b3"], [600, 400, "v", 300, "b4"]];
				$x = 0;
				$y = 0;
				$coins = [[100, 100, 'coin1'], [200, 200, 'coin2']];
				$key = 0;
				$en = [[200, 800, 'en1'], [0, 500, 'en2']];
				$score = 0;
				$mpr = [400, 500];
				function rc($min, $max){

					global $borders;

					$sx = [];
					//directories
					array_push($sx, rand($min, $max) * 100);
					array_push($sx, rand($min, $max) * 100);

					//position
					$a = rand(0, 1);
					$p = "";
					if ($a == 1) {
						$p = "v";
					}else{
						$p = "h";
					}
					array_push($sx, $p);

					//length
					array_push($sx, rand(1, 3) * 100);

					//generate id
					$bid = "b" . count($borders)+1;
					array_push($sx, $bid);

					array_push($borders, $sx);

				}

				for ($i=0; $i < 5; $i++) { 
					rc(1, 6);
				}
			}

		?>

	let x = <?php echo json_encode($x); ?>;
	let y = <?php echo json_encode($y); ?>;

	function bm(){

		borders = <?php echo json_encode($borders); ?>;
	
		document.getElementById('area').innerHTML = '';

		// creat borders
		for(let i = 0; i < borders.length; i++){
			str = (borders[i][2] == "v") ? "width:" + borders[i][3] + "px; height:1px" : "height:" + borders[i][3] + "px; width:1px"; 
			document.getElementById("area").innerHTML += "<div id="+borders[i][4]+" style='top:"+borders[i][0]+"px; left:"+borders[i][1]+"px; "+str+"'></div>";
		}
		
	}

		//move box
		let obj = document.getElementById("pers");

		//box first directions
		obj.style.marginLeft = x + "px";
		obj.style.marginTop = y + "px";
		document.getElementById("cord").innerHTML = x + " - " + y;
		
		//points
		let poi = document.getElementById("points").innerHTML = <?php echo json_encode($score); ?>;
		
		//top, left, id of coins
		let coins = <?php echo json_encode($coins); ?>;
		let key = <?php echo json_encode($key); ?>;
		
		//creat coins
		for(let i = 0; i < coins.length; i++){
			if(coins[i] != ""){
				document.getElementById("poi-area").innerHTML += "<div id="+coins[i][2]+" style='margin-top:"+coins[i][0]+"px; margin-left:"+coins[i][1]+"px'>$</div>";
			}
		}
		
		//enemy top, left, id
		let en = <?php echo json_encode($en); ?>;
		
		// creat enemies
		for(let i = 0; i < en.length; i++){
			if(en[i] != ""){
				document.getElementById("enemy").innerHTML += "<div id="+en[i][2]+" style='margin-top:"+en[i][0]+"px; margin-left:"+en[i][1]+"px'></div>";
			}
		}
		
		//mega prize top, left
		let mpr = <?php echo json_encode($mpr); ?>;
		
		//creat mega-prize
		if(mpr != ""){
			document.getElementById("m-pr").innerHTML += "<div id='megprize' style='margin-top:"+mpr[0]+"px; margin-left:"+mpr[1]+"px'></div>";
		}

		//lives
		let life = document.getElementById('life').innerHTML = 1;
		
		//life taking enemies
		let lt = [50, 750];
		document.getElementById('life-t').innerHTML += "<div id='life-take' style='margin-top:"+lt[0]+"px; margin-left:"+lt[1]+"px'></div>";

		function move(dir){
		
		//alert(dir.keyCode)
			
			// chack stop
			if( stop(dir) || life == 0 ){
				return false;	
			}
					
			// move
			switch(dir) {
				case 56: // top
					y -= 50;
					obj.style.marginTop = y + "px";
					saved = false;
					break;
				case 50: // bottom
					y += 50;
					obj.style.marginTop = y + "px";
					saved = false;
					break;
				case 54: // right:
					x += 50;
					obj.style.marginLeft = x + "px";
					saved = false;
					break;
				case 52: // left
					x -= 50;		
					obj.style.marginLeft = x + "px";
					saved = false;
			}
			
			document.getElementById("cord").innerHTML = x + " - " + y;
			
			getprize();
			takelife();
			
			//coins effect
			for(let i = 0; i < coins.length; i++){
				if(y == coins[i][0] && x == coins[i][1]){
					poi += 100;
					key ++;
					document.getElementById(coins[i][2]).style.display = 'none';
					coins[i].splice(0, 3);
					
					if(key == 2){
						document.getElementById(borders[0][4]).style.display = 'none';
						borders[0].splice(0, 4);
					}					
				}
				
			}
			
			//enemies effect
			for(let i = 0; i < en.length; i++){
				if(y == en[i][0] && x == en[i][1]){
					poi -= 100;
					document.getElementById(en[i][2]).style.display = 'none';
					en[i].splice(0, 3);
				}
			}
						
			
			document.getElementById("points").innerHTML = poi;
			
		}
		
		function stop(dir){
		
			for(let i = 0; i < borders.length; i++){
			
				// chack vertical
				if(dir == 56 || dir == 50){
	
					if(borders[i][2] == "v"){
					
						// chack cordinats
						if( dir == 56 && y == borders[i][0] && x >= borders[i][1] && x <= borders[i][1] + borders[i][3] || dir == 50 && y == borders[i][0] - 50 && x >= borders[i][1] && x <= borders[i][1] + borders[i][3]){
							return true;
						}
						
					}	
					
				// chack horizontal	
				}else{
									
					if(borders[i][2] == "h"){
					
						// chack cordinats
						if( dir == 54 && x == borders[i][1] - 50 && y >= borders[i][0] && y <= borders[i][0] + borders[i][3] || dir == 52 && x == borders[i][1] && y >= borders[i][0] && y <= borders[i][0] + borders[i][3]){
							return true;
						}
						
					}					
				
				}
				
			}
	
		}
		
		function getprize(){
		
			for(let i = 0; i < mpr.length; i++){
			
				if(y == mpr[0] && x == mpr[1]){
					poi += 1000;
					document.getElementById("megprize").style.display = 'none';
					mpr.splice(0, 2);
				}
				
			}
			
		}
		
		function takelife(){
			for(let i = 0; i < lt.length; i++){
				if(y == lt[0] && x == lt[1]){
					life--
					document.getElementById('life-t').style.display = 'none';
					lt.splice(0, 2);
					break;
				}
			}
			document.getElementById('life').innerHTML = life;
			
		}

		function save(){

			// ajax
			const xmlhttp = new XMLHttpRequest();

			xmlhttp.onload = function() {
				console.log(this.responseText);
			}

			let json = {"x": x, "y": y, "coins": coins, "enemies": en, "score": poi, "borders": borders, "keys": key, "megprize": mpr};
			const myJSON = JSON.stringify(json);
			xmlhttp.open("GET", "log.php?act=save&json="+myJSON);
			xmlhttp.send();

		}

		function out(){

			move();

			if (saved == false) {

				let b = confirm('Do you really want to submit the form?');

				if (b == true) {
					save();
				}

			}

		}

	</script>
	
</body>
</html>