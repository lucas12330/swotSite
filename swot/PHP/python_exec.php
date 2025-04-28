<?php 

$output = shell_exec('py ../PYTHON/python_script.py 2>&1');
echo "<pre>$output</pre>";