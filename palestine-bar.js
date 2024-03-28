const palestineBar = (function () {
	function isHexColor(str) {
		// Regular expression to check for hexadecimal color format.
		const hexColorRegex = /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/;
		return hexColorRegex.test(str);
	}

	function isImageUrl(url) {
		// Regular expression to match common image file extensions
		var imageRegex = /\.(jpeg|jpg|gif|png|svg)$/i;
		return imageRegex.test(url);
	}

	function loadCSS() {
		// Your CSS styles stored in a variable
		var cssStyles = `
			.palestine-bar {
				padding: 10px;
				margin: 0;
				display: flex;
			}
			
			.palestine-bar-fixed {
				width: 100%;
				position: fixed;
				z-index: 9999;
			}
			
			.palestine-bar-fixed-header {
				top: 0;
			}
			
			.palestine-bar-fixed-footer {
				bottom: 0;
			}
			
			.palestine-bar-flag {
				margin-right: auto;
			}
			
			.palestine-bar-text {
				flex: 1;
				text-align: center;
				font-weight: bold;
				margin: 3px 0px 0px 0px;
				padding: 0px 10px;
				line-height: 1;
				font-size: 4vw;
			}
			
			.palestine-bar-button {
				margin-left: auto;
				padding: 0px 10px;
				border: none;
				border-radius: 5px;
				font-size: 14px;
				cursor: pointer;
				max-height: 30px;
			}
			
			.palestine-bar-button:hover {
				filter: brightness(0.8) !important;
			}
			
			.palestine-bar-popup {
				position: fixed;
				bottom: 10px;
				border-radius: 20px;
				max-width: 200px;
				width: 40%;
			}
			
			.palestine-bar-popup .palestine-bar-text {
				font-size: 16px;
				position: absolute;
				bottom: 65px;
				left: 50%;
				transform: translate(-50%, 0);
				width: 90%;
				display: none;
				padding: 10px;
				background-color: rgba(0, 0, 0, 0.5);
				cursor: pointer;
			}
			
			.palestine-bar-popup .palestine-bar-button {
				position: absolute;
				bottom: 20px;
				padding: 10px;
				max-height: 60px;
				left: 50%;
				transform: translate(-50%, 0);
				width: 80%;
			}
			
			.palestine-bar-popup-left {
				left: 10px;
			}
			
			.palestine-bar-popup-right {
				right: 10px;
			}
			
			.palestine-bar-popup-img {
				width: 100%;
				border-radius: 20px;
				margin: 0;
				cursor: pointer;
			}
			
			.palestine-bar-close-button {
				border-radius: 50%;
				padding: 0.5em;
				width: 30px;
				height: 30px;
				color: white;
				position: absolute;
				right: -7px;
				top: -7px;
				cursor: pointer;
			}
			
			.palestine-bar-close-button::before {
				content: " ";
				position: absolute;
				display: block;
				background-color: white;
				width: 2px;
				left: 12px;
				top: 5px;
				bottom: 5px;
				transform: rotate(45deg);
			}
			
			.palestine-bar-close-button::after {
				content: " ";
				position: absolute;
				display: block;
				background-color: white;
				height: 2px;
				top: 12px;
				left: 5px;
				right: 5px;
				transform: rotate(45deg);
			}
			
			@media (min-width: 425px) {
				.palestine-bar-text {
					font-size: 24px;
				}
			
				.palestine-bar-popup .palestine-bar-text {
					display: block;
				}
			}
		`;

		// Create a new style element
		var styleElement = document.createElement("style");

		// Set type attribute to text/css
		styleElement.setAttribute("type", "text/css");

		// Check if the browser supports styleSheet property
		if (styleElement.styleSheet) {
			// For Internet Explorer
			styleElement.styleSheet.cssText = cssStyles;
		} else {
			// For other browsers
			styleElement.appendChild(document.createTextNode(cssStyles));
		}

		// Append the style element to the document head
		document.head.appendChild(styleElement);
	}

	function init() {
		let textContent = document.currentScript.getAttribute('data-text'); // The text message in the middle.
		let buttonTextContent = document.currentScript.getAttribute('data-button-text'); // The text of the button.
		let barMode = document.currentScript.getAttribute('data-bar-mode'); // Display mode fixed-header , fixed-footer , popup-left , popup-right , default = 'header'.
		let bgColor = document.currentScript.getAttribute('data-bg-color'); // Background color of the bar.
		let buttonColor = document.currentScript.getAttribute('data-button-color'); // Background color of the button.
		let textColor = document.currentScript.getAttribute('data-text-color'); // Text color.
		let donationLink = document.currentScript.getAttribute('data-donation-link'); // Link to the donation site.
		let imageLink = document.currentScript.getAttribute('data-image-link'); // The image to be displayed.

		if (typeof wpSettings !== 'undefined') {
			textContent = wpSettings.textContent;
			buttonTextContent = wpSettings.buttonText;
			barMode = wpSettings.barMode;
			bgColor = wpSettings.bgColor;
			buttonColor = wpSettings.buttonColor;
			textColor = wpSettings.textColor;
			donationLink = wpSettings.donationLink;
			imageLink = wpSettings.imageLink;
		}

		document.addEventListener("DOMContentLoaded", function() {
			loadCSS();
		});

		// Default values
		textContent = textContent === null ? 'Save Palestinian children' : textContent;
		buttonTextContent = buttonTextContent === null ? 'Donate now' : buttonTextContent;
		bgColor = isHexColor(bgColor) ? bgColor : '#000';
		buttonColor = isHexColor(buttonColor) ? buttonColor : '#ED2E38';
		textColor = isHexColor(textColor) ? textColor : '#fff';
		donationLink = donationLink == null ? 'https://irusa.org/middle-east/palestine/' : donationLink;
		imageLink = imageLink == null || !isImageUrl(imageLink) ? 'images/gaza.jpg' : imageLink;

		// Create the bar
		const bar = document.createElement('div');
		bar.style.backgroundColor = bgColor; // Set background color
		bar.style.color = textColor; // Set text color
		bar.classList.add('palestine-bar');

		if (barMode == 'fixed-header' || barMode == 'fixed-footer') {
			// fixed mode
			bar.classList.add('palestine-bar-fixed');
			if (barMode == 'fixed-header') {
				bar.classList.add('palestine-bar-fixed-header');
			} else if (barMode == 'fixed-footer') {
				bar.classList.add('palestine-bar-fixed-footer');
			}
		} else if (barMode == 'popup-left' || barMode == 'popup-right') {
			bar.classList.add('palestine-bar-popup');
			bar.classList.add('palestine-bar-'+barMode);
		}

		// create the logo
		let logo;
		if (barMode == 'popup-left' || barMode == 'popup-right') {
			logo = document.createElement('img');
			logo.src = imageLink;
			logo.classList.add('palestine-bar-popup-img');
		} else {
			const svgString = '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="30" viewBox="0 0 6 3"><rect fill="#009639" width="6" height="3"/><rect fill="#FFF" width="6" height="2"/><rect width="6" height="1"/><path fill="#ED2E38" d="M0,0l2,1.5L0,3Z"/></svg>';
			const parser = new DOMParser();
			const svgDoc = parser.parseFromString(svgString, 'image/svg+xml');
			logo = svgDoc.documentElement;
			logo.classList.add('palestine-bar-flag');
		}

		// Create the text in the middle
		const text = document.createElement('div');
		text.textContent = textContent;
		text.classList.add('palestine-bar-text');

		// Create the donation button
		const button = document.createElement('button');
		button.textContent = buttonTextContent;
		button.style.backgroundColor = buttonColor;
		button.style.color = textColor;
		button.classList.add('palestine-bar-button');

		button.addEventListener('click', function () {
			// Oppen donation link on click.
			window.open(donationLink, '_blank');
		});

		// Append elements to the bar
		bar.appendChild(logo);
		bar.appendChild(text);
		bar.appendChild(button);

		if (barMode == 'popup-left' || barMode == 'popup-right') {
			// Create the donation button
			const closeButton = document.createElement('button');
			closeButton.style.backgroundColor = buttonColor;
			closeButton.style.color = textColor;
			closeButton.classList.add('palestine-bar-close-button');

			logo.addEventListener('click', function () {
				// Oppen donation link on click.
				window.open(donationLink, '_blank');
			});
			text.addEventListener('click', function () {
				// Oppen donation link on click.
				window.open(donationLink, '_blank');
			});
			closeButton.addEventListener('click', function () {
				// close the bar.
				bar.style.display = 'none';
			});
			bar.appendChild(closeButton);
		}

		// Append the bar to the body of the webpage
		window.onload = function () {
			const bodyElement = document.body;
			bodyElement.insertBefore(bar, bodyElement.firstChild);
		};
	}

	return {
		init: init
	};
})();

palestineBar.init();