<?php
session_start(); // Right at the top of your script
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
if($_SESSION['logged']==true)
    { 
      //echo 'Hello ';
      $name= $_SESSION["abc"];
	//S_SESSION["name"] = $name;
        }
  else if($_SESSION['logged']==false)
    {
      echo 'Not logged in. Error<a href="index.html">';
    }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Disease Analyzer Result</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="styleabout.css" type="text/css" media="screen" />
</head>
<body>
<div class="headerbackground">
<header>
	<nav>
			<div class="right"><h7>Hello <?php echo $name; ?></h7>
			<a href="account.php">My Account&nbsp;</a>
			<a href="logout.php">Logout&nbsp;</a>
			</div>
		</nav>

<div class="logo">

</div>

<div class="dropdown">
  <button class="dropbtn" onclick="window.location.href='category.php?link=W'">EHR SOLUTIONS</button>
  <div class="dropdown-content">
    <a href="ehroverview.php?sub=te&link=W">EHR OVERVIEW</a>
    <a href="subcategory.php?sub=to&link=W">ELECTRONIC PRESCRIBING</a>
    <a href="subcategory.php?sub=ca&link=W">LABS & IMAGING</a>
    <a href="subcategory.php?sub=sh&link=W">ONLINE BOOKING</a>
  </div>
</div>

<div class="dropdown">
  <button class="dropbtn" onclick="window.location.href='category.php?link=I'">PRESCRIPTION</button>
  <div class="dropdown-content">
    <a href="subcategory.php?sub=et&link=I">VIEW PRESCRIPTIONS</a>
    <a href="addnewprescription.php?sub=pw&link=I">ADD NEW PRESCRIPTIONS</a>
    <a href="subcategory.php?sub=ku&link=I">UPDATE PRESCRIPTIONS</a>
   <a href="subcategory.php?sub=du&link=I">DELETE PRESCRIPTIONS</a>
  </div>
</div>

<div class="dropdown">
  <button class="dropbtn" onclick="window.location.href='category.php?link=B'">ABOUT</button>
  <div class="dropdown-content">
	<a href="about.php?sub=pl&link=B">ABOUT US</a>
    <a href="contact.php?sub=le&link=B">CONTACT US</a>
    <a href="team.php?sub=pl&link=B">TEAM</a>
  </div>
</div>

<div class="dropdown">
  <button class="dropbtn" onclick="window.location.href='category.php?link=L'">SUPPORT</button>
  <div class="dropdown-content">
    <a href="subcategory.php?sub=li&link=L">HELP CENTER</a>
    <a href="subcategory.php?sub=pa&link=L">EHR CONSULTING SERVICES</a>
  </div>
</div>

<div class="dropdown">
  <button class="dropbtn" onclick="window.location.href='category.php?link=N'">FAQS</button>
  <div class="dropdown-content">
    <a href="whyswitchtoehr.html?sub=mn&link=N">WHY SWITCH TO EHR ?</a>
    <a href="ehrvsemr.html?sub=ns&link=N">EHR vs EMR</a>
  </div>
</div>

</div>

</header>

</div>
<br><h1><center>Result</center></h1>
<?php
	//include('app.js');
	//$input=array();
	//$input=json_decode($_POST['jsondata']);
	$inputstr=$_GET['hagu'];
	$input=array();
	$input=explode(',',$inputstr);
	array_unshift($input,"newpatient");
	//print_r($inputarray);
	//echo "Hello ";
	$dataset=array();
	$len=0;
	$myfile = fopen("newsample.txt", "r") or die("Unable to open file!");
	while (!feof($myfile)) {
        $line = fgets($myfile);
		$dataset[$len]=array();
		$len++;
    }
	rewind($myfile);
	for($row=0;$row<$len;$row++){
		$dataset[$row]=explode(' ',fgets($myfile));
	}
	for($row=0;$row<$len;$row++){
		for($column=3;$column<=16;$column++){
			$dataset[$row][$column]=(float)$dataset[$row][$column];
		}
	}
	
	
	$sum=0;
$normalize=array();
$similarity=array();
for($i=0;$i<$len;$i++){
	$normalize[$i]=array();
	$similarity[$i]=array();
}
for($row=0;$row<$len;$row++){
	$sum=0;
	$sqrt=$dataset[$row][18];
	$normalize[$row][0]=$dataset[$row][0];
	$normalize[$row][15]=$dataset[$row][19];
	for($column=3;$column<=16;$column++){
		$data=$dataset[$row][$column]/$sqrt;
		$data=round($data,4);
		$normalize[$row][$column-2]=$data;
	}
}
/*
for($row=0;$row<25;$row++){
	for($column=0;$column<16;$column++){
		echo $normalize[$row][$column];
		echo "  ";
	}
	echo "<br/>";
}
*/

//$input=array("p26",1,0,1,1,0,0,1,1,1,0,0,1,0,0);
$sum=0;
for($i=1;$i<15;$i++){
	$input[$i]=pow($input[$i],2);
	$sum=$sum+$input[$i];
}
$sum=sqrt($sum);
for($i=1;$i<15;$i++){
	$input[$i]=round(($input[$i]/$sum),4);
	//echo $input[$i];
	//echo "<br/>";
}
$sum=0;
$temp=0;
$max=0;
$maxpos=0;
for($rows=0;$rows<$len;$rows++){
	$sum=0;
	for($columns=1;$columns<15;$columns++){
		$temp=$normalize[$rows][$columns]*$input[$columns];
		$sum=$sum+$temp;
	}
	if($sum>$max){
		$max=$sum;
		$maxpos=$rows;
	}
	$similarity[$rows][0]=$normalize[$rows][0];
	$similarity[$rows][1]=round($sum,4);
	$similarity[$rows][2]=$normalize[$rows][15];
}

echo "Maximum Similarity";
echo "<br/>";
echo $similarity[$maxpos][0];
echo "<br/>";
echo $similarity[$maxpos][1];
echo "<br/>";
echo $similarity[$maxpos][2];
echo "<br/>";




 
/*for ($row = 0; $row < $len; $row++) {
  echo "<p><b>Row number $row</b></p>";
  echo "<ul>";
  for ($col = 0; $col < 3; $col++) {
    echo "<li>".$similarity[$row][$col]."</li>";
  }
  echo "</ul>";
}*/

?><br><br/><br/>
	<footer>
	<ul id="nestedlist">
    <li><a href="#">EMERGE</a>
    <ul>
        <li class="connect"><a href="#">Main</a>
        <ul>
            <li><a href="#">Home Page</a></li>
            <li><a href="#">Register</a></li>
            <li><a href="#">Login</a></li>
            <li><a href="#">Overview</a></li>
            <li><a href="#">Contact</a></li>


        </ul>
        </li>
        <li><a href="#">Additional Features</a>
        <ul>
            <li><a href="#">E-presribing</a></li>
            <li><a href="#">Disease Analyzer</a></li>
        </ul>
        </li>
    </ul>
    </li>
</ul>
</footer>

</body>
</html>