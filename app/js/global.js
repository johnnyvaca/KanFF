/**
 *  Project: KanFF
 *  File: global.js javascript global file. is present on every page.
 *  Author: Team
 *  Creation date: 26.04.2020
 */

//========= Counter management =========

//Declare event keyup for start reload counter


$(document).ready(function () {
    $(".textFieldToCheck").on("keyup", function (txt) { //on event keyup on a input with class .textFieldToCheck
        checkTextFieldToCheck()
    })
    //Init tooltip in the page:
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    //#btnCancelFeedback on click delete text and close dropup
    if (document.getElementById("btnCancelFeedback") != null) {
        document.getElementById("btnCancelFeedback").addEventListener("click", function () {
            txtFeedback.value = ""

        })
    }

    //#btnSendFeedback on click check form and send if verified
    if (document.getElementById("btnSendFeedback") != null) {
        document.getElementById("btnSendFeedback").addEventListener("click", function (sender) {
            if (txtFeedback.value != "") {
                //TODO: send the ajax query

                //TODO: if success or error, display message
            }
        })
    }
})

//Reload the counter, for input text and textarea with class .textFieldToCheck and a associated counter
function checkTextFieldToCheck() {
    els = document.getElementsByClassName("textFieldToCheck");
    Array.prototype.forEach.call(els, function (txt) {
        thefield = txt   //get the field as object
        nblength = thefield.value.length    //get the length of the string inserted

//Get the <p> counter associated with the field. rebuild the name of p with pCounter then the name of thefield with an upper case at start of the string
        idOfP = "pCounter" + thefield.name.substr(0, 1).toUpperCase() + thefield.name.substr(1).toLowerCase()
        p = document.getElementById(idOfP)

        p.innerText = nblength + "/" + thefield.maxLength   //Change the content of the counter with the new length
        if (thefield.maxLength - nblength < thefield.maxLength / 5) {   //if the difference is lower than the fifth of the maxlength:
            if (thefield.classList.contains("counterVisibleOnlyIfFastMaxLength")) {
                p.hidden = false;
            }
            p.classList.add("redCounter")   //put the counter in red
        } else {
            p.classList.remove("redCounter")    //remove the red of the counter
            if (thefield.classList.contains("counterVisibleOnlyIfFastMaxLength")) {
                p.hidden = true;
            }
        }
    })
}

//When the page is loaded, start the check for remove bad text counter initialisation (called 0/50 instead of 0/100 for example)
document.addEventListener('DOMContentLoaded', function () {
    checkTextFieldToCheck()
})


//All elements with class "clickable" that have a "data-href" attribute to know the link that have to be opened.
document.addEventListener('DOMContentLoaded', function () {
    var els = document.getElementsByClassName("clickable");
    Array.prototype.forEach.call(els, function (el) {
        el.addEventListener('click', function (evt) {
            console.log(evt.target)
            if (evt.target.getAttribute('data-href') == null) {
                if (evt.target.parentNode.getAttribute('data-href') != null) {
                    window.location = evt.target.parentNode.getAttribute('data-href')
                }
            } else {
                window.location = evt.target.getAttribute('data-href')
            }
        })
    })
})

//Declare event keyup at start. Remove space in real time for inputs with the class .removeSpaceInRT
$(document).ready(function () {
    $(".removeSpaceInRT").on("keyup", function (txt) {
        inp = txt.target
        while (inp.value.search(" ") != -1) {   //while there are spaces in the string, remove them
            inp.value = inp.value.replace(" ", "")
        }
    })
})

//Declare event keyup at start. Trim values when user quit inputs with the class .trimItOnChange
$(document).ready(function () {
    $(".trimItOnChange").on("change", function (txt) {
        inp = txt.target
        inp.value = inp.value.trim()
    })
})

//Get an array with key corresponding to name fields and with value, of a form (sending with Ajax
function getArrayFromAFormFieldsWithName(formname) {
    frm = document.getElementById(formname)
    subelements = frm.getElementsByTagName("*")
    finalArray = []
    Array.prototype.forEach.call(subelements, function (el) {
        if (el.name != undefined && el.name != "") {
            if (el.value != null) {
                finalArray[removeNumbersInString(el.name)] = el.value
            }
        }
    })
    return finalArray
}

//Remove all numbers in a string
function removeNumbersInString(text) {
    for (i = 0; i < 10; i++) {
        text = text.replaceAll(i, "")
    }
    return text
}

//Send a HTTP request (with an Ajax call):
function sendRequest(verb, url, callback, data) {
    reqHttp = new XMLHttpRequest()  //Create XHR Object

    //Start function on change of readyState
    reqHttp.onreadystatechange = function () {
        if (reqHttp.readyState == XMLHttpRequest.DONE && reqHttp.status == 200) {   //if request is done and is success (HTTP status, not response status)
            callback(JSON.parse(reqHttp.responseText))  //launch the callback function with response text received
        }
    }
    reqHttp.open(verb, url)   //open the request with a verb (GET, POST, ...) and an URL
    reqHttp.setRequestHeader("Content-Type", "application/json")   //set header content type as json data

    if (data != null) { //if body is the request is not null
        if (Array.isArray(data)) {  //if it's an array
            formatedData = JSON.stringify(Object.assign({}, data))  //stringify in JSON the Array converted in Object
        } else {
            formatedData = JSON.stringify(data) //stringify in JSON the Object
        }
        reqHttp.send(formatedData)  //send the query with formated data
    } else {
        reqHttp.send()  //send the query without any body
    }
}

function displayResponseMsg(response) {
    //TODO: check if status is not failed with specified function
    msg = response.data.message

}

function checkAllValuesAreNotEmpty(values) {
    Array.prototype.forEach.call(values, function (val) {
        if (val == null || val == null) {
            return false
        }
    })
    return true
}