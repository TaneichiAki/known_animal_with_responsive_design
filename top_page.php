<?php

class Top
{
	protected function controller(): array
	{
		$value = array();
		return $value;
	}

	protected function model(array $value): array
	{
		session_start();
		$user = 'keisuke';
		
		$value['msg'] = "";
		//Database Access
		if(true)
		{
			$value['msg'] = "パスワードが間違っています";
			return $value;
		}
		$value['msg'] = $msg;

		return $value;
	}
	protected function view(array $value): array
	{
		echo <<<EOT
		<h1>Top</h1>
		<div>{$value['msg']}</div>
EOT;
		return $value;
	}
	public static function run(Top $top)
	{
		$value = $top->controller();
		$_value = $top->model($value);
		$result = $top->view($_value);
	}
}
$top = new Top();
Top::run($top);


