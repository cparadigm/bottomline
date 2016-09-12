(function () {
	if (!window.AuthorizeNetPopup) window.AuthorizeNetPopup = {};
	if (!AuthorizeNetPopup.options) AuthorizeNetPopup.options = {
		onPopupClosed:			null,
		eCheckEnabled:			true,
		skipZIndexCheck:		false,
		useTestEnvironment:		false,
		defaultSrc:				''
	};
	AuthorizeNetPopup.closePopup = function() {
		document.getElementById("divAuthorizeNetPopupScreen").style.display = "none";
		document.getElementById("divAuthorizeNetPopup").style.display = "none";
		document.getElementById("iframeAuthorizeNet").src = AuthorizeNetPopup.options.defaultSrc;
		if (AuthorizeNetPopup.options.onPopupClosed) AuthorizeNetPopup.options.onPopupClosed();
	};
	AuthorizeNetPopup.openManagePopup = function() {
		openSpecificPopup({action:"manage"});
	};
	AuthorizeNetPopup.openAddPaymentPopup = function() {
		openSpecificPopup({action:"addPayment", paymentProfileId:"new"});
	};
	AuthorizeNetPopup.openEditPaymentPopup = function(paymentProfileId) {
		openSpecificPopup({action:"editPayment", paymentProfileId:paymentProfileId});
	};
	AuthorizeNetPopup.openAddShippingPopup = function() {
		openSpecificPopup({action:"addShipping", shippingAddressId:"new"});
	};
	AuthorizeNetPopup.openEditShippingPopup = function(shippingAddressId) {
		openSpecificPopup({action:"editShipping", shippingAddressId:shippingAddressId});
	};
	AuthorizeNetPopup.onReceiveCommunication = function (querystr) {
		var params = parseQueryString(querystr);
		switch(params["action"]) {
			case "successfulSave":
				if( document.getElementById("divAuthorizeNetPopupScreen") != undefined ) {
					AuthorizeNetPopup.closePopup();
				}
				break;
			case "cancel":
				if( document.getElementById("divAuthorizeNetPopupScreen") != undefined ) {
					AuthorizeNetPopup.closePopup();
				}
				break;
			case "resizeWindow":
				var w = parseInt(params["width"]);
				var h = parseInt(params["height"]);
				var ifrm = document.getElementById("iframeAuthorizeNet");
				
				if( document.getElementById("divAuthorizeNetPopupScreen") != undefined ) {
					ifrm.style.width = w.toString() + "px";
				
					if( h.toString() < 650 ) {
						ifrm.style.height = h.toString() + "px";
						ifrm.style.overflowY = 'hidden';
					}
					else {
						ifrm.style.width = (w+20).toString() + "px";
						ifrm.style.height = '650px';
						ifrm.style.overflowY = 'auto';
					}
					
					centerPopup();
					adjustPopupScreen();
				}
				else {
					ifrm.style.height = h.toString() + "px";
				}
				break;
		}
	};
	function openSpecificPopup(opt) {
		if( document.getElementById("divAuthorizeNetPopupScreen") != undefined ) {
			var ifrm		= document.getElementById("iframeAuthorizeNet");
			var popup		= document.getElementById("divAuthorizeNetPopup");
			var popupScreen	= document.getElementById("divAuthorizeNetPopupScreen");
			
			switch (opt.action) {
				case "addPayment":
					ifrm.style.width = "435px";
					ifrm.style.height = AuthorizeNetPopup.options.eCheckEnabled ? "508px" : "479px";
					break;
				case "editPayment":
					ifrm.style.width = "435px";
					ifrm.style.height = "479px";
					break;
				case "addShipping":
					ifrm.style.width = "385px";
					ifrm.style.height = "359px";
					break;
				case "editShipping":
					ifrm.style.width = "385px";
					ifrm.style.height = "359px";
					break;
				case "manage":
					ifrm.style.width = "442px";
					ifrm.style.height = "578px";
					break;
			}
		}

		if (!AuthorizeNetPopup.options.skipZIndexCheck) {
			var zIndexHighest = getHighestZIndex();
			popup.style.zIndex = zIndexHighest + 2;
			popupScreen.style.zIndex = zIndexHighest + 1;
		}

		var form = document.forms["formAuthorizeNetPopup"];
		if (AuthorizeNetPopup.options.useTestEnvironment) {
			form.action = "https://test.authorize.net/profile/" + opt.action;
		}
		else {
			form.action = "https://secure.authorize.net/profile/" + opt.action;
		}
		if (form.elements["PaymentProfileId"]) form.elements["PaymentProfileId"].value = opt.paymentProfileId ? opt.paymentProfileId : "";
		if (form.elements["ShippingAddressId"]) form.elements["ShippingAddressId"].value = opt.shippingAddressId ? opt.shippingAddressId : "";
		form.submit();
		
		if( document.getElementById("divAuthorizeNetPopupScreen") != undefined ) {
			popup.style.display = "";
			popupScreen.style.display = "";
			centerPopup();
			adjustPopupScreen();
		}
	};
	function centerPopup() {
		var d = document.getElementById("divAuthorizeNetPopup");
		d.style.left = "50%";
		d.style.top = "50%";
		var left = -Math.floor(d.clientWidth / 2);
		var top = -Math.floor(d.clientHeight / 2);
		d.style.marginLeft = left.toString() + "px";
		d.style.marginTop = top.toString() + "px";
		if (d.offsetLeft < 16) {
			d.style.left = "16px";
			d.style.marginLeft = "0px";
		}
		if (d.offsetTop < 16) {
			d.style.top = "16px";
			d.style.marginTop = "0px";
		}
	}
	function adjustPopupScreen() { // IE6 fix
		var popupScreen = document.getElementById("divAuthorizeNetPopupScreen");
		if (popupScreen.currentStyle && popupScreen.currentStyle.position == "absolute") {
			if (popupScreen.clientHeight < document.documentElement.scrollHeight) {
				popupScreen.style.height = document.documentElement.scrollHeight.toString() + "px";
			}
			if (popupScreen.clientWidth < document.documentElement.scrollWidth) {
				popupScreen.style.width = document.documentElement.scrollWidth.toString() + "px";
			}
		}
	}
	function getHighestZIndex() {
		var max = 0;
		var z = 0;
		var a = document.getElementsByTagName('*');
		for (var i = 0; i < a.length; i++) {
			z = 0;
			if (a[i].currentStyle) {
				var style = a[i].currentStyle;
				if (style.display != "none") {
					z = parseFloat(style.zIndex);
				}
			} else if (window.getComputedStyle) {
				var style = window.getComputedStyle(a[i], null);
				if (style.getPropertyValue("display") != "none") {
					z = parseFloat(style.getPropertyValue("z-index"));
				}
			}
			if (!isNaN(z) && z > max) max = z;
		}
		return Math.ceil(max);
	}
	function parseQueryString(str) {
		var vars = [];
		var arr = str.split('&');
		var pair;
		for (var i = 0; i < arr.length; i++) {
			pair = arr[i].split('=');
			vars.push(pair[0]);
			vars[pair[0]] = unescape(pair[1]);
		}
		return vars;
	}
} ());
