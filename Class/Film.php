<?php
class Band
{
	private $name;
	private $connections;
	
	function __construct($name)
	{
		$this->name=$name;
	}
	
	public function drawMe()
	{
		
	}
	
	public function getMembers()
	{
		$arr = new stdClass();
		$arr->{'/music/musical_group/member'}[0] = new stdClass();
		$arr->{'/music/musical_group/member'}[0]->member = new stdClass();
		$arr->{'/music/musical_group/member'}[0]->member->name=null;
		$arr->id=$this->name;
		$arr->type="/music/artist";
		
		$query = array('q1'=>array('query'=>$arr));
		$query = json_encode($query);
		$query = str_replace('\\/', '/',$query);
		
		
		$apiendpoint = "http://sandbox.freebase.com/api/service/mqlread?queries";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "$apiendpoint=$query");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		
		$result = json_decode($result);
		$members=$result->q1->result->{'/music/musical_group/member'};
		
		$i=0;
		foreach($members as $member)
		{
			$resultArray[$i]=$member->member->name;
			$i++;
		}
		
		return $resultArray;
	}
	
	public function getConnections()
	{
		$members = $this->getMembers();
		
		
		
//		for($i=0;$i<sizeof($members);$i++)
//		{
			
			
		$arr = new stdClass();
			$arr->membership[0] = new stdClass();
				for($j=0;$j<sizeof($members);$j++)
				{
					$arr->{'name|='}[$j]=urlencode($members[$j]);
				}
			
			//urlencode($members[$i]);
			$arr->type= "/music/group_member";
			$arr->membership[0]->group = new stdClass();
			$arr->membership[0]->group->name=null;
			$arr = json_encode($arr);
			echo $arr;
			$query = array('q1'=>array('query'=>$arr));
			$query = json_encode($query);
			$query = str_replace('\\/', '/',$query);
			
			
			
		/*	$apiendpoint = "http://sandbox.freebase.com/api/service/mqlread?queries";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "$apiendpoint=$query");
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			$result = json_decode($result);
			
			print_r( $result);
			
			$conn=$result->q1->result->membership;
			

			foreach($conn as $con)
			{
				//$resultArray[$members[$i]][$i]=$member->group->name;

				
			}*/
			
	//	}
	
	//	print_r($resultArray);
		
	}
	
}


?>