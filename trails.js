var tourData;


////////////// google maps
var map;
var flightPlanCoordinates = new Array();
var flightPath;

flightPath = new google.maps.Polyline({
    path: flightPlanCoordinates,
    strokeColor: "#db3c3c",
    strokeOpacity: 0.67,
    strokeWeight: 7
  });

  function initialize() {
    var myOptions = {
      center: new google.maps.LatLng(39.368279, 5.273438),
      zoom: 2,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      backgroundColor: "#111111",
      disableDefaultUI: true,
      draggable: true,
      styles:
    	  [{
    		    featureType: "water",
    		    elementType: "geometry",
    		    stylers: [{
    		        invert_lightness: true
    		    }, 
    		    {
    		        saturation: -100
    		    },
    		    {
    		        lightness: -72
    		    }]
    		}, {
    		    featureType: "landscape",
    		    stylers: [{
    		        saturation: -98
    		    }, {
    		        lightness: -82
    		    }]
    		}, {
    		    featureType: "administrative",
    		    elementType: "geometry",
    		    stylers: [{
    		        lightness: -31
    		    },
    		    {
    		        visibility: "off"
    		    }
    		    ]
    		}, {
    		    featureType: "administrative",
    		    elementType: "labels",
    		    stylers: [{
    		        invert_lightness: true
    		    }, {
    		        lightness: -40
    		    }, {
    		        saturation: -100
    		    }]
    		}, {
    		    featureType: "water",
    		    elementType: "labels",
    		    stylers: [{
    		        invert_lightness: true
    		    }, 
    		    {
    		        visibility: "off"
    		    },
    		    {
    		        saturation: -100
    		    }, {
    		        lightness: -77
    		    }]
    		}, {
    		    featureType: "poi",
    		    stylers: [{
    		        visibility: "off"
    		    }, {
    		        saturation: -100
    		    }, {
    		        lightness: -60
    		    }]
    		}, {
    		    featureType: "road",
    		    stylers: [{
    		        visibility: "off"
    		    }]
    		}, {
    		    featureType: "administrative",
    		    elementType: "geometry",
    		    stylers: [{
    		        lightness: 6
    		    }]
    		}, {
    		    featureType: "administrative",
    		    elementType: "geometry",
    		    stylers: [{
    		        invert_lightness: true
    		    }, {
    		        saturation: -100
    		    }, {
    		        lightness: -21
    		    }]
    		}],
      
    };

    
    map = new google.maps.Map(document.getElementById("map"),
        myOptions);

	
    
  }
///////////////////////


$(".year_link").live("click", function(){
	var gig;
	var y;

if($(this).text()!="All")
{

	
	flightPlanCoordinates = new Array();
	for (gig in tourData[$(this).text()])
	{

		flightPlanCoordinates.push(new google.maps.LatLng(tourData[$(this).text()][gig]["lat"], tourData[$(this).text()][gig]["long"]));
	}
	
	
	
}	
else
{
	for (y in tourData)
	{
		for (gig in tourData[y])
		{
		flightPlanCoordinates.push(new google.maps.LatLng(tourData[y][gig]["lat"], tourData[y][gig]["long"]));
		}
	}
}

	
	flightPath.setPath(flightPlanCoordinates);
	

	flightPath.setMap(null);
    flightPath.setMap(map);
	
	
	});


function load()
{
	$(document).ready(function() {
		
	
	$("#map").height($(window).height());
	
	$("#band").click(function () {
      $(this).css("border-color","#6a6a6a");
    });
	
	$("#band").keypress(function () {
	      $(this).css("border-color","#6a6a6a");
	    });

	initialize();
	
	
	

	
	
	});
	
}

function doRequest()
{
	var cont = $("#band").val();
	
	$("#loader").css("display","inline");
	
	$.ajax({
		  url: 'dbHandler.php',
		  data:"cont="+cont,
		  success: function(data) 
		  {
			  var result = data.split("^");
			  if(result[0]=="evalthis")
			  {
			  		eval(result[1]);
			  }
			  else if(result[0]=="print")
			  {
				  tourData=jQuery.parseJSON(result[1]);
				  var years = jQuery.parseJSON(result[1]);
				  var year;
				  
				  var contents="";
				  for (year in years)
				  {
					  	contents=contents+"<a href='#' class='year_link' id='link_"+years[year][0].year+"'>"+years[year][0].year+"</a><BR>";
				  }
				  contents=contents+"<a href='#' class='year_link' id='link_all'>All</a><BR>";
				  $("#loader").css("display","none");
				  $("#years").html(contents);
				  
			  }
		  }

		});
	
	
}

	
