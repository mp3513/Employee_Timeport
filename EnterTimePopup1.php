
	
	<script type="text/javascript">
	
	function timeformatpop()  
{
	  var sitename ="http://localhost/timeport1";
		 //var sitename ="http://10.0.0.125/timeport";
	 var timeform="timeformat.html";
	  
	var url=sitename+"/"+timeform;
	
    window.open(url, "", "width=1000, height=670"); 
}  
	
	function getDailyVal(stday,otday,dtday)
	{
	
 var Val1Split=stday.split('.');
 var ststart=Val1Split[0];
 var stfinal=Val1Split[1]; 
  
 
 var Val2Split=otday.split('.');
 var otstart=Val2Split[0];
 var otfinal=Val2Split[1]; 
 
 
 var Val3Split=dtday.split('.');
 var dtstart=Val3Split[0]; 
 var dtfinal=Val3Split[1];  
  
 
		
var endminutes= (+stfinal)+(+otfinal)+(+dtfinal);	
var endquotient= Math.floor(endminutes/60);
var endremin= endminutes%60;  
if(endremin=='0')
{endremin='00';}

var finalHours=(+ststart)+(+otstart)+(+dtstart) +(+endquotient);
var endreminfinal=finalHours+'.'+endremin; 	
return endreminfinal;

	}
	
	
	
	function getWeeklyVal(monday,tuesday,wednesday,thursday,friday,saturday,sunday)
	{	
	
 var Val1Split=monday.split('.');
 var monstart=Val1Split[0];
 var monfinal=Val1Split[1]; 
  
 
 var Val2Split=tuesday.split('.');
 var tuestart=Val2Split[0];
 var tuefinal=Val2Split[1]; 
 
 
 var Val3Split=wednesday.split('.');
 var wedstart=Val3Split[0]; 
 var wedsfinal=Val3Split[1];  
 
 
  
  var Val4Split=thursday.split('.');
 var thustart=Val4Split[0];
 var thusfinal=Val4Split[1]; 
  
 
 var Val5Split=friday.split('.');
 var fristart=Val5Split[0];
 var frifinal=Val5Split[1]; 
 
 
 var Val6Split=saturday.split('.');
 var satstart=Val6Split[0]; 
 var satfinal=Val6Split[1];  
 
 
 var Val7Split=sunday.split('.');
 var sunstart=Val7Split[0]; 
 var sunfinal=Val7Split[1];  
 
 
  
			
var endminutes= (+monfinal)+(+tuefinal)+(+wedsfinal)+(+thusfinal)+(+frifinal)+(+satfinal)+(+sunfinal);	


var endquotient= Math.floor(endminutes/60);
var endremin= endminutes%60;  
if(endremin=='0')
{endremin='00';}

var finalHours=(+monstart)+(+tuestart)+(+wedstart) +(+thustart)+(+fristart)+(+satstart)+(+sunstart)+(+endquotient);
var endreminfinal=finalHours+'.'+endremin; 	
return endreminfinal;

	}
	
	
		
	
	function isNumber(evt) { 
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
     if (charCode > 31 && (charCode < 45 || charCode > 57 || charCode==47)) {
        return false;
    }
    return true;
}
	

	
	function timesheetcalci()
			{	
				var Timeformatval = $('#GetTimeFormat').val();
			
			if(Timeformatval==1)
			{
			
			var val1 = (+$('#st1').val()).toFixed(2);			
			var Val1Split=val1.split('.');
			var decimalval1=Val1Split[1];								
			if(val1>24){alert("hours can not exceed more than 24"); $('#st1').val(''); val1='0.00';}
			if(decimalval1>59){alert("Please enter time in hh.mm format");$('#st1').val(''); val1='0.00'; }
			if(isNaN(val1)){$('#st1').val(''); val1='0.00';}
			
			
			
			var val2=(+$('#st2').val()).toFixed(2);
			var Val2Split=val2.split('.');
			var decimalval2=Val2Split[1];
			if(val2>24){alert("hours can not exceed more than 24"); $('#st2').val(''); val2='0.00';}			
			if(decimalval2>59){alert("Please enter time in hh.mm format");$('#st2').val(''); val2='0.00';}
			if(isNaN(val2)){$('#st2').val(''); val2='0.00';}
			
			
			var val3 = (+$('#st3').val()).toFixed(2);
			var Val3Split=val3.split('.');
			var decimalval3=Val3Split[1];
			if(val3>24){alert("hours can not exceed more than 24"); $('#st3').val(''); val3='0.00';}
			if(decimalval3>59){alert("Please enter time in hh.mm format");$('#st3').val(''); val3='0.00';}
			if(isNaN(val3)){$('#st3').val(''); val3='0.00';}
			
			var val4 = (+$('#st4').val()).toFixed(2);
			var Val4Split=val4.split('.');
			var decimalval4=Val4Split[1];
			if(val4>24){alert("hours can not exceed more than 24"); $('#st4').val(''); val4='0.00';}
			if(decimalval4>59){alert("Please enter time in hh.mm format");$('#st4').val(''); val4='0.00';}
			if(isNaN(val4)){$('#st4').val(''); val4='0.00';}
			
			var val5 = (+$('#st5').val()).toFixed(2);
			var Val5Split=val5.split('.');
			var decimalval5=Val5Split[1];
			if(val5>24){alert("hours can not exceed more than 24"); $('#st5').val(''); val5='0.00';}
			if(decimalval5>59){alert("Please enter time in hh.mm format");$('#st5').val(''); val5='0.00';}
			if(isNaN(val5)){$('#st5').val(''); val5='0.00';}
			
			var val6 = (+$('#st6').val()).toFixed(2);
			var Val6Split=val6.split('.');
			var decimalval6=Val6Split[1];
			if(val6>24){alert("hours can not exceed more than 24"); $('#st6').val(''); val6='0.00';}
			if(decimalval6>59){alert("Please enter time in hh.mm format");$('#st6').val(''); val6='0.00';}
			if(isNaN(val6)){$('#st6').val(''); val6='0.00';}
			
			
			
			var val7 = (+$('#st7').val()).toFixed(2);
			var Val7Split=val7.split('.');
			var decimalval7=Val7Split[1];
			if(val7>24){alert("hours can not exceed more than 24"); $('#st7').val(''); val7='0.00';}
			if(decimalval7>59){alert("Please enter time in hh.mm format");$('#st7').val(''); val7='0.00';}
			if(isNaN(val7)){$('#st7').val(''); val7='0.00';}
			
						
			var val8 = (+$('#ot1').val()).toFixed(2);
			var Val8Split=val8.split('.');
			var decimalval8=Val8Split[1];			
			if(val8>24){alert("hours can not exceed more than 24"); $('#ot1').val(''); val8='0.00';}
			if(decimalval8>59){alert("Please enter time in hh.mm format");$('#ot1').val(''); val8='0.00';}
			if(isNaN(val8)){$('#ot1').val(''); val8='0.00';}
							
			
            var val9 = (+$('#ot2').val()).toFixed(2);
			var Val9Split=val9.split('.');
			var decimalval9=Val9Split[1];	
			if(val9>24){alert("hours can not exceed more than 24"); $('#ot2').val(''); val9='0.00';}
			if(decimalval9>59){alert("Please enter time in hh.mm format");$('#ot2').val(''); val9='0.00';}
			if(isNaN(val9)){$('#ot2').val(''); val9='0.00';}
			
		  	var val10 = (+$('#ot3').val()).toFixed(2);
			var Val10Split=val10.split('.');
			var decimalval10=Val10Split[1];					
			if(val10>24){alert("hours can not exceed more than 24"); $('#ot3').val(''); val10='0.00';}
			if(decimalval10>59){alert("Please enter time in hh.mm format");$('#ot3').val(''); val10='0.00';}
			if(isNaN(val10)){$('#ot3').val(''); val10='0.00';}
									
			
			var val11 = (+$('#ot4').val()).toFixed(2);
			var Val11Split=val11.split('.');
			var decimalval11=Val11Split[1];	
			if(val11>24){alert("hours can not exceed more than 24"); $('#ot4').val(''); val11='0.00';}
			if(decimalval11>59){alert("Please enter time in hh.mm format");$('#ot4').val(''); val11='0.00';}
			if(isNaN(val11)){$('#ot4').val(''); val11='0.00';}
			
			
			var val12 = (+$('#ot5').val()).toFixed(2);
			var Val12Split=val12.split('.');
			var decimalval12=Val12Split[1];
			if(val12>24){alert("hours can not exceed more than 24"); $('#ot5').val(''); val12='0.00';}
			if(decimalval12>59){alert("Please enter time in hh.mm format");$('#ot5').val(''); val12='0.00';}
			if(isNaN(val12)){$('#ot5').val(''); val12='0.00';}
			
					
			var val13 = (+$('#ot6').val()).toFixed(2);
			var Val13Split=val13.split('.');
			var decimalval13=Val13Split[1];		
			if(val13>24){alert("hours can not exceed more than 24"); $('#ot6').val(''); val13='0.00';}
			if(decimalval13>59){alert("Please enter time in hh.mm format");$('#ot6').val(''); val13='0.00';}
			if(isNaN(val13)){$('#ot6').val(''); val13='0.00';}
			
					
			
			
			var val14 = (+$('#ot7').val()).toFixed(2);
			var Val14Split=val14.split('.');
			var decimalval14=Val14Split[1];		
			if(val14>24){alert("hours can not exceed more than 24"); $('#ot7').val(''); val14='0.00';}
			if(decimalval14>59){alert("Please enter time in hh.mm format");$('#ot7').val(''); val14='0.00';}
			if(isNaN(val14)){$('#ot7').val(''); val14='0.00';}
			
								
			var val15 = (+$('#dt1').val()).toFixed(2);
			
			var Val15Split=val15.split('.');
			var decimalval15=Val15Split[1];		
			if(val15>24){alert("hours can not exceed more than 24"); $('#dt1').val(''); val15='0.00';}
			if(decimalval15>59){alert("Please enter time in hh.mm format");$('#dt1').val(''); val15='0.00';}
			if(isNaN(val15)){$('#dt1').val(''); val15='0.00';}
					
			
			var val16 = (+$('#dt2').val()).toFixed(2);
			var Val16Split=val16.split('.');
			var decimalval16=Val16Split[1];	
			if(val16>24){alert("hours can not exceed more than 24"); $('#dt2').val(''); val16='0.00';}
			if(decimalval16>59){alert("Please enter time in hh.mm format");$('#dt2').val(''); val16='0.00';}
			if(isNaN(val16)){$('#dt2').val(''); val16='0.00';}
			
						
			var val17 = (+$('#dt3').val()).toFixed(2);
			var Val17Split=val17.split('.');
			var decimalval17=Val17Split[1];				
			if(val17>24){alert("hours can not exceed more than 24"); $('#dt3').val(''); val17='0.00';}
			if(decimalval17>59){alert("Please enter time in hh.mm format");$('#dt3').val(''); val17='0.00';}
			if(isNaN(val17)){$('#dt3').val(''); val17='0.00';}
			
			
			
		    var val18 = (+$('#dt4').val()).toFixed(2);
			var Val18Split=val18.split('.');
			var decimalval18=Val18Split[1];			
			if(val18>24){alert("hours can not exceed more than 24"); $('#dt4').val(''); val18='0.00';}
			if(decimalval18>59){alert("Please enter time in hh.mm format");$('#dt4').val(''); val18='0.00';}
			if(isNaN(val18)){$('#dt4').val(''); val18='0.00';}
			
			
					
			var val19 = (+$('#dt5').val()).toFixed(2);
			var Val19Split=val19.split('.');
			var decimalval19=Val19Split[1];	
			if(val19>24){alert("hours can not exceed more than 24"); $('#dt5').val(''); val19='0.00';}
			if(decimalval19>59){alert("Please enter time in hh.mm format");$('#dt5').val(''); val19='0.00';}
			if(isNaN(val19)){$('#dt5').val(''); val19='0.00';}
			
			
			
			var val20 = (+$('#dt6').val()).toFixed(2);
			var Val20Split=val20.split('.');
			var decimalval20=Val20Split[1];
			if(val20>24){alert("hours can not exceed more than 24"); $('#dt6').val(''); val20='0.00';}
			if(decimalval20>59){alert("Please enter time in hh.mm format");$('#dt6').val(''); val20='0.00';}
			if(isNaN(val20)){$('#dt6').val(''); val20='0.00';}
			
			
		    var val21 = (+$('#dt7').val()).toFixed(2);
			var Val21Split=val21.split('.');
			var decimalval21=Val21Split[1];
			if(val21>24){ alert("hours can not exceed more than 24"); $('#dt7').val(''); val21='0.00';}
			if(decimalval21>59){alert("Please enter time in hh.mm format");$('#dt7').val(''); val21='0.00';}
			if(isNaN(val21)){$('#dt7').val(''); val21='0.00';}
		
			
			
					
			
		    var FinalMonTotal=getDailyVal(val1,val8,val15);			
			var FinalTueTotal=getDailyVal(val2,val9,val16);			
			var FinalWedTotal=getDailyVal(val3,val10,val17);		
			var FinalThuTotal=getDailyVal(val4,val11,val18);				
			var FinalFriTotal=getDailyVal(val5,val12,val19);			
			var FinalSatTotal=getDailyVal(val6,val13,val20);
			var FinalSunTotal=getDailyVal(val7,val14,val21);			
				
			
			
			
			
			 
$("#MonTotal").text(FinalMonTotal);
if(FinalMonTotal>24){ alert("Daily hours can not exceed more than 24"); $('#st1').val('0.00'); 
val1='0.00';$('#ot1').val('0.00'); val8='0.00';$('#dt1').val('0.00'); val15='0.00';$("#MonTotal").text('0.00');}
$("#TueTotal").text(FinalTueTotal);
if(FinalTueTotal>24){ alert("Daily hours can not exceed more than 24"); $('#st2').val('0.00'); 
val2='0.00';$('#ot2').val('0.00'); val9='0.00';$('#dt2').val('0.00'); val16='0.00'; $("#TueTotal").text('0.00');}
$("#WedTotal").text(FinalWedTotal);
if(FinalWedTotal>24){ alert("Daily hours can not exceed more than 24"); $('#st3').val('0.00'); 
val3='0.00';$('#ot3').val('0.00'); val10='0.00';$('#dt3').val('0.00'); val17='0.00';$("#WedTotal").text('0.00');}
$("#ThuTotal").text(FinalThuTotal);
if(FinalThuTotal>24){ alert("Daily hours can not exceed more than 24"); $('#st4').val('0.00'); 
val4='0.00';$('#ot4').val('0.00'); val11='0.00';$('#dt4').val('0.00'); val18='0.00';$("#ThuTotal").text('0.00');}
$("#FriTotal").text(FinalFriTotal);
if(FinalFriTotal>24){ alert("Daily hours can not exceed more than 24"); $('#st5').val('0.00'); 
val5='0.00';$('#ot5').val('0.00'); val12='0.00';$('#dt5').val('0.00'); val19='0.00';$("#FriTotal").text('0.00');}
$("#SatTotal").text(FinalSatTotal);
if(FinalSatTotal>24){ alert("Daily hours can not exceed more than 24"); $('#st6').val('0.00'); 
val6='0.00';$('#ot6').val('0.00'); val13='0.00';$('#dt6').val('0.00'); val20='0.00';$("#SatTotal").text('0.00');}
$("#SunTotal").text(FinalSunTotal);
if(FinalSunTotal>24){ alert("Daily hours can not exceed more than 24"); $('#st7').val('0.00'); 
val7='0.00';$('#ot7').val('0.00'); val14='0.00';$('#dt7').val('0.00'); val21='0.00';$("#SunTotal").text('0.00')}


var FinalStTotal=getWeeklyVal(val1,val2,val3,val4,val5,val6,val7);
var FinalOtTotal=getWeeklyVal(val8,val9,val10,val11,val12,val13,val14);
var FinalDtTotal=getWeeklyVal(val15,val16,val17,val18,val19,val20,val21);


$("#StTotal").text(FinalStTotal);
$("#OtTotal").text(FinalOtTotal);
$("#DtTotal").text(FinalDtTotal);

var FinalOverTotal=getDailyVal(FinalStTotal,FinalOtTotal,FinalDtTotal); 

$("#totals").text(FinalOverTotal);
}

else {
   var val1 = (+$('#st1').val()).toFixed(2);									
			if(val1>24){alert("hours can not exceed more than 24"); $('#st1').val('0.00'); val1='0.00';}
			if(isNaN(val1)){$('#st1').val(''); val1='0.00';}
			
			
			var val2=(+$('#st2').val()).toFixed(2);			
			if(val2>24){alert("hours can not exceed more than 24"); $('#st2').val('0.00'); val2='0.00';}	
			if(isNaN(val2)){$('#st2').val(''); val2='0.00';}			
			
			var val3 = (+$('#st3').val()).toFixed(2);
			if(val3>24){alert("hours can not exceed more than 24"); $('#st3').val('0.00'); val3='0.00';}			
			if(isNaN(val3)){$('#st3').val(''); val3='0.00';}	
			
			
			var val4 = (+$('#st4').val()).toFixed(2);			
			if(val4>24){alert("hours can not exceed more than 24"); $('#st4').val('0.00'); val4='0.00';}
			if(isNaN(val4)){$('#st4').val(''); val4='0.00';}	
			
			
			
			var val5 = (+$('#st5').val()).toFixed(2);			
			if(val5>24){alert("hours can not exceed more than 24"); $('#st5').val('0.00'); val5='0.00';}
			if(isNaN(val5)){$('#st5').val(''); val5='0.00';}	
			
			
			var val6 = (+$('#st6').val()).toFixed(2);			
			if(val6>24){alert("hours can not exceed more than 24"); $('#st6').val('0.00'); val6='0.00';}
			if(isNaN(val6)){$('#st6').val(''); val6='0.00';}	
			
			var val7 = (+$('#st7').val()).toFixed(2);			
			if(val7>24){alert("hours can not exceed more than 24"); $('#st7').val('0.00'); val7='0.00';}
			if(isNaN(val7)){$('#st7').val(''); val7='0.00';}	
			
						
			var val8 = (+$('#ot1').val()).toFixed(2);				
			if(val8>24){alert("hours can not exceed more than 24"); $('#ot1').val('0.00'); val8='0.00';}
			if(isNaN(val8)){$('#ot1').val(''); val8='0.00';}	
							
			
            var val9 = (+$('#ot2').val()).toFixed(2);			
			if(val9>24){alert("hours can not exceed more than 24"); $('#ot2').val('0.00'); val9='0.00';}
			if(isNaN(val9)){$('#ot2').val(''); val9='0.00';}	
			
			
		  	var val10 = (+$('#ot3').val()).toFixed(2);							
			if(val10>24){alert("hours can not exceed more than 24"); $('#ot3').val('0.00'); val10='0.00';}
			if(isNaN(val10)){$('#ot3').val(''); val10='0.00';}	
									
			
			var val11 = (+$('#ot4').val()).toFixed(2);			
			if(val11>24){alert("hours can not exceed more than 24"); $('#ot4').val('0.00'); val11='0.00';}
			if(isNaN(val11)){$('#ot4').val(''); val11='0.00';}	
			
			
			
			var val12 = (+$('#ot5').val()).toFixed(2);		
			if(val12>24){alert("hours can not exceed more than 24"); $('#ot5').val('0.00'); val12='0.00';}
		  if(isNaN(val12)){$('#ot5').val(''); val12='0.00';}	
			
			
					
			var val13 = (+$('#ot6').val()).toFixed(2);			
			if(val13>24){alert("hours can not exceed more than 24"); $('#ot6').val('0.00'); val13='0.00';}					
			 if(isNaN(val13)){$('#ot6').val(''); val13='0.00';}	
			
			var val14 = (+$('#ot7').val()).toFixed(2);			
			if(val14>24){alert("hours can not exceed more than 24"); $('#ot7').val('0.00'); val14='0.00';}
			if(isNaN(val14)){$('#ot7').val(''); val14='0.00';}	
			
			
								
			var val15 = (+$('#dt1').val()).toFixed(2);			
			if(val15>24){alert("hours can not exceed more than 24"); $('#dt1').val('0.00'); val15='0.00';}
		   if(isNaN(val15)){$('#dt1').val(''); val15='0.00';}	
			
					
			
			var val16 = (+$('#dt2').val()).toFixed(2);			
			if(val16>24){alert("hours can not exceed more than 24"); $('#dt2').val('0.00'); val16='0.00';}
			 if(isNaN(val16)){$('#dt2').val(''); val16='0.00';}	
			
						
			var val17 = (+$('#dt3').val()).toFixed(2);				
			if(val17>24){alert("hours can not exceed more than 24"); $('#dt3').val('0.00'); val17='0.00';}
			 if(isNaN(val17)){$('#dt3').val(''); val17='0.00';}	
			
			
			
			
		    var val18 = (+$('#dt4').val()).toFixed(2);			
			if(val18>24){alert("hours can not exceed more than 24"); $('#dt4').val('0.00'); val18='0.00';}
			 if(isNaN(val18)){$('#dt4').val(''); val18='0.00';}
			
			
					
			var val19 = (+$('#dt5').val()).toFixed(2);		
			if(val19>24){alert("hours can not exceed more than 24"); $('#dt5').val('0.00'); val19='0.00';}
			if(isNaN(val19)){$('#dt5').val(''); val19='0.00';}
			
			
			
			var val20 = (+$('#dt6').val()).toFixed(2);			
			if(val20>24){alert("hours can not exceed more than 24"); $('#dt6').val('0.00'); val20='0.00';}
			if(isNaN(val20)){$('#dt6').val(''); val20='0.00';}
			
			
			
		    var val21 = (+$('#dt7').val()).toFixed(2);		
			if(val21>24){ alert("hours can not exceed more than 24"); $('#dt7').val('0.00'); val21='0.00';}
			if(isNaN(val21)){$('#dt7').val(''); val21='0.00';}
			
		
			var montotal=((+val1)+(+val8)+(+val15)).toFixed(2);			
			var tuetotal=((+val2)+(+val9)+(+val16)).toFixed(2);						
			var wedtotal=((+val3)+(+val10)+(+val17)).toFixed(2);			
			var thutotal=((+val4)+(+val11)+(+val18)).toFixed(2);			
			var fritotal=((+val5)+(+val12)+(+val19)).toFixed(2);			
			var sattotal=((+val6)+(+val13)+(+val20)).toFixed(2);
			var suntotal=((+val7)+(+val14)+(+val21)).toFixed(2);			
			
			
			
			
			 
$("#MonTotal").text(montotal);
if(montotal>24){ alert("Daily hours can not exceed more than 24"); $('#st1').val('0.00'); 
val1='0.00';$('#ot1').val('0.00'); val8='0.00';$('#dt1').val('0.00'); val15='0.00';$("#MonTotal").text('0.00');}
$("#TueTotal").text(tuetotal);
if(tuetotal>24){ alert("Daily hours can not exceed more than 24"); $('#st2').val('0.00'); 
val2='0.00';$('#ot2').val('0.00'); val9='0.00';$('#dt2').val('0.00'); val16='0.00'; $("#TueTotal").text('0.00');}
$("#WedTotal").text(wedtotal);
if(wedtotal>24){ alert("Daily hours can not exceed more than 24"); $('#st3').val('0.00'); 
val3='0.00';$('#ot3').val('0.00'); val10='0.00';$('#dt3').val('0.00'); val17='0.00';$("#WedTotal").text('0.00');}
$("#ThuTotal").text(thutotal);
if(thutotal>24){ alert("Daily hours can not exceed more than 24"); $('#st4').val('0.00'); 
val4='0.00';$('#ot4').val('0.00'); val11='0.00';$('#dt4').val('0.00'); val18='0.00';$("#ThuTotal").text('0.00');}
$("#FriTotal").text(fritotal);
if(fritotal>24){ alert("Daily hours can not exceed more than 24"); $('#st5').val('0.00'); 
val5='0.00';$('#ot5').val('0.00'); val12='0.00';$('#dt5').val('0.00'); val19='0.00';$("#FriTotal").text('0.00');}
$("#SatTotal").text(sattotal);
if(sattotal>24){ alert("Daily hours can not exceed more than 24"); $('#st6').val('0.00'); 
val6='0.00';$('#ot6').val('0.00'); val13='0.00';$('#dt6').val('0.00'); val20='0.00';$("#SatTotal").text('0.00');}
$("#SunTotal").text(suntotal);
if(suntotal>24){ alert("Daily hours can not exceed more than 24"); $('#st7').val('0.00'); 
val7='0.00';$('#ot7').val('0.00'); val14='0.00';$('#dt7').val('0.00'); val21='0.00';$("#SunTotal").text('0.00')}

var total1=((+val1)+(+val2)+(+val3)+(+val4)+(+val5)+(+val6)+(+val7)).toFixed(2);;
 
var total2=((+val8)+(+val9)+(+val10)+(+val11)+(+val12)+(+val13)+(+val14)).toFixed(2);

var total3=((+val15)+(+val16)+(+val17)+(+val18)+(+val19)+(+val20)+(+val21)).toFixed(2);


$("#StTotal").text(total1);
$("#OtTotal").text(total2);
$("#DtTotal").text(total3);


var total = ((+val1)+(+val2)+(+val3)+(+val4)+(+val5)+(+val6)+(+val7)+(+val8)+(+val9)
+(+val10)+(+val11)+(+val12)+(+val13)+(+val14)+(+val15)+(+val16)+(+val17)+(+val18)+(+val19)+(+val20)+(+val21)).toFixed(2);
		
$("#totals").text(total);


}

}

	
	
	
	
</script>






<div id="entertimepopup1" style="display:none"  class="entertimepopup">


</div>