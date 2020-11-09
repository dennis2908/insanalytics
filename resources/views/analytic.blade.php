

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Total View & Visitor</title>

        <!-- Fonts -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet" type="text/css">
<style>
/* resets and general styles */

*{
	margin: 0 auto;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}

::-moz-selection{
	color: #FFFFFF;
	background: #FF5722;
}

::selection{
	color: #FFFFFF;
	background: #FF5722;
}

body{
	font: normal 16px 'Roboto', sans-serif;
	position: absolute; 
	height: 100%;
	width: 100%;
}

div.wrapper{
	width: calc(4*225px);
	margin: 0 auto;
}

span, a, strong{font-weight: 700;}
a{transition: .2s; text-decoration: none; color: #787878; border-bottom: 1px solid #607D8B;}
a:hover{transition: .2s; color: #FF5722;}

/* page layout */

/* header */

header{
	width: 100%;
	height: 50%;
	background: #607D8B;
}

/* section */

section{
	width: 100%;
	height: 50%;
}

section h1, section p.meta{
	color: #FFFFFF;
	margin-bottom: 15px;
}

section hgroup{margin-top: -360px;}

section p.numbers{font-size: 3em;}
section p.meta,section p.strings{font-size: 1.5em;}
section h1{font-size: 4.5em;}

/* countdown styles */

div#countdown{color: #353535;}

div#countdown p{
	width: 100%;
	display: inline-block;
	text-align: center;
}

p.numbers{
	width: 100%;
	height: 85%;
	background: #FFFFFF;
	margin-top: -25px;
	padding-top: 100px;
}

p.strings{
	width: 100%;
	height: auto;
	padding: 30px 0;
	background: #FF5722;
	color: #FFFFFF;
}

div.cd-box{
	width: 210px;
	height: 360px;
	background: #FFFFFF;
	float: left;
	padding: 25px 0 0 0;
	margin: 30px 15px 0 0;
	-webkit-box-shadow: 5px 6px 9px 1px rgba(53, 53, 53, 0.5);
	-moz-box-shadow: 5px 6px 9px 1px rgba(53, 53, 53, 0.5);
	box-shadow: 5px 6px 9px 1px rgba(53, 53, 53, 0.5);
}

/* footer */

footer{
	position: relative;
	top: 60px;
}

footer p{
  color: #787878;
}
</style>
		<!-- Styles -->
           </head>
    <body>
	
	<div class="container h-100">
  <div class="row h-100 justify-content-center align-items-center"> 
  <section class="col-4 mx-auto">  
     <div class="row">
    <div class="col-md-5">
        <div class="text-center">
            <h2><span class="badge badge-primary">Total View & Visitor</span></h2>
        </div>
    </div>
</div>
  	
  <div class="wrapper">

    <div id="countdown">
      <div class="cd-box">
        <p class="numbers days">{{number_format(array_sum(array_column($data->toArray(),'pageViews')),0,'.',',')}}</p><br>
        <p class="strings timeRefDays">Total View</p>
      </div>
      <div class="cd-box">
        <p class="numbers hours">{{number_format(array_sum(array_column($data->toArray(),'visitors')),0,'.',',')}}</p><br>
        <p class="strings timeRefHours">Total Visitor</p>
      </div>
    </div>

  </div>
  <!-- end div.wrapper -->
</section>
</div>
    </div>
	</body>
</html>
