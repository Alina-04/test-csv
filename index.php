<?php
	$args = getopt("i:o:");

	$file = $args['i'];
	$count = count(file($file));
	$titles = [];
	$data = [];
	if (($handle = fopen($file, "r")) !== false) {
            $head = fgetcsv($handle, 0, ",");
            for ($i = 0; $i < $count && ($values = fgetcsv($handle, 100000, ",")) !== false; $i++) {
                foreach ($values as $key => $value){
                    foreach ($head as $k => $name){
                        if ($key == $k){
                        	if($name == "ITEM") {
                        		$value = json_decode($value, true);
                        		foreach ($value as $index => $item) {
                        			if(is_array($item)){
                        				$item = json_encode($item);
                        			}
                        			if (!in_array($index, $titles)) {
                        				$titles[] = $index;
                        			}
                        			$data[$i][$index] = $item;	
                        		}
	                    	} else {
	                    		if(!in_array($name, $titles)){
	                    			$titles[] = $name;
	                    		}
	                        	$data[$i][$name] = $value;
	                    	}
                        }
                    }
                }
            }
            fclose($handle);
        }
        $result = [];
        $fp = fopen($args['o'], 'w');
        sort($titles);
        fputcsv($fp, $titles);
        foreach ($data as $index => $row) {
        	foreach ($titles as $key => $rowName) {
        		if(!isset($row[$rowName])){
        			$result[$index][$key] = '';
        		} else {
        			$result[$index][$key] = $row[$rowName];
        		}
        	}
	    	
		}
		foreach ($result as $value) {
			fputcsv($fp, $value);
		}
        fclose($fp);
?>