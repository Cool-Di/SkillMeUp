<?php

//Функция для дебага
function DebugMessage($message, $title = false, $color = "#008B8B")
{
	echo '<table class="debugmessage" border="0" cellpadding="5" cellspacing="0" style="border:1px solid '.$color.';margin:2px;background: #ffffff; text-align:left;"><tr><td>';
	if (strlen($title)>0)
	{
		echo '<p style="color: '.$color.';font-size:11px;font-family:Verdana;">['.$title.']</p>';
	}

	if (is_array($message) || is_object($message))
	{
		echo '<pre style="color:'.$color.';font-size:11px;font-family:Verdana;">'; print_r($message); echo '</pre>';
	}
	else
	{
		echo '<p style="color:'.$color.';font-size:11px;font-family:Verdana;">'.$message.'</p>';
	}

	echo '</td></tr></table>';
}

//Функция для дебага
function DebugMessage2($message)
{
	echo "<pre>";
    var_dump($message);
    echo "</pre";
}