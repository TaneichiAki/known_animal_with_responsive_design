<?php

class Human
{
	private string $name;
	private int $age;
	
	public function __construct(string $name,int $age)
	{
		$this->name = $name;
		$this->age = $age;
	}

	public function get_name(): string
	{
		return $this->name;
	}
	public function get_age(): int
	{
		return $this->age;
	}
	public function jikoshokai(){
		echo "私の名前は{$this->name}です。年は{$this->age}です。<br>";
	}
}


$human1 = new Human('aki',32);
$human2 = new Human('keisuke',35);

//echo $human1->get_age()."<br>";
//echo $human2->get_age()."<br>";
$human1->jikoshokai();
$human2->jikoshokai();


//変数 $human1->age
//関数 $fuman1->get_age()
