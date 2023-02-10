<?php
/* PHP script that is executed form the command line. 
* output contains :
* numbers from 1 to 100
* Where the number is divisible by three (3) output the word “foo”
* Where the number is divisible by five (5) output the word “bar”
* Where the number is divisible by three (3) and (5) output the word “foobar”
*/
// function to fetch the result
function Test($start, $end)
{
    for($i = $start; $i <= $end; $i++)
    {
        //echo $i;
        
        if(($i % 3) == 0)
        {
            if(($i % 5) == 0)
            {
                echo "foobar";
            }
            else
            {
                echo "foo";
            }
        }
        elseif(($i % 5) == 0)
        {
            echo "bar";
        }
        else{
            echo $i;
        }

        if(!($i == $end))
        {
            echo ",";
        }
    }
}
//calling the test function
Test(1, 100);
?>