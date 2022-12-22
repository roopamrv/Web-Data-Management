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
      <button name="reset" id="reset"> Reset</button>
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
          $price=""; $title = ""; $add="";
          $id = (string)$bussiness->id;
          $name = (string)$bussiness->name;
          $image_url = (string)$bussiness->image_url;
          $yelp_page_url = (string)$bussiness->url;

          foreach($bussiness->categories as $cat){
            $title = $title . (string)$cat->title . " ";
          }
          
         foreach($bussiness->location as $loc =>  $val){
          if($loc == "display_address")
          { 
            foreach($val as $addr)
            {
              $add = $add  .(string)$addr. " ";
            }
          }
          }
        if(!isset($bussiness->price)) { 
            $price ="";
          }
          else $price = (string)$bussiness->price;
          //print_r($bussiness->price);
          $rating = (float)$bussiness->rating;
          $phone = (string)$bussiness->phone;
          
        $_SESSION["search"][(string)$bussiness->id] = array("id"=>$id ,"name" => $name, "image_url" => $image_url, 
                                                            "url" => $yelp_page_url, "price" => $price,
                                                            "rating" => $rating, "phone" => $phone, "categories" => $title, "address" => $add); 

       
      }
    }
   displayRestaurants(); 
   fetchDb(); 
  }

  function displayRestaurants(){
    
    print("<div style=\"width:50%;float:left\">");
    print("<table style\"borderCollapse:collapse;textAlign:center\">");
    if(isset($_SESSION["search"]))
    {
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
    foreach($_SESSION["search"] as $id => $bussiness){
        if($id==$storeId)
        {
          favoritesDb((string)$id, (string)$bussiness["name"], (string)$bussiness["image_url"], 
          (string)$bussiness["url"], (string)$bussiness["price"], (float)$bussiness["rating"], 
          (string)$bussiness["phone"], (string)$bussiness["categories"], (string)$bussiness["address"]);
        }
      }
    fetchDb();
  }

  function favoritesDb($id, $name, $image_url, $yelp_page_url, $price, $rating, $phone, $categories, $address){
  
    $dbHost = "localhost";
    $dbUser = "root";
    $dbPassword ="";
    $dbName = "yelp";

    try{
      $dsn = "mysql:host=" . $dbHost . ";dbname=" .$dbName;
      $pdo = new PDO($dsn, $dbUser, $dbPassword);
      
    }
    catch(PDOException $err){
      echo "Db connection failed: " . $err -> getMessage();
    }

    $sql ="INSERT INTO favorites(id,name,image_url,yelp_page_url,price,rating,phone, categories, address)
                VALUES(:id, :name, :image_url ,:yelp_page_url, :price, :rating, :phone, :categories, :address)";

    $stmt = $pdo -> prepare($sql);
    $stmt->execute(['id'=> $id, "name" => $name, "image_url" => $image_url, "yelp_page_url"=> $yelp_page_url,
                  "price" => $price , "rating" => $rating, "phone" => $phone, "categories" => $categories, "address" => $address]);

    
  }   

  function fetchDb(){
  
    $dbHost = "localhost";
    $dbUser = "root";
    $dbPassword ="";
    $dbName = "yelp";

    try{
      $dsn = "mysql:host=" . $dbHost . ";dbname=" .$dbName;
      $pdo = new PDO($dsn, $dbUser, $dbPassword);
    }
    catch(PDOException $err){
      echo "Db connection failed: " . $err -> getMessage();
    }
    $query = "Select * from favorites";
    $fth = $pdo->prepare($query);

    $fth->execute();
    print("<div style=\"width:50%;float:left\">");
    print("<table style\"borderCollapse:collapse;textAlign:center\">");
    while ($row = $fth->fetch()){
      $id = $row[0];
      $name = $row[1];
      $image_url = $row[2];
      $url = $row[3];
     
      print("<tr><td>");
      print "<img style=\"width:100px;height:100px\" src=".$image_url."></td><td>";
      print $name."</td></tr>";
    }
      print("</table></div>");
    }
  

  function resetAll(){
    $_SESSION["search"] = array();
    $_SESSION["city"] = "";
  }
?>
