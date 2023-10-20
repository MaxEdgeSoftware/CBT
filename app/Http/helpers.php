<?php
function school()
{
    return \App\Models\School::where("domain", $_SERVER["HTTP_HOST"])->first();
}
