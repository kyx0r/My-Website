// JavaScript Document
$(document).ready(function () {

	///1. first we take care of scaling the height of the "results" div, so it is always in proportion to its width///////////////////////////
	var resultsW;
	var resultsH;
	if ($(window).width() > 1200) {
		resultsW = 1180;
		resultsH = 600;
	} else {
		resultsW = $("#container").innerWidth();
		resultsH = resultsW / 1.5;
	}

	//alert (resultsW);


	$("#results").height(resultsH);

	//////////////////////////end scaling results height//////////////////////////////////////////////////////////////////////////////////////////////////////

	///2. we create the loadXMLDoc function./////////////////////////////////////////////
	
	var xmlhttp;

	function loadXMLDoc(url, callfunc) {

		xmlhttp = new XMLHttpRequest();

		xmlhttp.onreadystatechange = callfunc;

		xmlhttp.open("POST", url, true);
		xmlhttp.send();


	} 

	//////////////////end loadXMLDoc/////////////////////////////////////////////////////////////////
	
	
	////////////////////////3. call and define the getMyData() function///////////////////////////////
	getMyData();

	function getMyData() {

		loadXMLDoc("poll_to_xml.php", function () {

			//alert(xmlhttp.status);

			if (xmlhttp.readyState == 4) {

				//alert(xmlhttp.responseText);
				var response = xmlhttp.responseText;
				/////


				var parser = new DOMParser();
				var xmlDoc = parser.parseFromString(response, "text/xml");

				var children = xmlDoc.getElementsByTagName('voter');
				//alert(children[3].getAttribute('myoption'));
				//alert(children.length);
				var totalVotes = 0;
				var Avotes = 0;
				var Bvotes = 0;
				var Cvotes = 0;


				for (var i = 0; i < children.length; i++) {


					var findOut = children[i].getAttribute('myoption');

					switch (findOut) {
						case "a":
							Avotes++;
							break;

						case "b":
							Bvotes++;
							break;

						case "c":
							Cvotes++;
							break;


					} //end switch


				} //end for loop

				//alert (Avotes);


				totalVotes = children.length;
				//alert(Cvotes);

				var Atext = "Maine Coon: " + Avotes + "<br/>" + (Avotes / totalVotes * 100).toFixed(1) + "%";
				var Btext = "Siamese: " + Bvotes + "<br/>" + (Bvotes / totalVotes * 100).toFixed(1) + "%";
				var Ctext = "Persian: " + Cvotes + "<br/>" + (Cvotes / totalVotes * 100).toFixed(1) + "%";

				$("#optAbottom").html(Atext);
				$("#optBbottom").html(Btext);
				$("#optCbottom").html(Ctext);

				var whoTallest = Math.max(Avotes, Bvotes, Cvotes);
				//	alert (whoTallest);
				var heightUnit = resultsH / whoTallest * 0.6;
				//alert (heightUnit);



				var Aheight = (resultsH * 0.3) + (Avotes * heightUnit);
				var Bheight = (resultsH * 0.3) + (Bvotes * heightUnit);
				var Cheight = (resultsH * 0.3) + (Cvotes * heightUnit);



				$("#optA").animate({
					height: Aheight + "px"
				}, 1300, "easeOutBounce", function () {
					$("#optB").animate({
							height: Bheight + "px"
						}, 1300, "easeOutBounce", function () {
							$("#optC").animate({
								height: Cheight + "px"
							}, 1300, "easeOutBounce")
						}) //end animate b
				}); //end animate a


			} //end if ready state
		}); //end loadXML func
	} //end getMy data

///////////////end getMyData()//////////////////////////////////////////////////////////////////////////


	/////////////////////////////////4. reset every time window is resized///////////////////////////////
	function myreset() {
		location.reload(); //reload the page
	}

	var $window = $(window);
	var lastWindowWidth = $window.width();

	$window.resize(function () {
		/* Do not calculate the new window width twice.
		 * Do it just once and store it in a variable. */
		var windowWidth = $window.width();

		/* Use !== operator instead of !=. */
		if (lastWindowWidth !== windowWidth) {
			var doit; //declare a new obj
			clearTimeout(doit); //clear prev values
			doit = setTimeout(myreset, 1000); //after 1000 ms call the func 
			lastWindowWidth = windowWidth;
		}
	});
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


}); //end doc ready
