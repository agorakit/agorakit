<?php

function summary($text, $length = 200)
{
  return str_limit(strip_tags($text), $length);
}
