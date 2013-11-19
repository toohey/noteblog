<?php
        $map_delimiter = " > ";
        if(isset($this->map) && count($this->map)) 
        {
            echo "<a href='/'>Root</a>";
            echo $map_delimiter;
            foreach($this->map as $index => $folder)
            {
                echo "<a href='/directory/{$folder->folder_id}.folders' id='id-{$folder->folder_id}'>{$folder->folder_name}</a>";
                echo $map_delimiter;
            }
        }
?>