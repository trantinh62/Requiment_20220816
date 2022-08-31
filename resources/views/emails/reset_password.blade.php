<!DOCTYPE html>
<html>
<head>
 <title>Links reset Password</title>
</head>
<body>
 
 <h1>hello  {{$data['email']}}</h1>
 <p>link reset password : http://localhost:3000/resetpassword?token={{$data['token']}}</p>
</body>
</html> 
