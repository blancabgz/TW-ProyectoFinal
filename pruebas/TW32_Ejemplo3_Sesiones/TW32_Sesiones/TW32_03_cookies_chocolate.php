<?php

if (isset($_COOKIE["galleta"]))
  echo "Tenemos una cookie almacenada: ", $_COOKIE["galleta"], PHP_EOL;
else {
  setcookie("galleta", "Chocolate", time()+10, "", "", false, true);
  echo "... acabo de almacenar una cookie";
  echo "<script>alert(document.cookie);</script>";
}
