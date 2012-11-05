<?php

/* *******************************************
// Copyright 2010-2012, Anthony Hand
//
// File version date: April 23, 2012
//		Update:
//		- Updated DetectAmazonSilk(): Fixed an issue in the detection logic.
//
// File version date: April 22, 2012 - Second update
//		Update: To address additional Kindle issues...
//		- Updated DetectRichCSS(): Excluded e-Ink Kindle devices.
//		- Created DetectAmazonSilk(): Created to detect Kindle Fire devices in Silk mode.
//		- Updated DetectMobileQuick(): Updated to include e-Ink Kindle devices and the Kindle Fire in Silk mode.
//
// File version date: April 11, 2012
//		Update:
//		- Added a new variable for the new BlackBerry Curve Touch (9380): deviceBBCurveTouch.
//		- Updated DetectBlackBerryTouch() to support the new BlackBerry Curve Touch (9380).
//		- Updated DetectKindle(): Added the missing 'this' class identifier for the DetectAndroid() call.
//
// File version date: January 21, 2012
//		Update:
//		- Added the constructor method per new features in PHP 5.0: __construct().
//		- Moved Windows Phone 7 to the iPhone Tier. WP7.5's IE 9-based browser is good enough now.
//		- Added a new variable for 2 versions of the new BlackBerry Bold Touch (9900 and 9930): deviceBBBoldTouch.
//		- Updated DetectBlackBerryTouch() to support the 2 versions of the new BlackBerry Bold Touch (9900 and 9930).
//		- Updated DetectKindle() to focus on eInk devices only. The Kindle Fire should be detected as a regular Android device.
//
// File version date: August 22, 2011
//		Update:
//		- Updated DetectAndroidTablet() to fix a bug introduced in the last fix! The true/false returns were mixed up.
//
// File version date: August 16, 2011
//		Update:
//		- Updated DetectAndroidTablet() to exclude Opera Mini, which was falsely reporting as running on a tablet device when on a phone.
//
//
// LICENSE INFORMATION
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//        http://www.apache.org/licenses/LICENSE-2.0
// Unless required by applicable law or agreed to in writing,
// software distributed under the License is distributed on an
// "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
// either express or implied. See the License for the specific
// language governing permissions and limitations under the License.
//
//
// ABOUT THIS PROJECT
//   Project Owner: Anthony Hand
//   Email: anthony.hand@gmail.com
//   Web Site: http://www.mobileesp.com
//   Source Files: http://code.google.com/p/mobileesp/
//
//   Versions of this code are available for:
//      PHP, JavaScript, Java, ASP.NET (C#), and Ruby
//
// *******************************************
*/



//**************************
// The mdetect class encapsulates information about
//   a browser's connection to your web site.
//   You can use it to find out whether the browser asking for
//   your site's content is probably running on a mobile device.
//   The methods were written so you can be as granular as you want.
//   For example, enquiring whether it's as specific as an iPod Touch or
//   as general as a smartphone class device.
//   The object's methods return 1 for true, or 0 for false.
class mdetect {

  var $useragent = '';
  var $httpaccept = '';
  var $agenthash = '';

  //Let's store values for quickly accessing the same info multiple times.
  var $isIphone = 0; //Stores whether the device is an iPhone or iPod Touch.
  var $isAndroidPhone = 0; //Stores whether the device is a (small-ish) Android phone or media player.
  var $isTierTablet = 0; //Stores whether is the Tablet (HTML5-capable, larger screen) tier of devices.
  var $isTierIphone = 0; //Stores whether is the iPhone tier of devices.
  var $isTierRichCss = 0; //Stores whether the device can probably support Rich CSS, but JavaScript support is not assumed. (e.g., newer BlackBerry, Windows Mobile)
  var $isTierGenericMobile = 0; //Stores whether it is another mobile device, which cannot be assumed to support CSS or JS (eg, older BlackBerry, RAZR)

  //Initialize some initial smartphone string variables.
  var $engineWebKit = 'webkit';
  var $deviceIphone = 'iphone';
  var $deviceIpod = 'ipod';
  var $deviceIpad = 'ipad';
  var $deviceMacPpc = 'macintosh'; //Used for disambiguation

  var $deviceAndroid = 'android';
  var $deviceGoogleTV = 'googletv';
  var $deviceXoom = 'xoom'; //Motorola Xoom
  var $deviceHtcFlyer = 'htc_flyer'; //HTC Flyer

  var $deviceNuvifone = 'nuvifone'; //Garmin Nuvifone

  var $deviceSymbian = 'symbian';
  var $deviceS60 = 'series60';
  var $deviceS70 = 'series70';
  var $deviceS80 = 'series80';
  var $deviceS90 = 'series90';

  var $deviceWinPhone7 = 'windows phone os 7';
  var $deviceWinMob = 'windows ce';
  var $deviceWindows = 'windows';
  var $deviceIeMob = 'iemobile';
  var $devicePpc = 'ppc'; //Stands for PocketPC
  var $enginePie = 'wm5 pie'; //An old Windows Mobile

  var $deviceBB = 'blackberry';
  var $vndRIM = 'vnd.rim'; //Detectable when BB devices emulate IE or Firefox
  var $deviceBBStorm = 'blackberry95';  //Storm 1 and 2
  var $deviceBBBold = 'blackberry97'; //Bold 97x0 (non-touch)
  var $deviceBBBoldTouch = 'blackberry 99'; //Bold 99x0 (touchscreen)
  var $deviceBBTour = 'blackberry96'; //Tour
  var $deviceBBCurve = 'blackberry89'; //Curve2
  var $deviceBBCurveTouch = 'blackberry 938'; //Curve Touch
  var $deviceBBTorch = 'blackberry 98'; //Torch
  var $deviceBBPlaybook = 'playbook'; //PlayBook tablet

  var $devicePalm = 'palm';
  var $deviceWebOS = 'webos'; //For Palm's line of WebOS devices
  var $deviceWebOShp = 'hpwos'; //For HP's line of WebOS devices

  var $engineBlazer = 'blazer'; //Old Palm browser
  var $engineXiino = 'xiino'; //Another old Palm

  var $deviceKindle = 'kindle'; //Amazon Kindle, eInk one
  var $engineSilk = 'silk'; //Amazon's accelerated Silk browser for Kindle Fire

  //Initialize variables for mobile-specific content.
  var $vndwap = 'vnd.wap';
  var $wml = 'wml';

  //Initialize variables for other random devices and mobile browsers.
  var $deviceTablet = 'tablet'; //Generic term for slate and tablet devices
  var $deviceBrew = 'brew';
  var $deviceDanger = 'danger';
  var $deviceHiptop = 'hiptop';
  var $devicePlaystation = 'playstation';
  var $deviceNintendoDs = 'nitro';
  var $deviceNintendo = 'nintendo';
  var $deviceWii = 'wii';
  var $deviceXbox = 'xbox';
  var $deviceArchos = 'archos';

  var $engineOpera = 'opera'; //Popular browser
  var $engineNetfront = 'netfront'; //Common embedded OS browser
  var $engineUpBrowser = 'up.browser'; //common on some phones
  var $engineOpenWeb = 'openweb'; //Transcoding by OpenWave server
  var $deviceMidp = 'midp'; //a mobile Java technology
  var $uplink = 'up.link';
  var $engineTelecaQ = 'teleca q'; //a modern feature phone browser

  var $devicePda = 'pda'; //some devices report themselves as PDAs
  var $mini = 'mini';  //Some mobile browsers put 'mini' in their names.
  var $mobile = 'mobile'; //Some mobile browsers put 'mobile' in their user agent strings.
  var $mobi = 'mobi'; //Some mobile browsers put 'mobi' in their user agent strings.

  //Use Maemo, Tablet, and Linux to test for Nokia's Internet Tablets.
  var $maemo = 'maemo';
  var $linux = 'linux';
  var $qtembedded = 'qt embedded'; //for Sony Mylo and others
  var $mylocom2 = 'com2'; //for Sony Mylo also

  //In some UserAgents, the only clue is the manufacturer.
  var $manuSonyEricsson = 'sonyericsson';
  var $manuericsson = 'ericsson';
  var $manuSamsung1 = 'sec-sgh';
  var $manuSony = 'sony';
  var $manuHtc = 'htc'; //Popular Android and WinMo manufacturer

  //In some UserAgents, the only clue is the operator.
  var $svcDocomo = 'docomo';
  var $svcKddi = 'kddi';
  var $svcVodafone = 'vodafone';

  //Disambiguation strings.
  var $disUpdate = 'update'; //pda vs. update


  //**************************
  //The constructor. Allows the latest PHP (5.0+) to locate a constructor object and initialize the object.
  function __construct(){
    $this->mdetect();
  }


  //**************************
  //The object initializer. Initializes several default variables.
  function mdetect(){
    if( isset( $_SERVER['HTTP_USER_AGENT'] ) )
      $this->useragent = strtolower( $_SERVER['HTTP_USER_AGENT'] );
    if( isset( $_SERVER['HTTP_ACCEPT'] ) )
      $this->httpaccept = strtolower( $_SERVER['HTTP_ACCEPT'] );
    $this->agenthash = md5( $this->useragent . $this->httpaccept );

    //Let's initialize some values to save cycles later.
    $this->InitDeviceScan();
  }

  //**************************
  // Initialize Key Stored Values.
  function InitDeviceScan(){
    global $isIphone, $isAndroidPhone, $isTierTablet, $isTierIphone;

    //We'll use these 4 variables to speed other processing. They're super common.
    $this->isIphone = $this->DetectIphoneOrIpod();
    $this->isAndroidPhone = $this->DetectAndroidPhone();
    $this->isTierIphone = $this->DetectTierIphone();
    $this->isTierTablet = $this->DetectTierTablet();

    //Optional: Comment these out if you don't need them.
    global $isTierRichCss, $isTierGenericMobile;
    $this->isTierRichCss = $this->DetectTierRichCss();
    $this->isTierGenericMobile = $this->DetectTierOtherPhones();
  }

  //**************************
  //Returns the contents of the User Agent value, in lower case.
  //DEPRECATED! DO NOT USE - WILL BE REMOVED IN A LATER VERSION
  function Get_Uagent(){
    return $this->useragent;
  }

  //**************************
  //Returns the contents of the HTTP Accept value, in lower case.
  //DEPRECATED! DO NOT USE - WILL BE REMOVED IN A LATER VERSION
  function Get_HttpAccept(){
    return $this->httpaccept;
  }


  //**************************
  // Detects if the current device is an iPhone.
  function DetectIphone(){
    if( stripos( $this->useragent , $this->deviceIphone )==-1 )
      return false;
    //The iPad and iPod Touch say they're an iPhone. So let's disambiguate.
    return (
             !$this->DetectIpad()
             &&
             !$this->DetectIpod()
           );
  }

  //**************************
  // Detects if the current device is an iPod Touch.
  function DetectIpod(){
    return ( stripos( $this->useragent , $this->deviceIpod )>-1 );
  }

  //**************************
  // Detects if the current device is an iPad tablet.
  function DetectIpad(){
    return (
             stripos( $this->useragent , $this->deviceIpad )>-1
             &&
             $this->DetectWebkit()
           );
  }

  //**************************
  // Detects if the current device is an iPhone or iPod Touch.
  function DetectIphoneOrIpod(){
    //We repeat the searches here because some iPods may report themselves as an iPhone, which would be okay.
    return (
             stripos( $this->useragent , $this->deviceIphone )>-1
             ||
             stripos($this->useragent , $this->deviceIpod )>-1
           );
  }

  //**************************
  // Detects *any* iOS device: iPhone, iPod Touch, iPad.
  function DetectIos(){
    return (
             $this->DetectIphoneOrIpod()
             ||
             $this->DetectIpad()
           );
  }


  //**************************
  // Detects *any* Android OS-based device: phone, tablet, and multi-media player.
  // Also detects Google TV.
  function DetectAndroid(){
    return (
             stripos( $this->useragent , $this->deviceAndroid )>-1
             ||
             $this->DetectGoogleTV()
             ||
             stripos( $this->useragent , $this->deviceHtcFlyer )>-1 //Special check for the HTC Flyer 7" tablet
           );
  }

  //**************************
  // Detects if the current device is a (small-ish) Android OS-based device
  // used for calling and/or multi-media (like a Samsung Galaxy Player).
  // Google says these devices will have 'Android' AND 'mobile' in user agent.
  // Ignores tablets (Honeycomb and later).
  function DetectAndroidPhone(){
    if (($this->DetectAndroid() == true) &&
        (stripos($this->useragent, $this->mobile) > -1))
      return true;
    //Special check for Android phones with Opera Mobile. They should report here.
    if (($this->DetectOperaAndroidPhone() == true))
      return true;
    //Special check for the HTC Flyer 7" tablet. It should report here.
    if ((stripos($this->useragent, $this->deviceHtcFlyer) > -1))
      return true;
    else
      return false;
  }

  //**************************
  // Detects if the current device is a (self-reported) Android tablet.
  // Google says these devices will have 'Android' and NOT 'mobile' in their user agent.
  function DetectAndroidTablet(){
    
    return  !(
               !$this->DetectAndroid()  //First, let's make sure we're on an Android device.
               ||
               $this->DetectOperaMobile()  //Special check for Opera Android Phones. They should NOT report here.
               ||
               stripos( $this->useragent , $this->deviceHtcFlyer )>-1  //Special check for the HTC Flyer 7" tablet. It should NOT report here
               ||
               stripos( $this->useragent , $this->mobile )>-1  //Otherwise, if it's Android and does NOT have 'mobile' in it, Google says it's a tablet.
             );
  }

  //**************************
  // Detects if the current device is an Android OS-based device and
  //   the browser is based on WebKit.
  function DetectAndroidWebKit(){
    return (
             $this->DetectAndroid()
             &&
             $this->DetectWebkit()
           );
  }

  //**************************
  // Detects if the current device is a GoogleTV.
  function DetectGoogleTV(){
    return ( stripos( $this->useragent , $this->deviceGoogleTV )>-1 );
  }

  //**************************
  // Detects if the current browser is based on WebKit.
  function DetectWebkit(){
    return ( stripos( $this->useragent , $this->engineWebKit )>-1 );
  }


  //**************************
  // Detects if the current browser is the Nokia S60 Open Source Browser.
  function DetectS60OssBrowser(){
    //First, test for WebKit, then make sure it's either Symbian or S60.
    return (
             $this->DetectWebkit()
             &&
             (
               stripos( $this->useragent , $this->deviceSymbian )>-1
               ||
               stripos( $this->useragent , $this->deviceS60 )>-1
             )
           );
  }

  //**************************
  // Detects if the current device is any Symbian OS-based device,
  //   including older S60, Series 70, Series 80, Series 90, and UIQ,
  //   or other browsers running on these devices.
  function DetectSymbianOS(){
    return (
             stripos( $this->useragent , $this->deviceSymbian )>-1
             ||
             stripos( $this->useragent , $this->deviceS60 )>-1
             ||
             stripos( $this->useragent , $this->deviceS70 )>-1
             ||
             stripos( $this->useragent , $this->deviceS80 )>-1
             ||
             stripos( $this->useragent , $this->deviceS90 )>-1
           );
  }

  //**************************
  // Detects if the current browser is a
  // Windows Phone 7 device.
  function DetectWindowsPhone7(){
    return ( stripos( $this->useragent , $this->deviceWinPhone7 )>-1 );
  }

  //**************************
  // Detects if the current browser is a Windows Mobile device.
  // Excludes Windows Phone 7 devices.
  // Focuses on Windows Mobile 6.xx and earlier.
  function DetectWindowsMobile(){
    if ($this->DetectWindowsPhone7() == true)
      return false;
    //Most devices use 'Windows CE', but some report 'iemobile'
    //  and some older ones report as 'PIE' for Pocket IE.
    if (stripos($this->useragent, $this->deviceWinMob) > -1 ||
        stripos($this->useragent, $this->deviceIeMob) > -1 ||
        stripos($this->useragent, $this->enginePie) > -1)
      return true;
    //Test for Windows Mobile PPC but not old Macintosh PowerPC.
    if (stripos($this->useragent, $this->devicePpc) > -1
        && !(stripos($this->useragent, $this->deviceMacPpc) > 1))
      return true;
    //Test for certain Windwos Mobile-based HTC devices.
    if (stripos($this->useragent, $this->manuHtc) > -1 &&
        stripos($this->useragent, $this->deviceWindows) > -1)
      return true;
    if ($this->DetectWapWml() == true &&
        stripos($this->useragent, $this->deviceWindows) > -1)
      return true;
    else
      return false;
  }

  //**************************
  // Detects if the current browser is any BlackBerry device.
  // Includes the PlayBook.
  function DetectBlackBerry(){
    return (
             stripos( $this->useragent , $this->deviceBB )>-1
             ||
             stripos( $this->httpaccept , $this->vndRIM )>-1
           );
  }

  //**************************
  // Detects if the current browser is on a BlackBerry tablet device.
  //    Examples: PlayBook
  function DetectBlackBerryTablet(){
    return ( stripos( $this->useragent , $this->deviceBBPlaybook )>-1 );
  }

  //**************************
  // Detects if the current browser is a BlackBerry phone device AND uses a
  //    WebKit-based browser. These are signatures for the new BlackBerry OS 6.
  //    Examples: Torch. Includes the Playbook.
  function DetectBlackBerryWebKit(){
    return (
             $this->DetectBlackBerry()
             &&
             $this->DetectWebkit()
           );
  }

  //**************************
  // Detects if the current browser is a BlackBerry Touch phone device with
  //    a large screen, such as the Storm, Torch, and Bold Touch. Excludes the Playbook.
  function DetectBlackBerryTouch(){
    return (
             stripos( $this->useragent , $this->deviceBBStorm )>-1
             ||
             stripos( $this->useragent , $this->deviceBBTorch )>-1
             ||
             stripos( $this->useragent , $this->deviceBBBoldTouch )>-1
             ||
             stripos( $this->useragent , $this->deviceBBCurveTouch )>-1
           );
  }

  //**************************
  // Detects if the current browser is a BlackBerry OS 5 device AND
  //    has a more capable recent browser. Excludes the Playbook.
  //    Examples, Storm, Bold, Tour, Curve2
  //    Excludes the new BlackBerry OS 6 and 7 browser!!
  function DetectBlackBerryHigh(){
    //Disambiguate for BlackBerry OS 6 or 7 (WebKit) browser
    if ($this->DetectBlackBerryWebKit() == true)
      return false;
    if ($this->DetectBlackBerry() == true){
      if (($this->DetectBlackBerryTouch() == true) ||
          stripos($this->useragent, $this->deviceBBBold) > -1 ||
          stripos($this->useragent, $this->deviceBBTour) > -1 ||
          stripos($this->useragent, $this->deviceBBCurve) > -1){
        return true;
      } else
        return false;
    } else
      return false;
  }

  //**************************
  // Detects if the current browser is a BlackBerry device AND
  //    has an older, less capable browser.
  //    Examples: Pearl, 8800, Curve1.
  function DetectBlackBerryLow(){
    return (
             $this->DetectBlackBerry()
             &&
             !$this->DetectBlackBerryHigh()  //Assume that if it's not in the High tier, then it's Low.
             &&
             !$this->DetectBlackBerryWebKit()
           );
  }

  //**************************
  // Detects if the current browser is on a PalmOS device.
  function DetectPalmOS(){
    //Most devices nowadays report as 'Palm', but some older ones reported as Blazer or Xiino.
    if (stripos($this->useragent, $this->devicePalm) > -1 ||
        stripos($this->useragent, $this->engineBlazer) > -1 ||
        stripos($this->useragent, $this->engineXiino) > -1){
      //Make sure it's not WebOS first
      if ($this->DetectPalmWebOS() == true)
        return false;
      else
        return true;
    } else
      return false;
  }


  //**************************
  // Detects if the current browser is on a Palm device
  //   running the new WebOS.
  function DetectPalmWebOS(){
    return ( stripos( $this->useragent , $this->deviceWebOS )>-1 );
  }

  //**************************
  // Detects if the current browser is on an HP tablet running WebOS.
  function DetectWebOSTablet(){
    return (
             stripos( $this->useragent , $this->deviceWebOShp )>-1
             &&
             stripos( $this->useragent , $this->deviceTablet )>-1
           );
  }

  //**************************
  // Detects if the current browser is a
  //   Garmin Nuvifone.
  function DetectGarminNuvifone(){
    return ( stripos( $this->useragent , $this->deviceNuvifone )>-1 );
  }


  //**************************
  // Check to see whether the device is any device
  //   in the 'smartphone' category.
  function DetectSmartphone(){
    return (
             $this->isIphone
             ||
             $this->isAndroidPhone
             ||
             $this->isTierIphone
             ||
             $this->DetectS60OssBrowser()
             ||
             $this->DetectSymbianOS()
             ||
             $this->DetectWindowsMobile()
             ||
             $this->DetectWindowsPhone7()
             ||
             $this->DetectBlackBerry()
             ||
             $this->DetectPalmWebOS()
             ||
             $this->DetectPalmOS()
             ||
             $this->DetectGarminNuvifone()
           );
  }


  //**************************
  // Detects whether the device is a Brew-powered device.
  function DetectBrewDevice(){
    return ( stripos( $this->useragent , $this->deviceBrew )>-1 );
  }

  //**************************
  // Detects the Danger Hiptop device.
  function DetectDangerHiptop(){
    return (
             stripos( $this->useragent , $this->deviceDanger )>-1
             ||
             stripos( $this->useragent , $this->deviceHiptop )>-1
           );
  }

  //**************************
  // Detects if the current browser is Opera Mobile or Mini.
  function DetectOperaMobile(){
    return (
             stripos( $this->useragent , $this->engineOpera )>-1
             &&
             (
               stripos( $this->useragent , $this->mini )>-1
               ||
               stripos( $this->useragent , $this->mobi )>-1
             )
           );
  }

  //**************************
  // Detects if the current browser is Opera Mobile
  // running on an Android phone.
  function DetectOperaAndroidPhone(){
    return (
             stripos( $this->useragent , $this->engineOpera )>-1
             &&
             stripos( $this->useragent , $this->deviceAndroid )>-1
             &&
             stripos( $this->useragent , $this->mobi )>-1
           );
  }

  //**************************
  // Detects if the current browser is Opera Mobile
  // running on an Android tablet.
  function DetectOperaAndroidTablet(){
    return (
             stripos( $this->useragent , $this->engineOpera )>-1
             &&
             stripos( $this->useragent , $this->deviceAndroid )>-1
             &&
             stripos( $this->useragent , $this->deviceTablet )>-1
           );
  }

  //**************************
  // Detects whether the device supports WAP or WML.
  function DetectWapWml(){
    return (
             stripos( $this->httpaccept , $this->vndwap )>-1
             ||
             stripos( $this->httpaccept , $this->wml )>-1
           );
  }

  //**************************
  // Detects if the current device is an Amazon Kindle (eInk devices only).
  // Note: For the Kindle Fire, use the normal Android methods.
  function DetectKindle(){
    return (
             stripos( $this->useragent , $this->deviceKindle )>-1
             &&
             !$this->DetectAndroid()
           );
  }

  //**************************
  // Detects if the current Amazon device is using the Silk Browser.
  // Note: Typically used by the the Kindle Fire.
  function DetectAmazonSilk(){
    return ( stripos( $this->useragent , $this->engineSilk )>-1 );
  }


  //**************************
  // The quick way to detect for a mobile device.
  //   Will probably detect most recent/current mid-tier Feature Phones
  //   as well as smartphone-class devices. Excludes Apple iPads and other modern tablets.
  function DetectMobileQuick(){
    return (
             !$this->isTierTablet  //Let's exclude tablets
             &&
             (
               $this->DetectSmartphone()  //Most mobile browsing is done on smartphones
               ||
               $this->DetectWapWml()
               ||
               $this->DetectBrewDevice()
               ||
               $this->DetectOperaMobile()
               ||
               stripos( $this->useragent , $this->engineNetfront )>-1
               ||
               stripos( $this->useragent , $this->engineUpBrowser )>-1
               ||
               stripos( $this->useragent , $this->engineOpenWeb )>-1
               ||
               $this->DetectDangerHiptop()
               ||
               $this->DetectMidpCapable()
               ||
               $this->DetectMaemoTablet()
               ||
               $this->DetectArchos()
               ||
               (
                 stripos( $this->useragent , $this->devicePda )>-1
                 &&
                 stripos( $this->useragent , $this->disUpdate )===false
               )
               ||
               stripos( $this->useragent , $this->mobile )>-1
               ||
               $this->DetectKindle()
               ||
               $this->DetectAmazonSilk()
             )
           );
  }

  //**************************
  // Detects if the current device is a Sony Playstation.
  function DetectSonyPlaystation(){
    return ( stripos( $this->useragent , $this->devicePlaystation )>-1 );
  }

  //**************************
  // Detects if the current device is a Nintendo game device.
  function DetectNintendo(){
    return (
             stripos( $this->useragent , $this->deviceNintendo )>-1
             ||
             stripos( $this->useragent , $this->deviceWii )>-1
             ||
             stripos( $this->useragent , $this->deviceNintendoDs )>-1
           );
  }

  //**************************
  // Detects if the current device is a Microsoft Xbox.
  function DetectXbox(){
    return ( stripos( $this->useragent , $this->deviceXbox )>-1 );
  }

  //**************************
  // Detects if the current device is an Internet-capable game console.
  function DetectGameConsole(){
    return (
             $this->DetectSonyPlaystation()
             ||
             $this->DetectNintendo()
             ||
             $this->DetectXbox()
           );
  }

  //**************************
  // Detects if the current device supports MIDP, a mobile Java technology.
  function DetectMidpCapable(){
    return (
             stripos( $this->useragent , $this->deviceMidp )>-1
             ||
             stripos( $this->httpaccept , $this->deviceMidp )>-1
           );
  }

  //**************************
  // Detects if the current device is on one of the Maemo-based Nokia Internet Tablets.
  function DetectMaemoTablet(){
    return (
             stripos( $this->useragent , $this->maemo )>-1
             ||
             (
               stripos( $this->useragent , $this->linux )>-1  //For Nokia N810, must be Linux + Tablet, or else it could be something else.
               &&
               stripos( $this->useragent , $this->deviceTablet )>-1
               &&
               !$this->DetectWebOSTablet()
               &&
               !$this->DetectAndroid()
             )
           );
  }

  //**************************
  // Detects if the current device is an Archos media player/Internet tablet.
  function DetectArchos(){
    return ( stripos( $this->useragent , $this->deviceArchos )>-1 );
  }

  //**************************
  // Detects if the current browser is a Sony Mylo device.
  function DetectSonyMylo(){
    return (
             stripos( $this->useragent , $this->manuSony )>-1
             &&
             (
               stripos( $this->useragent , $this->qtembedded )>-1
               ||
               stripos( $this->useragent , $this->mylocom2 )>-1
             )
           );
  }


  //**************************
  // The longer and more thorough way to detect for a mobile device.
  //   Will probably detect most feature phones,
  //   smartphone-class devices, Internet Tablets,
  //   Internet-enabled game consoles, etc.
  //   This ought to catch a lot of the more obscure and older devices, also --
  //   but no promises on thoroughness!
  function DetectMobileLong(){
    return (
             $this->DetectMobileQuick()
             ||
             $this->DetectGameConsole()
             ||
             $this->DetectSonyMylo()
             ||
             //Detect older phones from certain manufacturers and operators.
             stripos( $this->useragent , $this->uplink )>-1
             ||
             stripos( $this->useragent , $this->manuSonyEricsson )>-1
             ||
             stripos( $this->useragent , $this->manuericsson )>-1
             ||
             stripos( $this->useragent , $this->manuSamsung1 )>-1
             ||
             stripos( $this->useragent , $this->svcDocomo )>-1
             ||
             stripos( $this->useragent , $this->svcKddi )>-1
             ||
             stripos( $this->useragent , $this->svcVodafone )>-1
           );
  }



  //*****************************
  // For Mobile Web Site Design
  //*****************************

  //**************************
  // The quick way to detect for a tier of devices.
  //   This method detects for the new generation of
  //   HTML 5 capable, larger screen tablets.
  //   Includes iPad, Android (e.g., Xoom), BB Playbook, WebOS, etc.
  function DetectTierTablet(){
    return (
             $this->DetectIpad()
             ||
             $this->DetectAndroidTablet()
             ||
             $this->DetectBlackBerryTablet()
             ||
             $this->DetectWebOSTablet()
           );
  }


  //**************************
  // The quick way to detect for a tier of devices.
  //   This method detects for devices which can
  //   display iPhone-optimized web content.
  //   Includes iPhone, iPod Touch, Android, Windows Phone 7, WebOS, etc.
  function DetectTierIphone(){
    return (
             $this->isIphone
             ||
             $this->isAndroidPhone
             ||
             (
               $this->DetectBlackBerryWebKit()
               &&
               $this->DetectBlackBerryTouch()
             )
             ||
             $this->DetectWindowsPhone7()
             ||
             $this->DetectPalmWebOS()
             ||
             $this->DetectGarminNuvifone()
           );
  }

  //**************************
  // The quick way to detect for a tier of devices.
  //   This method detects for devices which are likely to be capable
  //   of viewing CSS content optimized for the iPhone,
  //   but may not necessarily support JavaScript.
  //   Excludes all iPhone Tier devices.
  function DetectTierRichCss(){
    return (
             $this->DetectMobileQuick()
             &&
             !$this->DetectTierIphone()
             &&
             !$this->DetectKindle()
             &&
             (
               $this->DetectWebkit()  //Any WebKit
               ||
               $this->DetectS60OssBrowser()
               ||
               $this->DetectBlackBerryHigh()  //Note: 'High' BlackBerry devices ONLY
               ||
               $this->DetectWindowsMobile()  //Older Windows 'Mobile' isn't good enough for iPhone Tier.
               ||
               stripos( $this->useragent , $this->engineTelecaQ )>-1
             )
           );
  }

  //**************************
  // The quick way to detect for a tier of devices.
  //   This method detects for all other types of phones,
  //   but excludes the iPhone and RichCSS Tier devices.
  function DetectTierOtherPhones(){
    //Exclude devices in the other 2 categories
    return (
             $this->DetectMobileLong()
             &&
             !$this->DetectTierIphone()
             &&
             !$this->DetectTierRichCss()
           );
  }

}


//Was informed by a MobileESP user that it's a best practice
//  to omit the closing ?&gt; marks here. They can sometimes
//  cause errors with HTML headers.
