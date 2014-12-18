//更改字体大小
var status0='';
var curfontsize=10;
var curlineheight=18;
function fontZoomA(){
	if(curfontsize>8){
		document.getElementById('ccont').style.fontSize=(--curfontsize)+'pt';
		document.getElementById('ccont').style.lineHeight=(--curlineheight)+'pt';
	}
}
function fontZoomB(){
	if(curfontsize<64){
		document.getElementById('ccont').style.fontSize=(++curfontsize)+'pt';
		document.getElementById('ccont').style.lineHeight=(++curlineheight)+'pt';
	}
}

	function newgdcode(obj,url) {
		//后面传递一个随机参数，否则在IE7和火狐下，不刷新图片
		obj.src = url+ '?nowtime=' + new Date().getTime();
				
	}

function getXmlhttp() {
	var http_request;

	if(window.XMLHttpRequest) { 
		http_request = new XMLHttpRequest();
		if (http_request.overrideMimeType) {
			http_request.overrideMimeType("text/xml");
		}
	}else if (window.ActiveXObject) { 
		try {
			http_request = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				http_request = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}
	if (!http_request) { 
		window.alert("can't create XMLHttpRequest object.");
		return null;
	}	
	return http_request;
}


function loadAJAXTab(url, objid){
	var result=true;;
	var xhttp=getXmlhttp();		
	xhttp.onreadystatechange=function(){
		if(xhttp.readyState == 4 && (xhttp.status==200 || window.location.href.indexOf("http")==-1)){	
			objid.innerHTML=xhttp.responseText;
		}
	}
	xhttp.open("GET",url,true);
	xhttp.send(null);
	
}

function setCom(obj, num, cid) {
	var url="comm_pro.php?action="+num+"&cid="+cid;
	loadAJAXTab(url, obj);
}

function quote(quoteid, cid){
	var quoteObj=document.getElementById(quoteid);
	quoteObj.value=cid;
}
