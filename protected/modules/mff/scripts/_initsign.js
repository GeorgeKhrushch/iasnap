function ff_loadFile(filepath, filecontext, filecrypt, imagestatus,scandata) {
    if (imagestatus!='') {
        $('#' + imagestatus + '1').show();
        $('#' + imagestatus + '2').hide();
        $('#' + imagestatus + '3').hide();
    }
    var euSign = document.getElementById("euSign");
    try {
        var euisinit = euSign.IsInitialized();
        if (euisinit == true) {
            euSign.Finalize();
        }
        euSign.SetCharset("UTF-16LE");
        euSign.SetUIMode(false);
        euSign.Initialize();
        euSign.width = "1px";
        euSign.SetUIMode(false);
    } catch (e) {
        if (confirm("Помилка при запуску Java-аплету. Можливо, Вам необхідно дозволити браузеру запуск Java. Чи бажаєти перейти на сторінку перевірки інсталяції Java?")) {
            window.open("http://www.java.com/ru/download/testjava.jsp");
        }
        try {
            euSign.Finalize();
        } catch (e1) {
            
        }
        ;
        return false;
    }
    var data;
    try {
        var data;
        if (!scandata) {
            var selectedFile = euSign.SelectFile(true, "*.pdf");
            var sepPos = selectedFile.lastIndexOf('/');
            sepPos = (sepPos === -1) ? selectedFile.lastIndexOf('\\') : sepPos;
            var selectedFileName = selectedFile.substring(sepPos + 1);
            document.getElementById(filepath).value = selectedFile;
            document.getElementById(filecontext + "_fileedsname").value = selectedFileName;
            data = euSign.ReadFile(selectedFile);
        } else {
            document.getElementById(filepath).value = "Відсканованно.pdf";
            document.getElementById(filecontext + "_fileedsname").value = "Відсканованно.pdf";
            data = scandata;
        }
        data = euSign.BASE64Encode(data);
        euSign.Finalize();

        EUWidgetSign(data, false, "", false, ff_callbackSign(filecontext, imagestatus), "", ff_callbackSignError(imagestatus), ff_callbackWait, filecrypt);
    } catch (e) {
        if (imagestatus!='') {
            $('#' + imagestatus + '1').show();
            $('#' + imagestatus + '2').hide();
            $('#' + imagestatus + '3').hide();
        }
        euSign.Finalize();
        alert("Файл не обраний");
    }

    return false;
}

function ff_callbackSign(filecontext, imagestatus) {
    return function(signed_data, original_data) {
        if (imagestatus!='') {
            $('#' + imagestatus + '1').hide();
            $('#' + imagestatus + '2').hide();
            $('#' + imagestatus + '3').show();
        }
        document.getElementById(filecontext).value = signed_data;
        $("#overlaywait").remove();
        alert("Успішно підписаний");
    }
}

function ff_callbackSignError(imagestatus) {
    return function(error_string, error_code) {
        if (error_code != 0) {
            if (imagestatus!='') {
                $('#' + imagestatus + '1').hide();
                $('#' + imagestatus + '2').show();
                $('#' + imagestatus + '3').hide();
            }
            $("#overlaywait").remove();
            alert(error_string+" "+error_code);
        }
    }
}

function ff_callbackWait(waitstatus) {
    if (waitstatus === "wait_on") {
        $(function() {
            var docHeight = $(document).height();
            $("body").append("<div id='overlaywait'></div>");
            $("#overlaywait")
                    .height(docHeight)
                    .css({
                        'opacity': 0.4,
                        'position': 'absolute',
                        'top': 0,
                        'left': 0,
                        'background-color': 'black',
                        'width': '100%',
                        'z-index': 50000
                    });
        });
    } else {
        $("#overlaywait").remove();
    }
}

function ff_certInfo(filecontext, imagestatus) {
    if (imagestatus!='') {
        $('#' + imagestatus + '1').show();
        $('#' + imagestatus + '2').hide();
        $('#' + imagestatus + '3').hide();
    }
    signed_data = document.getElementById(filecontext).value;
    var euSign = document.getElementById("euSign");
    try {
        euSign.SetCharset("UTF-16LE");
        euSign.SetUIMode(false);
        euSign.Initialize();
        euSign.width = "1px";
        euSign.SetUIMode(false);
        var SignInfo = euSign.VerifyInternal(signed_data);
        if (imagestatus!='') {
            $('#' + imagestatus + '1').hide();
            $('#' + imagestatus + '2').hide();
            $('#' + imagestatus + '3').show();
        }
        showSignerInfo(SignInfo);
//        SignInfo = SignInfo.GetOwnerInfo();
//        var summary = "Підпис дійсний!\n\n";
//        summary += "Центр видачи сертифікату:" + SignInfo.GetIssuerCN() + "\n";
//        summary += "Данні про центр видачи сертифікату:" + SignInfo.GetIssuer() + "\n";
//        summary += "Серійний номер сертифікату:" + SignInfo.GetSerial() + "\n";
//        summary += "Данні про власника сертифікату:" + SignInfo.GetSubject() + "\n";
//        summary += "Організація:" + SignInfo.GetSubjOrg() + "\n";
//        summary += "Підрозділ:" + SignInfo.GetSubjOrgUnit() + "\n";
//        summary += "Посада:" + SignInfo.GetSubjTitle() + "\n";
//        summary += "Область:" + SignInfo.GetSubjState() + "\n";
//        summary += "Місто/Населений пункт:" + SignInfo.GetSubjLocality() + "\n";
//        summary += "Повне Им'я:" + SignInfo.GetSubjFullName() + "\n";
//        summary += "Адреса:" + SignInfo.GetSubjAddress() + "\n";
//        summary += "Телефон:" + SignInfo.GetSubjPhone() + "\n";
//        summary += "EMail:" + SignInfo.GetSubjEMail() + "\n";
//        summary += "DNS:" + SignInfo.GetSubjDNS() + "\n";
//        summary += "ЄДРПОУ:" + SignInfo.GetSubjEDRPOUCode() + "\n";
//        summary += "ДРФО:" + SignInfo.GetSubjDRFOCode() + "\n";
//        alert(summary);
    } catch (e) {
            if (imagestatus!='') {
                $('#' + imagestatus + '1').hide();
                $('#' + imagestatus + '2').show();
                $('#' + imagestatus + '3').hide();
            }
        alert("Помилка при перевірці підпису");
    } finally {
        euSign.Finalize();
    }
    return false;
}

function ff_saveFile(filecontext, filename, imagestatus) {
    if (imagestatus!='') {
        $('#' + imagestatus + '1').show();
        $('#' + imagestatus + '2').hide();
        $('#' + imagestatus + '3').hide();
    }
    signed_data = document.getElementById(filecontext).value;
    var euSign = document.getElementById("euSign");
    try {
        euSign.SetCharset("UTF-16LE");
        euSign.SetUIMode(false);
        euSign.Initialize();
        euSign.width = "1px";
        euSign.SetUIMode(false);
        var returndata=euSign.VerifyInternal(signed_data,false);
        var saveFilePath = euSign.SelectFile(true, filename);
        returndata = euSign.BytesToString(returndata);
        returndata = euSign.BASE64Decode(returndata);
        euSign.WriteFile(saveFilePath,returndata);
        if (imagestatus!='') {
            $('#' + imagestatus + '1').hide();
            $('#' + imagestatus + '2').hide();
            $('#' + imagestatus + '3').show(); 
        }
    } catch (e) {
        if (imagestatus!='') {
            $('#' + imagestatus + '1').hide();
            $('#' + imagestatus + '2').show();
            $('#' + imagestatus + '3').hide();
        }
        alert("Помилка при перевірці підпису" + e.message);
    } finally {
        euSign.Finalize();
    }
    return false;
}

