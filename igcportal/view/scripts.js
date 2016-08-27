/**
 * Created by VuYeK on 27.08.2016.
 */
function ajax(options) {
    options = {
        type: options.type || "POST",
        url: options.url || "",
        onComplete: options.onComplete || function () {
        },
        onError: options.onError || function () {
        },
        onSuccess: options.onSuccess || function () {
        },
        dataType: options.dataType || "text"
    };

    var xml = new XMLHttpRequest();
    xml.open(options.type, options.url, true);

    xml.onreadystatechange = function () {
        if (xml.readyState == 4) {
            if (httpSuccess(xml)) {
                var returnData = (options.dataType == "xml") ? xml.responseXML : xml.responseText
                options.onSuccess(returnData);
            } else {
                options.onError();
            }
            options.onComplete();
            xml = null;
        }
    };

    xml.send();

    function httpSuccess(r) {
        try {
            return ( r.status >= 200 && r.status < 300 || r.status == 304 || navigator.userAgent.indexOf("Safari") >= 0 && typeof r.status == "undefined")
        } catch (e) {
            return false;
        }
    }
}


function getDetails() {
    var url = document.getElementById('url').value;
    url = encodeURI(url);

    ajax({
        type: "GET",
        url: "../controller/controller.php?url=" + url,
        onError: function (msg) {
            console.warn(msg)
        },
        onSuccess: function (msg) {
            fillDetails(msg);
        }
    });
}

function fillDetails(json) {
    var obj = JSON.parse(json);
    document.getElementById('details').innerHTML = "<table>" +
        "<tr><td><b>Data i godzina lotu:</b></td><td>" + obj.flight.flightDateTime.date
        + "</td></tr><tr><td><b>Strefa czasowa:</b></td><td>" + obj.flight.flightDateTime.timezone_type + " - " + obj.flight.flightDateTime.timezone
        + "</td></tr><tr><td><b>Pilot:</b></td><td>" + obj.flight.pilot
        + "</td></tr><tr><td><b>Model glajta:</b></td><td>" + obj.flight.gliderType
        + "</td></tr><tr><td><b>ID glajta:</b></td><td>" + obj.flight.gliderId
        + "</td></tr><tr><td><b>Precyzja pomiaru:</b></td><td>" + obj.flight.fixAccurancy + "m"
        + "</td></tr><tr><td><b>System odniesienia:</b></td><td>" + obj.flight.GPSdatum
        + "</td></tr><tr><td><b>Logger:</b></td><td>" + " <b>FW:</b> " + obj.flight.loggerFirmware + " <b>HW:</b> " + obj.flight.loggerHardware + "<b>Model:</b> " + obj.flight.loggerType
        + "</td></tr><tr><td><b>Klasa paralotniowa:</b></td><td>" + obj.flight.glideClass
        + "</td></tr></table>";
}