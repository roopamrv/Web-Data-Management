var dx = 2;
var dy = -2;      /* displacement at every dt */
var x, y;         /* ball location */
var score = 0;    /* # of walls you have cleaned */
var tries = 0;    /* # of tries to clean the wall */
var started = false;  /* false means ready to kick the ball */
var ball, court, paddle, brick, msg;
var court_height, court_width, paddle_left;
let timer = 0;
var bricks = new Array(4);  // rows of bricks
var colors = ["red","blue","yellow","green"];
//const scoreView = document.querySelector("#score");
const mesg = document.querySelector('#messages');
/* get an element by id */
function id ( s ) { return document.getElementById(s); }

/* convert a string with px to an integer, eg "30px" -> 30 */
function pixels ( pix ) {
    pix = pix.replace("px", "");
    num = Number(pix);
    return num;
}

/* place the ball on top of the paddle */
function readyToKick () {
    console.log("ready to kick");
    x = pixels(paddle.style.left)+paddle.width/2.0-ball.width/2.0;
    y = pixels(paddle.style.top)-2*ball.height;
    ball.style.left = x+"px";
    ball.style.top = y+"px";
}
function drawBall(x , y) {
    ball.style.left = x+"px";
    ball.style.top = y+"px";
}
/* paddle follows the mouse movement left-right */
function movePaddle (e) {
    console.log("movePaddle entered")
    var ox = e.pageX-court.getBoundingClientRect().left;
    paddle.style.left = (ox < 0) ? "0px"
                            : ((ox > court_width-paddle.width)
                               ? court_width-paddle.width+"px"
                               : ox+"px");
    console.log(started);
    if (!started)
        readyToKick();
}

function initialize () {
    console.log("init");
    court = id("court");
    ball = id("ball");
    paddle = id("paddle");
    wall = id("wall");
    msg = id("messages");
    brick = id("red");
    court_height = pixels(court.style.height);
    court_width = pixels(court.style.width);
    for (i=0; i<4; i++) {
        // each row has 20 bricks
        bricks[i] = new Array(20);
        var b = id(colors[i]);
        for (j=0; j<20; j++) {
            var x = b.cloneNode(true);
            bricks[i][j] = x;
            wall.appendChild(x);
        }
       b.style.visibility = "hidden";
      // b.style.position = "fixed"
    }
    started = false;
 }

/* true if the ball at (x,y) hits the brick[i][j] */
function hits_a_brick ( x, y, i, j ) {
    var top = i*brick.height - 450;
    var left = j*brick.width;
    return (x >= left && x <= left+brick.width
            && y >= top && y <= top+brick.height);
}

function moveBall () {

  x += dx;
  y += dy;
  drawBall(x , y);
  checkCollision();
}

function startGame () {
    started = true;

    timer = setInterval(moveBall, 30);
}


function checkCollision(){
    //bricks
    for(let i=0;i<bricks.length;i++){
        var b = id(colors[i]);
        for(let j = 0;j<20;j++){
            //console.log("inside for!");
            if(hits_a_brick(x,y,i,j)==true){
                console.log(j);
                console.log("Hitting block")
                var k = bricks[i][j];
                removeChild(k);
                score++;
                id("score").innerHTML = score;
                id("messages").innerHTML = score;
                changeDir();
                 if(score == 20*bricks.length){
                    id("messages").innerHTML = 'You Win!' + score;
                    clearInterval(timer);
                    document.removeEventListener('mouseClick',movePaddle);
                 }
            }
            }

        }


    //Wall
    //console.log(pixels(ball.style.top));
    if(pixels(ball.style.top) <= -(court_height-ball.height+paddle.height)){
        changeDir();
    }
    if (pixels(ball.style.left) >= (court_width-ball.height) ){
        changeDir();
    }
    if(pixels(ball.style.left) <=0){
        changeDir();
    }
    //gameover
    if(pixels(ball.style.top )>0){
        clearInterval(timer);
        started = false;
        dx = 2;
        dy = -2;
        tries += 1;
        id("tries").innerHTML = tries;
        id("messages").innerHTML = "Game Over!!"

    }
}

function changeDir(){
    if(dx == 2 && dy == 2){
        dy = -2;
        return;
    }

    if(dx == 2 && dy == -2){
        dx = -2;
        return;
    }

    if(dx == -2 && dy == -2){
        dy = 2;
        return;
    }

    if(dx == -2 && dy == 2){
        dx = 2;
        return;
    }

}

function resetGame () {
    console.log("Reset Game");
    initialize();
    score = 0;
    tries = 0;
    id("tries").innerHTML = tries;
    id("score").innerHTML = score;
    id("messages").innerHTML = "Click to Start";
    readyToKick();
}
