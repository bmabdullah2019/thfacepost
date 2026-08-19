<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>System error has occured!</title>
<style>
body {
color: #fff;
    font-family: sans-serif;
    text-align: center;
    width: 1000px;
    margin: 10% auto;
}
h2 {
font-size: 30px;
text-transform: uppercase;
font-weight: bold;
}
p {
	
}
.container {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
    border: 1px dashed;
    padding: 20px;
    border-radius: 10px;
}
.error {
	    font-size: 30px;
    font-weight: bold
}
</style>
</head>
<body>
	<div class="container">
    	<p class="error">¯ \ _ (ツ) _ / ¯</p>
		<h2>Unrecoverable error has occured!</h2>
    	<h3>Error Code: <?php echo $params['code']; ?></h3>
    	<p><?php echo $params['message']; ?></p>
    </div>
</body>
</html>