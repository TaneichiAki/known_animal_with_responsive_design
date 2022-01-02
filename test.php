<?php
	require_once(__DIR__."/classes/Dao.php");
	
		$sql = "select * from users";
		$param =array('ukei2021');
		$user = Dao::db()->show_any_rows($sql);
		
		//var_dump($user['data']['gender']);
		var_dump($user['data'][2]['gender']);
		
					$ar01 = array(0,1,2,3,4,5,6,7,8,9);
					$ar02 = array(a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z);
					$ar03 = array(A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z);
					$ar04 = array(0,1,2);
					$number = "";
					for($i = 0; $i <= 1; $i++){
					$key_[0] = $ar01[array_rand($ar01)];
					$key_[1] = $ar02[array_rand($ar02)];
					$key_[2] = $ar03[array_rand($ar03)];
					shuffle($ar04);
					$number =$number. $key_[$ar04[0]] . $key_[$ar04[1]] . $key_[$ar04[2]].$key_[rand(0,2)];
					//var_dump($number);
					}
					var_dump($number);
					exit;