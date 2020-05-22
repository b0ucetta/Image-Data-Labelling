<!DOCTYPE html>
<html>
<head>
	<title>YOLO</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body id = "ko">
	<div class ='title'>
		<h1>Data labeling for Yolo algorithme</h1>
		<div class="papper">
			Papper : You Only Look Once: Unified, Real-Time Object Detection<br>
			This code written by : BOUCETTA salah-eddine<br>
			<b>Gmail : salah.eddine.boucetta6@gmail.com</b>
		</div>
	</div>
	<br><br>
	<div class="informations">
		<h2>Data informations</h2>
		<form action="yo.php" method="post">
			<label>(*)Images path:</label><br>
			<input type="text" name = 'py' required size="50"><br><br>
			<label>(*)Number of cells for each image(width*height):</label><br>
			<input type="text" name = 'grid' required size="50"><br><br>
			<label>(*)Number of anchor boxes for each cell:</label><br>
			<input type="text" name = 'num_grid' required size="50"><br><br>
			<input type="submit" name = 'submit' value="Submit">
		</form>
	</div>
	<p id = "data"></p>
	<br><br><br>
	<?php
		if(isset($_POST['submit']))
		{
			$emptyArray = array();
			array_push($emptyArray, $_POST['py'], $_POST['num_grid'], $_POST['grid']);
			$cdir = scandir($_POST['py']);
			$max = count($cdir);
			for ($x = 0; $x < $max; $x++)
			{
				$info = pathinfo($cdir[$x]);
				if(($info["extension"] == "jpg")or($info["extension"] == "png"))
				{
					$infoimage = getimagesize($_POST['py'].$cdir[$x]);
					array_push($emptyArray, $cdir[$x], $infoimage[0], $infoimage[1]);
				}
			}
		}
	?>
	<center id ="canvas"></center>
	<center>
		<button onclick="myFunction();">Next image</button>
	</center>
	<script>
		var images = <?php echo json_encode($emptyArray)?>;
		var str_w_image = images[2].split("*");
		var w_image = parseInt(str_w_image[0]);
		var h_image = parseInt(str_w_image[1]);
		var k = -3;
		function myFunction()
		{	
			document.getElementById("data").innerHTML = "";
			k = k+3;
			var step_w = Math.round(images[k+4]/w_image);
			var step_h = Math.round(images[k+5]/h_image);
			var index = images[0]+images[k+3];
			if(document.getElementById("canvas_img") != null)
			{
				document.getElementById("canvas_img").remove();
				var t = document.createElement("canvas");
				t.id = "canvas_img";
				t.setAttribute("width", images[k+4].toString());
				t.setAttribute("height", images[k+5].toString());
				t.style.border = '1px solid #000';
				document.getElementById("canvas").appendChild(t);
				var canvas = document.getElementById("canvas_img");
				var x = document.createElement("img");
				x.src = index;
				x.addEventListener("load", function()
				{
					var ctx = canvas.getContext("2d");
					ctx.drawImage(x, 0, 0, images[k+4], images[k+5]);
					ctx.strokeStyle = '#FFFFFF';
					for (i = step_w; i <= window.images[k+4]; i=i+step_w)
					{
						ctx.moveTo(i,0);
						ctx.lineTo(i,window.images[k+5]);
						ctx.stroke();
					}
					for (j = step_h; j <= window.images[k+5]; j=j+step_h)
					{
						ctx.moveTo(0,j);
						ctx.lineTo(window.images[k+4],j);
						ctx.stroke();
					}
					var rect = {};
					var drag = false;
					var imageObj = null;
					var w = 0;
					var array = [];
					var arraycv2 = [];
					var n = 0;
					canvas.addEventListener('mousedown', mouseDown, false);
					canvas.addEventListener('mouseup', mouseUp, false);
					canvas.addEventListener('mousemove', mouseMove, false);
					function mouseDown(e)
					{
					    rect.startX = e.pageX - this.offsetLeft;
					    rect.startY = e.pageY - this.offsetTop;
					    drag = true;
					}
					function mouseUp()
					{
						drag = false;
						ctx.strokeStyle = '#00FFFF';
						var label = prompt("Enter label name");
						w = w + 1;
						array.push([label, rect.startX, rect.startY, rect.w, rect.h]);
						arraycv2.push([window.images[k+3], label, rect.startX, rect.startY, rect.startX+rect.w, rect.startY+rect.h]);
						ctx.strokeRect(rect.startX, rect.startY, rect.w, rect.h);
						for (n = 0; n < w; n++)
						{
							ctx.strokeRect(array[n][1], array[n][2],array[n][3],array[n][4]);
						}
						document.getElementById("data").innerHTML = arraycv2;
					}
					function mouseMove(e)
					{
					    if (drag)
					    {
					    	ctx.clearRect(0, 0, window.images[k+4], window.images[k+5]);
					        ctx.drawImage(x, 0, 0);
					        for (i = step_w; i <= window.images[k+4]; i=i+step_w)
							{
								ctx.moveTo(i,0);
								ctx.lineTo(i,window.images[k+5]);
								ctx.strokeStyle = "#FFFFFF";
								ctx.stroke();
							}
							for (j = step_h; j <= window.images[k+5]; j=j+step_h)
							{
								ctx.moveTo(0,j);
								ctx.lineTo(window.images[k+4],j);
								ctx.strokeStyle = "#FFFFFF";
								ctx.stroke();
							}
					        for (n = 0; n < w; n++)
							{
								ctx.strokeStyle = '#00FFFF';
								ctx.strokeRect(array[n][1], array[n][2], array[n][3], array[n][4]);
							}
					        rect.w = (e.pageX - this.offsetLeft) - rect.startX;
					        rect.h = (e.pageY - this.offsetTop) - rect.startY;
					        ctx.strokeStyle = '#00FFFF';
					        ctx.strokeRect(rect.startX, rect.startY, rect.w, rect.h);
					    }
					}
				});
			}
			else
			{
				document.getElementById("data").innerHTML = "";
				var t = document.createElement("canvas");
				t.id = "canvas_img";
				t.setAttribute("width", images[k+4].toString());
				t.setAttribute("height", images[k+5].toString());
				t.style.border = '1px solid #000';
				document.getElementById("canvas").appendChild(t);
				var canvas = document.getElementById("canvas_img");
				var x = document.createElement("img");
				x.src = index;
				x.addEventListener("load", function()
				{
					var ctx = canvas.getContext("2d");
					ctx.drawImage(x, 0, 0, images[k+4], images[k+5]);
					for (i = step_w; i <= window.images[k+4]; i=i+step_w)
					{
						ctx.moveTo(i,0);
						ctx.lineTo(i,window.images[k+5]);
						ctx.strokeStyle = "#FFFFFF";
						ctx.stroke();
					}
					for (j = step_h; j <= window.images[k+5]; j=j+step_h)
					{
						ctx.moveTo(0,j);
						ctx.lineTo(window.images[k+4],j);
						ctx.strokeStyle = "#FFFFFF";
						ctx.stroke();
					}
					var rect = {};
					var drag = false;
					var imageObj = null;
					var w = 0;
					var array = [];
					var arraycv2 = [];
					var n = 0;
					canvas.addEventListener('mousedown', mouseDown, false);
					canvas.addEventListener('mouseup', mouseUp, false);
					canvas.addEventListener('mousemove', mouseMove, false);
					function mouseDown(e)
					{
					    rect.startX = e.pageX - this.offsetLeft;
					    rect.startY = e.pageY - this.offsetTop;
					    drag = true;
					}
					function mouseUp()
					{
						drag = false;
						ctx.strokeStyle = '#00FFFF';
						var label = prompt("Enter label name");
						w = w + 1;
						array.push([label, rect.startX, rect.startY, rect.w, rect.h]);
						arraycv2.push([window.images[k+3], label, rect.startX, rect.startY, rect.startX+rect.w, rect.startY+rect.h]);
						ctx.strokeRect(rect.startX, rect.startY, rect.w, rect.h);
						for (n = 0; n < w; n++)
						{
							ctx.strokeRect(array[n][1], array[n][2],array[n][3],array[n][4]);
						}
						document.getElementById("data").innerHTML = arraycv2;
					}
					function mouseMove(e)
					{
					    if (drag)
					    {
					    	ctx.clearRect(0, 0, window.images[k+4], window.images[k+5]);
					        ctx.drawImage(x, 0, 0);
					        for (i = step_w; i <= window.images[k+4]; i=i+step_w)
							{
								ctx.moveTo(i,0);
								ctx.lineTo(i,window.images[k+5]);
								ctx.strokeStyle = "#FFFFFF";
								ctx.stroke();
							}
							for (j = step_h; j <= window.images[k+5]; j=j+step_h)
							{
								ctx.moveTo(0,j);
								ctx.lineTo(window.images[k+4],j);
								ctx.strokeStyle = "#FFFFFF";
								ctx.stroke();
							}
					        for (n = 0; n < w; n++)
							{
								ctx.strokeStyle = '#00FFFF';
								ctx.strokeRect(array[n][1], array[n][2], array[n][3], array[n][4]);
							}
					        rect.w = (e.pageX - this.offsetLeft) - rect.startX;
					        rect.h = (e.pageY - this.offsetTop) - rect.startY;
					        ctx.strokeStyle = '#00FFFF';
					        ctx.strokeRect(rect.startX, rect.startY, rect.w, rect.h);
					    }
					}
				});	
			}
		}
	</script>
</body>
</html>