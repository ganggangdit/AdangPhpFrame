function checkuser(){
	var username=document.getElementById("username");
	var userid =document.getElementById("userid");

	if(username.value.match(/^\s*$/)){
		userid.innerHTML='<font color="red">�û�������Ϊ��</font>';
		return false;	
	}else if(username.value.length < 3){
		userid.innerHTML='<font color="red">�û����ĳ��Ȳ�������3λ</font>';
		return false;	
	}else if(username.value.length > 20){
		userid.innerHTML='<font color="red">�û����ĳ��Ȳ��ܴ���20λ</font>';
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
		pwdokid.innerHTML='<font color="red">�������벻һ��</font>';
		return false;	
	}else if(userpwd.value.match(/^\s*$/)){
		pwdokid.innerHTML='<font color="red">�û����벻��Ϊ��</font>';
		return false;	
	}else if(userpwd.value.length < 3){
		pwdokid.innerHTML='<font color="red">�û�����ĳ��Ȳ�������3λ</font>';
		return false;	
	}else {
		pwdokid.innerHTML='<font color="green">�������ʹ��</font>';
		return true;
	}
}

function checkemail(){
	var email=document.getElementById("email");
	var emailid =document.getElementById("emailid");
	if(email.value.match(/^\s*$/)){
		emailid.innerHTML='<font color="red">�����ʼ�����Ϊ��</font>';
		return false;	
	}else if(!email.value.match(/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/)){
		emailid.innerHTML='<font color="red">���ǺϷ��ĵ����ʼ���ʽ</font>';
		return false;	
	}else {
		emailid.innerHTML='<font color="green">�����ʼ�����ʹ��</font>';
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
		alert("����д��֤��");
		result=false;	
	}
	
	return result;
}
