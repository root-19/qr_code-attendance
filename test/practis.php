<?php

echo "test <br>";
//  for ($x  = 0; $x < 10; $x++) {
//     if($x < 4) {
//         continue;
//     }
//     echo "the number is: $x <br>";
//     echo $x;
//  }

// function sum($x, $y) {
//   $z = $x + $y;
//   return $z;
// }

// echo "5 + 10 = " . sum(5, 10) . "<br>";
// echo "7 + 13 = " . sum(7, 13) . "<br>";
// echo "2 + 4 = " . sum(2, 4);

// function sumMyNumbers(...$x) {
//   $n = 0;
//   $len = count($x);
//   for($i = 0; $i < $len; $i++) {
//     $n += $x[$i];
//   }
//   return $n;
// }

// $a = sumMyNumbers(5, 2, 6, 2, 7, 7);
// echo $a;

// function myFamily($lastname, ...$firstname) {
//   $txt = "";
//   $len = count($firstname);
//   for($i = 0; $i < $len; $i++) {
//     $txt = $txt."Hi, $firstname[$i] $lastname.<br>";
//   }
//   return $txt;
// }

// $a = myFamily("Doe", "Jane", "John", "Joey");
// echo $a;
// $x = 75;
  
// function myfunction() {
//   echo $GLOBALS['x'];
// }

// myfunction()
$str = "Visit W3Schools";
$pattern = "/w3schools/i";
echo preg_match($pattern, $str);

$str = "Visit Microsoft!";
$pattern = "/microsoft/i";
echo preg_replace($pattern, "W3Schools", $str);

?>