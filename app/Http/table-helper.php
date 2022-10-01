<?php

//var_dump('manish');

function followuptable($followupnum = 0) 
{
	//default for firstfollowup
	$followupnum = $followupnum;
	$table = DB::table('followup'.config('site_vars.currennt_batch')."_".$followupnum);
	
	return $table;
}

function followuptablename($followupnum = 0) //for name only
{
	//default for firstfollowup
	$followupnum = $followupnum;
	$table = 'followup'.config('site_vars.currennt_batch')."_".$followupnum;
	
	return $table;
}

function followupoldtable($followupnum = 0) 
{
	//default for firstfollowup
	$followupnum = $followupnum;
	$table = DB::table('followup'.config('site_vars.currennt_batch')."_".$followupnum);
	
	return $table;
}