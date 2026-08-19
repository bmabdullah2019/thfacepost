//<script>
var CapsState = false;

function copy() {
  /* Get the text field */
  var copyText = document.getElementById("form_comment_text");

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /*For mobile devices*/

  /* Copy the text inside the text field */
  document.execCommand("copy");

  /* Alert the copied text */
  alert("<?php echo ossn_print('translit:copied'); ?>: " + copyText.value);
}

function initArray() 
{
	this.length = initArray.arguments.length
	for (var i = 0; i < this.length; i++)   
		this[i+1] = initArray.arguments[i]
}

eng_letters = new initArray(1040,1041,1062,1044,1045,1060,1043,1061,1048,1049,1050,1051,1052,1053,1054,1055,1071,1056,1057,1058,1059,1042,1065,1061,1067,1047,91,92,93,94,95,96,1072,1073,1094,1076,1077,1092,1075,1093,1080,1081,1082,1083,1084,1085,1086,1087,1103,1088,1089,1090,1091,1074,1097,120,1099,1079);
second_order = new initArray(72,79,69,85,65,72,77,79,85,65,72,104,111,101,117,97,104,109,111,117,97,104);
first_order  = new initArray(1062,1049,1049,1049,1049,1057,1065,1067,1067,1067,1047,1094,1081,1081,1081,1081,1089,1097,1099,1099,1099,1079);
two_result = new initArray(1063,1025,1069,1070,1071,1064,1068,1025,1070,1071,1046,1095,1105,1101,1102,1103,1096,1100,1105,1102,1103,1078);

var nexto = 1;
var language = (navigator.appName=='Netscape');

function getlanguagename()
{
	if (language==1) return "Eng";
	if (language==0) return "Rus";
}

function changelanguage()
{
if (language==1) {language=0; document.searchform.rus_eng.value="Rus"; document.searchform.comment_text.focus();return 0}
if (language==0) {language=1; document.searchform.rus_eng.value="Eng"; document.searchform.comment_text.focus();return 0}
}

function translate_letter()
{
	if(navigator.appName=='Netscape')
	{
		if (language==0) 
		{
			changelanguage();
			alert("The language is swithched to English. Please complete the text and press 'TRANSLITERATE ALL' button");
		}
		return true;
	}
        if (language==1) {return true;}
	var txt = document.searchform.comment_text.value;
	l1 = txt.substr(txt.length - 1,1);
	var code = l1.charCodeAt(0); 
	l2 = txt.substr(txt.length-2,1);
	var code2 = l2.charCodeAt(0);
	if ((code==39)||(code==35))
	//' and '' and # and ## treatment
	{
		if (code==39) 
		{
			var newcode =1100;
			var doublecode =1068;
		}
		if (code==35) 
		{
			var newcode =1098;
			var doublecode =1066;
		}

		var res = String.fromCharCode(newcode);		
		txt = txt.substr(0, txt.length-1);
		if (code2==newcode) 
		{ 
			res = String.fromCharCode(doublecode);
			txt = txt.substr(0, txt.length-1);
		}

		document.searchform.comment_text.value = txt + res;
                document.searchform.comment_text.focus();
	}

	if ((code<123) && (code>64)) 
	{
		var res = String.fromCharCode(eng_letters[code-64]);
		var rus_code = eng_letters[code-64];
		txt = txt.substr(0, txt.length-1);
		if (nexto==0)
		{ 
			nexto = 1;
			for (i = 1; i<25; i++)
			{
				if (rus_code == first_order[i]) nexto = 0; 
				if ((code==second_order[i])&&(code2==first_order[i])) 
				{ 
					res = String.fromCharCode(two_result[i]);
					txt = txt.substr(0, txt.length-1);
					break;
				}
			}
		}
		else nexto = 0;

                document.searchform.comment_text.focus();
		document.searchform.comment_text.value = txt + res;

	}
}


function translatesymbol(pretxt,txt)
{
	var res = pretxt+txt;
	var code = txt.charCodeAt(0); 
	var code2 = pretxt.charCodeAt(0);

	if ((code==39)||(code==35))
	//' and '' and # and ## treatment
	{
		if (code==39) 
		{
			var newcode =1100;
			var doublecode =1068;
		}
		if (code==35) 
		{
			var newcode =1098;
			var doublecode =1066;
		}

		res = pretxt+String.fromCharCode(newcode);		

		if (code2==newcode) 
		{ 
			res = String.fromCharCode(doublecode);
		}
	}

	if ((code<123) && (code>64)) 
	{
		res = pretxt+String.fromCharCode(eng_letters[code-64]);
		for (i = 1; i<25; i++)
		{ 
			if ((code==second_order[i])&&(code2==first_order[i])) 
			{ 
				res = String.fromCharCode(two_result[i]);
				break;
			}
		}
	}
	return res;
}

function translateAll()
{
	var txt = document.searchform.comment_text.value;
	var txtnew = translatesymbol("",txt.substr(0,1));
	var symb = "";
	for (kk=1;kk<txt.length;kk++)
	{
		symb = translatesymbol(txtnew.substr(txtnew.length-1,1),txt.substr(kk,1));
		txtnew = txtnew.substr(0,txtnew.length-1) + symb;
	}
	document.searchform.comment_text.value = txtnew;
	document.searchform.comment_text.focus();
}

     function onClickKBButtonEx(btn)
     {
	if (btn == 'Caps')
		CapsState = !(CapsState)
        else
	{ 
		var txt = document.searchform.comment_text.value;
		if (btn == 'BkSp')
		{
			txt = txt.substr(0, txt.length - 1);
		}
		else
		{
			txt = txt + btn;
		}
		document.searchform.comment_text.value = txt;
	}
		document.searchform.comment_text.focus();
     }
     function onClickKBButton(btn)
     {
	var code = btn.charCodeAt(0);
	if (CapsState == false)
	{
		if (code == 168) 
                   code = 184;
		else if (code == 1025)
		   code = 1105;
		else
                   code = code + 32;
	}
	btn = String.fromCharCode(code);
	onClickKBButtonEx(btn);
	 }