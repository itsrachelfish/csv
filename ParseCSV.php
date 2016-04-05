<?php

class ParseCSV
{
    // Simple function to convert lines in a CSV to an array
    private function csv_to_array($file)
    {
        $text = file_get_contents($file);
        return array_map('str_getcsv', preg_split('/\R/m', $text));
    }

    // Function to search a CSV generated array for certain columns in a user defined map
    private function map_columns($array, $map)
    {
        $columns = array();
        $found = 0;
        $rows = 0;

        // Make sure all CSV column names are lowercase in the map
        foreach($map as $key => $value)
        {
            $map[$key] = trim(strtolower($value));
        }

        foreach($array as $row_id => $row)
        {
            $rows++;

            foreach($row as $column_id => $column)
            {
                // Lowercase the current CSV column name
                $column = trim(strtolower($column));

                if(in_array($column, $map))
                {
                    $key = array_search($column, $map);
                    $columns[$column_id] = $key;
                    $found++;
                }
            }

            if(count($map) == $found)
                break;
        }


        // If we have not found all of the user defined columns, return false
        if(count($map) != $found)
        {
            return false;
        }

        // Otherwise, slice off the number of rows we needed to determine the column map
        $array = array_slice($array, $rows);

        // Return the updated array and mapped columns
        return array($array, $columns);
    }

    // Function to loop through all CSV data and build the final output based on the user-defined map
    private function build_output($array, $columns)
    {
        $output = array();
        
        foreach($array as $row)
        {
            $temp = array();

            foreach($columns as $index => $column)
            {
                if(isset($row[$index]))
                {
                    $temp[$column] = $row[$index];
                }
            }

            // Make sure this row isn't empty
            if(!empty($temp))
            {
                $output[] = $temp;
            }
        }

        return $output;
    }

    public function parse($file, $map)
    {
        $array = $this->csv_to_array($file);
        $mapped = $this->map_columns($array, $map);

        if(is_array($mapped))
        {
            list($array, $columns) = $mapped;
            return $this->build_output($array, $columns);
        }

        return false;
    }
}
