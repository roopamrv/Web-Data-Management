<html>
  <head>
  <title>FIND RESTAURANTS</title>
  <meta charset="utf-8"/>
  <body>

  <form action = "yelp.php" method="GET">

    <div>
    <label for="city">City: </label>
    <input name="city" id="city"/>
  </div>
  <div>
    <label for="keyword">Keyword: </label>
    <input name="keyword" id="keyword"/>
  </div>
  <div>
    <button>Find</button>
  </div>
    </form>
    <form action="yelp.php?reset" method="POST">
      <button> Reset</button>
    </form>
    <hr>
    </body>
  </head>



<?php
session_start();

  if(isset($_GET["store"])){
    displayRestaurants();
    setFavorites((string)$_GET["store"]);
  }

  if(isset($_POST["reset"])){
    resetAll();
  }
  if(isset($_GET["city"]) && isset($_GET["keyword"])){
    findRestaurants();
  }
  function findRestaurants(){

      $city = $_GET["city"];
      if(!isset($_SESSION["city"])){
        $_SESSION["city"] = string;
      }
      else{
        $_SESSION["city"] = $city;
      }
      $key = $_GET["keyword"];
      // put your Yelp API key here:
      $API_KEY = 'hN7TVbgI_IQ2tBOs2K4lRPopizv2CfWy6gfStLI9SMtTaoMrs0jur_kXxUj4E8Kc2GSWrUrtqGbUhKZMpjk_lgEmK3XK4Q3E_45VULdKw_xOHujYsxclKDpkkYk2Y3Yx';
      $API_HOST = "https://api.yelp.com";
      $SEARCH_PATH = "/v3/businesses/search";
      $BUSINESS_PATH = "/v3/businesses/";
      $url = $API_HOST.$SEARCH_PATH."?term=".$key."&location=".$city."&limit=10";
      $options = array('http' => array(
        'method' => 'GET',
        'header' => 'Authorization: Bearer ' .$API_KEY
      ));

      $context = stream_context_create($options);
      $resp = file_get_contents($url,false,$context);
      //echo $resp;
      $response = json_decode(file_get_contents($url,false,$context)) ;
      $restaurants = $response->businesses;
      searchRestaurants($restaurants);
  }

 function searchRestaurants($result)
  {
    if(!isset($_SESSION["search"]))
    {
      $_SESSION["search"] = array();
    }
    else
    {
      foreach($result as $bussiness)
      {
        $_SESSION["search"][(string)$bussiness->id] = array("name" => (string)$bussiness->name, "image_url" => (string)$bussiness->image_url); 
      }
    }
   
   displayRestaurants();  
   if(isset($_SESSION["favorites"])){
    displayFavorites();
  }

  }

  function displayRestaurants(){
    
    print("<div style=\"width:50%;float:left\">");
    print("<table style\"borderCollapse:collapse;textAlign:center\">");
   //print("<table style=\"display: inline-block; float:left\">"); 
   if(isset($_SESSION["search"])){
      foreach($_SESSION["search"] as $id => $bussiness)
      {
        print("<tr><td>");
        print "<a href=\"yelp.php?store=".$id."\"><img style=\"width:100px;height:100px\" src=".$bussiness["image_url"]."></a></td><td>";
        print $bussiness["name"]."</td></tr>";
      }
    }
    print("</table></div>");
  }


  function setFavorites($storeId){
    if(!isset($_SESSION["favorites"])){
      $_SESSION["favorites"] = array();
    }
    foreach($_SESSION["search"] as $id => $bussiness){
        if($id==$storeId)
        {
          $_SESSION["favorites"][(string)$id] = array("name" => (string)$bussiness["name"], "image_url" => (string)$bussiness["image_url"]);     
        }
      }
    
    displayFavorites();
  }


  function displayFavorites(){
    print("<div style=\"width:49%;float:left\">");
    //print("Favorites ");
    print("<table style\"borderCollapse:collapse;textAlign:center\">");
    //print("<table style=\"display: inline-block; float:left\">");
    if(isset($_SESSION["favorites"])){
      foreach($_SESSION["favorites"] as $id => $bussiness)
      {
        print("<tr><td>");
        print "<img style=\"width:100px;height:100px\" src=".$bussiness["image_url"]."></td><td>";
        print $bussiness["name"]."</td></tr>";
      }
    }
    print("</table></div>");
    
  }


  function resetAll(){
    $_SESSION["search"] = array();
    $_SESSION["city"] = "";
    $_SESSION["favorites"] = array();
  }
?>
