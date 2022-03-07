<?php
$classwork = (new \App\Models\ClassWork())->where('class', $student->class->id)->findAll();

d($classwork);