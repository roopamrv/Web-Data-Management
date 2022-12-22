function initialize () {
	limit = 10;
}
var map;
var bounds;
var list = [];
var InfoDisplayed = null;

function initMap() {
   console.log("inside google map init");
   //initialize map with given lat long values
   const mapinit = { lat: 32.75, lng: -97.13 }; 
   map = new google.maps.Map(document.getElementById("map"), {
     zoom: 16,
     center: mapinit,
   });
   // add eventListener to log for any changes made in map viewport
   google.maps.event.addListener(map, 'bounds_changed', function() {
    bounds =  map.getBounds();
  });
 }

// function to get 10 restaurants from Yelp API
function findMapRestaurants() {
  document.getElementById("result").innerHTML = '';
  var xhr = new XMLHttpRequest();

  // save type of search in search box
  
  var term_value = document.getElementById("term").value;
 
  if (term_value == ''){
    document.getElementById("result").innerHTML = 'Please enter a keyword to search Restaurants';
  }
  else{
  // find radius of current map viewport by using bounds
  radius = google.maps.geometry.spherical.computeDistanceBetween(bounds.getNorthEast(), bounds.getCenter());
  center_lat = bounds.getCenter().lat();
  center_long = bounds.getCenter().lng();

  
  xhr.open("GET", "proxy.php?term=" + term_value + "&limit=" + limit + "&radius=" + parseInt(radius) + "&latitude=" + center_lat + "&longitude=" + center_long);
	xhr.setRequestHeader("Accept","application/json");
  xhr.onreadystatechange = function () {
    if (this.readyState == 4) {
       json = JSON.parse(this.responseText);

       if(json.hasOwnProperty("error")) document.getElementById("result").innerHTML = json.error.code+" Search in smaller map area, Radius too large !!";
			  else{
				  if(json["businesses"].length==0) document.getElementById("result").innerHTML = "No Restaurants found with description";
				  else{
               addMarkers(json);
     }
    }
  }
};
xhr.send(null);
}
}
function deleteMap() {
	for (var i = 0; i < list.length; i++) {
	  list[i].setMap(null);
	}
	list = [];
}

function infoContent(item) {

  table = document.createElement("table");
  var tr = document.createElement("tr");
  table.style.borderCollapse = "collapse";
  table.style.textAlign = "center";
  
  var name = item['name'];
  var image = item['image_url'];
  var rating = item['rating'];
  //console.log(name,image,rating);
  
  var td1 = document.createElement("td");
  var img = document.createElement("img");
  img.src = image;
  img.style.width = "80px";
  img.style.height = "80px";
  img.style.border = "2px solid black";
  img.style.borderRadius = "10px";
  td1.appendChild(img);
  td1.style.padding = "10px";
  tr.appendChild(td1);

  var td2 = document.createElement("td");
  var Name = document.createElement("div");
  Name.innerHTML = name ;
  td2.appendChild(Name);
  td2.style.padding = "10px";

  //Adding Ratings
  var Rating = document.createElement("div");
  Rating.innerHTML = "<br/>Ratings : "+rating;
  td2.appendChild(Rating);
  tr.appendChild(td2);

  table.appendChild(tr);
  return table;
}

// function to add markers in current map viewport.
function addMarkers(json) {
  //to remove previous search markers in map viewport.
  deleteMap();
  //console.log(json["businesses"].length);
  for ( i=0; i<json["businesses"].length; i++ ){

   rlat = json["businesses"][i]["coordinates"]["latitude"];
   rlng = json["businesses"][i]["coordinates"]["longitude"];
   
   rest_cord = new google.maps.LatLng({lat: rlat, lng: rlng});
   var markerInfo = new google.maps.InfoWindow()
   infoData = infoContent(json["businesses"][i]);
   //var infoData = String(i+1) + "test"
   //markerInfo(json["businesses"][i]);
   var mark = new google.maps.Marker({position: rest_cord, map: map, label: String(i+1)});

   google.maps.event.addListener(mark,'click', (function(mark,infoData,markerInfo){ 
    return function() {
        markerInfo.setContent(infoData);
        if (InfoDisplayed == null) {
          markerInfo.open(map,mark);
          InfoDisplayed = markerInfo;
        }
        else if (InfoDisplayed != null) {
          if (infoData == InfoDisplayed.getContent()){
            console.log("closed");
            InfoDisplayed.close();
            InfoDisplayed = null;
          }
          else{
            InfoDisplayed.close();
            markerInfo.open(map,mark);
            InfoDisplayed = markerInfo;         
          }
        }
    };
    })(mark,infoData,markerInfo)); 
    list.push(mark);
  }
}
window.initMap = initMap;