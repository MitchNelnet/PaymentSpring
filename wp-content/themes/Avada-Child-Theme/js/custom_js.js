jQuery(document).ready(function($) {

	//if template is flat header
	if ($('.flat_header_template')[0] || $('.video-header-orange')[0]) {

	} else {
		//Dynamically add the slanted header and watermark. Don't add to homepage or pages using the Flat Header template
		$('.background_logo').remove();
		$('.slanted-header').remove();

		//if (!$('.page-id-6')[0]) { //if it is not the homepage or a page with the flat header
		if ($('.single-post')[0] || $('body.tag')[0] || $('body.category')[0]) {
			$('#content').addClass('uniform');
			$('#content').first().prepend('<div class="flat_header slanted-header"></div>');
		} else if (!$('.video-header')[0]) { //if it is not a page with video header span class
	    if ($('.post-content')[0] && !$('#content .post-content')[0]) {
	    	$('.post-content').first().prepend('<div class="slanted-header"></div><div class="background_logo"></div>');
			} else {
	    	$('#content').first().prepend('<div class="slanted-header"></div><div class="background_logo"></div>');
	    }
		} else {
			if ($('.post-content')[0] && !$('#content .post-content')[0]) {
	    	$('.post-content').first().prepend('<div class="jumpy_sticky_fix"></div><div class="background_logo"></div>');
			} else {
	    	$('#content').first().prepend('<div class="jumpy_sticky_fix"></div><div class="background_logo"></div>');
	    }
		}
	}

	//Make toTop not appear on any page except Developer, Support, and FAQ
	$('body').append('<div class="to-top-container"><a href="#" id="toTop"><i class="fa fa-angle-double-up"></i>top<span class="screen-reader-text">Go to Top</span></a></div>');

	if (!$('.page-id-52')[0] && !$('.page-id-224')[0] && !$('.page-id-9640')[0]) {
		$('.to-top-container').css('display','none');
	}

	$('#toTop').hover(function () {
		$(this).toggleClass('pull_out_right');
	});

	$('.left_sticky_float').hover(function () {
		$(this).toggleClass('pull_out_left');
	});

	/***************** Change Seach field placeholder text *******************/
	$('.search-field .s').prop('placeholder', 'Search...');

	/* Alter tabindex for the two Learn More gravity forms on partner-program page */
	if ($('.page-id-8348')[0]) { //if we're on the partner-program page
		$('#gform_19 #input_19_1').attr('tabindex', '4');
		$('#gform_19 #input_19_2').attr('tabindex', '5');
		$('#gform_19 #gform_submit_button_19').attr('tabindex', '6');
	}

	/*Support page used to have these anchor links. We need to redirect those to the new location on FAQ page*/
	if ($('.page-id-224')[0]) { //if it's the support page
		str = window.location.href;
		if (str.indexOf("account-access-reconciliation") >= 0) {
			window.location.replace("https://paymentspring.com/frequently-asked-questions/#account-access-and-reconciliation");
		}

		if (str.indexOf("chargebacks") >= 0) {
			window.location.replace("https://paymentspring.com/frequently-asked-questions/#chargebacks");
		}

		if (str.indexOf("pci-compliance") >= 0) {
			window.location.replace("https://paymentspring.com/frequently-asked-questions/#pci-compliance");
		}

	}

	/***************** Display Featured Blog post only on first page  *******************/
	permalink = window.location.href;

	//This is not the first page
	if (permalink.indexOf('blog/page/') >= 0) {
		$('.featured-blog-conditional-display').css('display','none');
		$('.featured-blog-conditional-display').next().addClass('internal-blog-column');
	}

	//Set up Sign Up and Sandbox forms
	$('form#signup').submit(function(e){
		e.preventDefault();
		formData = $('form#signup #email_address, form#signup #password, form#signup #password_confirmation').serialize();
		formData2 = $('form#signup #name, form#signup #email_address, form#signup #password, form#signup #password_confirmation').serialize();
		console.log('successful submit1');
		//console.log(formData);

		// Submit the form using AJAX.
		$.ajax({
		    type: 'POST',
		    url: 'https://api.paymentspring.com/api/v1/merchants/register',
		    data: formData,
		    success: function(msg)	{
		    	console.log('successful submit3');

					!function(s,a,e,v,n,t,z){if(s.saq)return;n=s.saq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!s._saq)s._saq=n;n.push=n;n.loaded=!0;n.version='1.0';n.queue=[];t=a.createElement(e);t.async=0;t.src=v;z=a.getElementsByTagName(e)[0];z.parentNode.insertBefore(t,z)}(window,document,'script','https://tags.srv.stackadapt.com/events.js');saq('conv', 'bKRF3E801MOd1kRuV6zCqg');
					$('#submit-button').append("<img src='https://mpp.vindicosuite.com/conv/v=5;m=1;t=25406;ts=<timestamp_here>' style='display: none' />");
					console.log('successful submit2');

					/*<!-- Tag for Activity Group: IP1816638, Activity Name: Get it Free Button~IP1816638, Activity ID: 6908272 -->
					<!-- Expected URL: https://paymentspring.com/partner-program -->

					<!--
					Activity ID: 6908272
					Activity Name: Get it Free Button~IP1816638
					Activity Group Name: IP1816638
					-->

					<!--
					Start of DoubleClick Floodlight Tag: Please do not remove
					Activity name of this tag: Get it Free Button~IP1816638
					URL of the webpage where the tag is expected to be placed: https://paymentspring.com/partner-program
					This tag must be placed between the <body> and </body> tags, as close as possible to the opening tag.
					Creation Date: 12/29/2017 --> */
					

					var axel = Math.random() + "";
					var a = axel * 10000000000000;
					$('body').prepend('<iframe src="https://8315976.fls.doubleclick.net/activityi;src=8315976;type=ip1810;cat=getit0;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;ord=' + a + '?" width="1" height="1" frameborder="0" style="display:none"></iframe>');

					$('body').prepend('<noscript><iframe src="https://8315976.fls.doubleclick.net/activityi;src=8315976;type=ip1810;cat=getit0;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;ord=1?" width="1" height="1" frameborder="0" style="display:none"></iframe></noscript>');
					/*<!-- End of DoubleClick Floodlight Tag: Please do not remove -->*/

          // Send the Admin and User email if the account was created successfully.
          $.ajax({
		        	url : "/wp-content/themes/Avada-Child-Theme/includes/sendemail.php",
		        	type: "POST",
		        	data : formData2,
		        	success: function(data, textStatus, jqXHR)
		        	{
		        		console.log('successful submit4');
		        	}
		    	});
              //	console.log(formData);
              console.log('successful submit5');
              e.target.submit(); //After everything, now submit the form normally
           	},
            error: function(data) { //An error was returned from the account creation
    			var r = jQuery.parseJSON(data.responseText);
            	alert(r.errors[0].message);
            }
		});
	});

});
