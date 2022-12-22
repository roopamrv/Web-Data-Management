
var term_value,location_value,limit,json;
var count;
function initialize () {
   // location_value = document.getElementById("city").innerHTML ="plano";
   // term_value = document.getElementById("term").innerHTML="indian";
   // limit = document.getElementById("level").innerHTML = 5;
   location_value = document.getElementById("city");
   term_value = document.getElementById("term");
   limit = document.getElementById("level");
}

function findRestaurants () {
   var xhr = new XMLHttpRequest();
   var url = "proxy.php?term="+term_value.value+"&location="+location_value.value+"&limit="+limit.value;
   xhr.open("GET", "proxy.php?term="+term_value.value+"&location="+location_value.value+"&limit="+limit.value);
   xhr.setRequestHeader("Accept","application/json");
   xhr.onreadystatechange = function () {
       if (this.readyState == 4) {
          json = JSON.parse(this.responseText);
          var str = JSON.stringify(json,undefined,2);
          createtb(json);
        }
   };
   xhr.send(null);
}

function createtb(json){

   var table = document.getElementById("tabledata");
   table.innerHTML = "";
   var info = "";
   
   info = document.getElementById("output");
   info.innerHTML = "<h2> TOP " + limit.value + " " + term_value.value.toUpperCase()+ " RESTAURANTS IN " + location_value.value.toUpperCase() + "</h2>";
   info.style.width = "100%";
   info.style.textAlign = "center";
   var tabledata = document.getElementById("tabledata");
   
   var business = Object.values(json)[0];
   var serial =0;

   //looping to each row
   business.forEach((row) => {
    
      var name = row['name'];
      var image = row['image_url'];
      var url = row['url'];
      var price = row['price'];
      if (!price){
         price = "-";
      }
      var catraw = row['categories'];
      var cat = "";
      catraw.forEach((elem) =>{
         cat += elem['title']+" ";
      });

      var rating = row['rating'];
      var addr = row['location']['display_address'];
      var phone = row['display_phone'];
      
      //table row
         var tr = document.createElement("tr");
         table.style.borderCollapse = "collapse";
         tr.style.borderBottom = "2px solid #ddd";
         table.style.textAlign = "center";
         table.style.width = "100%";
         
         //table column data
         //Serial no
         var td0 = document.createElement("td");
         td0.textContent = ++serial + ".";
         td0.style.padding = "50px";
         tr.appendChild(td0);

         // Add Image of restaurant
         var td1 = document.createElement("td");
         var img = document.createElement("img");
         //var imglink = document.createElement("a");
         //imglink.href = image;
         img.src = image;
         img.style.width = "100px";
         img.style.height = "100px";
         img.style.border = "1px solid black";
         img.style.borderRadius = "10px";
         img.onclick = function(){
            window.open(url,'_RestaurantDetailbyimageclick');
         }
         td1.appendChild(img);
         
         td1.style.padding = "50px";
         tr.appendChild(td1);

         //Working on name with link
         var td2 = document.createElement("td");
         var elink = document.createElement("a");
         var href = url;
         elink.href = href;
         elink.innerHTML = name;
         elink.onclick = function (){
            window.open(url, '_RestaurantDetailbyNameclick');
         }
         td2.appendChild(elink);
         td2.style.padding = "50px";
         
         //Adding Address
         var address = document.createElement("div");
         address.innerHTML = "<br/>Address : "+addr;
         td2.appendChild(address);

         //Adding Phone
         var ph = document.createElement("div");
         ph.innerHTML = "Phone : "+phone;
         td2.appendChild(ph);
         tr.appendChild(td2);

         //Adding Category
         var td3 = document.createElement("td");
         var category = document.createElement("div");
         category.innerHTML = "Category : "+cat ;
         td3.appendChild(category);
         td3.style.padding = "50px";

         //Adding Price
         var Price = document.createElement("div");
         Price.innerHTML = "Price : "+price;
         td3.appendChild(Price);
         tr.appendChild(td3);

         //Adding Rating
         var td4 = document.createElement("td");
         td4.textContent = "Rating: "+ rating;
         td4.style.padding = "50px";
         tr.appendChild(td4);
     
      tabledata.appendChild(tr);

      });

      function resetVal(){
         var table = document.getElementById("tabledata");
         //var tr = table.getElementsByTagName("tr");
         console.log(table.rows.length);
          for(var i =0;i<table.rows.length;i++){
         //    tr[i].remove();     
         
         table.deleteRow(i);
      }  
      }
      

   

}