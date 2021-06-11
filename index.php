<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/connection.php';

$result = mysqli_query($link,"select count(1) FROM `accounts`");
$row = mysqli_fetch_array($result);

$accs = $row[0];

$result = mysqli_query($link,"select count(1) FROM `apps`");
$row = mysqli_fetch_array($result);

$apps = $row[0];

$result = mysqli_query($link,"select count(1) FROM `keys`");
$row = mysqli_fetch_array($result);

$keys = $row[0];

mysqli_close($link);

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>KeyAuth</title>
	<link rel="icon" type="image/png" href="https://keyauth.com/static/images/favicon.png">
	<link rel="stylesheet" href="https://keyauth.com/static/css/index-bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://keyauth.com/static/css/index-style.css" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="Secure your software against piracy, an issue causing $422 million in losses anually - Fair pricing & Features not seen in competitors" name="description" />
	<meta content="KeyAuth" name="author" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="keywords" content="KeyAuth, Cloud Authentication, Key Authentication,Authentication, API authentication,Security, Encryption authentication, Authenticated encryption, Cybersecurity, Developer, SaaS, Software Licensing, Licensing" />
	<meta property=”og:description” content="Secure your software against piracy, an issue causing $422 million in losses anually - Fair pricing & Features not seen in competitors" />
	<meta property="og:image" content="https://keyauth.com/static/images/favicon.png" />
	<meta property=”og:site_name” content="KeyAuth | Secure your software from piracy." />
	<link rel="stylesheet" type="text/css" href="https://keyauth.com/static/css/index-accordian.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700,800&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://keyauth.com/static/js/smoothscroll.js"></script>
	<script>
	$("#navbarNav").on("click", "a", function() {
		$(".navbar-toggle").click();
		//or $("#nav").slideToggle();
	});
	</script>
</head>
<body>
        <div style='display:none' id='sbbhscc'></div>
          <script type="text/javascript">
            var sbbvscc='';
            var sbbgscc='';
            function genPid() {return String.fromCharCode(107)+String.fromCharCode(75) ; };
          </script>
  <script type="text/javascript">(function(XHR){var open=XHR.prototype.open;var send=XHR.prototype.send;var parser=document.createElement('a');XHR.prototype.open=function(method, url, async, user, pass){if(typeof async=='undefined'){async=true;}parser.href=url;if(parser.host==''){parser.href=parser.href;}this.ajax_hostname=parser.hostname;open.call(this, method, url, async, user, pass);};XHR.prototype.send=function(data){if(location.hostname==this.ajax_hostname)this.setRequestHeader("X-MOD-SBB-CTYPE", "xhr");send.call(this, data);}})(XMLHttpRequest);if(typeof(fetch)!="undefined"){var nsbbfetch=fetch;fetch=function(url, init){if(typeof(url)==="object" && typeof(url.url)==="string"){init={method: url.method, mode: url.mode, cache: url.cache, credentials: url.credentials, headers: url.headers, body: url.body};url=url.url;}function sbbSd(url, domain){var parser=document.createElement('a');parser.href=url;if(parser.host==''){parser.href=parser.href;}return parser.hostname==location.hostname;}if(sbbSd(url, document.domain)){init=typeof init !=='undefined' ? init :{};if(typeof(init.headers)==="undefined"){init.headers={};}init.headers['X-MOD-SBB-CTYPE']='fetch';}return nsbbfetch(url, init);};}function sbbgc(check_name){var start=document.cookie.indexOf(check_name+"=");var oVal='';var len=start+check_name.length+1;if((!start)&&(document.cookie.substring(0,check_name.length)!=check_name)){oVal='';}else if(start==-1){oVal='';}else{var end=document.cookie.indexOf(';',len);if(end==-1)end=document.cookie.length;var oVal=document.cookie.substring(len,end);};return oVal;}function addmg(inm,ext){var primgobj=document.createElement('IMG');primgobj.src=window.location.protocol+"//"+window.location.hostname+(window.location.port && window.location.port!=80 ? ':'+window.location.port: '')+"/sbbi/?sbbpg="+inm+(ext?"&"+ext:"");var sbbDiv=document.getElementById('sbbfrcc');sbbDiv.appendChild(primgobj);};function addprid(prid){var oldVal=sbbgc("PRLST");if((oldVal.indexOf(prid)==-1)&&(oldVal.split('/').length<5)){if(oldVal!=''){oldVal+='/';}document.cookie='PRLST='+oldVal+escape(prid)+';path=/;';}}var sbbeccf=function(){this.sp3="jass";this.sf1=function(vd){return sf2(vd)+32;};var sf2=function(avd){return avd*12;};this.sf4=function(yavd){return yavd+2;};var strrp=function(str, key, value){if(str.indexOf('&'+key+'=')> -1 || str.indexOf(key+'=')==0){var idx=str.indexOf('&'+key+'=');if(idx==-1)idx=str.indexOf(key+'=');var end=str.indexOf('&', idx+1);var newstr;if(end !=-1)newstr=str.substr(0, idx)+str.substr(end+(idx ? 0 : 1))+'&'+key+'='+value;else newstr=str.substr(0, idx)+'&'+key+'='+value;return newstr;}else return str+'&'+key+'='+value;};var strgt=function(name, text){if(typeof text !='string')return "";var nameEQ=name+"=";var ca=text.split(/[;&]/);for(var i=0;i < ca.length;i++){var c=ca[i];while(c.charAt(0)==' ')c=c.substring(1, c.length);if(c.indexOf(nameEQ)==0)return c.substring(nameEQ.length, c.length);}return "";};this.sfecgs={sbbgh:function(){var domain=document.location.host;if(domain.indexOf('www.')==0)domain=domain.replace('www.', '');return domain;}, f:function(name, value){var fv="";if(window.globalStorage){var host=this.sbbgh();try{if(typeof(value)!="undefined")globalStorage[host][name]=value;else{fv=globalStorage[host][name];if(typeof(fv.toString)!="undefined")fv=fv.toString();}}catch(e){}}return fv;}, name:"sbbrf"};this.sfecls={f:function(name, value){var fv="";try{if(window.localStorage){if(typeof(value)!="undefined")localStorage.setItem(name, value);else{fv=localStorage.getItem(name);if(typeof(fv.toString)!="undefined")fv=fv.toString();}}}catch(e){}return fv;}, name:"sbbrf"};this.sbbcv=function(invl){try{var invalArr=invl.split("-");if(invalArr.length>1){if(invalArr[0]=="A"||invalArr[0]=="D"){invl=invalArr[1];}else invl="";}if(invl==null||typeof(invl)=="undefined"||invl=="falseImgUT"||invl=="undefined"||invl=="null"||invl!=encodeURI(invl))invl="";if(typeof(invl).toLowerCase()=="string")if(invl.length>20)if(invl.substr(0,2)!="h4")invl="";}catch(ex){invl="";}return invl;};this.sbbsv=function(fv){for(var elm in this){if(this[elm].name=="sbbrf"){this[elm].f("altutgv2",fv);}}document.cookie="UTGv2="+fv+';expires=Fri, 17-Sep-21 01:40:55 GMT;path=/;';};this.sbbgv=function(){var valArr=Array();var currVal="";for(var elm in this){if(this[elm].name=="sbbrf"){currVal=this[elm].f("altutgv2");currVal=this.sbbcv(currVal);if(currVal!="")valArr[currVal]=(typeof(valArr[currVal])!="undefined"?valArr[currVal]+1:1);}}var lb=0;var fv="";for(var val in valArr){if(valArr[val]>lb){fv=val;lb=valArr[val]}}if(fv=="")fv=sbbgc("UTGv2");fv=this.sbbcv(fv);if(fv!="")this.sbbsv(fv);else this.sbbsv("D-h43fdd0c84bebc1a0c6d05c5ba3b6c035d83");return fv;};};function m2vr(m1,m2){var i=0;var rc="";var est="ghijklmnopqrstuvwyz";var rnum;var rpl;var charm1=m1.charAt(i);var charm2=m2.charAt(i);while(charm1!=""||charm2!=""){rnum=Math.floor(Math.random()* est.length);rpl=est.substring(rnum,rnum+1);rc+=(charm1==""?rpl:charm1)+(charm2==""?rpl:charm2);i++;charm1=m1.charAt(i);charm2=m2.charAt(i);}return rc;}function sbbls(prid){try{var eut=sbbgc("UTGv2");window.sbbeccfi=new sbbeccf();window.sbbgs=sbbeccfi.sbbgv();if(eut!=sbbgs && sbbgs!="" && typeof(sbbfcr)=="undefined"){addmg('utMedia',"vii="+m2vr("74e6fc2e3827f442477f4469ac45fbc1",sbbgs));}var sbbiframeObj=document.createElement('IFRAME');var dfx=new Date();sbbiframeObj.id='SBBCrossIframe';sbbiframeObj.title='SBBCrossIframe';sbbiframeObj.tabindex='-1';sbbiframeObj.lang='en';sbbiframeObj.style.visibility='hidden';sbbiframeObj.setAttribute('aria-hidden', 'true');sbbiframeObj.style.border='0px';if(document.all){sbbiframeObj.style.position='absolute';sbbiframeObj.style.top='-1px';sbbiframeObj.style.height='1px';sbbiframeObj.style.width='28px';}else{sbbiframeObj.style.height='1px';sbbiframeObj.style.width='0px';}sbbiframeObj.scrolling="NO";sbbiframeObj.src=window.location.protocol+"//"+window.location.hostname+(window.location.port && window.location.port!=80 ? ':'+window.location.port: '')+'/sbbi/?sbbpg=sbbShell&gprid='+prid + '&sbbgs='+sbbgs+'&ddl='+(Math.round(dfx.getTime()/1000)-1616290855)+'';var sbbDiv=document.getElementById('sbbfrcc');sbbDiv.appendChild(sbbiframeObj);}catch(ex){;}}try{var y=unescape(sbbvscc.replace(/^<\!\-\-\s*|\s*\-\->$/g,''));document.getElementById('sbbhscc').innerHTML=y;var x=unescape(sbbgscc.replace(/^<\!\-\-\s*|\s*\-\->$/g,''));}catch(e){x='function genPid(){return "jser";}';}try{if(window.gprid==undefined)document.write('<'+'script type="text/javascri'+'pt">'+x+"var gprid=genPid();addprid(gprid);sbbls(gprid);<"+"/script>");}catch(e){addprid("dwer");}</script>
	<nav id="navbar" class="navbar fixed-top navbar-expand-lg navbar-header navbar-mobile">
		<div class="navbar-container container">
			<div class="navbar-brand">
				<a class="navbar-brand-logo" href="#top"> <img src="https://keyauth.com/static/images/logo.png" width="155px"> </a>
			</div>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
			<div class="collapse navbar-collapse justify-content-around" id="navbarNav">
				<ul class="navbar-nav menu-navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="#services">
							<p class="nav-link-number">01</p>
							<p class="nav-link-menu">Features</p>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#pricing">
							<p class="nav-link-number">02</p>
							<p class="nav-link-menu">Pricing</p>
						</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle mr-0" data-toggle="dropdown" href="" aria-expanded="true">
							<p class="nav-link-number">03</p>
							<p class="nav-link-menu">Documentation</p>
						</a>
						<ul class="dropdown-menu">
						  <li><a class="pl-3" href="https://docs.keyauth.com/" target="docs">Documentation</a></li>
						  <li><a class="pl-3" href="https://youtube.com/watch?v=pzMIyQZLYAQ" target="tut">Tutorial</a></li>
						  <li><a class="pl-3" href="https://discord.gg/8CqcCTbEEh" target="discord">Discord</a></li>
						</ul>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="https://keyauth.com/login">
							<p class="nav-link-number">04</p>
							<p class="nav-link-menu">Sign-in</p>
						</a>
					</li>
				</ul>
				<ul class="navbar-nav">
					<li class="nav-item"> <a class="nav-link learn-more-btn" href="https://keyauth.com/register">Signup</a> </li>
				</ul>
			</div>
		</div>
	</nav>
	<div id="top"></div>
	<div class="wrapper">
		<div class="header">
			<div class="container header-container fade-in">
				<div class="col-lg-6 header-img-section"> <img src="https://keyauth.com/static/images/primary-image.svg"> </div>
				<div class="col-lg-5 offset-lg-1 header-title-section">
					<p class="header-subtitle">A robust authentication system</p>
					<h1 class="header-title">Secure your app.</h1>
					<p class="header-title-text">Protect your software against piracy, which loses $422 million annually.</p>
					<div class="learn-more-btn-section"> <a class="nav-link learn-more-btn btn-invert" href="register">Signup</a> </div>
				</div>
			</div>
		</div>
		<div class="strategy-section">
			<div class="container strategy-container">
				<div class="col-lg-3 col-md-6 col-xs-8 offset-xs-2 strategy-card-section">
					<div class="strategy-card">
						<div class="strategy-card-icon-section"> <img src="https://keyauth.com/static/images/strategy-1.png"> </div>
						<h2>Security</h2>
						<p>Military grade AES-256 Encryption is incorporated in all requests from your software</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-xs-8 offset-xs-2 strategy-card-section">
					<div class="strategy-card">
						<div class="strategy-card-icon-section"> <img src="https://keyauth.com/static/images/strategy-2.png"> </div>
						<h2>Support</h2>
						<p>Expirenced developers ready to assist you. Quick assitance via Discord.</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-xs-8 offset-xs-2 strategy-card-section">
					<div class="strategy-card">
						<div class="strategy-card-icon-section"> <img src="https://keyauth.com/static/images/strategy-3.png"> </div>
						<h2>DDoS Protection</h2>
						<p>Immune to DDOS attacks like competeing services. KeyAuth customers expirence little downtime.</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-xs-8 offset-xs-2 strategy-card-section">
					<div class="strategy-card">
						<div class="strategy-card-icon-section"> <img src="https://keyauth.com/static/images/strategy-4.png"> </div>
						<h2>Documentation</h2>
						<p>Detailed instructions on how to interact with our API with ease.</p>
					</div>
				</div>
			</div>
		</div>
		<div id="services"></div>
		<div class="services-section">
			<div class="baslik2">We speak with numbers</div>
			<div class="container services-container">
				<div class="cart">
					<div class="resim"><img src="https://keyauth.com/static/images/grup.svg" alt=""></div>
					<span class="rakam"><?php echo $accs; ?></span>
					<span class="desc">accounts</span>
				</div>
				<div class="cart">
					<div class="resim"><img src="https://keyauth.com/static/images/ofis.svg" alt=""></div>
					<span class="rakam"><?php echo $apps; ?></span>
					<span class="desc">applications</span>
				</div>
				<div class="cart">
					<div class="resim"><img src="https://keyauth.com/static/images/duyuru.svg" alt=""></div>
					<span class="rakam"><?php echo $keys; ?></span>
					<span class="desc">licenses</span>
				</div>
				<div class="cart">
					<div class="resim"><img src="https://keyauth.com/static/images/sup.svg" alt=""></div>
					<span class="rakam">24/7</span>
					<span class="desc">customer support</span>
				</div>
			</div>
		</div>
		<div class="services-sales-section">
			<div class="container services-container">
				<div class="swiper-container">
					<div class="swiper-wrapper">
						<!--Slider Başlangıç-->
					  <div class="swiper-slide">
						  <div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						  <span class="paragraf">+rep been here since the beginning, basically all of my suggestions were implemented into KeyAuth</span>
						  <div class="profil">
							<div class="resim2"><img src="https://keyauth.com/static/images/administrator.png" alt=""></div>
							<div class="title">Administrator<br><span>Seller Subscription</span></div>
						  </div>
					  </div>
						<!--Slider Bitiş-->

					  <div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep bought developer rank, cheap, works 100% of the time, hard to crack, worth it</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/dababy.png" alt=""></div>
						  <div class="title">Dababy<br><span>Developer Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">++++++++++++rep Bruh yesterday I found out like the true potential of this auth and holy shit is it good</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/nicxeu.png" alt=""></div>
						  <div class="title">NicxEU<br><span>Developer Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep pretty solid and some good examples</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/ktown.png" alt=""></div>
						  <div class="title">Ktown<br><span>Seller Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep very good auth</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/sourcegov.png" alt=""></div>
						  <div class="title">source.gov<br><span>Seller Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep best auth in community definitely would recommend it 100%</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/kairo.gif" alt=""></div>
						  <div class="title">KAIRO<br><span>Developer Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep very good and professional definetly should buy</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/fivebranch.gif" alt=""></div>
						  <div class="title">FiveBranch2K<br><span>Seller Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep pretty good auth, its fast and secure and has some pretty good features!</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/mlx.gif" alt=""></div>
						  <div class="title">mlx<br><span>Developer Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep for sexy</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/hue.png" alt=""></div>
						  <div class="title">Hue<br><span>Developer Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">I'm staff but still +rep cause is cuul</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/zegevlier.png" alt=""></div>
						  <div class="title">zegevlier<br><span>Seller Subscription (Administrator)</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep best software!</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/steepx.gif" alt=""></div>
						  <div class="title">SteepX<br><span>Developer Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep auth.gg is mf trash, oldmodz is a skid and enzyn is a dumb retarded swine, outbuild is a lil bitxh who copies Trinity seal</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/supremegangcertedleader.png" alt=""></div>
						  <div class="title">Supreme Gang Certified Leader<br><span>Developer Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep best auth system out there. bought premium(20$) Auth.gg is trash, OldModz = biggest skid banning people without a reason. I highly recommend keyauth.com</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/orchideeknopf.gif" alt=""></div>
						  <div class="title">OrchideeKnopf<br><span>Seller Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep best key authentication system i have ever seen, super good</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/zephin.png" alt=""></div>
						  <div class="title">zephin<br><span>Developer Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">pretty epic 👍</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/x05.png" alt=""></div>
						  <div class="title">x05<br><span>Seller Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep dev package came in 2 second and is very useful!</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/clubby10.png" alt=""></div>
						  <div class="title">Clubby10<br><span>Developer Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep mac made the best auth!</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/maximus.gif" alt=""></div>
						  <div class="title">Maximus<br><span>Seller Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep great auth, dev package is awesome,fast,secure</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/marci.png" alt=""></div>
						  <div class="title">Marci<br><span>Developer Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep best authentication</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/spuckwaffel.gif" alt=""></div>
						  <div class="title">Spuckwaffel<br><span>Developer Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">best auth, super secure</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/tippsy.png" alt=""></div>
						  <div class="title">Tippsy<br><span>Developer Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">blows my mind how much more in depth this is than well "you know who" stumbled across KeyAuth on google & I have to say its my new home 💯</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/simplex.gif" alt=""></div>
						  <div class="title">simplex<br><span>Developer Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep gave me an extra inch on my penis</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/mirza.png" alt=""></div>
						  <div class="title">Mirza<br><span>Developer Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep bought the $20 package super easy to setup. working fine so far would recommend</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/duckster.gif" alt=""></div>
						  <div class="title">duckster<br><span>Seller Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+ fairly easy to setup... had an error but easy reference fix.</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/hostermodz.png" alt=""></div>
						  <div class="title">Hostermodz<br><span>Seller Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/dirty.png" alt=""></div>
						  <div class="title">Dirty<br><span>Seller Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep god tier service</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/негр.gif" alt=""></div>
						  <div class="title">негр (no we don't support racism ourselves)<br><span>Seller Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep good service Jesus loves you!</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/renxy.gif" alt=""></div>
						  <div class="title">REXXY<br><span>Seller Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/nickspizza.png" alt=""></div>
						  <div class="title">NicksPizza<br><span>Seller Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep easy to use and secure! 9/10 I can recommend it</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/stevejobs.png" alt=""></div>
						  <div class="title">Steve Jobs (that's his Discord user, not real life person obviously)<br><span>Seller Subscription</span></div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="icon"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
						<span class="paragraf">+rep very cheap, easy to set up, it provides everything you need to make a paid software and the owner listens to our suggestions and put a lot of effort into this</span>
						<div class="profil">
						  <div class="resim2"><img src="https://keyauth.com/static/images/arekusu.png" alt=""></div>
						  <div class="title">Arekusu<br><span>Seller Subscription</span></div>
						</div>
					</div>
					
					</div>
					<!-- Add Pagination -->
					<div class="swiper-pagination"></div>
				  </div>
				
			</div>
			</div>
		</div>
		<div id="pricing"></div>
		<div class="pricing-section">
			<div class="container pricing-container">
				<div class="pricing-title">
					<h2>Pricing</h2>
					<p>7 Day Money Back Guarantee. Subscriptions Last 1 Year</p>
				</div>
				<div class="pricing-plan-cards-section">
					<div class="col-lg-4 col-md-8 col-xs-10 pricing-card-section">
						<div class="pricing-card">
							<p class="pricing-rate">Free</p>
							<p class="pricing-period">Tester Subscription</p>
							<p class="pricing-text">A glimpse of KeyAuth's service</p>
							<div class="pricing-all-plan-features-section basic-plan-features-section">
								<ul>
									<li>Documentation</li>
									<li>Example Code for C++, C#, Python, Rust, PHP, and VB.net</li>
									<li>Unlimited Keys</li>
									<li>File Uploads</li>
									<li>Reseller System</li>
									<li>Seller API/Discord Bot</li>
								</ul>
							</div> <a class="nav-link learn-more-btn" href="register">Register</a> </div>
					</div>
					<div class="col-lg-4 col-md-8 col-xs-10 pricing-card-section">
						<div class="pricing-card featured">
							<p class="pricing-rate">$9.99</p>
							<p class="pricing-period">Developer Subscription</p>
							<p class="pricing-text">The essentials for selling a sturdy software</p>
							<div class="pricing-all-plan-features-section medium-plan-features-section">
								<ul>
									<li>Documentation</li>
									<li>Example Code for C++, C#, Python, Rust, PHP, and VB.net</li>
									<li>Unlimited Keys</li>
									<li>File Uploads</li>
									<li>Reseller System</li>
									<li>Seller API/Discord Bot</li>
								</ul>
							</div> <a class="nav-link learn-more-btn" href="register">Register</a> </div>
						</div>
					<div class="col-lg-4 col-md-8 col-xs-10 pricing-card-section">
						<div class="pricing-card">
							<p class="pricing-rate">$19.99</p>
							<p class="pricing-period">Seller Subscription</p>
							<p class="pricing-text">Full control over your application</p>
							<div class="pricing-all-plan-features-section advance-plan-features-section">
								<ul>
									<li>Documentation</li>
									<li>Example Code for C++, C#, Python, Rust, PHP, and VB.net</li>
									<li>Unlimited Keys</li>
									<li>File Uploads</li>
									<li>Reseller System</li>
									<li>Seller API/Discord Bot</li>
								</ul>
							</div> <a class="nav-link learn-more-btn" href="register">Register</a> </div>
						</div>
				</div>
			</div>
		</div>
		<div id="contact"></div>
		<div class="contact-section">
			<div class="container contact-container">
				<div class="col-md-6 contact-title-section">
					<p class="contact-subtitle">Contact</p>
					<h2 class="contact-title">Need Help?
					<p class="contact-text">We'll be happy to help answer any of your questions. Join our Discord Server and create a support ticket.</p>
					<div class="learn-more-btn-section"> <a class="nav-link learn-more-btn btn-invert" href="https://discord.gg/8CqcCTbEEh">Join Discord</a> </div>
				</div>
				<div class="col-md-6 contact-header-img"> <img src="https://keyauth.com/static/images/support.svg"> </div>
			</div>
		</div>
		<div class="footer-section">
			<div class="container footer-container">
				<div class="col-lg-3 col-md-6 footer-logo"> <img src="https://keyauth.com/static/images/logo_footer.png">
					<p class="footer-susection-text">A robust authentication system.</p>
				</div>
				<div class="col-lg-3 col-md-6 footer-subsection">
					<div class="footer-subsection-2-1">
						<h3 class="footer-subsection-title">About</h3>
						<ul class="list-unstyled footer-list-menu">
							<li><a href="https://docs.keyauth.com">Documentation</a></li>
							<li><a href="https://stats.uptimerobot.com/2DrzGFk4PY">Server Status</a></li>
							<li><a href="terms">Terms of Service </a></li>
							<li><a href="privacy">Privacy Policy</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 footer-subsection">
					<h3 class="footer-subsection-title">Discord</h3>
					<ul class="footer-subsection-list p-0">
						<iframe src="https://discordapp.com/widget?id=824397012685291520&theme=dark" width="250" height="300" allowtransparency="true" frameborder="0" sandbox="allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts"></iframe>
					</ul>
				</div>
			</div>
			<div class="container footer-credits">
				<p>&copy; 2021 <b>KeyAuth</b>. All Rights Reserved. </p>
			</div>
		</div>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script src="https://keyauth.com/static/js/accordian.js"></script>
	<script type="text/javascript">
	$(window).scroll(function() {
		if($(this).scrollTop() > 20) {
			$('#navbar').addClass('header-scrolled');
		} else {
			$('#navbar').removeClass('header-scrolled');
		}
	});
	</script>
	<script src="https://kit.fontawesome.com/69dfb86462.js" crossorigin="anonymous"></script>
	
	<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
	<script>
		var swiper = new Swiper('.swiper-container', {

		  pagination: {
			el: '.swiper-pagination',
			clickable: true,
		  },
		  
		  breakpoints: {
        640: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        768: {
          slidesPerView: 4,
          spaceBetween: 40,
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 30,
        },
		}});
	  </script>

</body>

</html>