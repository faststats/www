/*jshint esversion: 6 */
function getOS() {
  var userAgent = window.navigator.userAgent,
  platform = window.navigator.platform,
  macosPlatforms = ['Macintosh', 'MacIntel', 'MacPPC', 'Mac68K'],
  windowsPlatforms = ['Win32', 'Win64', 'Windows', 'WinCE'],
  iosPlatforms = ['iPhone', 'iPad', 'iPod'],
  os = null;

  if (macosPlatforms.indexOf(platform) !== -1) {
    os = 'Mac OS';
  } else if (iosPlatforms.indexOf(platform) !== -1) {
    os = 'iOS';
  } else if (windowsPlatforms.indexOf(platform) !== -1) {
    os = 'Windows';
  } else if (/Android/.test(userAgent)) {
    os = 'Android';
  } else if (!os && /Linux/.test(platform)) {
    os = 'Linux';
  }
  return os;
}

function getDeviceInfo() {
  if (typeof(document.documentElement.clientWidth) != 'undefined') {
    var device = {
      width : document.documentElement.clientWidth,
      height : document.documentElement.clientHeight,
      height_ems : (document.documentElement.clientHeight / 16).toFixed(0),
      model : '',
      orientation : ''
    };
    if (device.width < device.height) {
      device.orientation = 'portrait';
    } else {
      device.orientation = 'landscape';
    }
    dev_w_h = device.width + ":" + device.height;
    if (dev_w_h == "980:1524" || dev_w_h == "980:428") {
      device.model = "Active S6";
    } else if (dev_w_h == "980:1123" || dev_w_h == "980:642") {
      device.model = "iPad2";
    } else if (dev_w_h == "1518:727") {
      device.model = "HP Laptop";
    } else if (dev_w_h == "800:1174" || dev_w_h == "1280:694") {
      device.model = "Acer One 10";
    } else if (dev_w_h == "980:1461" || dev_w_h == "1461:980") {
      device.model = "iPhone6";
    } else {
      device.model = "not_known";
    }
//    console.log(device);  
  }
  return device;
}
