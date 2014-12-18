function checkuser(){
	var username=document.getElementById("username");
	var userid =document.getElementById("userid");

	if(username.value.match(/^\s*$/)){
		userid.innerHTML='<font color="red">用户名不能为空</font>';
		return false;	
	}else if(username.value.length < 3){
		userid.innerHTML='<font color="red">用户名的长度不能少于3位</font>';
		return false;	
	}else if(username.value.length > 20){
		userid.innerHTML='<font color="red">用户名的长度不能大于20位</font>';
		return false;	
	}else{
		loadAJAXTab("reg_new.php?action=che&uname="+username.value, userid);
		return true;
	}
}

function checkpwd(){
	var userpwd=document.getElementById("txtPassword");
	var pwdok =document.getElementById("userpwdok");
	var pwdokid =document.getElementById("pwdokid");
	if(userpwd.value != pwdok.value){
		pwdokid.innerHTML='<font color="red">两次密码不一致</font>';
		return false;	
	}else if(userpwd.value.match(/^\s*$/)){
		pwdokid.innerHTML='<font color="red">用户密码不能为空</font>';
		return false;	
	}else if(userpwd.value.length < 3){
		pwdokid.innerHTML='<font color="red">用户密码的长度不能少于3位</font>';
		return false;	
	}else {
		pwdokid.innerHTML='<font color="green">密码可以使用</font>';
		return true;
	}
}

function checkemail(){
	var email=document.getElementById("email");
	var emailid =document.getElementById("emailid");
	if(email.value.match(/^\s*$/)){
		emailid.innerHTML='<font color="red">电子邮件不能为空</font>';
		return false;	
	}else if(!email.value.match(/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/)){
		emailid.innerHTML='<font color="red">不是合法的电子邮件格式</font>';
		return false;	
	}else {
		emailid.innerHTML='<font color="green">电子邮件可以使用</font>';
		return true;
	}	
}



function validate(){
	var result=true;

	if(!checkuser()){
		result=false;
	}

	if(!checkpwd()){
		result=false;
	}


	if(!checkemail()){
		result=false;
	}
	var vdcode=document.getElementById("vdcode");
	if(vdcode.value.match(/^\s*$/)){
		alert("请添写验证码");
		result=false;	
	}
	
	return result;
}
