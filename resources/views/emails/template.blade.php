<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width"/>

  <style type="text/css">

  body
  {
    background-color: #eee;
  }


  div.body
  {
    margin: auto;
    max-width: 600px;
    padding: 3%;
    /*border: 1px solid gray;*/
    background-color: white;
    border: 0;
    border-bottom: 3px solid #DCDCDC;

  }

  .button
  {
    text-align: center;
    margin-top: 40px;
    margin-bottom: 40px;
  }

  .button a
  {
    padding: 15px;
    background-color: #e29d27;
    border-radius: 3px;
    color: white;
    font-weight: bold;
    text-decoration: none;

  }

  </style>

  </head>

  <body style="background-color: #eee">

  <div class="body">

      @yield('content')

  </div>


  </body>
  </html>
